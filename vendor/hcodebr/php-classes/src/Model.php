<?php 

namespace Hcode;

class Model {

	private $values = [];

	public function __call($name, $args)
	{

		$metrod = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name));

	
		switch ($metrod) {
			case 'get':
				return $this->values[$fieldName];
			break;

			case 'set':
				$this->values[$fieldName] = $args[0];
			break;

		}



	}

 	public function setData($data =array())
 	{

 		foreach ($data as $key => $value) {
 			
 			$this->{"set".$key}($value);

 		}

 	}



}




 ?>