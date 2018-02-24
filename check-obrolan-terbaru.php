<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_obrolan = $_GET['id_obrolan'];

$q = $con->query("SELECT id_obrolan FROM obrolan ORDER BY id_obrolan DESC LIMIT 1");
$r = $q->fetch_assoc();

$outp = "";
if ($id_obrolan < $r['id_obrolan']) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>