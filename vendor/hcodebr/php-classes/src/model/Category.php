<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Category extends Model{

	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
	}

	public function save()
	{

	$sql->select("CALL sp_categories_save(:idcategory, :descategory", array(
		":idcategory"=>$this->getcategory(),
		":descategory"=>$this->getdescategory()
	));

		$this->setData($results[0]);

		Category::updateFile();

	}

	public function get($idcategory)
	{
		$sql = new Sql();

		$sql->select("SELECT * FROM tb_categories WHERE idcartegory = :idcategory", [
				':idcategory'=>$idcategory
		]);

		$this->setData($results[0]);
	}
	public function delete()
	{

		$sql = new Sql();

		$sql->query("DELETE FROM tb_categories WHERE idcartegory = :idcategory", [
				':idcategory'=>$this->geticategory()

		]);

		Category::updateFile();

	}

	public static function updateFile()
	{
		$category = Category::listAll();

		$html = [];

		foreach ($category as $row) {
			array_push($html,'<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
		}

		file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('',$html));

	}

	public function getProducts($related = true)
	{
		$sql = new Sql();

		if ($related === true){

			return $sql->select("
				SELECT * FROM tb_products WHERE id products IN(
					SELECT a.idproduct 
					FROM tb_products a 
					INNER JOIN tb_productscategories bb ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);
			",[
				':idcategory'=>$this->getcategory()
			]);
		}else{
				$sql->select("
				SELECT * FROM tb_products WHERE id products NOT IN(
					SELECT a.idproduct 
					FROM tb_products a 
					INNER JOIN tb_productscategories bb ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				",[
					':idcategory'=>$this->getcategory()
				]);

		}



	}
	
	public function getPorductsPage($page = 1, $itemsPerpage = 3)
	{
		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
		FROM tb_products a 
		INNER JOIN tb_productscategories b 
		ON a.idproduct = b.idproduct 
		INNER JOIN tb_categories c 
		ON c.idcategory = b.idcategory 
		WHERE c.idcategory = :idcategory 
		LIMIT $start,$itemsPerPage;
		
		", []);
		
		
		$resultTotal = $sql->select( "SELECT FOUND_ROWS() AS nrtotal;");
	
		return [
			'data'=>Product::checkList($results),
			'total'=>$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itensPerPage)
		];
	}

	public function removeProduct(Product $product)
	{
		$sql = new Sql();

		$sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduts)", [
				':idcategory'=>$this->getidcategory(),
				':idproduct'=>$product->getidproduct()
		]);



	}
}

 ?>