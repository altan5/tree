<?php

namespace Altan\TreeBuilder\Model;

use Altan\TreeBuilder\Tools\Db\Database;

abstract class Model
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        protected Database $db
    ) {
    }
    protected string $entity = "";
    /**
     * validate
     *
     * @param  mixed $item
     * @return bool
     */
    abstract public function validate(array $item): bool;
    /**
     * listItems
     *
     * @return array
     */
    abstract public function listItems(): array;
    /**
     * getItem
     *
     * @param  mixed $itemId
     * @return array
     */
    abstract public function getItem(int $itemId): array;
    /**
     * createItem
     *
     * @param  mixed $item
     * @return int
     */
    abstract public function createItem(array $item): int;
    /**
     * updateItem
     *
     * @param  mixed $itemId
     * @param  mixed $item
     * @return void
     */
    abstract public function updateItem(int $itemId, array $item): void;
    /**
     * deleteItem
     *
     * @param  mixed $itemId
     * @return void
     */
    abstract public function deleteItem(int $itemId): void;
}
