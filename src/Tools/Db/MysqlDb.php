<?php

namespace Altan\TreeBuilder\Tools\Db;

/**
 * MysqlDb
 */
class MysqlDb implements Database
{
    private \PDO $db;

    private array $allowed_tables_columns = [];

    /**
     * __construct
     *
     * @param  mixed $dsn
     * @param  mixed $username
     * @param  mixed $password
     * @return void
     */
    public function __construct(string $dsn, string $username, string $password)
    {
        $this->db = new \PDO($dsn, $username, $password);
        $this->fetchTablesColumns();
    }

    /**
     * getItems
     *
     * @param  mixed $entity
     * @param  mixed $order
     * @return array
     */
    public function getItems(string $entity, array $order = []): array
    {
        if (!$this->checkTableColumns($entity, $order)) {
            throw new \Exception("Wrong data format");
        }
        $query = "SELECT * FROM `" . $entity . "` ";
        if (count($order)) {
            $query .= " ORDER BY `" . implode("`, `", $order) . "`";
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * findItems
     *
     * @return void
     */
    public function findItems(
        string $entity,
        array $search = [],
        array $fields = []
    ): array {
        if (!$this->checkTableColumns($entity, array_keys($search))) {
            throw new \Exception("Wrong data format");
        }
        if (!$this->checkTableColumns($entity, $fields)) {
            throw new \Exception("Wrong data format");
        }
        $query = "SELECT " . (count($fields) > 0 ? implode(', ', $fields) : "*") .
            " FROM `" . $entity . "` ";
        if (count($search)) {
            $query .= " WHERE 1 ";
            foreach ($search as $key => $val) {
                $query .= " AND " . $key . " = :" . $key . " ";
            }
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($search);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * getItem
     *
     * @param  mixed $entity
     * @param  mixed $id
     * @return array
     */
    public function getItem(string $entity, int $id): array
    {
        if (!$this->checkTableColumns($entity)) {
            throw new \Exception("Wrong data format");
        }
        $query = "SELECT * FROM `" . $entity . "` WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        }
        return [];
    }

    /**
     * createItem
     *
     * @param  mixed $entity
     * @param  mixed $data
     * @return int
     */
    public function createItem(string $entity, array $data): int
    {
        if (
            !$this->checkTableColumns($entity, array_keys($data))
            || count($data) == 0
        ) {
            throw new \Exception("Wrong data format");
        }
        $query = "INSERT INTO `" . $entity . "`(" . implode(", ", array_keys($data)) .
            ") VALUES (:" . implode(", :", array_keys($data)) . ")";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    /**
     * updateItem
     *
     * @param  mixed $entity
     * @param  mixed $id
     * @param  mixed $data
     * @return bool
     */
    public function updateItem(string $entity, int $id, array $data): bool
    {
        if (
            !$this->checkTableColumns($entity, array_keys($data))
            || count($data) == 0
        ) {
            throw new \Exception("Wrong data format");
        }
        $update_arr = [];
        foreach ($data as $key => $val) {
            $update_arr[] = $key . " = :" . $key;
        }
        $query = "UPDATE `" . $entity . "` SET " . implode(", ", $update_arr) .
            " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([...$data, $id]);
        return true;
    }

    /**
     * deleteItem
     *
     * @param  mixed $entity
     * @param  mixed $id
     * @return bool
     */
    public function deleteItem(string $entity, int $id): bool
    {
        if (!$this->checkTableColumns($entity)) {
            throw new \Exception("Wrong data format");
        }
        $query = "DELETE FROM `" . $entity . "` WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return true;
    }

    /**
     * fetchTablesColumns
     *
     * @return void
     */
    private function fetchTablesColumns()
    {
        $stmt = $this->db->prepare("SHOW TABLES");
        $stmt->execute();
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($res as $tbl) {
            $tbl_name = $tbl[array_key_first($tbl)];
            $col_stmt = $this->db->prepare("SHOW COLUMNS FROM `" . $tbl_name . "`");
            $col_stmt->execute();
            $col_res = $col_stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($col_res as $col) {
                $this->allowed_tables_columns[$tbl_name][$col['Field']] = true;
            }
        }
    }

    /**
     * checkTableColumns
     *
     * @param  mixed $table
     * @param  mixed $columns
     * @return bool
     */
    private function checkTableColumns(string $table, array $columns = []): bool
    {
        if (!isset($this->allowed_tables_columns[$table])) {
            return false;
        }
        foreach ($columns as $column) {
            if (!isset($this->allowed_tables_columns[$table][$column])) {
                return false;
            }
        }
        return true;
    }
}
