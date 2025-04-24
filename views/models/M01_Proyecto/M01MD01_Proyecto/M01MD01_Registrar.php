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

    if(isset($_POST['btnRegistrarProyecto'])){

        $txtNombre = isset($_POST['txtNombre']) ? $_POST['txtNombre'] : Null;
        $txtNombrer = trim($txtNombre);        

        $txtResponsable = isset($_POST['txtResponsable']) ? $_POST['txtResponsable'] : Null;
        $txtResponsabler = trim($txtResponsable);        

        $txtArea = isset($_POST['txtArea']) ? $_POST['txtArea'] : Null;
        $txtArear = trim($txtArea);        

        $txtNroZonas = isset($_POST['txtNroZonas']) ? $_POST['txtNroZonas'] : Null;
        $txtNroZonasr = trim($txtNroZonas);        

        $txtDireccion = isset($_POST['txtDireccion']) ? $_POST['txtDireccion'] : Null;
        $txtDireccionr = trim($txtDireccion);    
        
        $cbxDepartamentoDir = isset($_POST['cbxDepartamentoDir']) ? $_POST['cbxDepartamentoDir'] : Null;
        $cbxDepartamentoDirr = trim($cbxDepartamentoDir); 

        $cbxProvinciaDir = isset($_POST['cbxProvinciaDir']) ? $_POST['cbxProvinciaDir'] : Null;
        $cbxProvinciaDirr = trim($cbxProvinciaDir); 

        $cbxDistritoDir = isset($_POST['cbxDistritoDir']) ? $_POST['cbxDistritoDir'] : Null;
        $cbxDistritoDirr = trim($cbxDistritoDir); 
        
        $txtCorrelativo = isset($_POST['txtCorrelativo']) ? $_POST['txtCorrelativo'] : Null;
        $txtCorrelativor = trim($txtCorrelativo); 

        $txtCodigo = isset($_POST['txtCodigo']) ? $_POST['txtCodigo'] : Null;
        $txtCodigor = trim($txtCodigo); 

        if(!empty($txtNombrer)){
            if(!empty($txtNroZonasr)){
               if(!empty($txtArear)){
                   if(!empty($txtDireccionr) && !empty($cbxDepartamentoDirr) && !empty($cbxProvinciaDirr) && !empty($cbxDistritoDirr)){

                        $consultar_proyecto = mysqli_query($conection, "SELECT * FROM gp_proyecto WHERE nombre='$txtNombrer' AND direccion='$txtDireccionr' AND departamento='$cbxDepartamentoDirr' AND provincia='$cbxProvinciaDirr' AND distrito='$cbxDistritoDirr'");
                        $respuesta_proyecto = mysqli_num_rows($consultar_proyecto);

                        if($respuesta_proyecto == 0){

                            //echo json_encode($data);
                            $query = mysqli_query($conection,"call gppa_insertar_proyecto('$txtNombrer','$txtDireccionr','$cbxDepartamentoDirr','$cbxProvinciaDirr','$cbxDistritoDirr','$txtNroZonasr','$txtArear','$txtResponsabler','$txtCodigor','$txtCorrelativor')"); 

                            //Consultar Nuevo Ingreso
                            $consultar = mysqli_query($conection, "SELECT idproyecto as id, codigo as codigo FROM gp_proyecto WHERE nombre='$txtNombrer' AND direccion='$txtDireccionr' AND departamento='$cbxDepartamentoDirr' AND provincia='$cbxProvinciaDirr' AND distrito='$cbxDistritoDirr'");
                            $respuesta = mysqli_fetch_assoc($consultar);
                            $consultar_registro = mysqli_num_rows($consultar);

                            if($consultar_registro > 0){
                            
                                    $data['status'] = "ok";
                                    $data['data'] = "Proyecto : ".$txtNombrer;

                                    $_SESSION['nombre_proy'] = "";
                                    $_SESSION['area_proy'] = "";
                                    $_SESSION['zonas_proy'] = "";
                                    $_SESSION['id_proy'] = "";

                                    $_SESSION['id_proy'] = $respuesta['id'];
                                    $_SESSION['nombre_proy'] = $txtNombrer;
                                    $_SESSION['area_proy'] = $txtArear;
                                    $_SESSION['zonas_proy'] = $txtNroZonasr;

                                    $data['id_proy'] = $respuesta['id'];
                                    $data['nombre_proy'] = $respuesta['codigo']." - ".$txtNombrer;
                                    $data['area_proy'] = $txtArear;
                                    $data['zonas_proy'] = $txtNroZonasr;

                            }else{

                                    $data['status'] = "bad";
                                    $data['data'] = "No se completo el Registro. Revise los datos ingresados.";

                            }

                        }else{

                            $data['status'] = "bad";
                            $data['data'] = "El Proyecto ingresado ya existe. Intente con otro.";

                        }  

                    }else{

                        $data['status'] = "bad";
                        $data['data'] = "Los datos de la ubicacion del proyecto son requeridos. (Direccion, Departamento, Provincia y Distrito)";

                    }    

                }else{

                    $data['status'] = "bad";
                    $data['data'] = "Ingrese el Área del Proyecto.";

                }   
            }else{

                $data['status'] = "bad";
                $data['data'] = "Ingrese el Nro de Zonas que tendrá el Proyecto.";

            }   
        }else{

            $data['status'] = "bad";
            $data['data'] = "Ingrese el Nombre del Proyecto.";

        }    
    }


    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

?>