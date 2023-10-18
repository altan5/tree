<?php

namespace Altan\TreeBuilder\View;

/**
 * View
 */
abstract class View
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        protected string $status,
        protected array $data
    ) {
    }

    /**
     * write
     *
     * @return void
     */
    abstract public function write(): void;
}
