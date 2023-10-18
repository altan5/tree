<?php

namespace Altan\TreeBuilder\Controller;

/**
 * Controller
 */
abstract class Controller
{
    protected string $status = "";
    protected array $data = [];
    /**
     * getStatus
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * getData
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
    /**
     * readRequest
     *
     * @return array
     */
    protected function readRequest(): array
    {
        return (array) json_decode(file_get_contents('php://input'), true);
    }
    /**
     * execute
     *
     * @param  mixed $uri
     * @param  mixed $request_method
     * @return void
     */
    public function execute(array $uri, string $request_method): void
    {
        switch ($request_method) {
            case "GET":
                $this->processGetRequest($uri);
                break;
            case "POST":
                $this->processPostRequest($uri);
                break;
            case "PUT":
                $this->processPutRequest($uri);
                break;
            case "DELETE":
                $this->processDeleteRequest($uri);
                break;
            default:
                break;
        }
    }
    /**
     * processGetRequest
     *
     * @param  mixed $uri
     * @return void
     */
    abstract protected function processGetRequest(array $uri): void;
    /**
     * processPostRequest
     *
     * @param  mixed $uri
     * @return void
     */
    abstract protected function processPostRequest(array $uri): void;
    /**
     * processPutRequest
     *
     * @param  mixed $uri
     * @return void
     */
    abstract protected function processPutRequest(array $uri): void;
    /**
     * processDeleteRequest
     *
     * @param  mixed $uri
     * @return void
     */
    abstract protected function processDeleteRequest(array $uri): void;
}
