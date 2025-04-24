<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 

   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

if(!empty($_POST)){

    if (isset($_POST['btnRegistrarManzana'])) {

        $txtidProyectoz = isset($_POST['txtidProyectoz']) ? $_POST['txtidProyectoz'] : null;
        $txtidProyectozr = trim($txtidProyectoz);

        $cbxZonas = isset($_POST['cbxZonas']) ? $_POST['cbxZonas'] : null;
        $cbxZonasr = trim($cbxZonas);

        $txtNroManzanas = isset($_POST['txtNroManzanas']) ? $_POST['txtNroManzanas'] : null;
        $txtNroManzanasr = trim($txtNroManzanas);

        $txtNombreManzana = isset($_POST['txtNombreManzana']) ? $_POST['txtNombreManzana'] : null;
        $txtNombreManzanar = trim($txtNombreManzana);

        $txtCodigoManzana = isset($_POST['txtCodigoManzana']) ? $_POST['txtCodigoManzana'] : null;
        $txtCodigoManzanar = trim($txtCodigoManzana);

        $txtAreaManzana = isset($_POST['txtAreaManzana']) ? $_POST['txtAreaManzana'] : null;
        $txtAreaManzanar = trim($txtAreaManzana);

        $txtNumLotes = isset($_POST['txtNumLotes']) ? $_POST['txtNumLotes'] : null;
        $txtNumLotesr = trim($txtNumLotes);

        $txtCodigoGeneracionManzanas = isset($_POST['txtCodigoGeneracionManzanas']) ? $_POST['txtCodigoGeneracionManzanas'] : null;
        $txtCodigoGeneracionManzanasr = trim($txtCodigoGeneracionManzanas);

        $txtCorrelativoManzana = isset($_POST['txtCorrelativoManzana']) ? $_POST['txtCorrelativoManzana'] : null;
        $txtCorrelativoManzanar = trim($txtCorrelativoManzana);

        $txtGeneracionManzanas = isset($_POST['txtGeneracionManzanas']) ? $_POST['txtGeneracionManzanas'] : null;
        $txtGeneracionManzanasr = trim($txtGeneracionManzanas);

        $txtNumManzanasGeneradas = isset($_POST['txtNumManzanasGeneradas']) ? $_POST['txtNumManzanasGeneradas'] : null;
        $txtNumManzanasGeneradasr = trim($txtNumManzanasGeneradas);
        
        $consultar_manzana = mysqli_query($conection, "SELECT idmanzana as id FROM gp_manzana WHERE idzona='$cbxZonasr'");
        $respuesta_manzana = mysqli_num_rows($consultar_manzana);
        
        if ($txtNroManzanasr >= $respuesta_manzana) {        
        
            if (!empty($txtNombreManzanar)) {
                if (!empty($txtCodigoManzanar)) {
                    if (!empty($txtAreaManzanar)) {
                        if (!empty($txtNumLotesr)) {
                            
                            $consultar_manzana = mysqli_query($conection, "SELECT idmanzana as id FROM gp_manzana WHERE nombre='$txtNombreManzanar' AND codigo='$txtCodigoManzanar' AND idzona='$cbxZonasr'");
                            $respuesta_manzana = mysqli_num_rows($consultar_manzana);
                            
                            if ($respuesta_manzana == 0) {
                                $consultar_sigla = mysqli_query($conection, "SELECT nombre_corto as sigla FROM configuracion_detalle WHERE codigo_tabla='_SIGLA' AND nombre_largo='MANZANA'");
                                $respuesta_sigla = mysqli_fetch_assoc($consultar_sigla);
                                $sigla = $respuesta_sigla['sigla'];

                                $consultar_correlativo = mysqli_query($conection, "SELECT max(correlativo) as correlativo FROM gp_manzana WHERE idzona='$cbxZonasr'");
                                $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
                                $correlativo = $respuesta_correlativo['correlativo'];

                                if (empty($correlativo)) {
                                    $correlativo = 1;
                                }else{
                                    $correlativo = $correlativo + 1;
                                }

                                if (!empty($txtGeneracionManzanasr) && $txtGeneracionManzanasr==1) {
                                    if (!empty($txtCodigoGeneracionManzanasr)) {
                                        $cont = 0;
                                        $num = "";
                                        $cont = $correlativo;                                        

                                        if(!empty($txtNumManzanasGeneradasr)){
                                            //Controlar cantidad de registros
                                            $consultar_registros = mysqli_query($conection, "SELECT idmanzana as id FROM gp_manzana WHERE idzona='$cbxZonasr'");
                                            $respuesta_registros = mysqli_num_rows($consultar_registros);                                

                                            $diferencia = $txtNroManzanasr - $respuesta_registros;
                                            
                                            if ($txtNumManzanasGeneradasr <= $diferencia) {

                                                for ($x=1;$x<=$txtNumManzanasGeneradasr;$x++) {

                                                    $nombre_manzana = $txtNombreManzanar." ".$txtCodigoGeneracionManzanasr.$cont;

                                                    if ($cont>0 && $cont<10) {
                                                        $num = "00";
                                                    } else {
                                                        if ($cont>10 && $cont<100) {
                                                            $num = "0";
                                                        } else {
                                                            if ($cont>100 && $cont<1000) {
                                                                $num = "";
                                                            }
                                                        }
                                                    }

                                                    $codigo = $sigla."-".$num.$cont;
                                                    $query = mysqli_query($conection, "call gppa_insertar_manzana('$nombre_manzana','$codigo','$txtNumLotesr','1','$txtAreaManzanar','$cbxZonasr','$cont')");

                                                    $cont = $cont + 1;
                                                }                                                   

                                                $consultar_datosproy = mysqli_query($conection, "SELECT idproyecto as id, nombre as nombre, area as area, nro_zonas as nro_zonas FROM gp_proyecto WHERE idproyecto='$txtidProyectozr'");
                                                $respuesta_datosproy = mysqli_fetch_assoc($consultar_datosproy);

                                                $data['id_proy'] = $txtidProyectozr;
                                                $data['nombre_proy'] = $respuesta_datosproy['nombre'];
                                                $data['area_proy'] = $respuesta_datosproy['area'];
                                                $data['zonas_proy'] = $respuesta_datosproy['nro_zonas'];

                                                $data['status'] = "ok";
                                                $data['data'] = "Se ha registrado ".$txtNumManzanasGeneradasr." manzanas.";


                                            } else {
                                                $data['status'] = "bad";
                                                $data['data'] = "La cantidad de Manzanas a Generar superan el limite permitido. Le quedan por registrar : ".$diferencia." mzs.";
                                            }
                                        }else {
                                            $data['status'] = "bad";
                                            $data['data'] = "Ingrese la cantidad de Manzanas a Generar.";
                                        }

                                    } else {
                                        $data['status'] = "bad";
                                        $data['data'] = "Ingrese la extension del nombre de las manzanas. Ejm: P";
                                    }
                                } else {

                                    $consultar_registros = mysqli_query($conection, "SELECT idmanzana as id FROM gp_manzana WHERE idzona='$cbxZonasr'");
                                    $respuesta_registros = mysqli_num_rows($consultar_registros);

                                    $diferencia = $txtNroManzanasr - $respuesta_registros;

                                    if ($diferencia != 0) {
                                        $query = mysqli_query($conection, "call gppa_insertar_manzana('$txtNombreManzanar','$txtCodigoManzanar','$txtNumLotesr','0','$txtAreaManzanar','$cbxZonasr','$txtCorrelativoManzanar')");

                                        $consultar_datosproy = mysqli_query($conection, "SELECT idproyecto as id, nombre as nombre, area as area, nro_zonas as nro_zonas FROM gp_proyecto WHERE idproyecto='$txtidProyectozr'");
                                        $respuesta_datosproy = mysqli_fetch_assoc($consultar_datosproy);

                                        $data['id_proy'] = $txtidProyectozr;
                                        $data['nombre_proy'] = $respuesta_datosproy['nombre'];
                                        $data['area_proy'] = $respuesta_datosproy['area'];
                                        $data['zonas_proy'] = $respuesta_datosproy['nro_zonas'];

                                        $data['status'] = "ok";
                                        $data['data'] = "Se registro la manzana ".$txtNombreManzanar.".";
                                    }else{
                                        $data['status'] = "bad";
                                        $data['data'] = "Usted ya no puede registrar mas Manzanas para la Zona seleccionada.";
                                    }

                                }
                            } else {
                                $data['status'] = "bad";
                                $data['data'] = "La Manzana ingresado ya existe. Intente con otro.";
                            }
                            

                        } else {
                            $data['status'] = "bad";
                            $data['data'] = "Ingrese el Número de Manzanas correspondientes a la Manzana.";
                        }
                    } else {
                        $data['status'] = "bad";
                        $data['data'] = "Ingrese el Área de la Manzana.";
                    }
                } else {
                    $data['status'] = "bad";
                    $data['data'] = "Ingrese el código de la Manzana.";
                }
            } else {
                $data['status'] = "bad";
                $data['data'] = "Ingrese el Nombre del Manzana.";
            }
        }else{
                $data['status'] = "bad";
                $data['data'] = "El número de registros añadidos aun no llega al limite establecido de Nro de Manzanams.";
        }

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT);
    }


    
}

?>