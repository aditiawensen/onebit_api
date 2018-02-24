<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_pengguna = $_POST['id_pengguna'];
$nama_grup = $_POST['nama_grup'];
$nama_grup = str_replace("\\", "\\\\", $nama_grup);
$nama_grup = str_replace('"', '``', $nama_grup);
$nama_grup = str_replace("'", "`", $nama_grup);
	
$key = date('YmdHis');
$id = $key.str_replace(" ", "_", $nama_grup);
$outp = "";
$con->query("INSERT INTO t_group(
            id_group,
            nama_group,
            pembuat_group,
            tglbuat_group,
            blokir_group
        ) VALUES(
            '$id',
            '$nama_grup',
            '$id_pengguna',
            NOW(),
            'N'
        )");

if ($con->affected_rows > 0) {
	$outp .= '{"success":1}';
    $insert = $con->query("INSERT INTO t_pengguna_group(id_group,id_pengguna_group) VALUES('$id','$id_pengguna')");
} else {
	$outp .= '{"success":0}';
}

echo($outp);

$con->close();
?>