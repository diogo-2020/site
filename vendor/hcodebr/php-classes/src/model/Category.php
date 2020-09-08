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



	}

}

 ?>