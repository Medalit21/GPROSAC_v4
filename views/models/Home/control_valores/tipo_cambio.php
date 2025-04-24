<?php

    session_start();
    include_once "../../../../config/configuracion.php";
    include_once "../../../../config/conexion_2.php";
    include_once "../../../../config/codificar.php";
    $hora = date("H:i:s", time());;
    $fecha = date('Y-m-d');
    $fecha_hoy = date('Y-m-d');  

    $data = array();
    $dataList = array();
    
    
if(isset($_POST['btnGuardarTipoCambio'])){
    
    $txtFechaTC = $_POST['txtFechaTC'];
    $txtValorTC = $_POST['txtValorTC'];
    $__ID_USER = $_POST['__ID_USER'];
    
    $__ID_USER = decrypt($__ID_USER, "123");
    
    //CONSULTAR ID USUARIO
    $consultar = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USER'");
    $respuesta = mysqli_fetch_assoc($consultar);
    
    $idusu = $respuesta['id'];
    
    //consultar si existe tipo de cambio del dia
    
    $consultar_tc = mysqli_query($conection, "SELECT fecha FROM configuracion_tipo_cambio WHERE fecha='$txtFechaTC'");
    $respuesta_tc = mysqli_num_rows($consultar_tc);
    
    if($respuesta_tc>0){
    
        //INSERTAR TIPO CAMBIO
        $actualizar = mysqli_query($conection, "UPDATE configuracion_tipo_cambio SET valor='$txtValorTC', id_usuario_crea='$idusu' WHERE fecha='$txtFechaTC'");
    
    }else{
        
        //INSERTAR TIPO CAMBIO
        $insertar = mysqli_query($conection, "INSERT INTO configuracion_tipo_cambio(valor, fecha, id_usuario_crea) VALUES ('$txtValorTC','$txtFechaTC','$idusu')");
        
    }
    
    
    $data['status'] = 'ok';
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

    
if(isset($_POST['btnBuscarDatos'])){
    
    $hora = date("H:i:s", time());;
    $fecha = date('Y-m-d');
    
    $registro = $fecha.' '.$hora;
    
    $data['status'] = 'ok';
    $data['fecha'] = $fecha;
    $data['registro'] = $registro;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}
    
if (isset($_POST['btnListarTipoCambio'])) {
    
    $query = mysqli_query($conection, "SELECT
    idconfig_tipo_cambio as id,
    date_format(fecha, '%d/%m/%Y') as fecha,
    valor as valor,
    creado as registro,
    @i := @i + 1 as contador    
    FROM configuracion_tipo_cambio
    CROSS JOIN (select @i := 0) r
    WHERE esta_borrado='0'
    ORDER BY fecha DESC");
    
     if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'valor' => $row['valor'],
                'registro' => $row['registro'],
                'contador' => $row['contador']
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