<?php 
	session_start();

    require_once "../../../config/control_sesion.php"; // Protege la vista

    $EsAdmin = false;

    $user = $_SESSION['usu'];

    // Obtener el perfil del usuario
    $consultar_idperfil = mysqli_query($conection, "SELECT IdPerfil as perfil FROM usuario WHERE usuario='$user'");
    $respuesta_idperfil = mysqli_fetch_assoc($consultar_idperfil);
    $id_perfil = $respuesta_idperfil['perfil']; 

    if (empty($_SESSION['usuario_logueo'])) {
        $_SESSION['usuario_logueo'] = $_SESSION['usu'];		
    }

    $IdPerfilUsuario = $id_perfil;
    $_SESSION['IdPerfil'] = $id_perfil; 
	
    //$IdPerfilUsuario=1;
    $ListaMenu = array();
    $query = mysqli_query($conection, "SELECT
	modd.id_modulo as id,
    modd.icono as Icono,
	modd.nombre as Nombre
	FROM modulo modd
	INNER JOIN conjunto_privilegios AS cpr ON modd.id_modulo=cpr.idmodulo AND cpr.idperfil='$IdPerfilUsuario'
	WHERE cpr.idPerfil='$IdPerfilUsuario'
	AND cpr.lectura=1
	AND cpr.idsubmodulo=0
	AND modd.estado=1
	ORDER BY modd.orden ASC 
    "); 
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($ListaMenu, $row);
        }
    }
?>
  
<nav class="sidebar-nav">
    <ul id="sidebarnav" class="p-t-30">
        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo $NAME_SERVER; ?>views/M00_Home/M01_Home/home.php" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">INICIO</span></a></li>
      
        <?php 
        foreach ($ListaMenu as $ItemPadre) { 
        ?>
            <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><img src="<?php echo $NAME_SERVER .$ItemPadre['Icono'] ?>" height="35px" width="35px" alt=""><span class="hide-menu">&nbsp;&nbsp; <?php echo $ItemPadre['Nombre']?></span></a>
            <?php 
                $IdPadre=$ItemPadre['id'];
                $ListaOpcionHijo = array();
                $queryHijo = mysqli_query($conection, "SELECT 
                modsub.ruta as Url,
				modsub.icono as Icono,
				modsub.nombre as Nombre
                FROM modulo_sub modsub
				INNER JOIN conjunto_privilegios AS cpr ON cpr.idsubmodulo=modsub.idsubmodulo
                WHERE modsub.estado=1
				AND cpr.idperfil='$IdPerfilUsuario'
				AND cpr.idmodulo='$IdPadre' 
                AND cpr.idsubmodulo!=0				
				AND cpr.lectura=1
				ORDER BY modsub.orden ASC, modsub.idsubmodulo ASC;
                ");
              
                if($queryHijo){
                    if ($queryHijo->num_rows > 0) {
                      while ($row1 = $queryHijo->fetch_assoc()) {
                        array_push($ListaOpcionHijo, $row1);
                    }
                     }
                } 
            ?>
            <ul aria-expanded="false" class="collapse  first-level" style="margin-left: 2%;">
            <?php
            foreach ($ListaOpcionHijo as $ItemHijo) {
            ?>
                <li class="sidebar-item" ><a href="<?php echo $NAME_SERVER. $ItemHijo['Url']; ?>" class="sidebar-link"><i class=" <?php echo $ItemHijo['Icono'] ?>"></i><span class="hide-menu">&nbsp;&nbsp;<?php echo $ItemHijo['Nombre'] ?> </span></a></li>
                <?php
        }
        ?>
            </ul>
        </li>
        <?php
        }
        ?>
        
    </ul>
</nav>