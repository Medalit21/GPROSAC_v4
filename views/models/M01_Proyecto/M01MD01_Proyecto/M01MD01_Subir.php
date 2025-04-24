<?php
    $nombre_temporal = $_FILES['txtPlano']['tmp_name'];
    $nombre = $_FILES['txtPlano']['name'];
    move_uploaded_file($nombre_temporal, '../../archivos/'.$nombre);
?>