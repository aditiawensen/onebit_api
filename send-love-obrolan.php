<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_obrolan = $_POST['id_obrolan'];
$id_pengguna = $_POST['id_pengguna'];
$id_love_obrolan = $id_pengguna.$id_obrolan;

$con->query("INSERT INTO love_obrolan(id_love_obrolan,id_obrolan,id_pengguna,waktu_love) VALUES('$id_love_obrolan','$id_obrolan','$id_pengguna',NOW())");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":"1","id_obrolan":"'.$id_obrolan.'","id_pengguna":"'.$id_pengguna.'"}';
	$time = date('Y-m-d H:i:s');
	$u = $con->query("SELECT * FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
	$i = $con->query("SELECT isi_obrolan FROM obrolan WHERE id_obrolan='$id_obrolan'")->fetch_assoc();
	$title = $u['nama_pengguna'];
	$isi_obrolan = $i['isi_obrolan'];
	$content = 'menyukai '.'"'.$isi_obrolan.'"';

    $r = $con->query("SELECT id_perangkat FROM obrolan WHERE id_obrolan='$id_obrolan'")->fetch_assoc();
    $target = $r['id_perangkat'];
    if($id_pengguna!=$target){
        $r2 = $con->query("SELECT nama_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
        $isi_notifikasi = $r2['nama_pengguna'].' menyukai obrolan anda';
        $con->query("INSERT INTO notifikasi(gambar_notifikasi,gambar2_notifikasi,judul_notifikasi,isi_notifikasi,waktu_notifikasi,dibaca,halaman,id_pengguna,id_table,id_pengirim) VALUES('http://pdambitung.96.lt/bsc/aw-pages/include/images/notifikasi/notif.png','http://pdambitung.96.lt/bsc/aw-pages/include/images/notifikasi/clock.png','Reaksi','$isi_notifikasi',NOW(),'N','obrolan','$target','$id_obrolan','$id_pengguna')");
    }
	//pushNotification($title, $content,'Pemberitahuan Baru',$time,4);
} else {
	$outp .= '{"success":"0","id_obrolan":"'.$id_obrolan.'","id_pengguna":"'.$id_pengguna.'"}';
}

echo($outp);

$con->close();
?>

<?php
//PUSH NOTIFICATION
function pushNotification($header, $body, $footer, $time, $intent) {
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

    $gcm->sendToTopic('bitungsmartcity', $push->getPush());
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