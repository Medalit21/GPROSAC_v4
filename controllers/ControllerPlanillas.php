<?php

	require_once "../../models/Procesos/Procesos_verPlanilla.php";

	/**
		 * 
		 */
		class ControllerPlanillas
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