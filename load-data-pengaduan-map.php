<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$lastId = $_GET['lastId'];
$startRow = $_GET['startRow'];
$limitRow = 1000;
$idDevice = $_GET['idDevice'];
if(!empty($_GET['filterMonth'])){
	$getMonth = $_GET['filterMonth'];
	$filterMonth = "AND DATE_FORMAT(waktu_pengaduan,'%Y-%m')='$getMonth'";
}else{
	$filterMonth = '';
}

$q = $con->query("SELECT * FROM pengaduan, pengguna WHERE pengaduan.id_perangkat=pengguna.id_pengguna AND id_pengaduan <= ".$lastId." ".$filterMonth."ORDER BY waktu_pengaduan DESC LIMIT ".$startRow.",".$limitRow);

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
		$id_pengaduan = $r["id_pengaduan"];
		$id_dukungan = $idDevice.$id_pengaduan;
		$count_support = $con->query("SELECT * FROM dukungan WHERE id_pengaduan='$id_pengaduan'")->num_rows;
		$check_support = $con->query("SELECT * FROM dukungan WHERE id_dukungan='$id_dukungan'")->num_rows;
		if($check_support > 0){
			$checked = "true";
		} else {
			$checked = "false";
		}
		$count_comment = $con->query("SELECT * FROM komentar WHERE id_pengaduan='$id_pengaduan'")->num_rows;
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_pengaduan":"' . $r["id_pengaduan"] . '",';
	    $outp .= '"id_kategori":"' . $r["id_kategori"] . '",';
	    $outp .= '"kategori":"' . $r["kategori"] . '",';
	    $outp .= '"id_sub_kategori":"' . $r["id_sub_kategori"] . '",';
	    $outp .= '"sub_kategori":"' . $r["sub_kategori"] . '",';
	    $outp .= '"icon_kategori":"' . $r["icon_kategori"] . '",';
	    $outp .= '"icon_sub_kategori":"' . $r["icon_sub_kategori"] . '",';
	    $outp .= '"isi_pengaduan":"' . escapeJsonString($r["isi_pengaduan"]) . '",';
	    $outp .= '"gambar_pengaduan":"' . $r["gambar_pengaduan"] . '",';
	    $outp .= '"telp_pengguna":"' . $r["telp_pengguna"] . '",';
	    $outp .= '"pengirim_pengaduan":"' . escapeJsonString($r["pengirim_pengaduan"]) . '",';
	    $outp .= '"gambar_pengirim_pengaduan":"' . $r["gambar_pengguna"] . '",';
	    $outp .= '"waktu_pengaduan":"' . time_elapsed_string($r["waktu_pengaduan"]) . '",';
	    $outp .= '"posisi_lat_pengaduan":"' . $r["posisi_lat_pengaduan"] . '",';
	    $outp .= '"posisi_lng_pengaduan":"' . $r["posisi_lng_pengaduan"] . '",';
	    $outp .= '"media_pengaduan":"' . $r["media_pengaduan"] . '",';
	    $outp .= '"status_pengaduan":"' . $r["status_pengaduan"] . '",';
	    $outp .= '"warna_pengaduan":"' . $r["warna_pengaduan"] . '",';
	    $outp .= '"sembunyikan":"' . $r["sembunyikan"] . '",';
	    $outp .= '"blokir":"' . $r["blokir"] . '",';
	    $outp .= '"id_perangkat":"' . $r["id_perangkat"] . '",';
	    $outp .= '"periode":"' . $r["periode"] . '",';
	    $outp .= '"link_gambar":"' . $r["link_gambar"] . '",';
	    $outp .= '"kecamatan_posisi":"' . $r["kecamatan_posisi"] . '",';
		$outp .= '"kota_posisi":"' . $r["kota_posisi"] . '",';
		$outp .= '"count_support":"' . $count_support . '",';
		$outp .= '"checked":"' . $checked . '",';
	    $outp .= '"count_comment":"' . $count_comment . '"}'; 
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