<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$idDevice = $_GET['idDevice'];

$d = $con->query("SELECT id_teman,nama_pengguna,gambar_pengguna FROM t_favorite_friends f, pengguna p WHERE f.id_teman=p.id_pengguna AND f.id_pengguna='$idDevice' ORDER BY waktu DESC");

$outp = "";
if ($d->num_rows > 0) {
	while($r = $d->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_pengguna":"' . $r["id_teman"] . '",';
	    $outp .= '"nama_pengguna":"' . $r["nama_pengguna"] . '",';
	    $outp .= '"warna":"pink",';
	    $outp .= '"gambar_pengguna":"' . $r["gambar_pengguna"] . '"}';
	}
} else {

}

$outp ='['.$outp.']';
echo($outp);	

$con->close();

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