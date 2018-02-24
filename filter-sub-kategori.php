<?
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	include('aw-config/config.php');

	$q = $con->query("SELECT k.*,id_sub_kategori,nama_sub_kategori FROM kategori k, sub_kategori sk WHERE k.`id_kategori`=sk.`id_kategori`");

	$outp = "";
	while($rs = $q->fetch_assoc()) {
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id_kategori":"' . $rs["id_kategori"] . '",';
	    $outp .= '"nama_kategori":"' . $rs["nama_kategori"] . '",';
	    $outp .= '"urut_kategori":"' . $rs["urut_kategori"] . '",';
	    $outp .= '"id_sub_kategori":"' . $rs["id_sub_kategori"] . '",';
	    $outp .= '"nama_sub_kategori":"' . $rs["nama_sub_kategori"] . '"}'; 
	}
	$outp ='['.$outp.']';

	echo($outp);

	$con->close();
?>