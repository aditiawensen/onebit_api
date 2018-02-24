<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_pengguna = $_POST['id_pengguna'];
$id_teman = $_POST['id_teman'];

$con->query("INSERT INTO t_favorite_friends(id_pengguna,id_teman,waktu) VALUES('$id_pengguna','$id_teman',NOW())");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

echo($outp);

$con->close();
?>