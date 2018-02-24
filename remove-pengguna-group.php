<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_group = $_POST['id_group'];
$id_pengguna_group = $_POST['id_pengguna_group'];

$con->query("DELETE FROM t_pengguna_group WHERE id_pengguna_group='$id_pengguna_group' AND id_group='$id_group'");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

echo($outp);

$con->close();
?>