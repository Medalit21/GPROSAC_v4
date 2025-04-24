 <?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $fecha_hoy = date('d-m-Y'); 
   $mes = date('m');
   //$anio = date('Y');
   


   $data = array();
   $dataList = array();

if(isset($_POST['btnValidarFechas'])){
    
    //CONSULTAR FECHA INICIO PROYECTO
    
    $consultar_fec_ini = mysqli_query($conection, "SELECT inicio_proyecto as inicio FROM gp_proyecto WHERE estado='1'");
    $respuesta_fec_ini = mysqli_fetch_assoc($consultar_fec_ini);
    $fec_ini = $respuesta_fec_ini['inicio'];
    
    $fec_fin = date('Y-m-d'); 
    
    $data['status'] = 'ok';
    $data['primero'] = $fec_ini;
    $data['ultimo'] = $fec_fin;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['ReturnIngresEgresCabec'])){

	$txtDesdeFiltro = isset($_POST['txtDesdeFiltro']) ? $_POST['txtDesdeFiltro'] : Null;
	$bxFiltrotxtDesde = trim($txtDesdeFiltro);
	
	$txtHastaFiltro = isset($_POST['txtHastaFiltro']) ? $_POST['txtHastaFiltro'] : Null;
	$bxFiltrotxtHasta = trim($txtHastaFiltro);
	
	$bxFiltroIngresEgres = isset($_POST['bxFiltroIngresEgres']) ? $_POST['bxFiltroIngresEgres'] : Null;
	$bxFiltroIngresEgress = trim($bxFiltroIngresEgres);
	
	$query_inicio = "AND MONTH(gppc.fecha) = MONTH('$fecha') AND YEAR(gppc.fecha) = YEAR('$fecha')";
	$query_fin	  = "";
	$query_estado = "";
	$dato_fec = "";
	
	if(!empty($bxFiltrotxtDesde)){
	   $query_inicio = "AND gppc.fecha='$bxFiltrotxtDesde'"; 
	   $dato_fec = "( ".date("d-m-Y", strtotime($bxFiltrotxtDesde))." )";
	}
	
	if(!empty($bxFiltrotxtHasta) && !empty($bxFiltrotxtDesde)){
	    
	   $query_inicio = "AND gppc.fecha BETWEEN '$bxFiltrotxtDesde' AND '$bxFiltrotxtHasta'"; 
	   $dato_fec = "( Del ".date("d-m-Y", strtotime($bxFiltrotxtDesde))." al ".date("d-m-Y", strtotime($bxFiltrotxtHasta))." )";
	}
	
	if(!empty($bxFiltroIngresEgress)){
	   $query_estado = "AND gppd.DebHab='$bxFiltroIngresEgress'"; 
	}
	

    $query = mysqli_query($conection,"SELECT 
	gppc.id_pago as id,
	gppc.Sede as Sede,                                    
	date_format(gppc.Fecha, '%d/%m/%Y') as Fecha,
	gppc.Moneda as Moneda,
	gppc.TipoCambio as TipoCambio,
	gppc.Glosa as Glosa,
	format(gppc.Total, 2) as TotalImporte,
	gppc.Accion as Accion,
	gppc.Cuenta_Contable as CuentaContable,
	gppc.Operacion as Operacion,
	gppc.Numero as Numero,
	gppd.identificador as id,
	gppd.Tipo as TipoComp,
	gppd.Serie as Serie,
	gppd.Numero as Numero,
	format(if(gppc.Moneda='PEN',(gppc.Total/gppc.TipoCambio),gppc.Total),2) as total,
	format(gppc.Total,2) as TotalImporte,
	gppc.Cuenta_Contable as CuentaContable,
	gppd.Centro_costo as CentroCosto,
	gppd.RazonSocial as RazonSocial,
	gppd.DniRuc as DniRuc,
	date_format(gppd.FechaR, '%d/%m/%Y') as FechaR,
	gppd.DebHab as DebHab
	FROM ingresos_cabecera gppc
	INNER JOIN ingresos_detalle AS gppd ON gppd.identificador=gppc.identificador
	$query_inicio
	$query_estado
	GROUP BY gppc.Id_Cabecera
	ORDER BY gppc.Fecha ASC"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id_pago' => $row['id'],
                'sede' => $row['Sede'],
                'fecha' => $row['Fecha'],
                'moneda' => $row['Moneda'],
                'tipocambio' => $row['TipoCambio'],
                'glosa' => $row['Glosa'],
				'total' => $row['TotalImporte'],
				'accion' => $row['Accion'],
				'cuenta_contable' => $row['CuentaContable'],
				'operacion' => $row['Operacion'],
				'numero' => $row['Numero'],
				'identificador' => $row['id'],
                'tipo' => $row['TipoComp'],
                'serie' => $row['Serie'],
                'numero' => $row['Numero'],
                'total' => $row['total'],
                'TotalImporte' => $row['TotalImporte'],
                'cuenta_contable' => $row['CuentaContable'],
				'centro_costo' => $row['CentroCosto'],
				'razonsocial' => $row['RazonSocial'],
				'dniruc' => $row['DniRuc'],
				'FechaR' => $row['FechaR'],
				'debhab' => $row['DebHab']
            ]);
        }
            
       $data['data'] = $dataList;
       $data['fec_hoy'] = $dato_fec;
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
