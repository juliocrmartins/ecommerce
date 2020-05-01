<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class SystemConfig extends Model
{
	public function get()
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_systemconfigs");

		$this->setData($results[0]);
	}	
}

 ?>