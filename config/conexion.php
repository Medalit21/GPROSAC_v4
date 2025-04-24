<?php 
     
	/**
	 * 
	 */
	class Conexion 
	{
		
		public static function Conectar()
		{	
		    //CONEXION SERVIDOR gprosac.acg-soft.com
		    $PDO = new PDO("mysql:host=localhost;dbname=gpros4c_gprosac;charset=utf8","root","");
			$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $PDO;
			
			
			//CONEXION SERVIDOR AWS
			/*
			$PDO = new PDO("mysql:host=ec2-18-213-2-221.compute-1.amazonaws.com;dbname=gprosac_acgsoft;charset=utf8","gpros4c_root","vEC89XXqDO*Z(dFt2s");
			$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $PDO;
			*/
			
			
		/*
		    $PDO = new PDO("mysql:host=localhost;dbname=acgcom_gprosac;charset=utf8","acgcom_gprosac","Ww;S_;d+xmdB");
			$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $PDO;
		
		   	$PDO = new PDO("mysql:host=localhost;dbname=acgsoft_gprosac;charset=utf8","root","");
			$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $PDO;
		*/
		/* $PDO = new PDO("mysql:host=localhost;dbname=acgsoft_nominas;charset=utf8","root","1234");
			$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $PDO;*/
			
		}
	}

?>