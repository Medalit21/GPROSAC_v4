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

    if (isset($_POST['btnRegistrarLotex'])) {

        $txtidProyectozlt = isset($_POST['txtidProyectozlt']) ? $_POST['txtidProyectozlt'] : null;
        $txtidProyectozltr = trim($txtidProyectozlt);

        $cbxManzanaslt = isset($_POST['cbxManzanaslt']) ? $_POST['cbxManzanaslt'] : null;
        $cbxManzanasltr = trim($cbxManzanaslt);

        $txtNroLotes = isset($_POST['txtNroLotes']) ? $_POST['txtNroLotes'] : null;
        $txtNroLotesr = trim($txtNroLotes);

        $txtNombreLote = isset($_POST['txtNombreLote']) ? $_POST['txtNombreLote'] : null;
        $txtNombreLoter = trim($txtNombreLote);

        $txtCodigoLote = isset($_POST['txtCodigoLote']) ? $_POST['txtCodigoLote'] : null;
        $txtCodigoLoter = trim($txtCodigoLote);

        $txtCorrelativoLote = isset($_POST['txtCorrelativoLote']) ? $_POST['txtCorrelativoLote'] : null;
        $txtCorrelativoLoter = trim($txtCorrelativoLote);

        $txtAreaLote = isset($_POST['txtAreaLote']) ? $_POST['txtAreaLote'] : null;
        $txtAreaLoter = trim($txtAreaLote);

        $cbxTipoMoneda = isset($_POST['cbxTipoMoneda']) ? $_POST['cbxTipoMoneda'] : null;
        $cbxTipoMonedar = trim($cbxTipoMoneda);

        $txtValorCCLote = isset($_POST['txtValorCCLote']) ? $_POST['txtValorCCLote'] : null;
        $txtValorCCLoter = trim($txtValorCCLote);

        $txtValorSCLote = isset($_POST['txtValorSCLote']) ? $_POST['txtValorSCLote'] : null;
        $txtValorSCLoter = trim($txtValorSCLote);

        $txtGeneracionLotes = isset($_POST['txtGeneracionLotes']) ? $_POST['txtGeneracionLotes'] : null;
        $txtGeneracionLotesr = trim($txtGeneracionLotes);

        $txtExtensionNombreLote = isset($_POST['txtExtensionNombreLote']) ? $_POST['txtExtensionNombreLote'] : null;
        $txtExtensionNombreLoter = trim($txtExtensionNombreLote);

        $txtNroLotesGenerar = isset($_POST['txtNroLotesGenerar']) ? $_POST['txtNroLotesGenerar'] : null;
        $txtNroLotesGenerarr = trim($txtNroLotesGenerar);
        
        $consultar_lote = mysqli_query($conection, "SELECT idlote as id FROM gp_lote WHERE idmanzana='$cbxManzanasltr'");
        $respuesta_lote = mysqli_num_rows($consultar_lote);
        
        if ($txtNroLotesr >= $respuesta_lote) {       
        
            if (!empty($txtNombreLoter)) {
                if (!empty($txtCodigoLoter)) {
                    if (!empty($txtAreaLoter)) {
                        if (!empty($cbxTipoMonedar)) {
                            if(!empty($txtValorCCLoter) && !empty($txtValorSCLoter)){
                            
                                $consultar_lote = mysqli_query($conection, "SELECT idlote as id FROM gp_lote WHERE nombre='$txtNombreLoter' AND codigo='$txtCodigoLoter' AND idmanzana='$cbxManzanasltr'");
                                $respuesta_lote = mysqli_num_rows($consultar_lote);
                                
                                if ($respuesta_lote == 0) {

                                    $consultar_sigla = mysqli_query($conection, "SELECT nombre_corto as sigla FROM configuracion_detalle WHERE codigo_tabla='_SIGLA' AND nombre_largo='LOTE'");
                                    $respuesta_sigla = mysqli_fetch_assoc($consultar_sigla);
                                    $sigla = $respuesta_sigla['sigla'];

                                    $consultar_correlativo = mysqli_query($conection, "SELECT max(correlativo) as correlativo FROM gp_lote WHERE idmanzana='$cbxManzanasltr'");
                                    $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
                                    $correlativo = $respuesta_correlativo['correlativo'];

                                    if (empty($correlativo)) {
                                        $correlativo = 1;
                                    }else{
                                        $correlativo = $correlativo + 1;
                                    }

                                    if (!empty($txtGeneracionLotesr) && $txtGeneracionLotesr==1) {
                                        if (!empty($txtExtensionNombreLoter)) {

                                            $cont = 0;
                                            $num = "";
                                            $cont = $correlativo;                                        

                                            if(!empty($txtNroLotesGenerarr)){

                                                //Controlar cantidad de registros
                                                $consultar_registros = mysqli_query($conection, "SELECT idlote as id FROM gp_lote WHERE idmanzana='$cbxManzanasltr'");
                                                $respuesta_registros = mysqli_num_rows($consultar_registros);                              
                                                $diferencia = $txtNroLotesr - $respuesta_registros;
                                                
                                                if ($txtNroLotesGenerarr <= $diferencia) {

                                                    for ($x=1;$x<=$txtNroLotesGenerarr;$x++) {

                                                        $nombre_manzana = $txtNombreLoter." ".$txtExtensionNombreLoter.$cont;

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
                                                        $query = mysqli_query($conection, "call gppa_insertar_lote('$nombre_manzana','$codigo','$txtAreaLoter','$cbxManzanasltr','$cbxTipoMonedar','$txtValorCCLoter','$txtValorSCLoter','$cont')");

                                                        $cont = $cont + 1;
                                                    }                                                   

                                                    $data['status'] = "ok";
                                                    $data['data'] = "Se ha registrado ".$txtNroLotesGenerarr." lotes.";


                                                } else {
                                                    $data['status'] = "bad";
                                                    $data['data'] = "La cantidad de Lotes a Generar superan el limite permitido. Le quedan por registrar : ".$diferencia." mzs.";
                                                }
                                            }else {
                                                $data['status'] = "bad";
                                                $data['data'] = "Ingrese la cantidad de Lotes a Generar.";
                                            }

                                        } else {
                                            $data['status'] = "bad";
                                            $data['data'] = "Ingrese la extension del nombre de los Lotes. Ejm: L";
                                        }
                                    } else {

                                        $consultar_registros = mysqli_query($conection, "SELECT idlote as id FROM gp_lote WHERE idmanzana='$cbxManzanasltr'");
                                        $respuesta_registros = mysqli_num_rows($consultar_registros);

                                        $diferencia = $txtNroLotesr - $respuesta_registros;

                                        if ($diferencia != 0) {
                                            $query = mysqli_query($conection, "call gppa_insertar_lote('$txtNombreLoter','$txtCodigoLoter','$txtAreaLoter','$cbxManzanasltr','$cbxTipoMonedar','$txtValorCCLoter','$txtValorSCLoter','$txtCorrelativoLoter')");

                                            $data['status'] = "ok";
                                            $data['data'] = "Se registro el lote.";
                                        }else{
                                            $data['status'] = "bad";
                                            $data['data'] = "Usted ya no puede registrar mas Lotes para la Zona seleccionada.";
                                        }

                                    }
                                } else {
                                    $data['status'] = "bad";
                                    $data['data'] = "El Lote ingresado ya existe. Intente con otro.";
                                }
                            }else{
                                $data['status'] = "bad";
                                $data['data'] = "Ingrese el precio de lote y lote + casa.";
                            }

                        } else {
                            $data['status'] = "bad";
                            $data['data'] = "Seleccionar el tipo de moneda.";
                        }
                    } else {
                        $data['status'] = "bad";
                        $data['data'] = "Ingrese el Área del lote.";
                    }
                } else {
                    $data['status'] = "bad";
                    $data['data'] = "Ingrese el código del lote.";
                }
            } else {
                $data['status'] = "bad";
                $data['data'] = "Ingrese el Nombre del Lote.";
            }
        }else{
                $data['status'] = "bad";
                $data['data'] = "El número de registros añadidos aun no llega al limite establecido de Nro de Lotes.";
        }


    }


    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

?>