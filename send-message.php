<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$time_now = date('Y-m-d H:i:s');
    
include('aw-config/config.php');

$id_koneksi = $_POST['id_koneksi'];
$id_pengguna = $_POST['id_pengguna'];
$id_partner = $_POST['id_partner'];
$r = $con->query("SELECT nama_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
$nama_partner = $r['nama_pengguna'];
$isi_pesan = $_POST['isi_pesan'];
$isi_pesan = str_replace("\\", "\\\\", $isi_pesan);
$isi_pesan = str_replace('"', '``', $isi_pesan);
$isi_pesan = str_replace("'", "`", $isi_pesan);

$outp = "";
$q = $con->query("SELECT urut FROM pesan WHERE id_koneksi='$id_koneksi' ORDER BY id DESC LIMIT 1");
if ($q->num_rows > 0) {
    $get_last_sort = $q->fetch_assoc();
    $urut = $get_last_sort['urut']+1;
} else {
    $urut = 1;
}
	
$con->query("INSERT INTO pesan(id_koneksi,isi_pesan,gambar,waktu,id_pengguna,urut) VALUES('$id_koneksi','$isi_pesan','empty','$time_now','$id_pengguna',$urut)");
if ($con->affected_rows > 0) {
    $con->query("UPDATE koneksi_pesan SET pesan_terbaru='$isi_pesan', urut='$urut', waktu_buat='$time_now' WHERE id_koneksi='$id_koneksi'");
	$outp .= '{"success":1}';
	pushNotification($nama_partner, $isi_pesan,'Pesan Baru',$time_now,77,$id_partner);
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