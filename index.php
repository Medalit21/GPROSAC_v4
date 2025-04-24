<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inmobiliaria</title>
	<link  rel="icon"   href="images/favicon.ico" type="image/png" />
	<!-- modelo 01 -->
	<link rel="stylesheet" type="text/css" href="views/librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="views/librerias/alertifyjs/css/themes/default.css">
	<link rel="stylesheet" type="text/css" href="views/librerias/select2/css/select2.css">
	<script src="views/librerias/alertifyjs/alertify.js"></script>
	<script src="views/librerias/select2/js/select2.js"></script>
</head>
<body>
	<?php
		require_once "controllers/ControllerLogin.php";
		require_once "config/conexion.php";
		$Controller = new ControllerLogin();
		$Controller->login();
	?>
</body>
	<script src="js/login.js?v=1.1.1"></script>
	<script src="views/librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="views/librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="views/librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="views/librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
</html>
