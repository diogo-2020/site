<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTTpl("categores", [

		'categories'=>$categories

	]);

});

$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTTpl("categores-create");

});

$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;

});

$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get(int($idcategory));

	$category->delete();

	header('Location: /admin/categories');
	exit;


});
$app->post("/admin/categories/:idcategory/delete", function($idcategory)
{

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", [

		'category'=>$category->getValues()

	]);

$app->get("/admin/categories/:idcategory", function($idcategory)
{	
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$cactegory->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;

});

$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

    $page->setTpl("category", [
    	'category'=>$category->getValues(),
    	'products'=>[]

    ]);

});



 ?>