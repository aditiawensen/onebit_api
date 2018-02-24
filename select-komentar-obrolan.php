<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_obrolan = $_GET['id_obrolan'];
	
$q = $con->query("SELECT * FROM komentar_obrolan,pengguna WHERE komentar_obrolan.id_pengguna=pengguna.id_pengguna AND id_obrolan='$id_obrolan' ORDER BY id_komentar_obrolan DESC");

$outp = "";
while($r = $q->fetch_assoc()) {
    if ($outp != "") {$outp .= ",";}
	$outp .= '{"id_komentar_obrolan":"' . $r["id_komentar_obrolan"] . '",';
	$outp .= '"id_obrolan":"' . $r["id_obrolan"] . '",';
    $outp .= '"id_pengguna":"' . $r["id_pengguna"] . '",'; 
	$outp .= '"nama_pengguna":"' . $r["nama_pengguna"] . '",'; 
	$outp .= '"gambar_pengguna":"' . $r["gambar_pengguna"] . '",'; 
	$outp .= '"isi_komentar":"' . $r["isi_komentar"] . '",'; 
	$outp .= '"waktu_komentar":"' . time_elapsed_string($r["waktu_komentar"]) . '"}'; 
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