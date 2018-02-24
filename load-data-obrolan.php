<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$lastId = $_GET['lastId'];
$startRow = $_GET['startRow'];
$limitRow = 5;
$idDevice = $_GET['idDevice'];
if(!empty($_GET['filterMonth'])){
	$getMonth = $_GET['filterMonth'];
	$filterMonth = "AND DATE_FORMAT(waktu_obrolan,'%Y-%m')='$getMonth'";
}else{
	$filterMonth = '';
}

$q = $con->query("SELECT * FROM obrolan, pengguna WHERE obrolan.id_perangkat=pengguna.id_pengguna AND id_obrolan <= ".$lastId." ".$filterMonth."ORDER BY waktu_obrolan DESC LIMIT ".$startRow.",".$limitRow);

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
	    $outp .= '"isi_obrolan":"' . $r["isi_obrolan"] . '",';
	    $outp .= '"gambar_obrolan":"' . $r["gambar_obrolan"] . '",';
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