<?php
date_default_timezone_set("Asia/Makassar");

include('aw-plugins/aw-image-compress.php');

if(!empty($_POST['id_pengguna'])){
    execute();
}else{
    echo "Data Kosong!";
}

function execute(){
    include('aw-config/config.php');

    makeDirectory();

    $id_pengguna = $_POST['id_pengguna'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $no_ktp = $_POST['no_ktp'];
    $telp_pengguna = $_POST['telp_pengguna'];

    $nama_pengguna = str_replace('"', '``', $nama_pengguna);
    $no_ktp = str_replace('"', '``', $no_ktp);
    $telp_pengguna = str_replace('"', '``', $telp_pengguna);

    if (!empty($_FILES['image']['name'])) {
        $img = str_replace( " ","_",$_FILES['image']['name'] );
        $source = 'aw-uploads/profile/';
        move_uploaded_file( $_FILES['image']['tmp_name'], $source.$img );

        $dest200 = 'aw-uploads/profile/compress200/';
        thumbnail( $img, $source, $dest200, 200, 200 );
		$dest100 = 'aw-uploads/profile/compress100/';
        thumbnail( $img, $source, $dest100, 100, 100 );
		
		$gambar_pengguna = 'compress200/'.$img;
		try {
			$q = $con->query("UPDATE pengguna set nama_pengguna='$nama_pengguna', no_ktp='$no_ktp', telp_pengguna='$telp_pengguna', gambar_pengguna='$gambar_pengguna' WHERE id_pengguna='$id_pengguna'");
			echo "Success!";
		} catch (Exception $e) {
			echo "Error";
		}
    } else {
        $img = '';
		try {
            $q = $con->query("UPDATE pengguna set nama_pengguna='$nama_pengguna', no_ktp='$no_ktp', telp_pengguna='$telp_pengguna' WHERE id_pengguna='$id_pengguna'");
            echo "Berhasil Dikirim!";
        } catch (Exception $e) {
            echo "Error";
        }
    }

    $con->close();
}

function makeDirectory(){
    if (!file_exists('aw-uploads/profile')) {
        mkdir('aw-uploads/profile', 0777, true);
        if (!file_exists('aw-uploads/profile/compress200')) {
            mkdir('aw-uploads/profile/compress200', 0777, true);
        }
		if (!file_exists('aw-uploads/profile/compress100')) {
            mkdir('aw-uploads/profile/compress100', 0777, true);
        }
    }
}
?>