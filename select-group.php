<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];
$id_group = $_GET['id_group'];

$q = $con->query("SELECT * FROM t_group g, pengguna p WHERE g.pembuat_group=p.id_pengguna AND blokir_group='N' AND id_group='$id_group'");

$outp = "";
if ($q->num_rows > 0) {
    while($r = $q->fetch_assoc()) {
        if ($outp != "") {$outp .= ",";}
        $outp .= '{"id":"' . $r["id_group"] . '",';
        $outp .= '"nama_group":"' . escapeJsonString($r["nama_group"]) . '",';
        $outp .= '"tglbuat_group":"' . $r["tglbuat_group"] . '",';
        $outp .= '"id_pembuat_group":"' . $r["pembuat_group"] . '",';
        $outp .= '"pembuat_group":"' . escapeJsonString($r["nama_pengguna"]) . '",';
        $outp .= '"owner":"' . ($id_pengguna==$r["pembuat_group"] ? 'Y' : 'N') . '",';
        $outp .= '"blokir_group":"' . $r["blokir_group"] . '"}';
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