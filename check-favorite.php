<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];
$id_teman = $_GET['id_teman'];

$q = $con->query("SELECT * FROM t_favorite_friends WHERE id_pengguna='$id_pengguna' AND id_teman='$id_teman'");

$outp = "";
if ($q->num_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>