<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_group = $_POST['id_group'];

$con->query("UPDATE t_group SET blokir_group='Y' WHERE id_group='$id_group'");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

echo($outp);

$con->close();
?>