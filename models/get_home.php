<?php

include_once "../config/conexion.php";
header('Content-Type: text/html; charset=UTF-8');

 /**
  * Usuario model
  */
 class get_categorias{

 	public $con;

 	public function __construct(){
 		try {
				$this->con = Conexion::Conectar();
			} catch (Exception $e) {
				die($e->getMessage());
			}
 	}

 	public function VerProyectos(){

		$consultaProyectos= "SELECT tbl1.idproyecto as ID, concat(ifnull(tbl1.nombre,''),' - ',ifnull(tbl2.nombre,'')) as Nombre FROM gp_proyecto as tbl1
			left join ubigeo_distrito as tbl2 on tbl1.distrito=tbl2.codigo
			where tbl1.esta_borrado=0;";
		return $this->con->query($consultaProyectos);
	}

	public function VerDepartamento(){

			$consultaDep = "SELECT codigo as ID, nombre as Nombre FROM ubigeo_region WHERE codigo!='0'";
			return $this->con->query($consultaDep);

	}



 }

?>