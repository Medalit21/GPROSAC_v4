<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());
   $fecha = date('Y-m-d'); 

   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

   $data = array();
$dataList = array();

if(isset($_POST['btnConsultarDatosZona'])){
    $IdReg=$_POST['idZona'];
   $query = mysqli_query($conection," SELECT 
            gpz.idzona as id,
            COUNT(gpm.idmanzana) as manzanas,
            IFNULL(SUM((
                SELECT COUNT(*) 
                FROM gp_lote gl 
                WHERE gl.idmanzana = gpm.idmanzana
            )), 0) as lotes
        FROM gp_zona gpz
        LEFT JOIN gp_manzana gpm ON gpz.idzona = gpm.idzona
        WHERE gpz.idzona = '$IdReg'
        GROUP BY gpz.idzona");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if(isset($_POST['btnEliminarZona'])){
    $IdReg=$_POST['idZona'];
   $querys = mysqli_query($conection,"UPDATE gp_zona 
       SET estado='0'
   WHERE idzona='$IdReg'");

   $query = mysqli_query($conection,"SELECT 
        idzona as id,
        idproyecto as idproyecto
   from gp_zona 
   where idzona='$IdReg' AND estado='0'");

   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}


if(isset($_POST['btnRegistrarDatosProyecto'])) { // Verifica si el formulario fue enviado con el botón "btnRegistrarDatosProyecto"

    // Captura y limpia los valores enviados por el formulario
    $txtNombrecc = isset($_POST['txtNombrecc']) ? $_POST['txtNombrecc'] : Null;
    $txtNombreccr = trim($txtNombrecc); 

    $txtCodigocc = isset($_POST['txtCodigocc']) ? $_POST['txtCodigocc'] : Null;
    $txtCodigoccr = trim($txtCodigocc); 
	
	$txtCorrelativoccx = isset($_POST['txtCorrelativoccx']) ? $_POST['txtCorrelativoccx'] : Null;
    $txtCorrelativoccccr = trim($txtCorrelativoccx);     	

    $txtResponsablecc = isset($_POST['txtResponsablecc']) ? $_POST['txtResponsablecc'] : Null;
    $txtResponsableccr = trim($txtResponsablecc);        

    $txtAreacc = isset($_POST['txtAreacc']) ? $_POST['txtAreacc'] : Null;
    $txtAreaccr = trim($txtAreacc);        

    $txtNroZonascc = isset($_POST['txtNroZonascc']) ? $_POST['txtNroZonascc'] : Null;
    $txtNroZonasccr = trim($txtNroZonascc);      

    $txtDireccioncc = isset($_POST['txtDireccioncc']) ? $_POST['txtDireccioncc'] : Null;
    $txtDireccionccr = trim($txtDireccioncc);

    $bxDepartamentoPopup = isset($_POST['bxDepartamentoPopup']) ? $_POST['bxDepartamentoPopup'] : Null;
    $bxDepartamentoPopupr = trim($bxDepartamentoPopup);

    $bxProvinciaPopup = isset($_POST['bxProvinciaPopup']) ? $_POST['bxProvinciaPopup'] : Null;
    $bxProvinciaPopupr = trim($bxProvinciaPopup);

    $bxDistritoPopup = isset($_POST['bxDistritoPopup']) ? $_POST['bxDistritoPopup'] : Null;
    $bxDistritoPopupr = trim($bxDistritoPopup);

    // Inicializa la variable de validación para verificar si el proyecto ya existe
    $respuesta_zona = 0;

    // Consulta si el código del proyecto ya existe en la base de datos
    $consultar_zona = mysqli_query($conection,"SELECT idproyecto FROM gp_proyecto WHERE codigo='$txtCodigoccr'");
    $respuesta_zona = mysqli_num_rows($consultar_zona); // Cuenta el número de registros encontrados

    // Validar que todos los campos requeridos están llenos
    if(!empty($txtNombreccr) && !empty($txtCodigoccr) && !empty($txtResponsableccr) && !empty($txtAreaccr) && 
       !empty($txtNroZonasccr) && !empty($txtDireccionccr) && !empty($bxDepartamentoPopupr) && 
       !empty($bxProvinciaPopupr) && !empty($bxDistritoPopupr)) {

        // Si no existe un proyecto con el mismo código, proceder con el registro
        // Si no existe un proyecto con el mismo código, proceder con el registro
		if ($respuesta_zona < 1) {

			// Insertar el nuevo proyecto en la base de datos
			$actualizar_proyecto = mysqli_query($conection, 
				"INSERT INTO gp_proyecto(nombre, codigo, correlativo, responsable, area, nro_zonas, direccion, departamento, provincia, distrito) 
				VALUES ('$txtNombreccr','$txtCodigoccr', '$txtCorrelativoccccr', '$txtResponsableccr', '$txtAreaccr', '$txtNroZonasccr', '$txtDireccionccr', '$bxDepartamentoPopupr', '$bxProvinciaPopupr', '$bxDistritoPopupr')");

			if ($actualizar_proyecto) {
				// Obtener el ID del último registro insertado
				$id_proyec = mysqli_insert_id($conection);

				if ($id_proyec > 0) { // Si el ID es válido, la inserción fue exitosa
					$data['status'] = "ok";
					$data['data'] = "Se registró el proyecto con éxito. A continuación, complemente el registro ingresando la información de zonas, manzanas y lotes asociados al proyecto.";
					$data['idproy'] = $id_proyec;
				} else {
					// Si no se pudo obtener el ID, algo salió mal en la inserción
					$data['status'] = "bad";
					$data['data'] = "No se completó el registro. Revise los datos ingresados.";
				}
			} else {
				// Error en la consulta de inserción
				$data['status'] = "bad";
				$data['data'] = "Error en la inserción del proyecto: " . mysqli_error($conection);
			}
		} else {
            // Si el código del proyecto ya existe, se envía un mensaje de error
            $data['status'] = "bad";
            $data['data'] = "Ya se registró un proyecto con los mismos datos. Intente registrar un nuevo proyecto. Código: ".$txtCodigoccr;
        }
    } else {
        // Si falta algún campo, se envía una respuesta de error
        $data['status'] = "bad";
        $data['data'] = "Complete todos los campos. (Nombre, Responsable, Área, Nro Zonas, Dirección, Departamento, Provincia y Distrito)";
    } 

    // Retorna la respuesta en formato JSON
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);                                 
}

/*************** AGREGAR ZONA ****************/
if (isset($_POST['btnActualizarDatosProyecto'])) {

    // Captura de valores y limpieza
    $txtidProyectoc       = isset($_POST['txtidProyectocc']) ? $_POST['txtidProyectocc'] : Null;
    $txtidProyectocr      = trim($txtidProyectoc);

    $txtNombrecc          = isset($_POST['txtNombrecc']) ? $_POST['txtNombrecc'] : Null;
    $txtNombreccr         = trim($txtNombrecc);

    $txtCodigocc          = isset($_POST['txtCodigocc']) ? $_POST['txtCodigocc'] : Null;
    $txtCodigoccr         = trim($txtCodigocc);

    $txtResponsablecc     = isset($_POST['txtResponsablecc']) ? $_POST['txtResponsablecc'] : Null;
    $txtResponsableccr    = trim($txtResponsablecc);

    $txtAreacc            = isset($_POST['txtAreacc']) ? $_POST['txtAreacc'] : Null;
    $txtAreaccr           = trim($txtAreacc);

    $txtNroZonascc        = isset($_POST['txtNroZonascc']) ? $_POST['txtNroZonascc'] : Null;
    $txtNroZonasccr       = trim($txtNroZonascc);

    $txtDireccioncc       = isset($_POST['txtDireccioncc']) ? $_POST['txtDireccioncc'] : Null;
    $txtDireccionccr      = trim($txtDireccioncc);

    $bxDepartamentoPopup  = isset($_POST['bxDepartamentoPopup']) ? $_POST['bxDepartamentoPopup'] : Null;
    $bxDepartamentoPopupr = trim($bxDepartamentoPopup);

    $bxProvinciaPopup     = isset($_POST['bxProvinciaPopup']) ? $_POST['bxProvinciaPopup'] : Null;
    $bxProvinciaPopupr    = trim($bxProvinciaPopup);

    $bxDistritoPopup      = isset($_POST['bxDistritoPopup']) ? $_POST['bxDistritoPopup'] : Null;
    $bxDistritoPopupr     = trim($bxDistritoPopup);

    $consultar_zona = mysqli_query($conection, "SELECT idproyecto FROM gp_proyecto WHERE idproyecto='$txtidProyectocr'");
    $respuesta_zona = mysqli_num_rows($consultar_zona);

    if (!empty($txtNombreccr) && !empty($txtCodigoccr) && !empty($txtResponsableccr) &&
        !empty($txtAreaccr) && !empty($txtNroZonasccr) && !empty($txtDireccionccr) &&
        !empty($bxDepartamentoPopupr) && !empty($bxProvinciaPopupr) && !empty($bxDistritoPopupr)) {

        if ($respuesta_zona > 0) {

            // Consulta la cantidad actual de zonas registradas en la tabla gp_zona
            $consultar_zonas = mysqli_query($conection, "SELECT COUNT(*) as total FROM gp_zona WHERE estado = 1 AND idproyecto='$txtidProyectocr'");
            $respuesta_zonas = mysqli_fetch_assoc($consultar_zonas)['total'];

            // Consulta el nro_zonas actual del proyecto (por si no fue modificado)
            $proyecto_actual = mysqli_query($conection, "SELECT nro_zonas FROM gp_proyecto WHERE idproyecto='$txtidProyectocr'");
            $proyecto_info   = mysqli_fetch_assoc($proyecto_actual);
            $nro_zonas_actual = intval($proyecto_info['nro_zonas']);
			$area_actual = floatval($proyecto_info['area']);
			
			// Consulta la suma de áreas ya registradas en zonas activas
			$consulta_area_zonas = mysqli_query($conection, 
			"SELECT SUM(area) AS total_area_zonas 
			FROM gp_zona 
			WHERE idproyecto = '$txtidProyectocr' AND estado = 1");
			$area_zonas_row = mysqli_fetch_assoc($consulta_area_zonas);
			$area_zonas_total = floatval($area_zonas_row['total_area_zonas'] ?? 0);
			
            // Si intenta reducir el límite por debajo del número de zonas ya registradas, se bloquea
            if ($txtNroZonasccr < $nro_zonas_actual && $txtNroZonasccr < $respuesta_zonas) {
                $data['status'] = "bad";
                $data['data'] = "El Nro de Zonas ingresado no es permitido ya que actualmente existe un número de zonas registradas superior al ingresado.";
			// ✅ Validar que no se ingrese un área menor que la suma de las zonas activas	
			} elseif (floatval($txtAreaccr) < $area_zonas_total) {
				$data['status'] = "bad";
				$data['data'] = "El área ingresada no es válida. Ya se han registrado " . number_format($area_zonas_total, 2, '.', ',') . " m² en zonas activas. El área del proyecto no puede ser menor.";	
				
            } else {
                // Realizar la actualización del proyecto
                $actualizar_proyecto = mysqli_query($conection,
                    "UPDATE gp_proyecto SET 
                        nombre='$txtNombreccr',
                        responsable='$txtResponsableccr',
                        area='$txtAreaccr',
                        nro_zonas='$txtNroZonasccr',
                        direccion='$txtDireccionccr',
                        departamento='$bxDepartamentoPopupr',
                        provincia='$bxProvinciaPopupr',
                        distrito='$bxDistritoPopupr'
                    WHERE idproyecto='$txtidProyectocr' AND estado='1'"
                );

                // Confirmar si los cambios fueron aplicados
                $consultar = mysqli_query($conection,
                    "SELECT idproyecto FROM gp_proyecto 
                     WHERE idproyecto='$txtidProyectocr' 
                     AND nombre='$txtNombreccr' 
                     AND responsable='$txtResponsableccr' 
                     AND area='$txtAreaccr' 
                     AND nro_zonas='$txtNroZonasccr' 
                     AND direccion='$txtDireccionccr' 
                     AND departamento='$bxDepartamentoPopupr' 
                     AND provincia='$bxProvinciaPopupr' 
                     AND distrito='$bxDistritoPopupr'"
                );

                $consultar_registro = mysqli_num_rows($consultar);

                if ($consultar_registro > 0) {
                    $data['status'] = "ok";
                    $data['data'] = "Se actualizaron los datos del proyecto con éxito.";
                } else {
                    $data['status'] = "bad";
                    $data['data'] = "No se completó la actualización. Revise los datos ingresados.";
                }
            }

        } else {
            $data['status'] = "bad";
            $data['data'] = "No se encontró el código del proyecto. Intente nuevamente.";
        }

    } else {
        $data['status'] = "bad";
        $data['data'] = "Completar todos los campos. (Nombre, Responsable, Área, Nro Zonas, Dirección, Departamento, Provincia y Distrito)";
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*************** AGREGAR ZONA ****************/
if (isset($_POST['btnAgregarZonaPopup'])) {

    // Captura y limpia el ID del proyecto
    $txtidProyectoZona = isset($_POST['txtidProyectocc']) ? $_POST['txtidProyectocc'] : Null;
    $txtidProyector = trim($txtidProyectoZona); 

    // Captura y limpia el nombre de la zona
    $txtNombreZona = isset($_POST['txtNombreZonacc']) ? $_POST['txtNombreZonacc'] : Null;
    $txtNombreZonar = trim($txtNombreZona);        

    // Captura y limpia el código de la zona
    $txtCodigoZona = isset($_POST['txtCodigoZonacc']) ? $_POST['txtCodigoZonacc'] : Null;
    $txtCodigoZonar = trim($txtCodigoZona);        

    // Captura y limpia el área de la zona
    $txtAreaZona = isset($_POST['txtAreaZonacc']) ? $_POST['txtAreaZonacc'] : Null;
    $txtAreaZonar = trim($txtAreaZona);        

    // Captura y limpia el correlativo
    $txtGeneracionManzanas = isset($_POST['txtCorrelativoZonacc']) ? $_POST['txtCorrelativoZonacc'] : Null;
    $txtCorrelativoZonar = trim($txtGeneracionManzanas);      

    // Captura y limpia el número de manzanas
    $txtNroManzana = isset($_POST['txtNroManzanacc']) ? $_POST['txtNroManzanacc'] : Null;
    $txtNroManzanar = trim($txtNroManzana);

    // Captura y limpia el número máximo de zonas permitidas para ese proyecto
    $txtNroZonascc = isset($_POST['txtNroZonascc']) ? $_POST['txtNroZonascc'] : Null;
    $txtNroZonasccr = trim($txtNroZonascc);

    // Consulta la cantidad máxima de zonas permitidas para el proyecto
    $consultar_nro_zonas = mysqli_query($conection, "SELECT nro_zonas as zonas FROM gp_proyecto WHERE idproyecto='$txtidProyector'");
    $respuesta_nro_zonas = mysqli_fetch_assoc($consultar_nro_zonas);
    $nro_zonas = $respuesta_nro_zonas['zonas'];
            
    // Consulta cuántas zonas ya han sido registradas para ese proyecto
    $consultar_zona = mysqli_query($conection, "SELECT idzona FROM gp_zona WHERE idproyecto='$txtidProyector' AND estado='1'");
    $respuesta_zona = mysqli_num_rows($consultar_zona);

    // Verifica si todavía se pueden registrar más zonas (comparando zonas existentes con el máximo permitido)
    if ($respuesta_zona < $nro_zonas) {

        // Validar que el nombre no esté vacío
        if (!empty($txtNombreZonar)) {

            // Validar que el área no esté vacía
            if (!empty($txtAreaZonar)) {
				
				
				// 1. Obtener el área total del proyecto
				$consulta_area_proyecto = mysqli_query($conection, "SELECT area FROM gp_proyecto WHERE idproyecto = '$txtidProyector'");
				$area_proyecto_row = mysqli_fetch_assoc($consulta_area_proyecto);
				$area_proyecto_total = floatval($area_proyecto_row['area']);

				// 2. Obtener la suma del área de todas las zonas registradas para ese proyecto
				$consulta_area_usada = mysqli_query($conection, "SELECT SUM(area) AS total_area_zonas FROM gp_zona WHERE idproyecto = '$txtidProyector' AND estado = '1'");
				$area_usada_row = mysqli_fetch_assoc($consulta_area_usada);
				$area_zonas_total = floatval($area_usada_row['total_area_zonas']);

				// 3. Convertir el área de la zona actual a registrar
				$area_nueva_zona = floatval($txtAreaZonar);

				// 4. Validar si se excede el área del proyecto
                if (($area_zonas_total + $area_nueva_zona) > $area_proyecto_total) {
                    $area_disponible = $area_proyecto_total - $area_zonas_total;

                    $data['status'] = "bad";
                    $data['data'] = "El área total del proyecto es $area_proyecto_total m². 
                    Ya se han asignado $area_zonas_total m². 
                    Solo puede registrar hasta $area_disponible m² como máximo para esta nueva zona.";

                    header('Content-type: text/javascript');
                    echo json_encode($data, JSON_PRETTY_PRINT);
                    exit; // 🛑 Cortamos ejecución si el área excede
                }

                // Validar que el número de manzanas no esté vacío
                if (!empty($txtNroManzanar)) {

                    // Verificar si ya existe una zona con el mismo nombre, código y proyecto
                    $consultar_proyecto = mysqli_query($conection, "SELECT idzona FROM gp_zona WHERE nombre='$txtNombreZonar' AND codigo='$txtCodigoZonar' AND idproyecto='$txtidProyector' AND estado='1'");
                    $respuesta_proyecto = mysqli_num_rows($consultar_proyecto);

                    // Si no existe una zona duplicada, se puede insertar
                    if ($respuesta_proyecto == 0) {

                        // Inserta la nueva zona en la base de datos
                        $query = mysqli_query($conection, "INSERT INTO gp_zona(nombre, codigo, nro_manzanas, area, idproyecto, correlativo) VALUES ('$txtNombreZonar','$txtCodigoZonar','$txtNroManzanar','$txtAreaZonar','$txtidProyector','$txtCorrelativoZonar')");

                        // Verifica si se insertó correctamente
                        $consultar = mysqli_query($conection, "SELECT idzona as id FROM gp_zona WHERE nombre='$txtNombreZonar' AND codigo='$txtCodigoZonar' AND idproyecto='$txtidProyector'");
                        $respuesta = mysqli_fetch_assoc($consultar);
                        $consultar_registro = mysqli_num_rows($consultar);

                        // Si se encuentra el nuevo registro, devuelve estado ok
                        if ($consultar_registro > 0) {
                            $data['status'] = "ok";
                            $data['data'] = "Se ha registrado la Zona : ".$txtNombreZonar;
                            $data['idproyecto'] = $txtidProyector;
                        } else {
                            // Fallo al insertar
                            $data['status'] = "bad";
                            $data['data'] = "No se completó el Registro. Revise los datos ingresados.";
                        }

                    } else {
                        // Zona ya existente
                        $data['status'] = "bad";
                        $data['data'] = "La Zona ingresada ya existe. Intente con otro.";
                    }

                } else {
                    // Falta número de manzanas
                    $data['status'] = "bad";
                    $data['data'] = "Ingrese el Número de Manzanas correspondientes a la Zona.";
                }

            } else {
                // Falta área
                $data['status'] = "bad";
                $data['data'] = "Ingrese el Área de la Zona.";
            }

        } else {
            // Falta nombre
            $data['status'] = "bad";
            $data['data'] = "Ingrese el Nombre del Zona.";
        }

    } else {
        // Ya se alcanzó el máximo de zonas permitidas para este proyecto
        $data['status'] = "bad";
        $data['data'] = "No se permite el ingreso de más Zonas. Se estableció un máximo de ".$nro_zonas." zonas para el Proyecto.";
    }      

    // Devuelve la respuesta en formato JSON
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);  
}

/*************** AGREGAR ZONA ****************/

if(isset($_POST['btnCargarParametrosZona'])){
    $IdReg=$_POST['idZona'];
   $query = mysqli_query($conection,"SELECT 
        gpz.idzona as id,
        format(gpz.area,2) as area,
        gpz.nro_manzanas as manzanas
   from gp_zona gpz
   where gpz.idzona='$IdReg'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if(isset($_POST['btnGuardarDatosZona'])){

    $txtidZonaPopup = isset($_POST['txtidZonaPopupcc']) ? $_POST['txtidZonaPopupcc'] : Null;
    $txtidZonaPopupr = trim($txtidZonaPopup); 

    $txtNombreZonaPopup = isset($_POST['txtNombreZonaPopup']) ? $_POST['txtNombreZonaPopup'] : Null;
    $txtNombreZonaPopupr = trim($txtNombreZonaPopup); 

    $txtNroManzanasPopup = isset($_POST['txtNroManzanasPopup']) ? $_POST['txtNroManzanasPopup'] : Null;
    $txtNroManzanasPopupr = trim($txtNroManzanasPopup);        

    $txtAreaPopup = isset($_POST['txtAreaPopup']) ? $_POST['txtAreaPopup'] : Null;
    $txtAreaPopupr = trim($txtAreaPopup);          

    $consultar_zona = mysqli_query($conection,"SELECT idzona as idzona, idproyecto as idproyecto FROM gp_zona WHERE idzona='$txtidZonaPopupr'");
    $respuesta_zona = mysqli_num_rows($consultar_zona);
    $respuesta = mysqli_fetch_assoc($consultar_zona);
    $idproyecto = $respuesta['idproyecto'];

    if(!empty($txtNombreZonaPopupr) && !empty($txtNroManzanasPopupr) && !empty($txtAreaPopupr)){
      if($respuesta_zona > 0){
		  
			/********** AREA ******************/
			
			// 1. Obtener área total del proyecto
            $consulta_area_proyecto = mysqli_query($conection, "SELECT area FROM gp_proyecto WHERE idproyecto = '$idproyecto'");
            $area_proyecto_row = mysqli_fetch_assoc($consulta_area_proyecto);
            $area_proyecto_total = floatval($area_proyecto_row['area']);

            // 2. Obtener suma de áreas de otras zonas (excluyendo la zona actual)
            $consulta_area_usada = mysqli_query($conection, "SELECT SUM(area) AS total_area_zonas FROM gp_zona WHERE idproyecto = '$idproyecto' AND estado = '1' AND idzona != '$txtidZonaPopupr'");
            $area_usada_row = mysqli_fetch_assoc($consulta_area_usada);
            $area_zonas_total = floatval($area_usada_row['total_area_zonas']);

            // 3. Convertir área nueva a número
            $area_nueva_zona = floatval($txtAreaPopupr);

            // 4. Verificar si nueva área + usadas supera el total
            if (($area_zonas_total + $area_nueva_zona) > $area_proyecto_total) {
                $area_disponible = $area_proyecto_total - $area_zonas_total;

                $data['status'] = "bad";
                $data['data'] = "No se puede actualizar. El área total del proyecto es $area_proyecto_total m², 
                ya se han asignado $area_zonas_total m². Solo puede asignar hasta $area_disponible m² a esta zona.";

                header('Content-type: text/javascript');
                echo json_encode($data, JSON_PRETTY_PRINT);
                exit; 
            }
			
			/********** AREA ******************/
        
            $actualizar_proyecto = mysqli_query($conection, "UPDATE gp_zona SET nombre='$txtNombreZonaPopupr', nro_manzanas='$txtNroManzanasPopupr', area='$txtAreaPopupr' WHERE idzona='$txtidZonaPopupr' AND estado='1'");

            //Consultar Nuevo Ingreso
            $consultar = mysqli_query($conection, "SELECT idzona FROM gp_zona WHERE idzona='$txtidZonaPopupr' AND nombre='$txtNombreZonaPopupr'AND nro_manzanas='$txtNroManzanasPopupr'AND area='$txtAreaPopupr'");
            $consultar_registro = mysqli_num_rows($consultar);

            if ($consultar_registro > 0) {                                       

               $data['status'] = "ok";
               $data['data'] = "Se actualizaron los datos de la zona con éxito.";
               $data['idproyecto'] = $idproyecto;

            } else {
               $data['status'] = "bad";
               $data['data'] = "No se completo la Actualización. Revise los datos ingresados.";
            } 

             
      }else {
         $data['status'] = "bad";
         $data['data'] = "No se encontro el codigo de la Zona. Intente nuevamente.";
      }
   }else {
      $data['status'] = "bad";
      $data['data'] = "Completar todos los campos. (Nombre, Nro Manzanas y Area)";
   } 

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}

//POPUP MANZANA

if(isset($_POST['btnAgregarManzanaPopup'])){

    $cbxZonascc = isset($_POST['cbxZonascc']) ? $_POST['cbxZonascc'] : Null;
    $cbxZonasccr = trim($cbxZonascc); 

    $txtNroManzanascc = isset($_POST['txtNroManzanascc']) ? $_POST['txtNroManzanascc'] : Null;
    $txtNroManzanasccr = trim($txtNroManzanascc); 

    $txtCodigoManzanacc = isset($_POST['txtCodigoManzanacc']) ? $_POST['txtCodigoManzanacc'] : Null;
    $txtCodigoManzanaccr = trim($txtCodigoManzanacc); 

    $txtCorrelativoManzanacc = isset($_POST['txtCorrelativoManzanacc']) ? $_POST['txtCorrelativoManzanacc'] : Null;
    $txtCorrelativoManzanaccr = trim($txtCorrelativoManzanacc); 

    $txtNombreManzanacc = isset($_POST['txtNombreManzanacc']) ? $_POST['txtNombreManzanacc'] : Null;
    $txtNombreManzanaPopupr = trim($txtNombreManzanacc); 

    $txtNumLotescc = isset($_POST['txtNumLotescc']) ? $_POST['txtNumLotescc'] : Null;
    $txtNroLotesPopupr = trim($txtNumLotescc);        

    $txtAreaManzanacc = isset($_POST['txtAreaManzanacc']) ? $_POST['txtAreaManzanacc'] : Null;
    $txtAreaManzanaPopupr = trim($txtAreaManzanacc);
    
    $cbxGeneracionAutom = isset($_POST['cbxGeneracionAutom']) ? $_POST['cbxGeneracionAutom'] : Null;
    $cbxGeneracionAutomr = trim($cbxGeneracionAutom);

    $txtNroMzcc = isset($_POST['txtNroMzcc']) ? $_POST['txtNroMzcc'] : Null;
    $txtNroMzccr = trim($txtNroMzcc);

    $txtNombreMzs = isset($_POST['txtNombreMzs']) ? $_POST['txtNombreMzs'] : Null;
    $txtNombreMzsr = trim($txtNombreMzs);

    if ($cbxGeneracionAutomr == 0) {
		//Verifica si la manzana ya existe en la base de datos
        $consultar_manzana = mysqli_query($conection,"SELECT idmanzana FROM gp_manzana WHERE codigo='$txtCodigoManzanaccr' AND estado='1' AND idzona='$cbxZonasccr' AND nombre='$txtNombreManzanaPopupr'");
        $respuesta_manzana = mysqli_num_rows($consultar_manzana);
		
		// Si no existe una manzana activa con el mismo código, se puede registrar
        if ($respuesta_manzana < 1) {

            // CONSULTAR CUÁNTAS MANZANAS ACTIVAS EXISTEN EN LA ZONA
			$consultar_total_manzana = mysqli_query($conection, "SELECT COUNT(*) as total FROM gp_manzana WHERE estado='1' AND idzona='$cbxZonasccr'");
			$respuesta_total_manzana = mysqli_fetch_assoc($consultar_total_manzana);
			$total_manzanas_activas = $respuesta_total_manzana['total'];

			// CONSULTA EL LÍMITE DE MANZANAS PERMITIDAS EN LA ZONA (no debería depender del estado)
			$consultar_nro_mz = mysqli_query($conection, "SELECT nro_manzanas as manzanas, area FROM gp_zona WHERE idzona='$cbxZonasccr'");
			$respuesta_nro_mz = mysqli_fetch_assoc($consultar_nro_mz);
			$nro_manzanas = $respuesta_nro_mz['manzanas'];
			$area_total_zona = floatval($respuesta_nro_mz['area']);
			
			
			 // 3. CONSULTAR suma del área actual de manzanas en esa zona
			$consulta_area_ocupada = mysqli_query($conection, 
				"SELECT SUM(area) AS total_area 
				 FROM gp_manzana 
				 WHERE estado='1' 
				 AND idzona='$cbxZonasccr'");
			$row_area = mysqli_fetch_assoc($consulta_area_ocupada);
			$area_ocupada = floatval($row_area['total_area']);
			
			// Valida si ya existe una manzana con el mismo nombre en esta zona
			$validar_nombre = mysqli_query($conection, 
				"SELECT idmanzana FROM gp_manzana 
				 WHERE nombre='$txtNombreManzanaPopupr' 
				 AND idzona='$cbxZonasccr' 
				 AND estado='1'");
			if (mysqli_num_rows($validar_nombre) > 0) {
				$data['status'] = "bad";
				$data['data'] = "Ya existe una manzana con ese nombre en esta zona. Use un nombre diferente.";
				echo json_encode($data, JSON_PRETTY_PRINT);
				exit;
			}
			
			//verifica si aún se pueden registrar más manzanas
            if ($total_manzanas_activas < $nro_manzanas) {
				
			   // 5. Validar que el área de esta manzana no exceda el total
				$area_nueva = floatval($txtAreaManzanaPopupr);
				if (($area_ocupada + $area_nueva) > $area_total_zona) {
					$area_disponible = $area_total_zona - $area_ocupada;
					$data['status'] = "bad";
					$data['data'] = "El área total de la zona es $area_total_zona m². 
					Ya se han asignado $area_ocupada m². 
					Solo puede registrar hasta $area_disponible m² como máximo.";
					
				} else {				
						//inserta manzanas
						$insertar_manzanas = mysqli_query($conection, "INSERT INTO gp_manzana(nombre, codigo, estado, area, nro_lotes, idzona, generacion_automatica, correlativo, tipo_casa) VALUES ('$txtNombreManzanaPopupr', '$txtCodigoManzanaccr','1','$txtAreaManzanaPopupr', '$txtNroLotesPopupr', '$cbxZonasccr','$cbxGeneracionAutomr','$txtCorrelativoManzanaccr', '1')");
					
						if (mysqli_affected_rows($conection) > 0) {
							$id_insertado = mysqli_insert_id($conection); // ID de la manzana creada
							
							// Inserta tipo de casa por defecto (1)
							$insertar_tipocasa = mysqli_query($conection, 
								"INSERT INTO gp_manzana_tipocasa(idzona, idmanzana, tipo_casa, estado, registro_control) 
								 VALUES ('$cbxZonasccr', '$id_insertado', 1, 1, NOW())");

							if (mysqli_affected_rows($conection) > 0) {
								$data['status'] = "ok";
								$data['data'] = "Se registró la manzana y su tipo de casa por defecto correctamente.";
							} else {
								$data['status'] = "ok";
								$data['data'] = "La manzana fue registrada, pero no se asignó el tipo de casa por defecto. ";
							}
							
						} else {
								$data['status'] = "bad";
								$data['data'] = "No se completó el registro. Revise los datos ingresados.";
						}
				}		
            } else {
                $data['status'] = "bad";
                $data['data'] = "Ya no es posible registrar más manzanas debido al limite que establece el registro de la zona, si desea adicionar manzanas ir a la pestaña de zonas y ampliar el limite de manzanas permitido.";
            }
        } else {
            $data['status'] = "bad";
            $data['data'] = "La manzana ya existe, registre una diferente. Gracias";
        }
		
    } else {
		// Consulta cuántas manzanas activas hay en la zona
		$consultar_total_manzana = mysqli_query($conection, 
			"SELECT COUNT(*) as total 
			 FROM gp_manzana 
			 WHERE estado='1' AND idzona='$cbxZonasccr'");
		$respuesta_total_manzana = mysqli_fetch_assoc($consultar_total_manzana);
		$total_manzanas_activas = $respuesta_total_manzana['total'];

		// Consulta límite de manzanas y área total permitida en la zona
		$consultar_nro_mz = mysqli_query($conection, 
			"SELECT nro_manzanas as manzanas, area 
			 FROM gp_zona 
			 WHERE idzona='$cbxZonasccr'");
		$respuesta_nro_mz = mysqli_fetch_assoc($consultar_nro_mz);
		$nro_manzanas = $respuesta_nro_mz['manzanas'];	
		$area_total_zona = floatval($respuesta_nro_mz['area']);

		// Área total ya ocupada por manzanas activas
		$consulta_area_usada = mysqli_query($conection, 
			"SELECT SUM(area) AS total 
			 FROM gp_manzana 
			 WHERE estado='1' AND idzona='$cbxZonasccr'");
		$row_area = mysqli_fetch_assoc($consulta_area_usada);
		$area_ocupada = floatval($row_area['total']);

		// Área de una nueva manzana
		$area_manzana = floatval($txtAreaManzanaPopupr);

		// Cantidad a insertar
		$nuevas_manzanas = intval($txtNroMzccr);

		//  VALIDACIONES PREVIAS

		// 1. Límite de cantidad
		if (($total_manzanas_activas + $nuevas_manzanas) > $nro_manzanas) {
			
			$disponibles = $nro_manzanas - $total_manzanas_activas;
			$data['status'] = "bad";
			$data['data'] = "No se pueden registrar $nuevas_manzanas manzanas. 
			Solo puedes agregar $disponibles adicionales. Si desea adicionar manzanas ir a la pestaña de zonas y ampliar el limite de manzanas permitido.";
			echo json_encode($data, JSON_PRETTY_PRINT);
			exit;
		}

		// Validación de área
		$area_total_necesaria = $area_manzana * $nuevas_manzanas;
		
		if (($area_ocupada + $area_total_necesaria) > $area_total_zona) {
			
			$area_disponible = $area_total_zona - $area_ocupada;
			
			$data['status'] = "bad";
			$data['data'] = "No se pueden registrar manzanas por exceder el área total. 

			Área disponible: " . number_format($area_disponible, 2, '.', ',') . " m². 
			Área requerida: " . number_format($area_total_necesaria, 2, '.', ',') . " m².";		
			echo json_encode($data, JSON_PRETTY_PRINT);
			exit;
		}

		// Obtener correlativo base
		$consultar_correlativo = mysqli_query($conection, 
			"SELECT MAX(correlativo) AS cor 
			 FROM gp_manzana 
			 WHERE idzona='$cbxZonasccr' AND estado='1'");
		$respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
		$variable = $respuesta_correlativo['cor'] ?? 0;
		
		// Obtener última letra usada
		$consulta_letra = mysqli_query($conection, 
			"SELECT RIGHT(TRIM(nombre), 1) AS ultima_letra
			 FROM gp_manzana 
			 WHERE idzona='$cbxZonasccr' AND estado='1' 
			 AND nombre LIKE '$txtNombreMzsr %'
			 ORDER BY correlativo DESC LIMIT 1");

		$row_letra = mysqli_fetch_assoc($consulta_letra);
		$ascii_letra_inicial = isset($row_letra['ultima_letra']) ? ord($row_letra['ultima_letra']) : 64;

		// Insertar manzanas
		$x = 0; // duplicados
		$y = 0; // insertados

		for ($i = 0; $i < $nuevas_manzanas; $i++) {
			
			$variable++;
			$codigo = "MZ-" . str_pad($variable, 3, "0", STR_PAD_LEFT);
			$letra = chr($ascii_letra_inicial + 1 + $i);
			$new_nombre = $txtNombreMzsr . " " . $letra;

			// Verifica duplicado
			$consultar_manzana_ver = mysqli_query($conection,
				"SELECT idmanzana 
				 FROM gp_manzana 
				 WHERE codigo='$codigo' 
				 AND estado='1' 
				 AND idzona='$cbxZonasccr' 
				 AND nombre='$new_nombre'");
			$respuesta_manzana_ver = mysqli_num_rows($consultar_manzana_ver);

			if ($respuesta_manzana_ver < 1) {
				// Insertar manzana
				$insertar_manzanas = mysqli_query($conection, 
					"INSERT INTO gp_manzana(nombre, codigo, estado, area, nro_lotes, idzona, generacion_automatica, correlativo, tipo_casa) 
					 VALUES ('$new_nombre', '$codigo', '1', '$txtAreaManzanaPopupr', '$txtNroLotesPopupr', '$cbxZonasccr', '$cbxGeneracionAutomr', '$variable', '1')");

				if (mysqli_affected_rows($conection) > 0) {
					$idmanzana = mysqli_insert_id($conection);

					// Insertar tipo de casa por defecto
					mysqli_query($conection, 
						"INSERT INTO gp_manzana_tipocasa(idzona, idmanzana, tipo_casa, estado, registro_control) 
						 VALUES ('$cbxZonasccr', '$idmanzana', 1, 1, NOW())");

					$area_ocupada += $area_manzana;
					$total_manzanas_activas++;
					$y++;
				}
			} else {
				$x++;
			}
		}

		if ($y === 0) {
			$data['status'] = "bad";
			$data['data'] = "No se registraron manzanas. Posibles duplicados o errores.";
		} else if ($x > 0) {
			$data['status'] = "ok";
			$data['data'] = "Se registraron $y manzanas. Excepto $x duplicadas.";
		} else {
			$data['status'] = "ok";
			$data['data'] = "Se registraron todas las manzanas correctamente.";
		}
    }

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}

if (isset($_POST['btnGuardarDatosManzana'])) {

    $txtidManzanaPopupcc = trim($_POST['txtidManzanaPopupcc'] ?? '');
    $txtNombreManzanaPopup = trim($_POST['txtNombreManzanaPopup'] ?? '');
    $txtNroLotesPopup = trim($_POST['txtNroLotesPopup'] ?? '');
    $txtAreaManzanaPopup = trim($_POST['txtAreaManzanaPopup'] ?? '');

    if (!empty($txtNombreManzanaPopup) && !empty($txtNroLotesPopup) && !empty($txtAreaManzanaPopup)) {

        $consultar_manzana = mysqli_query($conection, "SELECT idmanzana, idzona FROM gp_manzana WHERE idmanzana='$txtidManzanaPopupcc'");
        $respuesta_manzana = mysqli_fetch_assoc($consultar_manzana);

        if ($respuesta_manzana) {

            $idzona = $respuesta_manzana['idzona'];

            // Área total de la zona
            $consulta_zona = mysqli_query($conection, "SELECT area FROM gp_zona WHERE idzona='$idzona'");
            $row_zona = mysqli_fetch_assoc($consulta_zona);
            $area_total_zona = floatval($row_zona['area']);

            // Área total de las otras manzanas en la zona (excluyendo la actual)
            $consulta_area_ocupada = mysqli_query($conection, "SELECT SUM(area) AS total FROM gp_manzana WHERE idzona='$idzona' AND idmanzana != '$txtidManzanaPopupcc' AND estado='1'");
            $row_area = mysqli_fetch_assoc($consulta_area_ocupada);
            $area_ocupada = floatval($row_area['total']);

            $nueva_area = floatval($txtAreaManzanaPopup);

            if (($area_ocupada + $nueva_area) > $area_total_zona) {
				
                $area_disponible = $area_total_zona - $area_ocupada;
				
				// Aplicar formato a los valores
				$total_formateado = number_format($area_total_zona, 2, '.', ',');
				$ocupada_formateada = number_format($area_ocupada, 2, '.', ',');
				$disponible_formateada = number_format($area_disponible, 2, '.', ',');
				
                $data['status'] = "bad";           
				$data['data'] = "El área total permitida en la zona es {$total_formateado} m². Ya hay {$ocupada_formateada} m² asignados. Solo puede registrar hasta {$disponible_formateada} m².";
				
            } else {
				
				$validar_nombre = mysqli_query($conection,
                    "SELECT idmanzana FROM gp_manzana 
                     WHERE nombre='$txtNombreManzanaPopup' 
                     AND idzona='$idzona' 
                     AND idmanzana != '$txtidManzanaPopupcc' 
                     AND estado='1'");

                if (mysqli_num_rows($validar_nombre) > 0) {
                    $data['status'] = "bad";
                    $data['data'] = "El nombre de manzana ya está registrado en esta zona. Por favor, use un nombre diferente.";
                } else {

					// Verificar cantidad de lotes ya existentes
					$consulta_lotes = mysqli_query($conection, "SELECT COUNT(*) AS total FROM gp_lote WHERE idmanzana='$txtidManzanaPopupcc' AND esta_borrado = 0");
					$row_lotes = mysqli_fetch_assoc($consulta_lotes);
					$total_lotes = $row_lotes['total'];

					if ($total_lotes <= $txtNroLotesPopup) {

						$actualizar = mysqli_query($conection, "UPDATE gp_manzana SET nombre='$txtNombreManzanaPopup', nro_lotes='$txtNroLotesPopup', area='$txtAreaManzanaPopup' WHERE idmanzana='$txtidManzanaPopupcc' AND estado='1'");

						if (mysqli_affected_rows($conection) > 0) {
							$data['status'] = "ok";
							$data['data'] = "Se actualizaron los datos de la manzana correctamente.";
						} else {
							$data['status'] = "bad";
							$data['data'] = "No se realizaron cambios. Verifique si modificó algún dato.";
						}
					} else {
						$data['status'] = "bad";
						$data['data'] = "No se puede reducir el número de lotes. Actualmente hay $total_lotes lotes registrados en esta manzana.";
					}
				}
            }

        } else {
            $data['status'] = "bad";
            $data['data'] = "No se encontró la manzana seleccionada.";
        }

    } else {
        $data['status'] = "bad";
        $data['data'] = "Complete todos los campos: Nombre, Área y Nro. de Lotes.";
    }

    header('Content-type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnConsultarDatosManzana'])){
    $IdReg=$_POST['idManzana'];
	
   $query = mysqli_query($conection," SELECT 
            gpm.idmanzana as id,
            COUNT(gpl.idlote) as lotes
        FROM gp_manzana gpm
        LEFT JOIN gp_lote gpl ON gpm.idmanzana = gpl.idmanzana
        WHERE gpm.idmanzana = '$IdReg'
        GROUP BY gpm.idmanzana");

   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if(isset($_POST['btnEliminarManzana'])){
	
    $IdReg=$_POST['idManzana'];
	
	$querys = mysqli_query($conection,"UPDATE gp_manzana SET estado='0' WHERE idmanzana='$IdReg'");

	// Desactivar tipo de casa asociado
    $query_tipocasa = mysqli_query($conection, "UPDATE gp_manzana_tipocasa SET estado='0' WHERE idmanzana='$IdReg'");


	if (mysqli_affected_rows($conection) > 0) {
		$data['status'] = 'ok';
		$data['data'] = 'Manzana y tipo de casa desactivados correctamente.';
	} else {
		$data['status'] = 'bad';
		$data['data'] = 'No se desactivó ningún registro. Puede que ya estuviera desactivado.';
	}
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}


//POPUP LOTE
if(isset($_POST['btnCargarParametrosManzana'])){
    $IdReg=$_POST['idManazana'];
   $query = mysqli_query($conection,"SELECT 
        gpm.idmanzana as id,
        format(gpm.area,2) as area,
        gpm.nro_lotes as lotes
   from gp_manzana gpm
   where gpm.idmanzana='$IdReg'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if(isset($_POST['btnAgregarLotePopups'])){
	
	$txtAreaMzEdicion = isset($_POST['txtAreaMzEdicion']) ? $_POST['txtAreaMzEdicion'] : 0;
	$area_max_manzana = floatval($txtAreaMzEdicion);

    $bxZonaslte = isset($_POST['bxZonaslte']) ? $_POST['bxZonaslte'] : Null;
    $bxZonaslter = trim($bxZonaslte); 

    $bxManzanaslte = isset($_POST['bxManzanaslte']) ? $_POST['bxManzanaslte'] : Null;
    $bxManzanaslter = trim($bxManzanaslte); 

    $txtCodigoLotee = isset($_POST['txtCodigoLotee']) ? $_POST['txtCodigoLotee'] : Null;
    $txtCodigoLoteer = trim($txtCodigoLotee); 

    $txtCorrelativoLote = isset($_POST['txtCorrelativoLote']) ? $_POST['txtCorrelativoLote'] : Null;
    $txtCorrelativoLoter = trim($txtCorrelativoLote); 

    $txtAreaLotee = isset($_POST['txtAreaLotee']) ? $_POST['txtAreaLotee'] : Null;
    $txtAreaLoteer = trim($txtAreaLotee); 

    $txtNombreLotee = isset($_POST['txtNombreLotee']) ? $_POST['txtNombreLotee'] : Null;
    $txtNombreLoteer = trim($txtNombreLotee);        

    $txtValorCCLotee = isset($_POST['txtValorCCLotee']) ? $_POST['txtValorCCLotee'] : Null;
    $txtValorCCLoteer = trim($txtValorCCLotee);
    
    $txtValorSCLotee = isset($_POST['txtValorSCLotee']) ? $_POST['txtValorSCLotee'] : Null;
    $txtValorSCLoteer = trim($txtValorSCLotee);

    $cbxGeneracionAutomLote = isset($_POST['cbxGeneracionAutomLote']) ? $_POST['cbxGeneracionAutomLote'] : Null;
    $cbxGeneracionAutomLoter = trim($cbxGeneracionAutomLote);

    $txtExtensionNombreLotee = isset($_POST['txtExtensionNombreLotee']) ? $_POST['txtExtensionNombreLotee'] : Null;
    $txtExtensionNombreLoteer = trim($txtExtensionNombreLotee);

    $txtNroLoteeGenerar = isset($_POST['txtNroLoteeGenerar']) ? $_POST['txtNroLoteeGenerar'] : Null;
    $txtNroLoteeGenerarr = trim($txtNroLoteeGenerar);
	
	$cbxTipoMonedaLoteess = isset($_POST['cbxTipoMonedaLoteess']) ? $_POST['cbxTipoMonedaLoteess'] : Null;
    $cbxTipoMonedaLoteer = trim($cbxTipoMonedaLoteess);

    if ($cbxGeneracionAutomLoter == 0) {
		
		//Verifica si el lote ya existe en la base de datos
        $consultar_lote = mysqli_query($conection,"
		SELECT idlote 
		FROM gp_lote
		WHERE codigo='$txtCodigoLoteer' AND estado='1' AND idmanzana='$bxManzanaslter' AND nombre='$txtNombreLoteer'");
        $respuesta_lote = mysqli_num_rows($consultar_lote);
		
		//Si el lote no existe ingresa
        if ($respuesta_lote < 1) {

			// Verifica si ya existe un lote con el mismo NOMBRE en la misma manzana
			$verificar_nombre = mysqli_query($conection, 
				"SELECT idlote FROM gp_lote 
				 WHERE nombre='$txtNombreLoteer' AND estado='1' AND idmanzana='$bxManzanaslter'");

			if (mysqli_num_rows($verificar_nombre) > 0) {
				$data['status'] = "bad";
				$data['data'] = "Ya existe un lote con el mismo nombre en esta manzana. Intente con otro nombre.";
				echo json_encode($data, JSON_PRETTY_PRINT);
				exit;
			}
            //CONSULTAR # DE MANZANAS REGISTRADOS PARA LA ZONA
			
			//Verifica de límite de lotes permitidos
            $consultar_total_lote = mysqli_query($conection, "SELECT COUNT(*) as total FROM gp_lote WHERE estado='1' AND idmanzana='$bxManzanaslter'");
			$respuesta_total_lote = mysqli_fetch_assoc($consultar_total_lote);
			$total_lotes_activas = isset($respuesta_total_lote['total']) ? (int)$respuesta_total_lote['total'] : 0;
			
            //CONSULTAR # DE lotes PERMITIDAS EN LA ZONA
            $consultar_nro_lts = mysqli_query($conection, "SELECT nro_lotes as lotes FROM gp_manzana WHERE idmanzana='$bxManzanaslter'");		
			$respuesta_nro_lts = mysqli_fetch_assoc($consultar_nro_lts);
			$nro_lotes = isset($respuesta_nro_lts['lotes']) ? (int)$respuesta_nro_lts['lotes'] : 0;

			if ($total_lotes_activas < $nro_lotes) {
				
				// VALIDAR ÁREA TOTAL ACUMULADA DE LOTES
				$area_max_manzana = floatval($txtAreaMzEdicion);

				// Obtener suma del área actual de los lotes existentes en esa manzana
				$query_area_ocupada = mysqli_query($conection, "
					SELECT SUM(area) as total 
					FROM gp_lote 
					WHERE estado='1' AND idmanzana='$bxManzanaslter'");
				$row_area = mysqli_fetch_assoc($query_area_ocupada);
				$area_ocupada = floatval($row_area['total']);

				// Área del nuevo lote que se intenta registrar
				$area_nuevo_lote = floatval($txtAreaLoteer);

				// Verificación: que no se exceda el área máxima
				if (($area_ocupada + $area_nuevo_lote) > $area_max_manzana) {
					$area_disponible = $area_max_manzana - $area_ocupada;

					// Mensaje con formato
					$data['status'] = "bad";
					$data['data'] = "El área total asignada a la manzana es de " . number_format($area_max_manzana, 2, '.', ',') . " m². 
					Ya se han asignado " . number_format($area_ocupada, 2, '.', ',') . " m². 
					Solo puede registrar hasta " . number_format($area_disponible, 2, '.', ',') . " m².";
				} else {
					
					//inserción del nuevo lote
					$insertar_lotes = mysqli_query($conection, "INSERT INTO gp_lote(nombre, codigo, estado, tipo_moneda, area, valor_con_casa, valor_sin_casa, idmanzana, generacion_automatica, correlativo) VALUES ('$txtNombreLoteer', '$txtCodigoLoteer','1', '$cbxTipoMonedaLoteer','$txtAreaLoteer', '$txtValorCCLoteer','$txtValorSCLoteer', '$bxManzanaslter','$cbxGeneracionAutomLoter','$txtCorrelativoLoter')");
					
					
					// Verificar si la inserción fue exitosa usando 
					if (mysqli_affected_rows($conection) > 0) {
						$data['status'] = "ok";
						$data['data'] = "Se registró el lote con éxito.";
					} else {
						$data['status'] = "bad";
						$data['data'] = "No se completó el registro. Revise los datos ingresados.";
					}
				}
            } else {
                $data['status'] = "bad";
                $data['data'] = "Ya no es posible registrar más lotes debido al limite que establece el registro de la zona, si desea adicionar lotes ir a la pestaña de manzanas y ampliar el limite de lotes permitido.";
            }
        } else {
            $data['status'] = "bad";
            $data['data'] = "El lote ya existe, registre una diferente. Gracias";
        }
	} else {

	   // Verifica si ya existe un lote con el mismo código en la base de datos (solo activos)
		$consultar_lote = mysqli_query($conection, "SELECT idlote FROM gp_lote 
			WHERE codigo='$txtCodigoLoteer' AND estado='1' AND idmanzana='$bxManzanaslter'");
		$respuesta_lote = mysqli_num_rows($consultar_lote);

		// Obtiene el máximo correlativo de lotes en la manzana
		$variable = 0;
		if ($respuesta_lote < 1) { 
			$consultar_correlativo = mysqli_query($conection, "SELECT max(correlativo) as cor 
				FROM gp_lote WHERE idmanzana='$bxManzanaslter' AND estado='1'");
			
			$respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
			$maximo = $respuesta_correlativo['cor'] ?? 0;
			$variable = $maximo;
		}

		// Verifica el número actual de lotes registrados en la manzana
		$consultar_total_lote = mysqli_query($conection, "SELECT COUNT(*) as total FROM gp_lote 
			WHERE estado='1' AND idmanzana='$bxManzanaslter'");
		$respuesta_total_lote = mysqli_fetch_assoc($consultar_total_lote);
		$total_lotes_activas = (int) $respuesta_total_lote['total']; // 🔹 Se asegura de obtener el número real de lotes

		$consultar_nro_lts = mysqli_query($conection, "
			SELECT nro_lotes as lotes, area 
			FROM gp_manzana 
			WHERE idmanzana='$bxManzanaslter'");
		$respuesta_nro_lts = mysqli_fetch_assoc($consultar_nro_lts);
		$nro_lotes = (int) $respuesta_nro_lts['lotes'];
		$area_max_manzana = floatval($respuesta_nro_lts['area']); // ✅
		
		// Suma del área actual de los lotes en esa manzana
		$query_area_ocupada = mysqli_query($conection, "SELECT SUM(area) as total 
			FROM gp_lote WHERE estado='1' AND idmanzana='$bxManzanaslter'");
		$row_area = mysqli_fetch_assoc($query_area_ocupada);
		$area_ocupada = floatval($row_area['total']);

		// Área del nuevo lote
		$area_lote = floatval($txtAreaLoteer);

		// Total área necesaria para registrar los nuevos lotes
		$area_necesaria = $txtNroLoteeGenerarr * $area_lote;

		// Validación del área
		if (($area_ocupada + $area_necesaria) > $area_max_manzana) {
			$disponible = $area_max_manzana - $area_ocupada;
			$data['status'] = "bad";
			$data['data'] = "No se pueden registrar los lotes por superar el área asignada. Área disponible: " . number_format($disponible, 2, '.', ',') . " m². Área requerida: " . number_format($area_necesaria, 2, '.', ',') . " m².";
			echo json_encode($data);
			exit;
		}

		// Validación del número de lotes permitidos
		if (($total_lotes_activas + $txtNroLoteeGenerarr) > $nro_lotes) {
			$disponibles = $nro_lotes - $total_lotes_activas;
			$data['status'] = "bad";
			$data['data'] = "No se pueden registrar más lotes. Solo puede registrar $disponibles adicional(es).";
			echo json_encode($data);
			exit;
		}
		// Inicializa el contador de duplicados
		$x = 0;

		// Bucle para generar los nuevos lotes
		for ($i = 0; $i < $txtNroLoteeGenerarr; $i++) { 
			$variable++;
			$codigo = "LT-" . str_pad($variable, 3, "0", STR_PAD_LEFT);
			$new_nombre = $txtExtensionNombreLoteer . " " . ($i + 1);
			
			$consultar_manzana_ver = mysqli_query($conection, "SELECT idlote FROM gp_lote 
				WHERE estado='1' AND idmanzana='$bxManzanaslter' AND nombre='$new_nombre'");

			if (mysqli_num_rows($consultar_manzana_ver) > 0) {
				$x++;
				continue;
			}

			// Si no existe un lote con ese código y nombre, procede a insertarlo
		
			$insertar_lotes = mysqli_query($conection, "INSERT INTO gp_lote 
				(nombre, codigo, estado, area, tipo_moneda, valor_con_casa, valor_sin_casa, idmanzana, generacion_automatica, correlativo) 
				VALUES ('$new_nombre', '$codigo','1','$txtAreaLoteer', '$cbxTipoMonedaLoteer', 
				'$txtValorCCLoteer','$txtValorSCLoteer', '$bxManzanaslter','$cbxGeneracionAutomLoter','$variable')");

			if (mysqli_affected_rows($conection) > 0) {
				$total_lotes_activas++;
				$area_ocupada += $area_lote;
			}else {
				// Si el lote ya existe, aumenta el contador de duplicados
				$x++;
			}           
		}

		// 🔹 Verifica si se alcanzó el límite después de la inserción
		if ($x === $txtNroLoteeGenerarr) {
			$data['status'] = "bad";
			$data['data'] = "No se pudo registrar los lotes debido a coincidencias con otros registros. Revise el campo de extensión nombre, se sugiere utilizar otro nombre de referencia.";
		} else if ($x > 0) {
				// Se registraron algunos lotes, pero otros fueron duplicados
				$data['status'] = "ok";
				$data['data'] = "Se registraron los lotes con éxito. Excepto " . $x . " lote(s) que eran duplicados de registros ya existentes.";
		} else {
			// Todos los lotes se registraron correctamente
			$data['status'] = "ok";
			$data['data'] = "Se registraron los lotes con éxito.";
		}
		



	}

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}

if(isset($_POST['btnEliminarLote'])){
    $IdReg=$_POST['idLote'];
   $querys = mysqli_query($conection,"UPDATE gp_lote 
       SET esta_borrado='1', estado='0'
   WHERE idlote='$IdReg'");

   $query = mysqli_query($conection,"SELECT 
        idlote as id,
        idmanzana as idmanzana
   from gp_lote 
   where idlote='$IdReg' AND estado='0' AND esta_borrado='1'");

   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if (isset($_POST['btnGuardarDatosLote'])) {

    $txtidLotePopupcc = trim($_POST['txtidLotePopupcc'] ?? '');
    $txtNombreLotePopup = trim($_POST['txtNombreLotePopup'] ?? '');
    $txtAreaLotePopup = trim($_POST['txtAreaLotePopup'] ?? '');
    $cbxTipoMonedaPopup = trim($_POST['cbxTipoMonedaPopup'] ?? '');
    $txtValorCCasaPopup = trim($_POST['txtValorCCasaPopup'] ?? '');
    $txtValorSCasaPopup = trim($_POST['txtValorSCasaPopup'] ?? '');

    if (!empty($txtidLotePopupcc) && !empty($txtNombreLotePopup) && !empty($txtAreaLotePopup) &&
        !empty($cbxTipoMonedaPopup) && !empty($txtValorCCasaPopup) && !empty($txtValorSCasaPopup)) {

        // Verifica si el lote existe
        $consulta_lote = mysqli_query($conection, 
            "SELECT idmanzana FROM gp_lote WHERE idlote='$txtidLotePopupcc'");
        $respuesta_lote = mysqli_fetch_assoc($consulta_lote);

        if ($respuesta_lote) {

            $idmanzana = $respuesta_lote['idmanzana'];

            // Verifica si hay otro lote en la misma manzana con el mismo nombre
            $verificar_nombre = mysqli_query($conection, 
                "SELECT idlote 
                 FROM gp_lote 
                 WHERE nombre='$txtNombreLotePopup' 
                 AND idmanzana='$idmanzana' 
                 AND idlote != '$txtidLotePopupcc' 
                 AND estado = 1");
            if (mysqli_num_rows($verificar_nombre) > 0) {
                $data['status'] = "bad";
                $data['data'] = "Ya existe otro lote con el mismo nombre en esta manzana. Por favor elige uno diferente.";
            } else {
                // Obtener el área total permitida de la manzana
                $consulta_area_max = mysqli_query($conection, 
                    "SELECT area FROM gp_manzana WHERE idmanzana='$idmanzana'");
                $row_area_max = mysqli_fetch_assoc($consulta_area_max);
                $area_maxima = floatval($row_area_max['area']);

                // Sumar las áreas de otros lotes activos excepto el actual
                $consulta_area_ocupada = mysqli_query($conection, 
                    "SELECT SUM(area) as total 
                     FROM gp_lote 
                     WHERE idmanzana='$idmanzana' AND estado='1' AND idlote != '$txtidLotePopupcc'");
                $row_area = mysqli_fetch_assoc($consulta_area_ocupada);
                $area_ocupada = floatval($row_area['total']);

                $nueva_area = floatval($txtAreaLotePopup);

                if (($area_ocupada + $nueva_area) > $area_maxima) {
                    $area_disponible = $area_maxima - $area_ocupada;

                    $data['status'] = "bad";
                    $data['data'] = "El área total asignada a la manzana es de " . number_format($area_maxima, 2, '.', ',') . " m². 
                    Ya se han asignado " . number_format($area_ocupada, 2, '.', ',') . " m². 
                    Solo puede registrar hasta " . number_format($area_disponible, 2, '.', ',') . " m².";
                } else {
                    // Actualiza el lote
                    $actualizar = mysqli_query($conection, 
                        "UPDATE gp_lote 
                         SET nombre='$txtNombreLotePopup', tipo_moneda='$cbxTipoMonedaPopup', 
                             area='$txtAreaLotePopup', valor_sin_casa='$txtValorSCasaPopup', 
                             valor_con_casa='$txtValorCCasaPopup' 
                         WHERE idlote='$txtidLotePopupcc' AND esta_borrado='0'");

                    if (mysqli_affected_rows($conection) > 0) {
                        $data['status'] = "ok";
                        $data['data'] = "Se actualizaron los datos del lote con éxito.";
                    } else {
                        $data['status'] = "bad";
                        $data['data'] = "No se completó la actualización. Revise los datos ingresados.";
                    }
                }
            }

        } else {
            $data['status'] = "bad";
            $data['data'] = "No se encontró el lote. Intente nuevamente.";
        }

    } else {
        $data['status'] = "bad";
        $data['data'] = "Completar todos los campos.";
    }

    header('Content-type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


//TIPO CASA
if (isset($_POST['ReturnTipoCasa'])) {
    
    $query = mysqli_query($conection, "SELECT cd.codigo_item as ID, cd.nombre_corto as Nombre 
    FROM configuracion_detalle cd 
    WHERE cd.codigo_tabla='_TIPO_CASA'");
    $conteo = mysqli_num_rows($query);

    if (($conteo  > 0)) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['ID'],
                'texto' => $row['Nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        array_push($dataList, [
            'valor' => '',
            'texto' => 'NINGUNO',
        ]);
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnAgregarTipoCasaMz'])){

    $cbxZonaTC = isset($_POST['cbxZonaTC']) ? $_POST['cbxZonaTC'] : null;
    $cbxZonaTCr = trim($cbxZonaTC); 

    $cbxManzanaTC = isset($_POST['cbxManzanaTC']) ? $_POST['cbxManzanaTC'] : null;
    $cbxManzanaTCr = trim($cbxManzanaTC); 

    $cbxTipoCasaTC = isset($_POST['cbxTipoCasaTC']) ? $_POST['cbxTipoCasaTC'] : null;
    $cbxTipoCasaTCr = trim($cbxTipoCasaTC); 

    if (!empty($cbxZonaTCr) && !empty($cbxManzanaTCr) && !empty($cbxTipoCasaTCr)) { 
        
        $consultar_id = mysqli_query($conection, "SELECT idmz_tipocasa FROM gp_manzana_tipocasa WHERE idzona='$cbxZonaTCr' AND idmanzana='$cbxManzanaTCr'");
        $respuesta_id = mysqli_num_rows($consultar_id);
        
        if($respuesta_id <= 0){

            // Si no existe, inserta
            $insertar_tcm = mysqli_query($conection, "INSERT INTO gp_manzana_tipocasa(idzona, idmanzana, tipo_casa) VALUES ('$cbxZonaTCr','$cbxManzanaTCr','$cbxTipoCasaTCr')");

            $data['status'] = "ok";
            $data['data'] = "Se registró correctamente el tipo de casa para la manzana seleccionada.";

        }else{

            // Si ya existe, ACTUALIZA EL TIPO DE CASA
            $actualizar_tcm = mysqli_query($conection, "UPDATE gp_manzana_tipocasa SET tipo_casa='$cbxTipoCasaTCr' WHERE idzona='$cbxZonaTCr' AND idmanzana='$cbxManzanaTCr'");

            $data['status'] = "ok";
            $data['data'] = "Se actualizó correctamente el tipo de casa para la manzana seleccionada.";
        }     

    }else{
        $data['status'] = "bad";
        $data['data'] = "Completar los campos.";
            
    }
    
   header('Content-type: text/javascript');
   echo json_encode($data, JSON_PRETTY_PRINT);                                 
}


//Popup tipo casa

if(isset($_POST['btnRegistrarNuevoTipoCasa'])){

    $txtNombreTipoCasa = isset($_POST['txtNombreTipoCasa']) ? $_POST['txtNombreTipoCasa'] : Null;
    $txtNombreTipoCasar = trim($txtNombreTipoCasa);
    
    $txtNroHabitaciones = isset($_POST['txtNroHabitaciones']) ? $_POST['txtNroHabitaciones'] : Null;
    $txtNroHabitacionesr = trim($txtNroHabitaciones); 

    $txtNroBanios = isset($_POST['txtNroBanios']) ? $_POST['txtNroBanios'] : Null;
    $txtNroBaniosr = trim($txtNroBanios); 

    $txtNroCocina = isset($_POST['txtNroCocina']) ? $_POST['txtNroCocina'] : Null;
    $txtNroCocinar = trim($txtNroCocina); 

    $txtNroSala = isset($_POST['txtNroSala']) ? $_POST['txtNroSala'] : Null;
    $txtNroSalar = trim($txtNroSala); 

    $txtAreaDescripcion = isset($_POST['txtAreaDescripcion']) ? $_POST['txtAreaDescripcion'] : Null;
    $txtAreaDescripcionr = trim($txtAreaDescripcion); 

    if (!empty($txtNombreTipoCasar) && !empty($txtNroHabitacionesr) && !empty($txtNroBaniosr) && !empty($txtNroCocinar)) { 

        $consultar_id = mysqli_query($conection, "SELECT max(codigo_item) as id FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CASA' AND estado='ACTI'");
        $respuesta_id = mysqli_fetch_assoc($consultar_id);
        $codigo = $respuesta_id['id'];
        $codigo = $codigo + 1;
        $nombre_file = "plano-".$codigo.".pdf";

        $consultar_reg = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE nombre_corto='$txtNombreTipoCasar' AND codigo_item='$codigo' AND codigo_tabla='_TIPO_CASA' AND estado='ACTI' AND texto1='$nombre_file'");
        $respuesta_reg = mysqli_num_rows($consultar_reg);

        if($respuesta_reg<=0){        
           
            $insertar = mysqli_query($conection, "INSERT INTO configuracion_detalle(empresa, codigo_tabla, codigo_sunat, codigo_item, nombre_corto, nombre_largo, texto1, texto2, valor1, valor2, valor3, valor4) VALUES ('000','_TIPO_CASA','$codigo','$codigo','$txtNombreTipoCasar','$txtNombreTipoCasar','$nombre_file','$txtAreaDescripcionr','$txtNroHabitacionesr','$txtNroBaniosr','$txtNroCocinar','$txtNroSalar')");

            $consultar_idreg = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE nombre_corto='$txtNombreTipoCasar' AND codigo_item='$codigo' AND codigo_tabla='_TIPO_CASA' AND estado='ACTI' AND texto1='$nombre_file'");
            $respuesta_idreg = mysqli_fetch_assoc($consultar_idreg);
            $id_tipocasa = $respuesta_idreg['id'];
        
            $data['status'] = "ok";
            $data['data'] = "Se registro el tipo de casa Correctamente";
            $data['IDREGISTRO'] = $id_tipocasa;
            $data['ADJUNTO'] = "archivos/".$nombre_file;

        }else{
            $data['status'] = "bad";
            $data['data'] = "Ya existe un registro similar, intente registrar un tipo de casa diferente.";
        }

    }else{
        $data['status'] = "bad";
        $data['data'] = "Completar todos los campos.";
            
    }

    

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}


if(isset($_POST['btnSeleccionarTipoCasa'])){

    $IdRegistro = isset($_POST['IdRegistro']) ? $_POST['IdRegistro'] : Null;
    $IdRegistror = trim($IdRegistro); 

    if(!empty($IdRegistror)){

        $query = mysqli_query($conection, "SELECT 
        cd.idconfig_detalle as id, 
        cd.nombre_corto as Nombre,
        cd.valor1 as habitacion,
        cd.valor2 as banios,
        cd.valor3 as cocinas,
        cd.valor4 as salas,
        cd.texto1 as plano,
        cd.texto2 as descripcion
        FROM configuracion_detalle cd 
        WHERE cd.codigo_tabla='_TIPO_CASA' AND cd.idconfig_detalle='$IdRegistror' AND cd.estado='ACTI'");
        $conteo = mysqli_num_rows($query);
    
        if ($conteo  > 0) {
            $row = mysqli_fetch_assoc($query);
            $data['status'] = 'ok';
            $data['id'] = $row['id'];
            $data['Nombre'] = $row['Nombre'];
            $data['habitacion'] = $row['habitacion'];
            $data['banios'] = $row['banios'];
            $data['cocinas'] = $row['cocinas'];
            $data['salas'] = $row['salas'];
            $data['plano'] = $row['plano'];
            $data['descripcion'] = $row['descripcion'];
        } 

   }else {
      $data['status'] = "bad";
      $data['data'] = "No se pudo encontrar los datos del registro. Intente nuevamente.";
   } 

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}


if(isset($_POST['btnEliminarTipoCasa'])){

    $IdRegistro = isset($_POST['IdRegistroc']) ? $_POST['IdRegistroc'] : Null;
    $IdRegistror = trim($IdRegistro); 

    if(!empty($txtNombreccr)){

         //Consultar numero de zonas para el proyecto
         $consultar_tipocasa = mysqli_query($conection, "SELECT idconfig_detalle FROM configuracion_detalle WHERE idconfig_detalle='$IdRegistror'");
         $respuesta_tipocasa = mysqli_num_rows($consultar_tipocasa);

         if($respuesta_tipocasa > 0){


            $actualizar_proyecto = mysqli_query($conection, "UPDATE gp_proyecto SET nombre='$txtNombreccr', responsable='$txtResponsableccr', area='$txtAreaccr', nro_zonas='$txtNroZonasccr', direccion='$txtDireccionccr', departamento='$bxDepartamentoPopupr', provincia='$bxProvinciaPopupr', distrito='$bxDistritoPopupr' WHERE idproyecto='$txtidProyectocr' AND estado='1'");

            //Consultar Nuevo Ingreso
            $consultar = mysqli_query($conection, "SELECT idproyecto FROM gp_proyecto WHERE idproyecto='$txtidProyectocr' AND nombre='$txtNombreccr'AND responsable='$txtResponsableccr'AND area='$txtAreaccr'AND nro_zonas='$txtNroZonasccr'AND direccion='$txtDireccionccr'AND departamento='$bxDepartamentoPopupr'AND provincia='$bxProvinciaPopupr'AND distrito='$bxDistritoPopupr'");
            $consultar_registro = mysqli_num_rows($consultar);

            if ($consultar_registro > 0) {                                       

               $data['status'] = "ok";
               $data['data'] = "Se actualizaron los datos del proyecto con éxito.";

            } else {
               $data['status'] = "bad";
               $data['data'] = "No se completo la Actualización. Revise los datos ingresados.";
            } 

         }else {
            $data['status'] = "bad";
            $data['data'] = "El Nro de Zonas ingresado no es permitido ya que actualmente existe un numero de zonas registradas superior al ingresado.";
         } 

   }else {
      $data['status'] = "bad";
      $data['data'] = "Completar todos los campos. (Nombre, Responsable, Area, Nro Zonas, Dirección, Departamento, Provincia y Distrito)";
   } 

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}

if(isset($_POST['btnActualizarTipoCasa'])){

    $IdRegistro = isset($_POST['IdRegistroc']) ? $_POST['IdRegistroc'] : Null;
    $IdRegistror = trim($IdRegistro); 

    if(!empty($txtNombreccr)){

         //Consultar numero de zonas para el proyecto
         $consultar_tipocasa = mysqli_query($conection, "SELECT idconfig_detalle FROM configuracion_detalle WHERE idconfig_detalle='$IdRegistror'");
         $respuesta_tipocasa = mysqli_num_rows($consultar_tipocasa);

         if($respuesta_tipocasa > 0){


            $actualizar_proyecto = mysqli_query($conection, "UPDATE gp_proyecto SET nombre='$txtNombreccr', responsable='$txtResponsableccr', area='$txtAreaccr', nro_zonas='$txtNroZonasccr', direccion='$txtDireccionccr', departamento='$bxDepartamentoPopupr', provincia='$bxProvinciaPopupr', distrito='$bxDistritoPopupr' WHERE idproyecto='$txtidProyectocr' AND estado='1'");

            //Consultar Nuevo Ingreso
            $consultar = mysqli_query($conection, "SELECT idproyecto FROM gp_proyecto WHERE idproyecto='$txtidProyectocr' AND nombre='$txtNombreccr'AND responsable='$txtResponsableccr'AND area='$txtAreaccr'AND nro_zonas='$txtNroZonasccr'AND direccion='$txtDireccionccr'AND departamento='$bxDepartamentoPopupr'AND provincia='$bxProvinciaPopupr'AND distrito='$bxDistritoPopupr'");
            $consultar_registro = mysqli_num_rows($consultar);

            if ($consultar_registro > 0) {                                       

               $data['status'] = "ok";
               $data['data'] = "Se actualizaron los datos del proyecto con éxito.";

            } else {
               $data['status'] = "bad";
               $data['data'] = "No se completo la Actualización. Revise los datos ingresados.";
            } 

         }else {
            $data['status'] = "bad";
            $data['data'] = "El Nro de Zonas ingresado no es permitido ya que actualmente existe un numero de zonas registradas superior al ingresado.";
         } 

   }else {
      $data['status'] = "bad";
      $data['data'] = "Completar todos los campos. (Nombre, Responsable, Area, Nro Zonas, Dirección, Departamento, Provincia y Distrito)";
   } 

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}
/******************** TIPO CASA ***************/

if (isset($_POST['btnEliminarTipCasa'])) {
    $IdReg = $_POST['idTipCasa'];

    // Realizar la actualización directamente
    $update = mysqli_query($conection, "
        UPDATE gp_manzana_tipocasa 
        SET estado='0' 
        WHERE idmz_tipocasa='$IdReg'
    ");

    // Validar el resultado directamente del UPDATE
    if ($update && mysqli_affected_rows($conection) > 0) {
        $data['status'] = 'ok';
        $data['data'] = 'El registro se eliminó correctamente.';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al eliminar, póngase en contacto con soporte.';
    }

    header('Content-type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnEditarTipoCasa'])){

    $IdReg=$_POST['IdRegistro'];

    $query = mysqli_query($conection,"SELECT 
        gpm.idmz_tipocasa as id,
        cd.nombre_corto as nombre
        FROM gp_manzana_tipocasa gpm
        INNER JOIN configuracion_detalle AS cd ON gpm.tipo_casa=cd.codigo_item AND cd.codigo_tabla='_TIPO_CASA'
        WHERE gpm.idmz_tipocasa='$IdReg' AND gpm.estado='1'");
    
    if($query->num_rows > 0){
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }

    header('Content-type: application/json'); // ✅ Esto es clave
    echo json_encode($data,JSON_PRETTY_PRINT);
}

/********** UPDATE EDIT ***********/
if(isset($_POST['btnGuardarTipoCasaEdit'])){

    $IdRegistro = isset($_POST['IdRegistroc']) ? $_POST['IdRegistroc'] : Null;
    $IdRegistror = trim($IdRegistro); 

    if(!empty($txtNombreccr)){

         //Consultar numero de zonas para el proyecto
         $consultar_tipocasa = mysqli_query($conection, "SELECT idconfig_detalle FROM configuracion_detalle WHERE idconfig_detalle='$IdRegistror'");
         $respuesta_tipocasa = mysqli_num_rows($consultar_tipocasa);

        if($respuesta_tipocasa > 0){


            $actualizar_proyecto = mysqli_query($conection, "UPDATE gp_proyecto SET nombre='$txtNombreccr', responsable='$txtResponsableccr', area='$txtAreaccr', nro_zonas='$txtNroZonasccr', direccion='$txtDireccionccr', departamento='$bxDepartamentoPopupr', provincia='$bxProvinciaPopupr', distrito='$bxDistritoPopupr' WHERE idproyecto='$txtidProyectocr' AND estado='1'");

            //Consultar Nuevo Ingreso
            $consultar = mysqli_query($conection, "SELECT idproyecto FROM gp_proyecto WHERE idproyecto='$txtidProyectocr' AND nombre='$txtNombreccr'AND responsable='$txtResponsableccr'AND area='$txtAreaccr'AND nro_zonas='$txtNroZonasccr'AND direccion='$txtDireccionccr'AND departamento='$bxDepartamentoPopupr'AND provincia='$bxProvinciaPopupr'AND distrito='$bxDistritoPopupr'");
            $consultar_registro = mysqli_num_rows($consultar);

            if ($consultar_registro > 0) {                                       

               $data['status'] = "ok";
               $data['data'] = "Se actualizaron los datos del proyecto con éxito.";

            } else {
               $data['status'] = "bad";
               $data['data'] = "No se completo la Actualización. Revise los datos ingresados.";
            } 

         }else {
            $data['status'] = "bad";
            $data['data'] = "El Nro de Zonas ingresado no es permitido ya que actualmente existe un numero de zonas registradas superior al ingresado.";
         } 

   }else {
      $data['status'] = "bad";
      $data['data'] = "Completar todos los campos. (Nombre, Responsable, Area, Nro Zonas, Dirección, Departamento, Provincia y Distrito)";
   } 

   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);                                 
}
/********** UPDATE EDIT ***********/

/******************** TIPO CASA ***************/

?>