<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);

include('aw-plugins/aw-image-compress.php');

if(isset($_POST['isi'])){
    execute();
}else{
    echo "Data Kosong!";
}

function execute(){
    date_default_timezone_set("Asia/Makassar");
    include('aw-config/config.php');

    makeDirectory();

    $periode = date('Ym');
    $isi = $_POST['isi'];
    $isi = str_replace("\\", "\\\\", $isi);
    $isi = str_replace('"', '``', $isi);
    $isi = str_replace("'", "`", $isi);

    if (!empty($_FILES['image']['name'])) {
        $img = str_replace( " ","_",$_FILES['image']['name'] );
        $source = 'aw-uploads/group-chat/'.$periode.'/';
        move_uploaded_file( $_FILES['image']['tmp_name'], $source.$img );

        $dest100 = 'aw-uploads/group-chat/'.$periode.'/compress100/';
        thumbnail( $img, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/group-chat/'.$periode.'/compress400/';
        thumbnail( $img, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/group-chat/'.$periode.'/compress700/';
        thumbnail( $img, $source, $dest700, 700, 700 );
    } else {
        $img = 'empty';
    }

    $link_gambar = 'group-chat/'.$periode.'/';

    $id_pengguna = $_POST['id_pengguna'];
    $u = $con->query("SELECT nama_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
    $pengirim_obrolan = $u['nama_pengguna'];

    $id_group = $_POST['id_group'];
    $posisi_lat_pengaduan = $_POST['lat'];
    $posisi_lng_pengaduan = $_POST['lng'];
    $media_pengaduan = $_POST['mediapengaduan'];

    try {

        $q = $con->query("INSERT INTO t_chat_group(id_group,id_pengirim_chat,isi_chat,gambar_chat,link_gambar,waktu_chat) VALUES('$id_group','$id_pengguna','$isi','$img','$link_gambar',NOW())");
        echo "Berhasil Dikirim!";
    } catch (Exception $e) {
        echo "Error";
    }

    $con->close();
}

function makeDirectory(){
    $periode = date('Ym');
    if (!file_exists('aw-uploads/group-chat/'.$periode)) {
        mkdir('aw-uploads/group-chat/'.$periode, 0777, true);
        if (!file_exists('aw-uploads/group-chat/'.$periode.'/compress100')) {
            mkdir('aw-uploads/group-chat/'.$periode.'/compress100', 0777, true);
        }
        if (!file_exists('aw-uploads/group-chat/'.$periode.'/compress400')) {
            mkdir('aw-uploads/group-chat/'.$periode.'/compress400', 0777, true);
        }
        if (!file_exists('aw-uploads/group-chat/'.$periode.'/compress700')) {
            mkdir('aw-uploads/group-chat/'.$periode.'/compress700', 0777, true);
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