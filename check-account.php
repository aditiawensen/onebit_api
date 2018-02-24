<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];

$q = $con->query("SELECT * FROM pengguna WHERE id_pengguna='$id_pengguna'");

$outp = "";
if ($q->num_rows > 0) {
	$remove_log_login = $con->query("DELETE FROM log_login WHERE username='$id_pengguna'");
	if ($remove_log_login) {
		$insert_log_login = $con->query("INSERT INTO log_login(username,time_login) VALUES('$id_pengguna',NOW())");
	} else {
		$insert_log_login = $con->query("INSERT INTO log_login(username,time_login) VALUES('$id_pengguna',NOW())");
	}
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>