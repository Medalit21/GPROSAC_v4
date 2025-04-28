<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V12</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css?v=<?php echo time(); ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<link href="views/assets/libs/flot/css/float-chart.css" rel="stylesheet">

</head>
<body>

	<?php 
		//session_start();
		//if(!empty($_POST)){
			//$_SESSION['variable'] = $_POST['usuario'];
		//}  
	?>
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/fondo.jpg');">
			<div class="wrap-login100 p-t-190 p-b-30">
				<form class="login100-form validate-form" method="POST">
					<div class="login100-form-avatar" style="margin-top: -5%; border-radius: 0 !important;">
						<img src="images/gprosac_log.png" alt="AVATAR">
					</div>

					<div class="wrap-input100 validate-input m-b-10 espacio-campo" data-validate = "Username is required">
						<input class="input100" type="text" name="usuario" id="usuario" placeholder="Usuario" autocomplete="off">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
						<input class="input100" type="password" name="psw" placeholder="Clave">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required" hidden>
						<select name="bxempresa" id="bxempresa"  class="input100" style="outline: none !important;">
							<option selected="true" disabled="disabled">Seleccionar Empresa</option>
						</select>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-building"></i>
						</span>						
					</div>


					<div class="container-login100-form-btn p-t-10">
						<button type="submit" class="login100-form-btn" id="btnacceder" name="btnacceder">Acceder</button>
					</div>

					<span class="login100-form-title p-t-20 p-b-45 tamano-text">
						ACG SOFT - Versión 1.0
					</span>	

					<div class="text-center w-full p-t-25 p-b-230" hidden>
						<a href="#" class="txt1">
							Forgot Username / Password?
						</a><br>
						<a class="txt1" href="#">
							Create new account
							<i class="fa fa-long-arrow-right"></i>						
						</a>
					</div>

				</form>
			</div>
		</div>
	</div>

<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>