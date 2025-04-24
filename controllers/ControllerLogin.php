<?php
  
   
	 /**
     * 
     */
    include_once "models/Trabajador/get_login.php";
    

    class ControllerLogin{

    	public $mode;

    	public function __construct(){
    		$this->mode = new get_login();
    	}
    	
    	public function login(){
			if(isset($_POST["btnacceder"])){
				    session_start();
					$variable = $this->mode->ValidarUsuario($_POST["usuario"], $_POST["psw"]);
					$_SESSION['usu']=$_POST['usuario'];
					$_SESSION['psw']=$_POST["psw"];
					$_SESSION['variable']=$_SESSION['usu'];
					require_once "config/conexion_2.php";				
					include_once "config/configuracion.php";
					include_once "config/rutas.php";
					if($variable==1){	
						include_once "config/usuario_log.php";
						include_once "config/codificar.php";
						$claves = "123";
						//$Vsr = $encriptar($_SESSION['usu']);  
						$Vsr = encrypt($_SESSION['usu'], $claves);  
						echo "<script>window.location.replace('".$_SESSION['Ruta']."?Vsr=".$Vsr."');</script>";
						//echo "<script>alertify.error('".$Vsr."');</script>";
					}
					else{
						echo "<script>alertify.error('".$_SESSION['mensaje']."');</script>";
					}
			}

				include_once "views/login.php";

    	}


    	
    }

?>