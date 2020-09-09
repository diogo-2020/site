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

		foreach ($categories as $row) {
			array_push($html,'<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
		}

		file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('',$html));

	}

	

}

 ?>