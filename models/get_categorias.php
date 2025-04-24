<?php

include_once "../../../config/conexion.php";
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

	 public function VerListadoPropiedades(){

		$consultaBS= "SELECT 
		gpl.idlote as ID, 
		concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2),' - ',gpy.nombre) as Nombre 
		FROM gp_lote gpl 
		INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
		INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
		INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
		INNER JOIN gp_venta AS gpv ON gpv.id_lote=gpl.idlote AND gpv.cancelado='0' AND gpv.devolucion='0'
		WHERE gpl.esta_borrado='0' AND gpm.esta_borrado='0'
		ORDER BY gpm.nombre ASC, gpl.nombre ASC";
		return $this->con->query($consultaBS);
	}


	 public function VerEstadoVenta(){
		$consultatd = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_VENTA' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultatd);
	}	

	public function VerClientesBusquedaVenta(){

		$consultaBS= "SELECT 
		dc.id as ID, 
		concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as Nombre 
		FROM datos_cliente dc 
		INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
		WHERE dc.esta_borrado='0' AND gpv.esta_borrado='0'
		ORDER BY dc.apellido_paterno ASC";
		return $this->con->query($consultaBS);
	}

 	
 	public function VerEstadoValidacionDevolucion(){
		$consultatd = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_VALIDA_DEVOLUCION' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultatd);
	}
 	
 	public function VerFiltroTrabajadores(){

		$consultaBS= "SELECT idpersona as ID, CONCAT(SUBSTRING_INDEX(nombre,' ',1),' ',apellido) as Nombre FROM persona WHERE estado='1' AND idArea!='0' ORDER BY nombre asc";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerTiposNotaCredito(){
		$consultatd = "SELECT codigo_sunat as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_NOTA_CREDITO' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultatd);
	}
 	
 	public function VerVendedores(){

		$consultaBS= "SELECT DNI as ID, CONCAT(SUBSTRING_INDEX(nombre,' ',1),' ',SUBSTRING_INDEX(apellido,' ',1)) as Nombre FROM persona WHERE estado='1' AND idArea='4' AND idCargo='3' AND idpersona!=28 GROUP BY nombre ORDER BY nombre asc";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerConceptosFacturacion(){
		$consultatd = "SELECT codigo_sunat as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CONCEPTOS_VENTAS' AND texto3='SI' AND estado='ACTI' ORDER BY texto1 ASC";
		return $this->con->query($consultatd);
	}
 	
 	public function VerTipoDocumentoFacturacion(){
		$consultatd = "SELECT codigo_sunat as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_DOCUMENTO' AND estado='ACTI' ORDER BY texto1 ASC";
		return $this->con->query($consultatd);
	}
 	
 	public function VerMesRecaudaciones(){
		$consultaMes = "SELECT texto1 as ID, nombre_largo as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MES' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
 	
 	public function VerTipoArchivoRecaudacion(){

		$consultaBS= "SELECT texto1 as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_ARCHIVO_RECAUDACION' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
	
	public function VerTipoRegistroRecaudacion(){

		$consultaBS= "SELECT texto1 as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_REGISTRO_RECAUDACION' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerNumCuentaBanco(){

		$consultaBS= "SELECT nombre_corto as ID, concat(nombre_corto,' (',texto1,'-',texto2,')') as Nombre FROM configuracion_detalle WHERE codigo_tabla='_NRO_CUENTA_BANCO' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerEstadoFacturacionPago(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_FACTURACION_PAGO' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}
	
	public function VerEstadoValidacionPago(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_VALIDACION_PAGO' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}


	public function VerConceptos(){

		$consultaBS= "SELECT codigo_sunat as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CONCEPTOS_VENTAS' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}

 	public function VerTipoComprobanteSunatImpr(){

		$consultaBS= "SELECT codigo_sunat as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE_SUNAT' AND estado='ACTI' AND texto1='SI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
 	
 	
 	public function VerFlujoCaja(){

		$consultaBS= "SELECT codigo_sunat as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_FLUJO_CAJA' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerTipoComprobanteSunat(){

		$consultaBS= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE_SUNAT' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerTipoPagoC(){

		$consultaBS= "SELECT codigo_item as ID, texto1 as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_PAGO_C' ORDER BY texto1 ASC";
		return $this->con->query($consultaBS);
	}

	public function VerClientesBusqueda(){

		$consultaBS= "SELECT documento as ID, concat(apellido_paterno,' ',apellido_materno,' ',SUBSTRING_INDEX(nombres,' ',1)) as Nombre FROM datos_cliente WHERE esta_borrado='0' ORDER BY apellido_paterno ASC";
		return $this->con->query($consultaBS);
	}
	
 	
 	public function VerTipoAmortizacion(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_AMORTIZACION' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerEstadoAdenda(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_ADENDA' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}

	public function VerTiposAdenda(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_ADENDA' AND codigo_item IN ('2','4') AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}

 	
 	public function VerEstadosVP(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_VP' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}

	public function VerEstadoPagosEC(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_EC' AND codigo_item IN ('2','4') AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}
	
	public function VerEstadosEC(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_EC' AND estado='ACTI' ORDER BY codigo_item ASC";
		return $this->con->query($consultaBS);
	}
	
	public function VerEstadosLoteInventarioReport(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND texto2='E' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
	
	public function VerMotivosLoteInventarioReport(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND texto2='M' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
 	
 	public function VerEstadosLoteBloqueo(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND texto2='E' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}
	
	public function VerEstadosReportePropietarios(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND codigo_item IN (5,6,8) ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}

	public function VerMotivosLoteBloqueo(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND texto2='M' AND nombre_corto!='BLOQUEADO' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaBS);
	}

 	public function VerEstadosReporteReservas(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND codigo_item IN (1,2) ORDER BY nombre_corto DESC";
		return $this->con->query($consultaBS);
	}

	public function VerMotivosReporteReservas(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND codigo_item IN (3, 4, 7) ORDER BY nombre_corto";
		return $this->con->query($consultaBS);
	}
	
	public function VerMotivosReporteVentas(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND codigo_item IN (5, 6, 8) ORDER BY nombre_corto";
		return $this->con->query($consultaBS);
	}

	public function VerFiltroLote(){

		$consultaBS= "SELECT nombre as ID, nombre as Nombre FROM gp_lote WHERE esta_borrado=0 GROUP BY nombre ORDER BY correlativo asc";
		return $this->con->query($consultaBS);
	}
	
	public function VerMedioCaptaciones(){

		$consultaMediO = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_CAPTACION' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMediO);

	}


	public function VerFiltroClientes(){

		$consultaBS= "SELECT idusuario as ID, nombre as Nombre FROM persona WHERE estatus='Activo' GROUP BY nombre ORDER BY nombre asc";
		return $this->con->query($consultaBS);
	}
	
	
	public function VerFiltroVendedor(){

		$consultaBS= "SELECT idusuario as ID, CONCAT(SUBSTRING_INDEX(nombre,' ',1),' ',SUBSTRING_INDEX(apellido,' ',1)) as Nombre FROM persona WHERE estatus='Activo' AND idArea='4' AND idCargo='3' AND idpersona!=28 GROUP BY nombre ORDER BY nombre asc";
		return $this->con->query($consultaBS);
	}


 	public function VerNotarias(){

		$consultaBS= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_NOTARIA' AND estado='ACTI' ORDER BY orden ASC";
		return $this->con->query($consultaBS);

	}

 	
 	public function VerProyectoss(){

		$consultaBS= "SELECT idproyecto as ID, nombre as Nombre FROM gp_proyecto WHERE estado='1' ORDER BY nombre";
		return $this->con->query($consultaBS);

	}
 	

	public function VerMedioPago(){

		$consultaBS= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_PAGO' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaBS);

	}


	public function VerTipoComprobanteVentas(){

		$consultaBS= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE' AND codigo_item='3' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaBS);

	}

 	
 	 public function VerTipoCasa(){

		$consultaBS= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CASA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaBS);

	}
	
	public function VerTipoCasaReporte(){

		$consultaBS= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CASA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaBS);

	}

	 public function VerManzanas($idzona){

		$consultaBS= "SELECT idmanzana as ID, nombre as Nombre FROM gp_manzanas WHERE idzona='$idzona' AND estado='1' ORDER BY nombre";
		return $this->con->query($consultaBS);

	}

	public function VerZonas($idproyecto){

		$consultaBS= "SELECT idzona as ID, nombre as Nombre FROM gp_zona WHERE idproyecto='$idproyecto' AND estado='1' ORDER BY nombre";
		return $this->con->query($consultaBS);

	}
 	
 	public function VerBeneficiosSociales(){

		$consultaBS= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_BENEFICIO_SOCIAL' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaBS);

	}


	public function VerSimbolosUnidades(){

		$consultaSU= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SIMBOLO_UNIDAD' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaSU);

	}
 	
 	public function VerFormatoBoleta(){

		$consultatd = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_FORMATO_BOLETAS' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($consultatd);

	}

	public function VerTipoComprobante(){

		$consultatd = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE_PREST_SERV' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($consultatd);

	}


 	public function VerTipoDocumento(){

			$consultatd = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_DOCUMENTO' AND estado='ACTI' ORDER BY texto1 ASC";
			return $this->con->query($consultatd);

	}

	public function VerGeneroPersonal(){

			$consultagen = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_GENERO' AND estado='ACTI'";
			return $this->con->query($consultagen);

	}

	public function VerEstadoCivil(){

			$consultaest = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_CIVIL' AND estado='ACTI'";
			return $this->con->query($consultaest);

	}

	public function VerDiscapacidad(){

			$consultadisc = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_DISCAPACIDAD' AND estado='ACTI'";
			return $this->con->query($consultadisc);

	}

	public function VerPais(){

		$consultaDep = "SELECT codigo as ID, nombre as Nombre FROM ubigeo_pais WHERE codigo!='0'";
		return $this->con->query($consultaDep);

	}


	public function VerDepartamento(){

			$consultaDep = "SELECT codigo as ID, nombre as Nombre FROM ubigeo_region WHERE codigo!='0'";
			return $this->con->query($consultaDep);

	}

	public function VerProvincia(){

			$consultaProv = "SELECT codigo as ID, nombre as Nombre FROM ubigeo_provincia WHERE codigo!='0'";
			return $this->con->query($consultaProv);

	}

	public function VerDistrito(){

			$consultaDist = "SELECT codigo as ID, nombre as Nombre FROM ubigeo_distrito WHERE codigo!='0'";
			return $this->con->query($consultaDist);

	}


	public function VerGrupoSanguineo(){

			$consultags = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_GRUPO_SANGUINEO' AND estado='ACTI'";
			return $this->con->query($consultags);

	}


	public function VerSituacionDomiciliaria(){

			$consultasitdom = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SITUACION_DOMICILIARIA' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultasitdom);

	}


	public function VerTipoActividad(){

			$consultaTipAct = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_ACTIVIDAD' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaTipAct);

	}


	public function VerCategoriaOcupacional($sector){

			/*require_once "../../../config/conexion_2.php";
			$consulta_sec = mysqli_query($conection, "SELECT valor as val FROM datos_empresa WHERE codigo=1");
			$consulta_sector = mysqli_fetch_assoc($consulta_sec);

			$sector=$consulta_sector['val'];*/
			if($sector=="SECTOR_PRIVADO"){
				$sector="texto1";
			}else{
				if($sector=="SECTOR PUBLICO"){
					$sector="texto2";
				}else{
					$sector="texto1";
				}}

			$consultaTipAct = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CATEGORIA_OCUPACIONAL' AND estado='ACTI' AND $sector='A' ORDER BY nombre_corto";
			return $this->con->query($consultaTipAct);

	}


	public function VerOcupacionAplicable(){

			$consultaOcupApli = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_OCUPACION_SP' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaOcupApli);

	}

	public function VerCargoSunat(){

		$cargoSunat = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_OCUPACION_SPPF' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($cargoSunat);

	}

	public function VerCargoInterno(){

		$cargointerno = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CARGO_INTERNO' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($cargointerno);

	}

	public function VerArea(){

		$area = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_AREA' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($area);

	}

	public function VerCentroCosto(){

		$CentroCosto = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CENTRO_COSTO' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($CentroCosto);

	}

	public function VerVinculoFamiliar(){

		$VinculoFamiliar = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_VINCULO_FAMILIAR' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($VinculoFamiliar);

	}

	public function VerTipoPagoBanco(){

		$TipoPagoBanco = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_PAGO_BANCO' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($TipoPagoBanco);

	}

	public function VerBancos(){

		$Bancos = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_BANCOS' AND texto1='SI' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($Bancos);

	}

	public function VerTipoCuenta(){

		$TipoCuenta = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CUENTA' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($TipoCuenta);

	}

	public function VerMoneda(){

		$Moneda = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MONEDA' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($Moneda);

	}

	public function VerTallaUniforme(){

		$TallaUniforme = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TALLA_UNIFORME' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($TallaUniforme);

	}


	public function VerSituacionEducativa(){

			$consultaSitEdu = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SITUACION_EDUCATIVA' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaSitEdu);

	}


	public function VerRegimenPensionario(){

			$consultaRegPen = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_REGIMEN_PENSIONARIO' AND estado='ACTI' AND texto1='A' ORDER BY nombre_corto";
			return $this->con->query($consultaRegPen);

	}


	public function VerNacionalidad(){

			$consultaNacionalidad = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_NACIONALIDAD' AND estado='ACTI' ORDER BY nombre_corto ASC";
			return $this->con->query($consultaNacionalidad);

	}


	public function VerTipoPago(){

			$consultaTipoPago = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_PAGO' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaTipoPago);

	}


	public function VerTipoContrato(){

			$consultaTipoContrato = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CONTRATO' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaTipoContrato);

	}


	public function VerPeriodicidad(){

			$consultaPeriodicidad = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_PERIODICIDAD' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaPeriodicidad);

	}


	public function VerSituacionTrabajador(){

			$consultaSitTrab = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SITUACION_TRAB' AND estado='ACTI' ORDER BY nombre_corto";
			return $this->con->query($consultaSitTrab);

	}

	public function VerMeses(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MES' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerEstadoTrabajador(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_TRABAJADOR' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerMotivoEliminarEmpleado(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MOTIVO_ELIMINAR_EMPLEADO' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerSituacionFinanciera(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SIT_FINANCIERA' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerComision(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_COMISION' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerMesesNum(){

		$consultaMes = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MES' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerMotivoLicencia()
    {

        $consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_SUSPENSION' AND estado='ACTI' AND texto2='SI' ORDER BY codigo_item";
        return $this->con->query($consultaMes);

    }

	public function VerVia(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_VIA' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerZona(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ZONA' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);

	}

	public function VerPaisEmisorDoc(){
		$consultaMes = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_PAIS_EMISOR_DOC' AND estado='ACTI' ORDER BY nombre_corto ASC";
		return $this->con->query($consultaMes);
	}

	public function VerSituacionEspecial(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SITUACION_ESPECIAL' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerCategoriaPersonal(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CATEGORIA_TRABAJADOR' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerTipoTrabajador(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_TRAB_PPS' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerRegimenLaboral(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_REGIMEN_LABORAL' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerConveniosDT(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CONVENIOS' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerRegimenAsegSalud(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_REG_ASEG_SALUD' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerEPSSevPropios(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_EPSSERV_PROPIOS' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerCoberturaPension(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SCTR_PENSION' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerCoberturaSalud(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SCTR_SALUD' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerSistemaFinanciero(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ENTIDAD_SIT_FINANCIERA' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}
	public function VerAcreditaVincFam(){
		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ACREDITA_VINC_FAM' AND estado='ACTI' ORDER BY codigo_item";
		return $this->con->query($consultaMes);
	}

	public function VerMotivoBajaRegistro($sector){
		if($sector=="SECTOR_PRIVADO"){
			$sector="texto1";
		}else{
			if($sector=="SECTOR PUBLICO"){
				$sector="texto2";
			}else{
				$sector="texto1";
			}}

		$consultaTipAct = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MOTIVO_BAJA_REG' AND estado='ACTI' AND $sector='A' ORDER BY nombre_corto";
		return $this->con->query($consultaTipAct);
    }
    
    public function VerEmpresas(){

		$consultaMes = "SELECT id as id, nombre as Nombre FROM configuracion_empresas WHERE estado='1' ORDER BY nombre";
		return $this->con->query($consultaMes);

	}

	public function VerUsuariosResponsables(){

		$consultaResponsables = "SELECT u.idusuario as id, concat(SUBSTRING_INDEX(du.nombres,' ',1),' ',SUBSTRING_INDEX(du.apellidos,' ',1)) as datos FROM usuario u, datos_usuarios du WHERE u.iddatos_usuario=du.iddatos_usuario AND u.estado='1' ORDER BY du.nombres";
		return $this->con->query($consultaResponsables);

	}

	
	public function VerTamanoEmpresa(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TAMANO_EMPRESA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerSectorEmpresa(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_SECTOR_EMPRESA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerOrigenEmpresa(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ORIGEN_EMPRESA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerConstJuridicaEmpresa(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CONST_JURIDICA_EMPRESA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerDestBeneficiosEmpresa(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_DEST_BENEFICIOS_EMPRESA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerAmbitoActEmpresa(){

		$consultaMes = "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_AMBITO_ACT_EMPRESA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerEstadosPeriodos(){

		$consultaMes = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADOS_PERIODO_PLANILLAS' AND estado='ACTI' AND codigo_item!='2' ORDER BY idconfig_detalle";
		return $this->con->query($consultaMes);

	}

	public function VerConceptosPlanilla(){

		$consultaConceptos = "SELECT codigo_item as ID, nombre_largo as Nombre FROM configuracion_detalle WHERE codigo_tabla='_PLANILLA_CONCEPTOS' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaConceptos);

	}

	public function VerTipoMoneda(){

		$consultaConceptos = "SELECT idconfig_detalle as ID, texto1 as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaConceptos);

	}

	
	public function VerTipoMoneda2(){

		$consultaConceptos = "SELECT codigo_item as ID, texto1 as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaConceptos);

	}

	public function VerTipoMonedaSimbolo(){

		$consultaConceptos = "SELECT idconfig_detalle as ID, texto1 as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaConceptos);

	}

	public function VerAFP(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_REGIMEN_PENSIONARIO' AND texto4='SPP' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}

	public function VerTipoPlanilla(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_PLANILLA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}


	public function VerPeriodosPlanilla($idempresa){

		$consultaPeriodo= "call pa_periodos_parametros('$idempresa')";
		return $this->con->query($consultaPeriodo);

	}
	
	public function VerPeriodosAperturaCierre($idempresa){

		$consultaPeriodos= "call pa_periodos_apertura_cierre('$idempresa')";
		return $this->con->query($consultaPeriodos);

	}
	
	public function VerEstadoOperacionAC(){

		$consultaAfp= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_OPERACION_AC' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}


public function VerAnio(){
	$consultaAnio = "SELECT anio AS ID, anio AS Nombre FROM configuracion_anio WHERE esta_borrado=0 ORDER BY anio DESC";
	return $this->con->query($consultaAnio);
}

	public function VerInstitucionRegimen(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_INTITUCION_REGIMEN' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}
	public function VerTipoInstitucionEducativa(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_INSTITUCION_EDUCATIVA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}

	public function VerIdioma(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_IDIOMA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}
	public function VerNivelIdioma(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_NIVEL_IDIOMA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}

	public function VerComputacionPrograma(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_COMPUTACION_PROGRAMA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}
	public function VerNivelPrograma(){

		$consultaAfp= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_NIVEL_PROGRAMA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaAfp);

	}
       public function VerTipoCorreo(){

		$consultaTipoCorreo= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CORREO' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaTipoCorreo);
	}
		public function VerTipoPropiedad(){

		$consultaTipoPropiedad= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_PROPIEDAD' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaTipoPropiedad);
	}
		public function VerProyectos(){

		$consultaProyectos= "SELECT tbl1.idproyecto as ID, concat(ifnull(tbl1.nombre,''),' - ',ifnull(tbl2.nombre,'')) as Nombre FROM gp_proyecto as tbl1
		left join ubigeo_distrito as tbl2 on tbl1.distrito=tbl2.codigo
		where tbl1.esta_borrado=0;";
		return $this->con->query($consultaProyectos);
	}
	
		public function VerTipoMonedaSigla(){

		$consultaTipoPropiedad= "SELECT idconfig_detalle as ID, texto1 as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaTipoPropiedad);
	}
	public function VerMotivoLiberacion(){

		$consultaTipoPropiedad= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MOTIVO_LIBERACION' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consultaTipoPropiedad);
	}
	public function VerTipoComprobanteVenta(){

		$consulta= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE_VENTA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consulta);
	}
	public function VerTipoCondicionVenta(){

		$consulta= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_CONDICION_VENTA' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consulta);
	}
	public function VerTipoCreditoVenta(){

		$consulta= "SELECT idconfig_detalle as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CREDITO' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consulta);
	}
		public function VerTipoInmueble(){

		$consulta= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_INMUEBLE' AND estado='ACTI' ORDER BY idconfig_detalle";
		return $this->con->query($consulta);
	}
	
	public function VerTipoDocumentoVenta(){

		$consulta= "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_DOCUMENTO_VENTA' AND estado='ACTI' ORDER BY nombre_corto";
		return $this->con->query($consulta);
	}
	/************* Personal ************/
	public function VerCargo(){

		$cargo = "SELECT idcargo as ID, cargo as Nombre FROM cargo WHERE idcargo!='5' ORDER BY cargo asc";
		return $this->con->query($cargo);

	}
	public function VerAreaPers(){

		$area = "SELECT idArea as ID, area as Area FROM area";
		return $this->con->query($area);
	}
	public function VerPerfilUsu(){
		$perfil = "SELECT idPerfil as ID, descripcion as Descripcion FROM perfiles WHERE estado='1' AND idPerfil!='8'";
		return $this->con->query($perfil);
	}
	
	public function VerJefeInmUsu(){
		$jefeinm = "SELECT idusuario as ID, CONCAT(SUBSTRING_INDEX(nombre,' ',1),' ',SUBSTRING_INDEX(apellido,' ',1)) as Nombre FROM persona WHERE estado='1' AND idArea!='0' order by Nombre";
		return $this->con->query($jefeinm);
	}
	
	public function VerGeneroUsuario(){

			$consultusu = "SELECT codigo_item as ID, nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_GENERO' AND estado='ACTI'";
			return $this->con->query($consultusu);

	}
	
	
	public function VerListaClientesGpro($txtFiltroDocumentoCP='', $bxFiltroProyectoCP='',$bxFiltroZonaCP='',$bxFiltroManzanaCP='',$bxFiltroLoteCP='',$bxFiltroEstadoCP=''){

		$query_documento="";
        $query_proyecto="";
        $query_zona="";
        $query_manzana="";
        $query_lote="";
        $query_estado="";

        if(!empty($txtFiltroDocumentoCP)){
            $query_documento = "AND cli.documento='$txtFiltroDocumentoCP'";
        }

        if(!empty($bxFiltroProyectoCP)){
            $query_proyecto = "AND gpy.idproyecto='$bxFiltroProyectoCP'";
        }

        if(!empty($bxFiltroZonaCP)){
            $query_zona = "AND gpz.idzona='$bxFiltroZonaCP'";
        }

        if(!empty($bxFiltroManzanaCP)){
            $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaCP'";
        }

        if(!empty($bxFiltroLoteCP)){
            $query_lote = "AND gpl.idlote='$bxFiltroLoteCP'";
        }

        if(!empty($bxFiltroEstadoCP)){
            $query_estado = "AND gpv.cancelado='$bxFiltroEstadoCP'";
        }

		$consultusu = "SELECT
        gpv.id_venta AS id,
        gpv.fecha_venta AS fecha,
        CONCAT(cli.apellido_paterno,' ',cli.apellido_materno,' ',cli.nombres) as cliente,
        concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
        format((gpv.total),2) AS total_venta,
        format(((select SUM(gpc.monto_letra) from gp_cronograma gpc where gpc.id_venta=gpv.id_venta)-(gpv.total)),2) AS intereses,
        format(((select SUM(gpc.monto_letra) from gp_cronograma gpc where gpc.id_venta=gpv.id_venta)),2) AS total_financiado,
        format(((select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta)),2) AS total_pagado,
        format(((select SUM(gcr.monto_letra) from gp_cronograma gcr where gcr.id_venta=gpv.id_venta)-(select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta)),2) AS total_pendiente,
        if(gpv.cancelado='1','CANCELADO','ACTIVO') as estado,
        if(gpv.cancelado='1','red','green') as color_estado
        FROM datos_cliente cli
        INNER JOIN gp_venta  AS gpv ON gpv.id_cliente=cli.id
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
        WHERE gpv.estado='1' 
        AND gpv.conformidad='1' 
        AND cli.esta_borrado='0'
        $query_documento
        $query_proyecto
        $query_zona
        $query_manzana
        $query_lote
        $query_estado
        ORDER BY cli.apellido_paterno ASC";
		return $this->con->query($consultusu);

	}
	
	public function VerCronogramaPagosGpro($Codigo){

		$consultusu = "SELECT 
		gpcp.id as id,
		date_format(gpcp.fecha_vencimiento, '%d/%m/%Y') as fecha,
		gpcp.item_letra as letra,
		format(gpcp.monto_letra,2) as monto,
		format(gpcp.interes_amortizado,2) as intereses,
		format(gpcp.capital_amortizado,2) as amortizacion,
		format(gpcp.capital_vivo,2) as capital_vivo,
		format(gpcp.monto_letra,2) as pagado,
		gpcp.estado as estado,
		cd.nombre_corto as descEstado,
		cd.texto1 as color
		FROM gp_cronograma gpcp
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcp.estado AND cd.codigo_tabla='_ESTADO_EC'
		INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcp.id_venta
		INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
		WHERE gpcp.esta_borrado=0 AND gpv.id_venta='$Codigo'
		ORDER BY gpcp.correlativo ASC";
		return $this->con->query($consultusu);

	}
	
	/************* AGREGADO *****************/
	public function VerFiltroMant(){
		$consultaMN= "Select codigo_tabla, nombre_largo from configuracion_cabecera cab order by cab.nombre_largo ASC";
		return $this->con->query($consultaMN);
	}
	
	public function VerCatDetalle(){

			$consultatd = "SELECT * FROM configuracion_cabecera cab ORDER BY cab.nombre_largo ASC;";
			return $this->con->query($consultatd);

	}
	
	/************* AGREGADO *****************/

 }
 