<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model{

	public static function login ($login, $senha)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(

				":LOGIN"=>$login
				));

			if(count($results) === 0)
			{

				throw new \Exception("usuario não existe, ou senha invalida ",1);
			}
			
			$data = $results [0];

			if(password_verify($password, $data["despassword"]) === true )
			{

				$user = new User();

				$user->setData($data);

				return $user;
			} else {

				throw new \Exception("usuario não existe, ou senha invalida ",1);
			}
	}

}

 ?>