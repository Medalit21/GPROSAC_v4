<?php

  require_once "../models/get_home.php";
	/**
	 * 
	 */
	class ControllerCategorias
	{
		
			public $mo;

			public function __construct()
			{
				$this->mo = new get_categorias();
			}
			
			public function VerProyectos(){
				return $this->mo->VerProyectos()->fetchAll();
			}

			public function VerDepartamento(){

				return $this->mo->VerDepartamento()->fetchAll();
		}




	}

?>