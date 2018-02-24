<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    
include('aw-config/config.php');

$id_pengaduan = $_POST['id_pengaduan'];
$id_pengguna = $_POST['id_pengguna'];
$id_komentar = $id_pengguna.$id_pengaduan;
$isi_komentar = $_POST['isi_komentar'];
$isi_komentar = str_replace("\\", "\\\\", $isi_komentar);
$isi_komentar = str_replace('"', '``', $isi_komentar);
$isi_komentar = str_replace("'", "`", $isi_komentar);
	
$outp = "";
$con->query("INSERT INTO komentar(id_pengaduan,id_pengguna,isi_komentar,waktu_komentar) VALUES('$id_pengaduan','$id_pengguna','$isi_komentar',NOW())");
if ($con->affected_rows > 0) {
	$outp .= '{"success":1}';
	$title = 'No.Pengaduan '.$id_pengaduan;
	$time = date('Y-m-d H:i:s');

    $r = $con->query("SELECT id_perangkat FROM pengaduan WHERE id_pengaduan='$id_pengaduan'")->fetch_assoc();
    $target = $r['id_perangkat'];
    if($id_pengguna!=$target){
        $r2 = $con->query("SELECT nama_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
        $isi_notifikasi = $r2['nama_pengguna'].' memberikan komentar pada pengaduan anda';
        $con->query("INSERT INTO notifikasi(gambar_notifikasi,gambar2_notifikasi,judul_notifikasi,isi_notifikasi,waktu_notifikasi,dibaca,halaman,id_pengguna,id_table) VALUES('http://pdambitung.96.lt/bsc/aw-pages/include/images/notifikasi/notif.png','http://pdambitung.96.lt/bsc/aw-pages/include/images/notifikasi/clock.png','Komentar','$isi_notifikasi',NOW(),'N','komentar_pengaduan','$target','$id_pengaduan')");
    }
	//pushNotification($title, 'mendapat komentar...','Pemberitahuan Baru',$time,2,'bitungsmartcity');
} else {
	$outp .= '{"success":0}';
}

echo($outp);

$con->close();

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