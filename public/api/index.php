<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "../../vendor/autoload.php";

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri  = explode("/", trim($uri, "/"));
while(array_shift($uri) != 'api');

$db = new \Altan\TreeBuilder\Tools\Db\MysqlDb(
	"mysql:host=localhost;dbname=tree_test",
	"tree_test",
	"super_tree_pass"
);

if($uri[0] == "tree") {
    $controller = new \Altan\TreeBuilder\Controller\TreeController($db);
    $controller->execute($uri, $_SERVER["REQUEST_METHOD"]);
    $view = new \Altan\TreeBuilder\View\JsonView($controller->getStatus(), $controller->getData());
    $view->write();
}
