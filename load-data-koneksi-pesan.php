<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];

$q = $con->query("SELECT kp.id, kp.id_koneksi, kp.id_pengguna, kp.id_partner, kp.waktu_buat, kp.blokir, p.nama_pengguna nama_partner, p.gambar_pengguna gambar_partner, kp.pesan_terbaru, kp.urut FROM koneksi_pesan kp, pengguna p WHERE kp.id_pengguna='$id_pengguna' AND kp.id_partner=p.id_pengguna ORDER BY waktu_buat DESC");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id":"' . $r["id"] . '",';
	    $outp .= '"id_koneksi":"' . $r["id_koneksi"] . '",';
        $outp .= '"id_pengguna":"' . $r["id_pengguna"] . '",';
        $outp .= '"id_partner":"' . $r["id_partner"] . '",';
        $outp .= '"nama_partner":"' . $r["nama_partner"] . '",';
        $outp .= '"gambar_partner":"' . $r["gambar_partner"] . '",';
        $outp .= '"waktu_buat":"' . date('d/M/y H:i',strtotime($r["waktu_buat"])) . '",';
        $outp .= '"pesan_terbaru":"' . $r["pesan_terbaru"] . '",';
        $outp .= '"urut":' . $r["urut"] . ',';
        $outp .= '"blokir":"' . $r["blokir"] . '"}';
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