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

			if(password_verify($senha, $data["despassword"]) === true )
			{

				$user = new User();

				$user->setData($data);

				$_SESSION[User::SESSION] = $user->getValues(); 

				return $user;
			} else {

				throw new \Exception("usuario não existe, ou senha invalida.. ");
			}
	}

	public static function verifyLogin($inadmin = true)
	{

		if(
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"]> 0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"]!== $inadmin
		)
		{
			header("Location: /admin/login");
			exit;

		}


	}

	public static function logout()
	{

		$_SESSION[User::SESSION] = NULL;


	}
}

 ?>