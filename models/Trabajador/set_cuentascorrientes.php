<?php


session_start();

/*$host = 'localhost';
  $user = 'acgsoft_appVisitas';
  $password = 'adm2019acg';
  $db = 'acgsoft_Nominas';
   /**/
  $host = 'localhost';
  $user = 'root';
  $password = '';
  $db = 'acgsoft_nominas';
     
  $conection = mysqli_connect($host,$user,$password,$db);

  if(!$conection){
    echo "Error en la conexi贸n";
  }


$tipo_doc = $_SESSION['tipdoc'];
$doc = $_SESSION['doc'];

$forma_pago = isset($_POST['forma_pago']) ? $_POST['forma_pago'] : Null;
$forma_pagor = trim($forma_pago);

$banco = isset($_POST['banco']) ? $_POST['banco'] : Null;
$bancor = trim($banco);

$tipo_cuenta = isset($_POST['tipo_cuenta']) ? $_POST['tipo_cuenta'] : Null;
$tipo_cuentar = trim($tipo_cuenta);

$moneda = isset($_POST['moneda']) ? $_POST['moneda'] : Null;
$monedar = trim($moneda);

$num_cuenta = isset($_POST['num_cuenta']) ? $_POST['num_cuenta'] : Null;
$num_cuentar = trim($num_cuenta);

$haberes_interbancaria = isset($_POST['haberes_interbancaria']) ? $_POST['haberes_interbancaria'] : Null;
$haberes_interbancariar = trim($haberes_interbancaria);

$cts_banco = isset($_POST['cts_banco']) ? $_POST['cts_banco'] : Null;
$cts_bancor = trim($cts_banco);

$cts_moneda = isset($_POST['cts_moneda']) ? $_POST['cts_moneda'] : Null;
$cts_monedar = trim($cts_moneda);

$cts_num_cuenta = isset($_POST['cts_num_cuenta']) ? $_POST['cts_num_cuenta'] : Null;
$cts_num_cuentar = trim($cts_num_cuenta);

$cts_interbancaria = isset($_POST['cts_interbancaria']) ? $_POST['cts_interbancaria'] : Null;
$cts_interbancariar = trim($cts_interbancaria);

$situacion_financiera = isset($_POST['situacion_financiera']) ? $_POST['situacion_financiera'] : Null;
$situacion_financierar = trim($situacion_financiera);


$insertar_dp = mysqli_query($conection, "
  UPDATE cuentas_corrientes SET 

  forma_pago='$forma_pagor', 
  banco='$bancor', 
  tipo_cuenta='$tipo_cuentar', 
  moneda='$monedar', 
  num_cuenta='$num_cuentar', 
  haberes_interbancaria='$haberes_interbancariar', 
  cts_banco='$cts_bancor', 
  cts_moneda='$cts_monedar', 
  cts_num_cuenta='$cts_num_cuentar', 
  cts_interbancaria='$cts_interbancariar', 
  situacion_financiera='$situacion_financierar' 

  WHERE documento='$doc')");

$consultarreg = mysqli_query($conection, "SELECT * FROM cuentas_corrientes WHERE documento='$doc'");
$result = mysqli_num_rows($consultarreg);

            if ($result > 0) {
                echo '<script type="text/javascript"> 
                      alert("Se grabaron los datos correctamente.!")
                      window.history.go(-1);
                      </script>';
            } else {
                echo '<script type="text/javascript">
                      alert("ERROR! El registro no pudo ser completado. Intente nuevamente. Gracias");
                      window.history.go(-1);
                      </script>';
            }