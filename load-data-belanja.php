<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

if(empty($_GET['position'])){
	$position = 0;
} else {
	$position = $_GET['position'];
}
$q = $con->query("SELECT * FROM belanja ORDER BY id_belanja DESC LIMIT $position,10");

$outp = "";
if ($q->num_rows > 0) {
	while($r = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_belanja":"' . $r["id_belanja"] . '",';
	    $outp .= '"nama_produk":"' . $r["nama_produk"] . '",';
	    $outp .= '"gambar_produk":"' . $r["gambar_produk"] . '",';
        $outp .= '"deskripsi_produk":"' . $r["deskripsi_produk"] . '",';
	    $outp .= '"keterangan":"' . $r["keterangan"] . '",';
        $outp .= '"stok":"Stok : ' . $r["stok"] . '",';
        $outp .= '"harga_produk":"' . $r["harga_produk"] . '",';
	    $outp .= '"no_virtual":"' . $r["no_virtual"] . '"}';
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