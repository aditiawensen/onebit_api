<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$q = $con->query("SELECT * FROM pengaduan, pengguna WHERE pengaduan.id_perangkat=pengguna.id_pengguna AND id_pengaduan ORDER BY waktu_pengaduan DESC LIMIT 1");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_pengaduan":"' . $r["id_pengaduan"] . '",';
	    $outp .= '"id_kategori":"' . $r["id_kategori"] . '",';
	    $outp .= '"kategori":"' . $r["kategori"] . '",';
	    $outp .= '"id_sub_kategori":"' . $r["id_sub_kategori"] . '",';
	    $outp .= '"sub_kategori":"' . $r["sub_kategori"] . '",';
	    $outp .= '"icon_kategori":"' . $r["icon_kategori"] . '",';
	    $outp .= '"icon_sub_kategori":"' . $r["icon_sub_kategori"] . '",';
	    $outp .= '"isi_pengaduan":"' . $r["isi_pengaduan"] . '",';
	    $outp .= '"gambar_pengaduan":"' . $r["gambar_pengaduan"] . '",';
	    $outp .= '"pengirim_pengaduan":"' . $r["pengirim_pengaduan"] . '",';
	    $outp .= '"gambar_pengirim_pengaduan":"' . $r["gambar_pengguna"] . '",';
	    $outp .= '"telp_pengguna":"' . $r["telp_pengguna"] . '",';
	    $outp .= '"waktu_pengaduan":"' . time_elapsed_string($r["waktu_pengaduan"]) . '",';
	    $outp .= '"posisi_lat_pengaduan":"' . $r["posisi_lat_pengaduan"] . '",';
	    $outp .= '"posisi_lng_pengaduan":"' . $r["posisi_lng_pengaduan"] . '",';
	    $outp .= '"media_pengaduan":"' . $r["media_pengaduan"] . '",';
	    $outp .= '"status_pengaduan":"' . $r["status_pengaduan"] . '",';
	    $outp .= '"warna_pengaduan":"' . $r["warna_pengaduan"] . '",';
	    $outp .= '"img_tindak_lanjut":"' . getImgTindakLanjut($r["img_tindak_lanjut"]) . '",';
	    $outp .= '"sembunyikan":"' . $r["sembunyikan"] . '",';
	    $outp .= '"blokir":"' . $r["blokir"] . '",';
	    $outp .= '"id_perangkat":"' . $r["id_perangkat"] . '",';
	    $outp .= '"periode":"' . $r["periode"] . '",';
	    $outp .= '"link_gambar":"' . $r["link_gambar"] . '",';
	    $outp .= '"kecamatan_posisi":"' . $r["kecamatan_posisi"] . '",';
		$outp .= '"kota_posisi":"' . $r["kota_posisi"] . '"}';
	}
} else {

}

$outp ='['.$outp.']';
echo($outp);	

$con->close();

function getImgTindakLanjut($x) {
	if (empty($x)) {
		return "empty";
	} else {
		return $x;
	}
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