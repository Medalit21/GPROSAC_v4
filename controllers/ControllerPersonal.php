<?php

	require_once "../../models/Trabajador/get_personal.php";

	/**
		 * 
		 */
		class ControllerPersonal
		{
			
			public $modelo;

			public function __construct()
			{
				$this->modelo = new get_personal();
			}

			public function VerPersonal(){

				return $this->modelo->VerPersonal()->fetchAll();
			}
		}	

?>