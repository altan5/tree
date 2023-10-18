<?php

namespace Altan\TreeBuilder\Tools\Db;

interface Database
{
    /**
     * getItems
     *
     * @param  mixed $entity
     * @param  mixed $order
     * @return array
     */
    public function getItems(string $entity, array $order = []): array;
    /**
     * findItems
     *
     * @return void
     */
    public function findItems(
        string $entity,
        array $search = [],
        array $fields = []
    ): array;
    /**
     * getItem
     *
     * @param  mixed $entity
     * @param  mixed $id
     * @return array
     */
    public function getItem(string $entity, int $id): array;
    /**
     * createItem
     *
     * @param  mixed $entity
     * @param  mixed $data
     * @return int
     */
    public function createItem(string $entity, array $data): int;
    /**
     * updateItem
     *
     * @param  mixed $entity
     * @param  mixed $id
     * @param  mixed $data
     * @return bool
     */
    public function updateItem(string $entity, int $id, array $data): bool;
    /**
     * deleteItem
     *
     * @param  mixed $entity
     * @param  mixed $id
     * @return bool
     */
    public function deleteItem(string $entity, int $id): bool;
}
