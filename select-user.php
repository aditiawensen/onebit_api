<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];

$q = $con->query("SELECT * FROM pengguna p, log_login l WHERE p.id_pengguna=l.username AND id_pengguna='$id_pengguna'");

$outp = "";
if ($q->num_rows > 0) {
	while ($r = $q->fetch_assoc()) {
		if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_pengguna":"' . $r["id_pengguna"] . '",';
	    $outp .= '"nama_pengguna":"' . escapeJsonString($r["nama_pengguna"]) . '",';
	    $outp .= '"gambar_pengguna":"' . setImageProfile($r["gambar_pengguna"]) . '",'; 
	    $outp .= '"telp_pengguna":"' . escapeJsonString($r["telp_pengguna"]) . '",';
	    $outp .= '"no_ktp":"' . escapeJsonString($r["no_ktp"]) . '",';
	    $outp .= '"last_online":"' . time_elapsed_string($r["time_login"]) . '",';
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

function setImageProfile($x) {
    $val = str_replace("compress200/", "", $x);
    return $val;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' yang lalu' : 'saat ini';
}
?>