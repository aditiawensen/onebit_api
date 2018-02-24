<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$q = $con->query("SELECT * FROM sos,pengguna WHERE sos.id_pengirim=pengguna.id_pengguna ORDER BY id_sos DESC LIMIT 1");

$outp = "";
if ($q->num_rows > 0) {
	while ($r = $q->fetch_assoc()) {
		if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_sos":"' . $r["id_sos"] . '",';
	    $outp .= '"id_pengirim":"' . $r["id_pengirim"] . '",';
	    $outp .= '"nama_pengguna":"' . escapeJsonString($r["nama_pengguna"]) . '",'; 
	    $outp .= '"telp_pengguna":"' . escapeJsonString($r["telp_pengguna"]) . '",'; 
	    $outp .= '"waktu_sos":"' . date('d M Y H:i:s',strtotime($r["waktu_sos"])) . '",';
	    $outp .= '"lat":"' . $r["lat"] . '",';
	    $outp .= '"lng":"' . $r["lng"] . '",';
	    $outp .= '"kategori_sos":"' . $r["kategori_sos"] . '"}';
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