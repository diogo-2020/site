<?php 

require_once("vendor/autoload.php");
 
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\model\User; 

$app = new Slim();

$app->config('debug', true);

require_once("site.php"); 
require_once("admin.php");
require_once("admin-products.php");
require_once("admin-categories.php");
require_once("admin-users.php");


$app->run();
 
?>
 