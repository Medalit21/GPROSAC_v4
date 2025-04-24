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

	    if(isset($_REQUEST["btnacceder"])){

			$variable = $this->mode->ValidarUsuario($_REQUEST["usuario"], $_REQUEST["psw"]);
			if($variable==1){
	 			session_start();
	 			$_SESSION["dato"]=$_REQUEST["usuario"];
	 			header("location: views/inicio.php");
			}
			else
				echo "<script>alert('Usuario o contraseña no validos')</script>";
				echo "<script>window.history.go(-1);</script>";
		}

    		include_once "views/login.php";

    	}
    	
    }

?>