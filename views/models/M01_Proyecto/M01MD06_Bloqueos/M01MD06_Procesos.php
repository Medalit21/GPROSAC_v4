<?php
   session_start();
   date_default_timezone_set('America/Lima');
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $fechahoy = date('d/m/Y');
   $hoy = $fecha." ".$hora;
   
   $nom_user = $_SESSION['usu'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];


    if(isset($_POST['btnEnviarMensajeWts'])){
    
            $celular = '947226996';
            
            //CONSULTAR DATOS
            $consultar_codigo = mysqli_query($conection, "SELECT clave_autoriza as clave FROM codigo_permiso_lote WHERE estado='1'");
            $respuesta_codigo = mysqli_fetch_assoc($consultar_codigo);
            
            $codigo= $respuesta_codigo['clave'];
    
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.ultramsg.com/instance8326/messages/chat",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_SSL_VERIFYHOST => 0,
              CURLOPT_SSL_VERIFYPEER => 0,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "token=bpnleu3mjub3utqa&to=%2B51".$celular."&body=Estimado(a) Fernando Parodi, se ha solicitado la clave de autorizacion para el bloqueo y desbloqueo de lotes en el Sistema de Gestion Inmobiliario - GPROSAC, la clave de autorizacion es la siguiente :\n\n  ".$codigo."  \n\n La cual queda a su disposicion para su uso respectivo en el modulo de BLOQUEO DE LOTE del sistema. (La clave tiene vigencia por 01 semana calendario a partir de hoy ".$fechahoy.") &priority=1&referenceId=",
              CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            /*
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }*/
    
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    
    }



    if (isset($_POST['btnDesbloquearLote'])) {

        $txtIdLoter = $_POST['txtIdLote'];
        $txtCodigoDesbloqueoLoter = $_POST['txtCodigoDesbloqueoLote'];

        if(!empty($txtCodigoDesbloqueoLoter)){

            //VALIDAR CODIGO DE AUTORIZACION
            $consultar_codigo = mysqli_query($conection, "SELECT idcodigo FROM codigo_permiso_lote WHERE clave_autoriza='$txtCodigoDesbloqueoLoter' AND estado='1'");
            $respuesta_codigo = mysqli_num_rows($consultar_codigo);
            
            if($respuesta_codigo > 0){

                $desbloquear_lote = mysqli_query($conection, "UPDATE gp_lote SET bloqueo_estado=0, desbloqueo_responsable='$IdUser', registro_desbloqueo='$hoy'  WHERE idlote='$txtIdLoter' AND bloqueo_estado!=0");

                $data['status'] = 'ok';
                $data['data'] = "Usted a desbloqueado el Lote seleccionado.";
            }else{
                $data['status'] = 'bad';
                $data['data'] = "La clave de autorización ingresada no es correcta. Revise e intente nuevamente.";
            }    
        }else{
            $data['status'] = 'bad';
            $data['data'] = "Ingrese la clave de autorización para desbloquear el Lote seleccionado.";
        }      

        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    if (isset($_POST['btnBloquearLote'])) {

        $txtIdLoter = $_POST['txtIdLote'];
        $txtCodigoBloqueoLoter = $_POST['txtCodigoBloqueoLote'];

        if(!empty($txtCodigoBloqueoLoter)){

            //VALIDAR CODIGO DE AUTORIZACION
            $consultar_codigo = mysqli_query($conection, "SELECT idcodigo FROM codigo_permiso_lote WHERE clave_autoriza='$txtCodigoBloqueoLoter' AND estado='1'");
            $respuesta_codigo = mysqli_num_rows($consultar_codigo);
            
            if($respuesta_codigo > 0){

                $desbloquear_lote = mysqli_query($conection, "UPDATE gp_lote SET bloqueo_estado=7, bloqueo_responsable='$IdUser', registro_bloqueo='$hoy'  WHERE idlote='$txtIdLoter' AND bloqueo_estado=0");

                $data['status'] = 'ok';
                $data['data'] = "Usted a bloqueado el Lote seleccionado.";
            }else{
                $data['status'] = 'bad';
                $data['data'] = "La clave de autorización ingresada no es correcta. Revise e intente nuevamente.";
            }    
        }else{
            $data['status'] = 'bad';
            $data['data'] = "Ingrese la clave de autorización para bloquear el Lote seleccionado.";
        }      

        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }


    if (isset($_POST['ReturnGuardarRegProyecto'])) {

        $Descripcion = $_POST['txtDescripcion'];
        $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

        $TipoPropiedad = $_POST['cbxTipoPropiedad'];
        $TipoPropiedad = !empty($TipoPropiedad) ? "'$TipoPropiedad'" : "NULL";

        $Departamento = $_POST['cbxDepartamentoDir'];
        $Departamento = !empty($Departamento) ? "'$Departamento'" : "NULL";

        $data['status'] = 'ok';
        $data['data'] = $resultado;

        $query = mysqli_query($conection, "");

    
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }







   ?>