<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

if(empty($_GET['position'])){
	$position = 0;
} else {
	$position = $_GET['position'];
}
$id_pengguna = $_GET['id_pengguna'];

$q = $con->query("SELECT id_pengaduan id, pengirim_pengaduan pengirim, gambar_pengirim_pengaduan gambar_pengirim, isi_pengaduan isi, waktu_pengaduan waktu, 'pengaduan' tipe, '#FFFFFF' warna, '#000000' warna_tulisan FROM pengaduan WHERE id_perangkat='$id_pengguna' UNION SELECT id_obrolan id, pengirim_obrolan pengirim, gambar_pengirim_obrolan gambar_pengirim, isi_obrolan isi, waktu_obrolan waktu, 'obrolan' tipe, warna_tulisan warna, '#FFFFFF' warna_tulisan FROM obrolan WHERE id_perangkat='$id_pengguna' ORDER BY waktu DESC LIMIT $position,10");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id":"' . $r["id"] . '",';
        $outp .= '"pengirim":"' . escapeJsonString($r["pengirim"]) . '",';
	    $outp .= '"gambar_pengirim":"' . $r["gambar_pengirim"] . '",';
	    $outp .= '"waktu":"' . time_elapsed_string($r["waktu"]) . '",';
        $outp .= '"tipe":"' . $r["tipe"] . '",';
        $outp .= '"warna":"' . $r["warna"] . '",';
	    $outp .= '"warna_tulisan":"' . $r["warna_tulisan"] . '",';
	    $outp .= '"isi":"' . escapeJsonString($r["isi"]) . '"}';
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
    return $string ? implode(', ', $string) . ' yang lalu' : 'beberapa detik yang lalu';
}
?>