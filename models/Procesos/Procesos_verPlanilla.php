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


		public function VerPlanilla(){
            include_once "../../config/conexion_2.php";
            $consultar_planilla = mysqli_query($conection, "SELECT mes as m, año as a FROM planilla_operaciones WHERE estado='1'");
            $valores = mysqli_fetch_assoc($consultar_planilla);

            $mes = $valores['m'];
            $año = $valores['a'];




			$consulta = "
            SELECT 

			dp.documento as doc, 
			concat(dp.apellido_paterno,' ',dp.apellido_materno) as apellidos,  
            pi.año as año,
            pi.mes as mes,
            pi.base as base,
            pi.quincena as quincena,
            pi.otros_ingresos as otros_ingresos,
            pi.prestamo as prestamo,
            pi.adelanto as adelanto,
            pi.abono as abono,
            pi.dias as dias,
            pi.descuentos as descuentos,
            pi.seguro as seguro,
            pi.asignacionfamiliar as asignacionfamiliar

			FROM datos_personales dp, planilla_informacion pi
            WHERE dp.documento=pi.documento GROUP BY dp.documento";
			return $this->conec->query($consulta);

		}

	}

	?>