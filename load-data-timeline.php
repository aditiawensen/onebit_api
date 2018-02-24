<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

if(empty($_GET['position'])){
	$position = 0;
} else {
	$position = $_GET['position'];
}
$limitRow = 5;
$idDevice = $_GET['idDevice'];

$q = $con->query("SELECT *, 0 tipe FROM obrolan, pengguna WHERE obrolan.id_perangkat=pengguna.id_pengguna UNION SELECT 777, 'Berita', 'user.png', tanggal_berita, isi_berita, gambar_berita, sub_judul_berita,'','',judul_berita,'#ffffff',0,0,':t:empty:t::c::t:empty:t::c::t:empty:t::c::t:empty:t::c::t:empty:t:',0,'onebit','Berita','user.png','777',tanggal_berita,'','',1 FROM berita ORDER BY waktu_obrolan DESC LIMIT ".$position.",".$limitRow);

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
		$id_obrolan = $r["id_obrolan"];
		$id_love_obrolan = $idDevice.$id_obrolan;
		$count_love = $con->query("SELECT * FROM love_obrolan WHERE id_obrolan='$id_obrolan'")->num_rows;
		$check_love = $con->query("SELECT * FROM love_obrolan WHERE id_love_obrolan='$id_love_obrolan'")->num_rows;
		if($check_love > 0){
			$checked = "true";
		} else {
			$checked = "false";
		}
		$count_comment = $con->query("SELECT * FROM komentar_obrolan WHERE id_obrolan='$id_obrolan'")->num_rows;
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_obrolan":"' . $r["id_obrolan"] . '",';
	    $outp .= '"type":' . $r["tipe"] . ',';
	    $outp .= '"isi_obrolan":"' . $r["isi_obrolan"] . '",';
	    $outp .= '"gambar_obrolan":"' . $r["gambar_obrolan"] . '",';
	    $outp .= '"jumlah_gambar":' . $r["jumlah_gambar"] . ',';
	    $outp .= '"multi_gambar_obrolan":[' . getMultiGambar($r["multi_gambar"]) . '],';
	    $outp .= '"pengirim_obrolan":"' . $r["pengirim_obrolan"] . '",';
	    $outp .= '"gambar_pengirim_obrolan":"' . $r["gambar_pengguna"] . '",';
	    $outp .= '"waktu_obrolan":"' . time_elapsed_string($r["waktu_obrolan"]) . '",';
	    $outp .= '"link_gambar":"' . $r["link_gambar"] . '",';
	    $outp .= '"sembunyikan":"' . $r["sembunyikan"] . '",';
	    $outp .= '"id_perangkat":"' . $r["id_perangkat"] . '",';
	    $outp .= '"warna_tulisan":"' . $r["warna_tulisan"] . '",';
	    $outp .= '"blokir":"' . $r["blokir"] . '",';
		$outp .= '"count_love":"' . $count_love . '",';
		$outp .= '"checked":"' . $checked . '",';
	    $outp .= '"count_comment":"' . $count_comment . '"}'; 
	}
} else {

}

$outp ='['.$outp.']';
echo($outp);	

$con->close();

function getMultiGambar($x) {
	$v = str_replace(':t:', '"', $x);
	$v = str_replace(':c:', ',', $v);
	return $v;
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