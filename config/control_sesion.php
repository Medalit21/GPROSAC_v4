<?php
    //session_start();
    date_default_timezone_set('America/Lima');
    $time = time();
    $fecha = date('Y-m-d');
    $hora = date("H:i:s", $time);

    //consultar id usuario
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id, idperfil as perfil  FROM usuario WHERE usuario='$valor_user'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $idusuario = $respuesta_idusuario['id'];
    $idperfil = $respuesta_idusuario['perfil'];

    //consultar la session actual
    $consultar_sesion = mysqli_query($conection, "SELECT max(id) as id FROM system_loginusuario WHERE user='$idusuario'");
    $respuesta_sesion = mysqli_fetch_assoc($consultar_sesion);

    $idmax = $respuesta_sesion['id'];

    //verificar estado de session
    $consultar_estado = mysqli_query($conection, "SELECT estado as estad, idempresa as empresa FROM system_loginusuario WHERE id='$idmax'");
    $respuesta_estado = mysqli_fetch_assoc($consultar_estado);

    $_SESSION['nom_empresa'] = $valor_user;
    $_SESSION['ruc_empresa'] = "";
    $_SESSION['razon_social'] = "";
     $_SESSION['direccion'] = "";
    $_SESSION['id_empresa'] = "";

    $mes="";
    $a���o = "";
	
	$valor_usuario = $_SESSION['usu'];

    $consultar_idusuarioo = mysqli_query($conection, "SELECT idperfil as perfil  FROM usuario WHERE usuario='$valor_usuario'");
    $respuesta_idusuarioo = mysqli_fetch_assoc($consultar_idusuarioo);
    $idperfil2 = $respuesta_idusuarioo['perfil'];

    if($idperfil2 == "6"){
        $consultar_datos = mysqli_query($conection, "SELECT concat(SUBSTRING_INDEX(p.nombres,' ',1),' ',p.apellido_paterno) as usuario FROM datos_cliente p, usuario u WHERE p.documento=u.usuario AND u.usuario='$valor_usuario'");
        $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
        $_SESSION['datos'] = $respuesta_datos['usuario'];
        $_SESSION['filtro'] = "hidden";
    }else{
        $consultar_datos = mysqli_query($conection, "SELECT concat(SUBSTRING_INDEX(p.nombre,' ',1),' ',SUBSTRING_INDEX(p.apellido,' ',1)) as usuario FROM persona p, usuario u WHERE p.idusuario=u.idusuario AND u.usuario='$valor_usuario'");
        $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
        $_SESSION['datos'] = $respuesta_datos['usuario'];
        $_SESSION['filtro'] = "";
    }


     //CONSULTA TIPO DE CAMBIO DEL DIA
    $consultar_tipocambio = mysqli_query($conection, "SELECT valor as tp FROM configuracion_tipo_cambio WHERE fecha='$fecha'");
    $respuesta_tipocambio = mysqli_num_rows($consultar_tipocambio); 
    
    $_SESSION['tipo_cambio'] = "";
    if($respuesta_tipocambio>0){
        $registro = mysqli_fetch_assoc($consultar_tipocambio);
        $_SESSION['tipo_cambio'] = $registro['tp'];
    }

	
	
   
    if(empty($_SESSION['usu'])){
        
        session_destroy();
        echo '<script type="text/javascript">';
        echo 'alert("El tiempo de su sesion ha expirado! Ingrese nuevamente.")';
        echo '</script>';
        echo '<script type="text/javascript">';
        echo 'location.href="'.$NAME_SERVER.'"';
        echo '</script>';
        
    }
    

?>