<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model{

	const SESSION = "User";

	const SECRET = "HcodePhp77_Secret";

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

		if( !isset($_SESSION[User::SESSION])
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
	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

	public function save()
	{
		$sql = new Sql();

	$sql->select("CALL sp_users_save(:desperson, :despassword, :desemail, :nrphone, :inadmin", array(

		":desperson"=>$this->getdesperson(),
		":deslogin"=>$this->getdeslogin(),
		":despassword"=>$this->getdespassword(),		
		":desemail"=>$this->getdesemail(),
		":nrphone"=>$this->getnrphone(),
		":inadmin"=>$this->getinadmin()
	));

		$this->setData($results[0]);
	}

	public function get($iduser)
	{
		$sql = new Sql();

		$sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
			":iduser"=>$iduser
		));

		$this->setData($results[0]);

	}
	public function update()
	{

		$sql = new Sql();

		$sql->select("CALL sp_usersupdate_save(:desperson, :despassword, :desemail, :nrphone, :inadmin", array(
		":iduser"=>$this->getiduser(),
		":desperson"=>$this->getdesperson(),
		":deslogin"=>$this->getdeslogin(),
		":despassword"=>$this->getdespassword(),		
		":desemail"=>$this->getdesemail(),
		":nrphone"=>$this->getnrphone(),
		":inadmin"=>$this->getinadmin()
	));

		$this->setData($results[0]);
	}

	public function delete()
	{
		$sql = new Sql();

		$sql->query("CALL sp_users_delete(:iduser)",  array(
			"iduser"=>$this->getiduser()

		   ));


	}
	public static Function getForgot($email)
	{

	$sql = new Sql();

	$results = $sql->select("SELECT * 
	FROM tb_persons a
	INNER JOIN tb_users b USING(idoerson)
	WHERE a.desemail =:email;", array(
		":email"=>$email
	));
		if (count($results) === 0)
		{
			throw new Exception("Não foi possivel recuperar a senha do Email do usuario");
		}
		else
		{
			$sql->select("CALL SP_userspassowordsrecoveries_create(:iduser, :desip", array(
				":iduser"=>$data["iduser"],
				":idesip"=>$_SERVER["REMOTE_ADDR"]
			));
			if(count($results2) === 0)
			{
				throw new Exception("Não foi possivel recuperar a senha", 1);
			}
			else
			{
				$dataRecovery = $results2[0];

				base64_encode(openssl_encrypt(MCRYPT_RIJNDAEL_128,User::SECRET, $dataRecovery["idrecovery"], MCRYPT_MODE_ECB));

				$link = "HTTP://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";

				$mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir senha da Hcode Store","forgot",array(
						"name"=>$data["desperson"],
						"link"=>$link
				));
					$mailer->send();

					return $data;

			}


		}

	}

	public static function validForgotDescrypt($code)
	{
		$idrecovery = openssl_encrypt(MCRYPT_RIJNDAEL_128, User::SECRET, base64_decode($code),MCRYPT_MODE_ECB);

			$sql = new Sql();

			$sql->select("SELECT * FROM tb_userspasswordrecoveries a 
						INNER JOIN tb_persons c 
						USING(idperson) 
						WHERE a.idrecovery = :idrecovery AND a.idrecovery IS 
						NULL AND DARA_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();",array(

							":idrecovery"=>$idrecovery
							)); 
			
				if(count($results) === 0)
				{

					throw new \Exception("Não foi possível recuperar a senha.", 1);
				}
				else
				{

					return $results[0];

				}

	}

		public static function setForgotUsed($idrecovery)
		{

			$sql = new Sql();

			$sql->query("UPDATE tb_userspasswordsrecoverie SET dtrecovery = NOW() WHERE idrecovery = :idrecivery", array(
					":idrecovery"=>$idrecovery

			));
		}
}

 ?>