<?
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];
$id_koneksi = $_GET['id_koneksi'];
if(empty($_GET['position'])){
    $position = 0;
} else {
    $position = $_GET['position'];
}

$q = $con->query("SELECT * FROM pesan msg, pengguna p WHERE msg.id_pengguna=p.id_pengguna AND id_koneksi='$id_koneksi' ORDER BY waktu DESC LIMIT $position, 20");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id":"' . $r["id"] . '",';
        $outp .= '"id_koneksi":"' . $r["id_koneksi"] . '",';
        $outp .= '"isi_pesan":"' . $r["isi_pesan"] . '",';
        $outp .= '"gambar":"' . $r["gambar"] . '",';
        $outp .= '"waktu":"' . date('d/M/y H:i',strtotime($r["waktu"])) . '",';
        $outp .= '"id_pengguna":"' . $r["id_pengguna"] . '",';
        $outp .= '"nama_pengguna":"' . $r["nama_pengguna"] . '",';
        $outp .= '"gambar_pengguna":"' . $r["gambar_pengguna"] . '",';
	    $outp .= '"pemilik":' . ($r["id_pengguna"]==$id_pengguna ? 'true' : 'false') . '}';
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