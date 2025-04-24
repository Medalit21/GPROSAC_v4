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

    if(isset($_POST['btnRegistrarZona'])){

        $txtidProyectoZona = isset($_POST['txtidProyectoZona']) ? $_POST['txtidProyectoZona'] : Null;
        $txtidProyector = trim($txtidProyectoZona); 

        $txtNroZonasProyectoZona = isset($_POST['txtNroZonasProyectoZona']) ? $_POST['txtNroZonasProyectoZona'] : Null;
        $txtNroZonasProyectoZonar = trim($txtNroZonasProyectoZona); 

        $txtNombreZona = isset($_POST['txtNombreZona']) ? $_POST['txtNombreZona'] : Null;
        $txtNombreZonar = trim($txtNombreZona);        

        $txtCodigoZona = isset($_POST['txtCodigoZona']) ? $_POST['txtCodigoZona'] : Null;
        $txtCodigoZonar = trim($txtCodigoZona);        

        $txtAreaZona = isset($_POST['txtAreaZona']) ? $_POST['txtAreaZona'] : Null;
        $txtAreaZonar = trim($txtAreaZona);        

        $txtGeneracionManzanas = isset($_POST['txtGeneracionManzanas']) ? $_POST['txtGeneracionManzanas'] : Null;
        $txtGeneracionManzanasr = trim($txtGeneracionManzanas);      

        $txtNroManzana = isset($_POST['txtNroManzana']) ? $_POST['txtNroManzana'] : Null;
        $txtNroManzanar = trim($txtNroManzana);

        //CAMPOS DE INSERCION AUTOMATICA

        $txtGeneracionManzanas = isset($_POST['txtGeneracionManzanas']) ? $_POST['txtGeneracionManzanas'] : Null;
        $txtGeneracionManzanasr = trim($txtGeneracionManzanas);

        $txtCorrelativoZona = isset($_POST['txtCorrelativoZona']) ? $_POST['txtCorrelativoZona'] : Null;
        $txtCorrelativoZonar = trim($txtCorrelativoZona);
                
        $consultar_zona = mysqli_query($conection, "SELECT idzona FROM gp_zona WHERE idproyecto='$txtidProyector'");
        $respuesta_zona = mysqli_num_rows($consultar_zona);
        
        if($respuesta_zona < $txtNroZonasProyectoZonar){
            if (!empty($txtNombreZonar)) {
                if (!empty($txtCodigoZonar)) {
                    if (!empty($txtAreaZonar)) {
                        if (!empty($txtNroManzanar)) {
                            $consultar_proyecto = mysqli_query($conection, "SELECT * FROM gp_zona WHERE nombre='$txtNombreZonar' AND codigo='$txtCodigoZonar' AND idproyecto='$txtidProyector'");
                            $respuesta_proyecto = mysqli_num_rows($consultar_proyecto);

                            if ($respuesta_proyecto == 0) {
                                
                                    //echo json_encode($data);
                                    $query = mysqli_query($conection, "call gppa_insertar_zona('$txtNombreZonar','$txtCodigoZonar','$txtNroManzanar','$txtAreaZonar','$txtidProyector','$txtCorrelativoZonar')");

                                    //Consultar Nuevo Ingreso
                                    $consultar = mysqli_query($conection, "SELECT idzona as id FROM gp_zona WHERE nombre='$txtNombreZonar' AND codigo='$txtCodigoZonar' AND idproyecto='$txtidProyector'");
                                    $respuesta = mysqli_fetch_assoc($consultar);
                                    $consultar_registro = mysqli_num_rows($consultar);

                                    if ($consultar_registro > 0) {                                       

                                        $data['status'] = "ok";
                                        $data['data'] = "Se ha registrado la Zona : ".$txtNombreZonar;

                                        $consultar_datosproy = mysqli_query($conection, "SELECT idproyecto as id, nombre as nombre, area as area, nro_zonas as nro_zonas FROM gp_proyecto WHERE idproyecto='$txtidProyector'");
                                        $respuesta_datosproy = mysqli_fetch_assoc($consultar_datosproy);

                                        $data['id_proy'] = $txtidProyector;
                                        $data['nombre_proy'] = $respuesta_datosproy['nombre'];
                                        $data['area_proy'] = $respuesta_datosproy['area'];
                                        $data['zonas_proy'] = $respuesta_datosproy['nro_zonas'];
                                    } else {
                                        $data['status'] = "bad";
                                        $data['data'] = "No se completo el Registro. Revise los datos ingresados.";
                                    }                                   

                            } else {
                                $data['status'] = "bad";
                                $data['data'] = "La Zona ingresado ya existe. Intente con otro.";
                            }
                        } else {
                            $data['status'] = "bad";
                            $data['data'] = "Ingrese el Número de Manzanas correspondientes a la Zona.";
                        }
                    } else {
                        $data['status'] = "bad";
                        $data['data'] = "Ingrese el Área de la Zona.";
                    }
                } else {
                    $data['status'] = "bad";
                    $data['data'] = "Ingrese el código de la Zona.";
                }
            } else {
                $data['status'] = "bad";
                $data['data'] = "Ingrese el Nombre del Zona.";
            }
        } else {
            $data['status'] = "bad";
            $data['data'] = "No se permite el ingreso de más Zonas. Se estableció un máximo de ".$txtNroZonasProyectoZonar." Zonas para el Proyecto.";
        }      


    }


    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

?>