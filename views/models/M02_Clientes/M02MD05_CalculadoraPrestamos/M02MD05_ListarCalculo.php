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

if(isset($_POST['ReturnListaCalculo'])){


	$query = mysqli_query($conection,"SELECT 
			numero as numero,
			format(cuota,2) as cuota,
			format(intereses,2) as intereses,
			format(amortizacion,2) as amortizacion,
			format(capital_vivo,2) as capital_vivo,
			format(capital_amortizado,2) as capital_amortizado,
			format(total_pagado,2) as total_pagado
			FROM temp_calculadora"); 

	 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'nro_mes' => $row['numero'],
                'cuota' => $row['cuota'],
                'intereses' => $row['intereses'],
                'amortizacion' => $row['amortizacion'],
				'capital_vivo' => $row['capital_vivo'],
				'capital_amortizado' => $row['capital_amortizado'],
				'total_pagado' => $row['total_pagado']
				
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
        $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}

if(isset($_POST['btnCalcular'])){

	$txtTEA = isset($_POST['txtTEA']) ? $_POST['txtTEA'] : Null;
	$txtTEAr = trim($txtTEA);
	
	$txtTEM= isset($_POST['txtTEM']) ? $_POST['txtTEM'] : Null;
	$txtTEMr = trim($txtTEM);
	
	$txtCuotas = isset($_POST['txtCuotas']) ? $_POST['txtCuotas'] : Null;
	$txtCuotasr = trim($txtCuotas);
	
	$txtPrecioVenta = isset($_POST['txtPrecioVenta']) ? $_POST['txtPrecioVenta'] : Null;
	$txtPrecioVentar = trim($txtPrecioVenta);
	
	$txtCuotaInicial = isset($_POST['txtCuotaInicial']) ? $_POST['txtCuotaInicial'] : Null;
	$txtCuotaInicialr = trim($txtCuotaInicial);
	
	$valor_TEA = 0;
	$valor_TEM = 0;
	
	if(!empty($txtTEAr) && !empty($txtCuotasr) && !empty($txtPrecioVentar) && !empty($txtCuotaInicialr)){
	
	    //CONVERTIR DATOS TEM
	    $valor_TEA= $txtTEAr/100;
	    $valor_TEM = pow(( 1 + $valor_TEA),(30/360)) - 1;
	    
	   
	    //CALCULO CUOTA MENSUAL
	    
	    $capital_vivo = $txtPrecioVentar - $txtCuotaInicialr;
	    
	    $valor_cuota = (($capital_vivo * ($valor_TEM * pow((1 + $valor_TEM),$txtCuotasr)))/((pow((1+$valor_TEM),$txtCuotasr))-1));

	    //limpiar tabla
	    $limpiar_calculadora = mysqli_query($conection, "TRUNCATE TABLE temp_calculadora;");
	    $amortizacion = 0;
	    
	    for($cont=0; $cont<=$txtCuotasr; $cont++){
	        
	        $capital_inicial = $capital_vivo;
	        $cuota = $valor_cuota;
	        $intereses = $capital_vivo * $valor_TEM;
	        $amortizacion = $cuota - $intereses;
	        $capital_vivo = $capital_vivo - $amortizacion;
	        $capital_amortizado = $amortizacion;
	        $total_pagado = $cuota;
	        
	        if($cont==0){
	            $insertar_fila0 = mysqli_query($conection,"INSERT INTO temp_calculadora(numero,cuota,intereses,amortizacion,capital_vivo,capital_amortizado,total_pagado) VALUES
	            ('$cont','0','0','0','$capital_inicial','0','0')");
	            $cont = $cont + 1;
	            $insertar_fila0 = mysqli_query($conection,"INSERT INTO temp_calculadora(numero,cuota,intereses,amortizacion,capital_vivo,capital_amortizado,total_pagado) VALUES
	            ('$cont','$cuota','$intereses','$amortizacion','$capital_vivo','$capital_amortizado','$total_pagado')");
	        }else{
	             $insertar_fila0 = mysqli_query($conection,"INSERT INTO temp_calculadora(numero,cuota,intereses,amortizacion,capital_vivo,capital_amortizado,total_pagado) VALUES
	            ('$cont','$cuota','$intereses','$amortizacion','$capital_vivo','$capital_amortizado','$total_pagado')");
	        }
	        
	    }
	  
	    $data['status'] = "ok";
         $data['data'] = "El calculo del prestamo se ha completado!";
          header('Content-type: text/javascript');
          echo json_encode($data,JSON_PRETTY_PRINT);
	}else{
	    $data['status'] = "bad";
         $data['data'] = "El calculo no pudo ser completado. Todos los campos son obligatorios.";
          header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT);
	}  
	   

}

if(isset($_POST['LimpiarTabla'])){


	    $limpiar_calculadora = mysqli_query($conection, "TRUNCATE TABLE temp_calculadora;");
	    
	    header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}
