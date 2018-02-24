<?
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	include('aw-config/config.php');

	$q = $con->query("SELECT id_kategori, COUNT(*) jumlah FROM pengaduan GROUP BY id_kategori");

	$outp = "";
	while($rs = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_kategori":"' . $rs["id_kategori"] . '",';
	    $outp .= '"jumlah":"' . $rs["jumlah"] . '"}'; 
	}
	$outp ='['.$outp.']';

	echo($outp);

	$con->close();
?>