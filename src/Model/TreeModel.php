<?php

namespace Altan\TreeBuilder\Model;

use Altan\TreeBuilder\Tools\Db\Database;

/**
 * TreeModel
 */
class TreeModel extends Model
{
    /**
     * __construct
     *
     * @param  mixed $db
     * @return void
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
        $this->entity = "tree";
    }
    /**
     * validate
     *
     * @param  mixed $item
     * @return bool
     */
    public function validate(array $item): bool
    {
        if (
            isset($item["parent_id"]) && is_int($item["parent_id"])
            && isset($item["title"]) && is_string($item["title"])
        ) {
            return true;
        }
        return false;
    }
    /**
     * listItems
     *
     * @return array
     */
    public function listItems(): array
    {
        $res = $this->db->getItems($this->entity, ["parent_id", "id"]);
        return $res;
    }
    /**
     * getItem
     *
     * @param  mixed $itemId
     * @return array
     */
    public function getItem(int $itemId): array
    {
        $res = $this->db->getItem($this->entity, $itemId);
        return $res;
    }
    /**
     * createItem
     *
     * @param  mixed $item
     * @return int
     */
    public function createItem(array $item): int
    {
        return $this->db->createItem($this->entity, $item);
    }
    /**
     * deleteItem
     *
     * @param  mixed $itemId
     * @return void
     */
    public function deleteItem(int $itemId): void
    {
        $sub_items = $this->db->findItems(
            $this->entity,
            ['parent_id' => $itemId],
            ['id']
        );
        foreach ($sub_items as $item) {
            $this->deleteItem($item['id']);
        }
        $this->db->deleteItem($this->entity, $itemId);
    }
    /**
     * updateItem
     *
     * @param  mixed $itemId
     * @param  mixed $item
     * @return void
     */
    public function updateItem(int $itemId, array $item): void
    {
        $this->db->updateItem($this->entity, $itemId, $item);
    }
}
