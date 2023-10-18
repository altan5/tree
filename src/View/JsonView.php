<?php

namespace Altan\TreeBuilder\View;

/**
 * JsonView
 */
class JsonView extends View
{
    /**
     * write
     *
     * @return void
     */
    public function write(): void
    {
        header($this->status);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header(
            "Access-Control-Allow-Headers: Content-Type," .
            "Access-Control-Allow-Headers, Authorization, X-Requested-With"
        );
        echo json_encode($this->data);
    }
}
