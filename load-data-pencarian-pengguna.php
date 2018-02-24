<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

if(empty($_GET['position'])){
	$position = 0;
} else {
	$position = $_GET['position'];
}

if(empty($_GET['nama_pengguna'])){
	$nama_pengguna = "";
	$q = $con->query("SELECT * FROM pengguna ORDER BY waktu_daftar DESC LIMIT $position,50");
} else {
	$nama_pengguna = $_GET['nama_pengguna'];
	$q = $con->query("SELECT * FROM pengguna WHERE nama_pengguna like '%$nama_pengguna%' ORDER BY waktu_daftar DESC LIMIT $position,50");
}

$outp = "";
if ($q->num_rows > 0) {
	while ($r = $q->fetch_assoc()) {
		if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_pengguna":"' . $r["id_pengguna"] . '",';
	    $outp .= '"nama_pengguna":"' . escapeJsonString($r["nama_pengguna"]) . '",';
	    $outp .= '"gambar_pengguna":"' . $r["gambar_pengguna"] . '",'; 
	    $outp .= '"status_pengguna":"' . $r["status_pengguna"] . '"}'; 
	}
} else {

}
	
$outp ='['.$outp.']';
echo($outp);	

$con->close();

function escapeJsonString($value) {
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}
?>