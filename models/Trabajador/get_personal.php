<?php

	/**
	 * 
	 */

	include_once "../../config/conexion.php";

	class get_personal
	{

		public $conec;
		
		public function __construct()
		{
			try {
				$this->conec = Conexion::Conectar();
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}


		public function VerPersonal(){

			$consulta = "SELECT dp.idDatosPersonales as id,cd.nombre_corto as tpd, dp.documento as documento, concat(dp.apellido_paterno,' ',dp.apellido_materno) as apellidos, dp.nombres as nombres,dp.email as email, dp.telefono as telefono, dp.celular as celular, dl.*, cc.*	FROM datos_personales dp, datos_laborales dl, cuentas_corrientes cc, configuracion_detalle cd WHERE dp.tipodocumento=cd.codigo_item AND cd.codigo_tabla='_TIPO_DOCUMENTO' AND dp.documento=dl.documento AND dp.documento=cc.documento AND dp.estado='1' GROUP BY dp.documento";
			return $this->conec->query($consulta);

		}

	}

	?>