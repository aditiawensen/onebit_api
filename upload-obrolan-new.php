<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);

include('aw-plugins/aw-image-compress.php');

if(!empty($_POST['isi'])){
    execute();
}else{
    echo "Data Kosong!";
}

function execute(){
    include('aw-config/config.php');

    makeDirectory();

    $periode = date('Ym');
    $isi = $_POST['isi'];
    $isi = str_replace("\\", "\\\\", $isi);
    $isi = str_replace('"', '``', $isi);
    $isi = str_replace("'", "`", $isi);

    $jumlah_gambar = 0;
    if (!empty($_FILES['img1']['name'])) {
        $key = date('YmdHis');
        $img1 = $key.str_replace( " ","_",$_FILES['img1']['name'] );
        $source = 'aw-uploads/obrolan/'.$periode.'/';
        move_uploaded_file( $_FILES['img1']['tmp_name'], $source.$img1 );

        $dest100 = 'aw-uploads/obrolan/'.$periode.'/compress100/';
        thumbnail( $img1, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/obrolan/'.$periode.'/compress400/';
        thumbnail( $img1, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/obrolan/'.$periode.'/compress700/';
        thumbnail( $img1, $source, $dest700, 700, 700 );

        $jumlah_gambar = $jumlah_gambar +1;
    } else {
        $img1 = 'empty';
    }

    if (!empty($_FILES['img2']['name'])) {
        $key = date('YmdHis');
        $img2 = $key.str_replace( " ","_",$_FILES['img2']['name'] );
        $source = 'aw-uploads/obrolan/'.$periode.'/';
        move_uploaded_file( $_FILES['img2']['tmp_name'], $source.$img2 );

        $dest100 = 'aw-uploads/obrolan/'.$periode.'/compress100/';
        thumbnail( $img2, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/obrolan/'.$periode.'/compress400/';
        thumbnail( $img2, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/obrolan/'.$periode.'/compress700/';
        thumbnail( $img2, $source, $dest700, 700, 700 );

        $jumlah_gambar = $jumlah_gambar +1;
    } else {
        $img2 = 'empty';
    }

    if (!empty($_FILES['img3']['name'])) {
        $key = date('YmdHis');
        $img3 = $key.str_replace( " ","_",$_FILES['img3']['name'] );
        $source = 'aw-uploads/obrolan/'.$periode.'/';
        move_uploaded_file( $_FILES['img3']['tmp_name'], $source.$img3 );

        $dest100 = 'aw-uploads/obrolan/'.$periode.'/compress100/';
        thumbnail( $img3, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/obrolan/'.$periode.'/compress400/';
        thumbnail( $img3, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/obrolan/'.$periode.'/compress700/';
        thumbnail( $img3, $source, $dest700, 700, 700 );

        $jumlah_gambar = $jumlah_gambar +1;
    } else {
        $img3 = 'empty';
    }

    if (!empty($_FILES['img4']['name'])) {
        $key = date('YmdHis');
        $img4 = $key.str_replace( " ","_",$_FILES['img4']['name'] );
        $source = 'aw-uploads/obrolan/'.$periode.'/';
        move_uploaded_file( $_FILES['img4']['tmp_name'], $source.$img4 );

        $dest100 = 'aw-uploads/obrolan/'.$periode.'/compress100/';
        thumbnail( $img4, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/obrolan/'.$periode.'/compress400/';
        thumbnail( $img4, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/obrolan/'.$periode.'/compress700/';
        thumbnail( $img4, $source, $dest700, 700, 700 );

        $jumlah_gambar = $jumlah_gambar +1;
    } else {
        $img4 = 'empty';
    }

    if (!empty($_FILES['img5']['name'])) {
        $key = date('YmdHis');
        $img5 = $key.str_replace( " ","_",$_FILES['img5']['name'] );
        $source = 'aw-uploads/obrolan/'.$periode.'/';
        move_uploaded_file( $_FILES['img5']['tmp_name'], $source.$img5 );

        $dest100 = 'aw-uploads/obrolan/'.$periode.'/compress100/';
        thumbnail( $img5, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/obrolan/'.$periode.'/compress400/';
        thumbnail( $img5, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/obrolan/'.$periode.'/compress700/';
        thumbnail( $img5, $source, $dest700, 700, 700 );

        $jumlah_gambar = $jumlah_gambar +1;
    } else {
        $img5 = 'empty';
    }

    $link_gambar = 'obrolan/'.$periode.'/';

    $id_pengguna = $_POST['id_pengguna'];
    $u = $con->query("SELECT nama_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
    $pengirim_obrolan = $u['nama_pengguna'];
    $gambar_pengirim_obrolan = $id_pengguna.'.jpg';

    $warna = $_POST['warna'];
    if (!empty($_POST['lat'])) {
        $posisi_lat_pengaduan = $_POST['lat'];
    } else {
        $posisi_lat_pengaduan = 0;
    }
    if (!empty($_POST['lng'])) {
        $posisi_lng_pengaduan = $_POST['lng'];
    } else {
        $posisi_lng_pengaduan = 0;
    }

    if (!empty($_POST['mediapengaduan'])) {
        $media_pengaduan = $_POST['mediapengaduan'];
    }else{
        $media_pengaduan = "";
    }

    try {

        $q = $con->query("INSERT INTO obrolan(pengirim_obrolan,gambar_pengirim_obrolan,waktu_obrolan,isi_obrolan,gambar_obrolan,link_gambar,id_perangkat,warna_tulisan,lat,lng,multi_gambar,jumlah_gambar) VALUES ('$pengirim_obrolan','$gambar_pengirim_obrolan',NOW(),'$isi','$img1','$link_gambar','$id_pengguna','$warna','$posisi_lat_pengaduan','$posisi_lng_pengaduan',':t:$img1:t::c::t:$img2:t::c::t:$img3:t::c::t:$img4:t::c::t:$img5:t:','$jumlah_gambar')");
        echo "Berhasil Dikirim!";
    } catch (Exception $e) {
        echo "Error";
    }

    $con->close();
}

function makeDirectory(){
    $periode = date('Ym');
    if (!file_exists('aw-uploads/obrolan/'.$periode)) {
        mkdir('aw-uploads/obrolan/'.$periode, 0777, true);
        if (!file_exists('aw-uploads/obrolan/'.$periode.'/compress100')) {
            mkdir('aw-uploads/obrolan/'.$periode.'/compress100', 0777, true);
        }
        if (!file_exists('aw-uploads/obrolan/'.$periode.'/compress400')) {
            mkdir('aw-uploads/obrolan/'.$periode.'/compress400', 0777, true);
        }
        if (!file_exists('aw-uploads/obrolan/'.$periode.'/compress700')) {
            mkdir('aw-uploads/obrolan/'.$periode.'/compress700', 0777, true);
        }
    }
}

//PUSH NOTIFICATION
function pushNotification($header, $body, $footer, $time, $intent, $topic) {
    $gcm = new GCM();
    $push = new Push();

    $data = array();
    $data['header'] = $header;
    $data['body'] = $body;
    $data['footer'] = $footer;
    $data['time'] = $time;

    $push->setTitle("Notification");
    $push->setIsBackground(FALSE);
    $push->setFlag($intent);
    $push->setData($data);

    $gcm->sendToTopic($topic, $push->getPush());
}

class GCM {

    // constructor
    function __construct() {
        
    }

    // sending push message to single user by gcm registration id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic id
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by gcm registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to gcm servers
    private function sendPushNotification($fields) {

        // Set POST variables
        $url = 'https://gcm-http.googleapis.com/gcm/send';

        $headers = array(
            'Authorization: key=AIzaSyAI99zHr1gwToviddKU3x5BSl8AehKiWxg',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }

}

class Push{
    // push message title
    private $title;
    
    // push message payload
    private $data;
    
    // flag indicating background task on push received
    private $is_background;
    
    // flag to indicate the type of notification
    private $flag;
    
    function __construct() {
        
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    
    public function setData($data){
        $this->data = $data;
    }
    
    public function setIsBackground($is_background){
        $this->is_background = $is_background;
    }
    
    public function setFlag($flag){
        $this->flag = $flag;
    }
    
    public function getPush(){
        $res = array();
        $res['title'] = $this->title;
        $res['is_background'] = $this->is_background;
        $res['flag'] = $this->flag;
        $res['data'] = $this->data;
        
        return $res;
    }
}
?>