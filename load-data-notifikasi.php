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
$q = $con->query("SELECT * FROM notifikasi WHERE id_pengguna='$id_pengguna' OR id_pengguna='publik' ORDER BY id_notifikasi DESC LIMIT $position,10");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_notifikasi":"' . $r["id_notifikasi"] . '",';
	    $outp .= '"gambar_notifikasi":"' . $r["gambar_notifikasi"] . '",';
	    $outp .= '"gambar2_notifikasi":"' . $r["gambar2_notifikasi"] . '",';
        $outp .= '"judul_notifikasi":"' . $r["judul_notifikasi"] . '",';
	    $outp .= '"isi_notifikasi":"' . $r["isi_notifikasi"] . '",';
	    $outp .= '"waktu_notifikasi":"' . time_elapsed_string($r["waktu_notifikasi"]) . '",';
        $outp .= '"dibaca":"' . $r["dibaca"] . '",';
        $outp .= '"halaman":"' . $r["halaman"] . '",';
        $outp .= '"id_table":"' . $r["id_table"] . '",';
	    $outp .= '"id_pengguna":"' . $r["id_pengguna"] . '"}';
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