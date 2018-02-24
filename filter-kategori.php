<?
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	include('aw-config/config.php');

	$q = $con->query("SELECT * FROM kategori");

	$outp = "";
	while($rs = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_kategori":"' . $rs["id_kategori"] . '",';
	    $outp .= '"nama_kategori":"' . $rs["nama_kategori"] . '",';
	    $outp .= '"urut_kategori":"' . $rs["urut_kategori"] . '"}'; 
	}
	$outp ='['.$outp.']';

	echo($outp);

	$con->close();
?>