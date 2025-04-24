<?php

  require_once "../../../models/get_categorias.php";
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

		public function VerListadoPropiedades(){
			return $this->mo->VerListadoPropiedades()->fetchAll();
		}


		public function VerEstadoVenta(){
			return $this->mo->VerEstadoVenta()->fetchAll();
		}

		public function VerClientesBusquedaVenta(){
			return $this->mo->VerClientesBusquedaVenta()->fetchAll();
		}
		
		public function VerEstadoValidacionDevolucion(){
			return $this->mo->VerEstadoValidacionDevolucion()->fetchAll();
		}
		
		public function VerFiltroTrabajadores(){
			return $this->mo->VerFiltroTrabajadores()->fetchAll();
		}
		
		public function VerFiltroMant(){
			return $this->mo->VerFiltroMant()->fetchAll();
		}
		
		
		public function VerTiposNotaCredito(){
			return $this->mo->VerTiposNotaCredito()->fetchAll();
		}
		
		public function VerVendedores(){
			return $this->mo->VerVendedores()->fetchAll();
		}
		
		public function VerConceptosFacturacion(){
			return $this->mo->VerConceptosFacturacion()->fetchAll();
		}

		
		public function VerTipoDocumentoFacturacion(){
			return $this->mo->VerTipoDocumentoFacturacion()->fetchAll();
		}
		
		public function VerMesRecaudaciones(){
			return $this->mo->VerMesRecaudaciones()->fetchAll();
		}
		
		
		public function VerTipoArchivoRecaudacion(){
			return $this->mo->VerTipoArchivoRecaudacion()->fetchAll();
		}
		
		public function VerTipoRegistroRecaudacion(){
			return $this->mo->VerTipoRegistroRecaudacion()->fetchAll();
		}
		
		public function VerNumCuentaBanco(){
			return $this->mo->VerNumCuentaBanco()->fetchAll();
		}
		
		public function VerEstadoFacturacionPago(){
			return $this->mo->VerEstadoFacturacionPago()->fetchAll();
		}
		
		public function VerEstadoValidacionPago(){
			return $this->mo->VerEstadoValidacionPago()->fetchAll();
		}

		public function VerConceptos(){
			return $this->mo->VerConceptos()->fetchAll();
		}

		public function VerTipoComprobanteSunatImpr(){
			return $this->mo->VerTipoComprobanteSunatImpr()->fetchAll();
		}
		
		public function VerFlujoCaja(){
			return $this->mo->VerFlujoCaja()->fetchAll();
		}
		
		public function VerTipoComprobanteSunat(){
			return $this->mo->VerTipoComprobanteSunat()->fetchAll();
		}
		
		public function VerTipoPagoC(){
			return $this->mo->VerTipoPagoC()->fetchAll();
		}
		
		public function VerClientesBusqueda(){
			return $this->mo->VerClientesBusqueda()->fetchAll();
		}
		
		public function VerTipoAmortizacion(){
			return $this->mo->VerTipoAmortizacion()->fetchAll();
		}
		
		public function VerEstadoAdenda(){
			return $this->mo->VerEstadoAdenda()->fetchAll();
		}

		public function VerTiposAdenda(){
			return $this->mo->VerTiposAdenda()->fetchAll();
		}
		
		public function VerEstadosVP(){
			return $this->mo->VerEstadosVP()->fetchAll();
		}

		public function VerEstadoPagosEC(){
			return $this->mo->VerEstadoPagosEC()->fetchAll();
		}
		public function VerEstadosEC(){
			return $this->mo->VerEstadosEC()->fetchAll();
		}
		
		public function VerEstadosLoteInventarioReport(){
			return $this->mo->VerEstadosLoteInventarioReport()->fetchAll();
		}
		
		public function VerMotivosLoteInventarioReport(){
			return $this->mo->VerMotivosLoteInventarioReport()->fetchAll();
		}
		
		public function VerEstadosLoteBloqueo(){
			return $this->mo->VerEstadosLoteBloqueo()->fetchAll();
		}
		
		public function VerEstadosReportePropietarios(){
			return $this->mo->VerEstadosReportePropietarios()->fetchAll();
		}

		public function VerMotivosLoteBloqueo(){
			return $this->mo->VerMotivosLoteBloqueo()->fetchAll();
		}

		public function VerEstadosReporteReservas(){
			return $this->mo->VerEstadosReporteReservas()->fetchAll();
		}

		public function VerMotivosReporteReservas(){
			return $this->mo->VerMotivosReporteReservas()->fetchAll();
		}
		
		public function VerMotivosReporteVentas(){
			return $this->mo->VerMotivosReporteVentas()->fetchAll();
		}


		public function VerFiltroLote(){
			return $this->mo->VerFiltroLote()->fetchAll();
		}
		public function VerMedioCaptaciones(){
			return $this->mo->VerMedioCaptaciones()->fetchAll();
		}

		public function VerFiltroClientes(){
			return $this->mo->VerFiltroClientes()->fetchAll();
		}
		
		public function VerFiltroVendedor(){
			return $this->mo->VerFiltroVendedor()->fetchAll();
		}


		public function VerNotarias(){
			return $this->mo->VerNotarias()->fetchAll();
		}
		
		public function VerProyectoss(){
			return $this->mo->VerProyectoss()->fetchAll();
		}

		public function VerMedioPago(){
			return $this->mo->VerMedioPago()->fetchAll();
		}

		public function VerTipoComprobanteVentas(){
			return $this->mo->VerTipoComprobanteVentas()->fetchAll();
		}
		
		public function VerTipoCasa(){
			return $this->mo->VerTipoCasa()->fetchAll();
		}
		
		public function VerTipoCasaReporte(){
			return $this->mo->VerTipoCasaReporte()->fetchAll();
		}


		public function VerManzanas($idzona){
			return $this->mo->VerManzanas($idzona)->fetchAll();
		}

		public function VerZonas($idproyecto){
			return $this->mo->VerZonas($idproyecto)->fetchAll();
		}
		
		public function VerFormatoBoleta(){
			return $this->mo->VerFormatoBoleta()->fetchAll();
		}

		public function VerTipoComprobante(){
			return $this->mo->VerTipoComprobante()->fetchAll();
		}

		public function VerBeneficiosSociales(){
			return $this->mo->VerBeneficiosSociales()->fetchAll();
		}

		public function VerSimbolosUnidades(){
			return $this->mo->VerSimbolosUnidades()->fetchAll();
		}


		public function VerTipoDocumento(){

				return $this->mo->VerTipoDocumento()->fetchAll();
		}

		public function VerGeneroPersonal(){

				return $this->mo->VerGeneroPersonal()->fetchAll();
		}

		public function VerEstadoCivil(){

				return $this->mo->VerEstadoCivil()->fetchAll();
		}

		public function VerDiscapacidad(){

				return $this->mo->VerDiscapacidad()->fetchAll();
		}

		public function VerPais(){

			return $this->mo->VerPais()->fetchAll();
		}

		public function VerDepartamento(){

				return $this->mo->VerDepartamento()->fetchAll();
		}

		public function VerProvincia(){

				return $this->mo->VerProvincia()->fetchAll();
		}

		public function VerDistrito(){

				return $this->mo->VerDistrito()->fetchAll();
		}


		public function VerGrupoSanguineo(){

				return $this->mo->VerGrupoSanguineo()->fetchAll();
		}


		public function VerSituacionDomiciliaria(){

				return $this->mo->VerSituacionDomiciliaria()->fetchAll();
		}


		public function VerTipoActividad(){

				return $this->mo->VerTipoActividad()->fetchAll();
		}

		public function VerCategoriaOcupacional($sector){

				return $this->mo->VerCategoriaOcupacional($sector)->fetchAll();
		}


		public function VerOcupacionAplicable(){

				return $this->mo->VerOcupacionAplicable()->fetchAll();
		}

		public function VerCargoSunat(){

			return $this->mo->VerCargoSunat()->fetchAll();
		}

		public function VerCargoInterno(){

			return $this->mo->VerCargoInterno()->fetchAll();
		}

		public function VerArea(){

			return $this->mo->VerArea()->fetchAll();
		}

		public function VerCentroCosto(){

			return $this->mo->VerCentroCosto()->fetchAll();
		}

		public function VerVinculoFamiliar(){

			return $this->mo->VerVinculoFamiliar()->fetchAll();
		}

		public function VerTipoPagoBanco(){

			return $this->mo->VerTipoPagoBanco()->fetchAll();
		}

		public function VerBancos(){

			return $this->mo->VerBancos()->fetchAll();
		}

		public function VerTipoCuenta(){

			return $this->mo->VerTipoCuenta()->fetchAll();
		}

		public function VerMoneda(){

			return $this->mo->VerMoneda()->fetchAll();
		}

		public function VerSituacionEducativa(){

				return $this->mo->VerSituacionEducativa()->fetchAll();
		}


		public function VerRegimenPensionario(){

				return $this->mo->VerRegimenPensionario()->fetchAll();
		}


		public function VerNacionalidad(){

				return $this->mo->VerNacionalidad()->fetchAll();
		}


		public function VerTipoPago(){

				return $this->mo->VerTipoPago()->fetchAll();
		}


		public function VerTipoContrato(){

				return $this->mo->VerTipoContrato()->fetchAll();
		}


		public function VerPeriodicidad(){

				return $this->mo->VerPeriodicidad()->fetchAll();
		}


		public function VerSituacionTrabajador(){

				return $this->mo->VerSituacionTrabajador()->fetchAll();
		}

		public function VerTallaUniforme(){

			return $this->mo->VerTallaUniforme()->fetchAll();
		}


		public function VerMeses(){

			return $this->mo->VerMeses()->fetchAll();
		}

		public function VerEstadoTrabajador(){

			return $this->mo->VerEstadoTrabajador()->fetchAll();
		}


		public function VerMotivoEliminarEmpleado(){

			return $this->mo->VerMotivoEliminarEmpleado()->fetchAll();
		}

		public function VerSituacionFinanciera(){

			return $this->mo->VerSituacionFinanciera()->fetchAll();
		}

		public function VerComision(){

			return $this->mo->VerComision()->fetchAll();
		}

		public function VerMesesNum(){

			return $this->mo->VerMesesNum()->fetchAll();
		}

		public function VerMotivoLicencia(){

			return $this->mo->VerMotivoLicencia()->fetchAll();
		}
		public function VerVia(){

			return $this->mo->VerVia()->fetchAll();
		}
		public function VerZona(){

			return $this->mo->VerZona()->fetchAll();
		}
		public function VerPaisEmisorDoc(){
			return $this->mo->VerPaisEmisorDoc()->fetchAll();
		}
		public function VerSituacionEspecial(){
			return $this->mo->VerSituacionEspecial()->fetchAll();
		}
		public function VerCategoriaPersonal(){
			return $this->mo->VerCategoriaPersonal()->fetchAll();
		}
		public function VerTipoTrabajador(){
			return $this->mo->VerTipoTrabajador()->fetchAll();
		}
		public function VerRegimenLaboral(){
			return $this->mo->VerRegimenLaboral()->fetchAll();
		}
		public function VerConveniosDT(){
			return $this->mo->VerConveniosDT()->fetchAll();
		}
		public function VerRegimenAsegSalud(){
			return $this->mo->VerRegimenAsegSalud()->fetchAll();
		}
		public function VerEPSSevPropios(){
			return $this->mo->VerEPSSevPropios()->fetchAll();
		}
		public function VerCoberturaPension(){
			return $this->mo->VerCoberturaPension()->fetchAll();
		}
		public function VerCoberturaSalud(){
			return $this->mo->VerCoberturaSalud()->fetchAll();
		}
		public function VerSistemaFinanciero(){
			return $this->mo->VerSistemaFinanciero()->fetchAll();
		}
		public function VerAcreditaVincFam(){
			return $this->mo->VerAcreditaVincFam()->fetchAll();
		}

		public function VerMotivoBajaRegistro($sector){
			return $this->mo->VerMotivoBajaRegistro($sector)->fetchAll();
		}
		
		public function VerEmpresas(){

			return $this->mo->VerEmpresas()->fetchAll();
		}

		public function VerUsuariosResponsables(){

			return $this->mo->VerUsuariosResponsables()->fetchAll();
		}

		public function VerTamanoEmpresa(){

			return $this->mo->VerTamanoEmpresa()->fetchAll();
		}

		public function VerSectorEmpresa(){

			return $this->mo->VerSectorEmpresa()->fetchAll();
		}

		public function VerOrigenEmpresa(){

			return $this->mo->VerOrigenEmpresa()->fetchAll();
		}

		public function VerConstJuridicaEmpresa(){

			return $this->mo->VerConstJuridicaEmpresa()->fetchAll();
		}

		public function VerDestBeneficiosEmpresa(){

			return $this->mo->VerDestBeneficiosEmpresa()->fetchAll();
		}

		public function VerAmbitoActEmpresa(){

			return $this->mo->VerAmbitoActEmpresa()->fetchAll();
		}

		public function VerEstadosPeriodos(){

			return $this->mo->VerEstadosPeriodos()->fetchAll();
		}

		public function VerConceptosPlanilla(){

			return $this->mo->VerConceptosPlanilla()->fetchAll();
		}

		public function VerTipoMoneda(){

			return $this->mo->VerTipoMoneda()->fetchAll();
		}

		public function VerTipoMoneda2(){

			return $this->mo->VerTipoMoneda2()->fetchAll();
		}

		public function VerTipoMonedaSimbolo(){

			return $this->mo->VerTipoMonedaSimbolo()->fetchAll();
		}

		public function VerAfp(){

			return $this->mo->VerAfp()->fetchAll();
		}

		public function VerTipoPlanilla(){

			return $this->mo->VerTipoPlanilla()->fetchAll();
		}

		public function VerPeriodosPlanilla($idempresa){

			return $this->mo->VerPeriodosPlanilla($idempresa)->fetchAll();
		}
		
		public function VerPeriodosAperturaCierre($idempresa){

		    return $this->mo->VerPeriodosAperturaCierre($idempresa)->fetchAll();
		}
		
		public function VerEstadoOperacionAC(){

			return $this->mo->VerEstadoOperacionAC()->fetchAll();
		}

	public function VerAnio(){
			return $this->mo->VerAnio()->fetchAll();
		}
		  public function VerInstitucionRegimen()
    {
        return $this->mo->VerInstitucionRegimen()->fetchAll();
    }

	public function VerTipoInstitucionEducativa()
    {
        return $this->mo->VerTipoInstitucionEducativa()->fetchAll();
    }
	public function VerIdioma()
    {
        return $this->mo->VerIdioma()->fetchAll();
    }
	public function VerNivelIdioma()
    {
        return $this->mo->VerNivelIdioma()->fetchAll();
    }
	public function VerComputacionPrograma()
    {
        return $this->mo->VerComputacionPrograma()->fetchAll();
    }
	public function VerNivelPrograma()
    {
        return $this->mo->VerNivelPrograma()->fetchAll();
    }
    public function VerTipoCorreo()
    {
        return $this->mo->VerTipoCorreo()->fetchAll();
    }
    public function VerTipoPropiedad()
    {
        return $this->mo->VerTipoPropiedad()->fetchAll();
    }
    	public function VerProyectos()
    {
        return $this->mo->VerProyectos()->fetchAll();
    }
    	public function VerTipoMonedaSigla()
    {
        return $this->mo->VerTipoMonedaSigla()->fetchAll();
    }
	public function VerMotivoLiberacion()
    {
        return $this->mo->VerMotivoLiberacion()->fetchAll();
    }
	public function VerTipoComprobanteVenta()
    {
        return $this->mo->VerTipoComprobanteVenta()->fetchAll();
    }
	public function VerTipoCondicionVenta()
    {
        return $this->mo->VerTipoCondicionVenta()->fetchAll();
    }
	public function VerTipoCreditoVenta()
    {
        return $this->mo->VerTipoCreditoVenta()->fetchAll();
    }
    	public function VerTipoInmueble()
    {
        return $this->mo->VerTipoInmueble()->fetchAll();
    }

	public function VerTipoDocumentoVenta()
    {
        return $this->mo->VerTipoDocumentoVenta()->fetchAll();
    }
	/************* Personal ************/
	public function VerCargo(){

		return $this->mo->VerCargo()->fetchAll();
	}
	public function VerAreaPers(){

		return $this->mo->VerAreaPers()->fetchAll();
	}
	public function VerPerfilUsu(){

		return $this->mo->VerPerfilUsu()->fetchAll();
	}
	
	public function VerJefeInmUsu(){

		return $this->mo->VerJefeInmUsu()->fetchAll();
	}
	public function VerGeneroUsuario(){

			return $this->mo->VerGeneroUsuario()->fetchAll();
	}
	
	/************ AGREG **********/
	public function VerCatDetalle(){

		return $this->mo->VerCatDetalle()->fetchAll();
	}
	/************ AGREG **********/

	
	}

?>