<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_POST['id_pengguna'];
$id_teman = $_POST['id_teman'];

$q = $con->query("DELETE FROM t_favorite_friends WHERE id_pengguna='$id_pengguna' AND id_teman='$id_teman'");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>