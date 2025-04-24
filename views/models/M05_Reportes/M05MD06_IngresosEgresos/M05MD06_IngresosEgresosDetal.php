 <?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');
   


   $data = array();
   $dataList = array();

if(!empty($_POST)){

	$txtDesdeFiltro2 = isset($_POST['txtDesdeFiltro2']) ? $_POST['txtDesdeFiltro2'] : Null;
	$bxFiltrotxtDesde = trim($txtDesdeFiltro2);
	
	$txtHastaFiltro2 = isset($_POST['txtHastaFiltro2']) ? $_POST['txtHastaFiltro2'] : Null;
	$bxFiltrotxtHasta = trim($txtHastaFiltro2);
	
	$bxFiltroIngresEgres2 = isset($_POST['bxFiltroIngresEgres2']) ? $_POST['bxFiltroIngresEgres2'] : Null;
	$bxFiltroIngresEgress = trim($bxFiltroIngresEgres2);
	
	$query_inicio = "AND MONTH(gppc.fecha_pago) = MONTH('$fecha') AND YEAR(gppc.fecha_pago) = YEAR('$fecha')";
	$query_fin	  = "";
	$query_estado = "";
	
	if(!empty($bxFiltrotxtDesde)){
	   $query_inicio = "AND gppc.fecha_pago='$bxFiltrotxtDesde'"; 
	}
	
	if(!empty($bxFiltrotxtHasta)){
	   $query_inicio = "AND gppc.fecha_pago BETWEEN '$bxFiltrotxtDesde' AND '$bxFiltrotxtHasta'"; 
	}
	
	if(!empty($bxFiltroIngresEgress)){
	   $query_estado = "AND gppd.debe_haber='$bxFiltroIngresEgress'"; 
	}

    $query = mysqli_query($conection,"SELECT 
                                    gppd.idpago as id_detalle,
									gppd.idpago_detalle as id,
									gppd.tipo_comprobante as TipoComp,
									gppd.serie as Serie,
									gppd.numero as Numero,
									format(gppd.importe_pago,2) as TotalImporte,
									gppc.cuenta_contable as CuentaContable,
									gppd.centro_costo as CentroCosto,
									gppd.razon_social as RazonSocial,
									gppd.dni_ruc as DniRuc,
									date_format(gppd.fecha_pago, '%d/%m/%Y') as FechaR,
									gppd.debe_haber as DebHab
									FROM gp_pagos_detalle gppd
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									WHERE gppd.esta_borrado=0
									$query_inicio
									$query_estado
									ORDER BY gppc.fecha_pago
									"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'idpagodetalle' => $row['id_detalle'],
                'idpago_detalle' => $row['id'],
                'tipo' => '01',
                'tipo_comprobante' => $row['TipoComp'],
                'serie' => $row['Serie'],
                'numero' => $row['Numero'],
                'importe_pago' => $row['TotalImporte'],
                'centro_costo' => $row['CentroCosto'],
                'cuenta_contable' => $row['CuentaContable'],
				'razon_social' => $row['RazonSocial'],
				'dni_ruc' => $row['DniRuc'],
				'fecha_pago' => $row['FechaR'],
				'debe_haber' => $row['DebHab']
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
			//$data['recordsTotal'] = 0;
            //$data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}
