<?php
use Altan\TreeBuilder\Controller\TreeController;
use Altan\TreeBuilder\Tools\Db\MysqlDb;
use Altan\TreeBuilder\View\JsonView;

require_once "bootstrap.php";
require_once "../../vendor/autoload.php";

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri  = explode("/", trim($uri, "/"));
while(array_shift($uri) != 'api');

$db = new MysqlDb(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);

if($uri[0] == "tree") {
    $controller = new TreeController($db);
    $controller->execute($uri, $_SERVER["REQUEST_METHOD"]);
    $view = new JsonView($controller->getStatus(), $controller->getData());
    $view->write();
}
