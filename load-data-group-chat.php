<?
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];
$id_group = $_GET['id_group'];
if(empty($_GET['position'])){
    $position = 0;
} else {
    $position = $_GET['position'];
}

$q = $con->query("SELECT * FROM t_chat_group cg, t_group g, pengguna p WHERE cg.id_group=g.id_group AND cg.id_pengirim_chat=p.id_pengguna AND cg.id_group='$id_group' ORDER BY waktu_chat DESC LIMIT $position, 20");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id":"' . $r["id_group"] . '",';
        $outp .= '"id_pengirim":"' . $r["id_pengirim_chat"] . '",';
        $outp .= '"pengirim":"' . $r["nama_pengguna"] . '",';
        $outp .= '"gambar_pengirim":"' . $r["gambar_pengguna"] . '",';
        $outp .= '"isi":"' . $r["isi_chat"] . '",';
        $outp .= '"gambar_chat":"' . $r["gambar_chat"] . '",';
        $outp .= '"waktu":"' . time_elapsed_string($r["waktu_chat"]) . '",';
        $outp .= '"gambar":"' . $r['link_gambar'].$r['gambar_chat'] . '",';
	    $outp .= '"warna":"' . ($r["id_pengirim_chat"]==$id_pengguna ? '#2196F3' : '#da2f74') . '",';
	    $outp .= '"pemilik":' . ($r["id_pengirim_chat"]==$id_pengguna ? 'true' : 'false') . '}';
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