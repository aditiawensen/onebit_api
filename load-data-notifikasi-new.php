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
$q = $con->query("SELECT *, n.id_pengguna owner FROM notifikasi n LEFT JOIN pengguna p ON n.id_pengirim=p.id_pengguna WHERE n.id_pengguna='$id_pengguna' OR n.id_pengguna='publik' ORDER BY id_notifikasi DESC LIMIT $position,10");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
        if (empty($r['id_pengirim'])) {
            $url_image = "http://pdambitung.96.lt/bsc/aw-pages/include/images/notifikasi/notif.png";
        } else {
            $url_image = "http://onebit.asia/data/aw-uploads/profile/";
        }
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_notifikasi":"' . $r["id_notifikasi"] . '",';
	    $outp .= '"gambar_notifikasi":"' . $url_image.$r["gambar_pengguna"] . '",';
	    $outp .= '"gambar2_notifikasi":"' . $r["gambar2_notifikasi"] . '",';
        $outp .= '"judul_notifikasi":"' . $r["judul_notifikasi"] . '",';
	    $outp .= '"isi_notifikasi":"' . $r["isi_notifikasi"] . '",';
	    $outp .= '"waktu_notifikasi":"' . time_elapsed_string($r["waktu_notifikasi"]) . '",';
        $outp .= '"dibaca":"' . $r["dibaca"] . '",';
        $outp .= '"halaman":"' . $r["halaman"] . '",';
        $outp .= '"id_table":"' . $r["id_table"] . '",';
	    $outp .= '"id_pengguna":"' . $r["owner"] . '"}';
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