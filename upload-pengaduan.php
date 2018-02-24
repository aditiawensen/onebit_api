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
    date_default_timezone_set("Asia/Makassar");
	include('aw-config/config.php');

    makeDirectory();

    $periode = date('Ym');
    $kategori = $_POST['tipepengaduan'];
    $sub_kategori = $_POST['tipepengaduan'];
    $icon_kategori = getIconCategory($kategori);
    $icon_sub_kategori = getIconSubCategory($sub_kategori);
    $isi_pengaduan = str_replace("\\", "\\\\", $_POST['isi']);
    $isi_pengaduan = str_replace('"', '``', $isi_pengaduan);
    $isi_pengaduan = str_replace("'", "`", $isi_pengaduan);

    if (!empty($_FILES['image']['name'])) {
        $img = str_replace( " ","_",$_FILES['image']['name'] );
        $source = 'aw-uploads/images/'.$periode.'/';
        move_uploaded_file( $_FILES['image']['tmp_name'], $source.$img );

        $dest100 = 'aw-uploads/images/'.$periode.'/compress100/';
        thumbnail( $img, $source, $dest100, 100, 100 );

        $dest400 = 'aw-uploads/images/'.$periode.'/compress400/';
        thumbnail( $img, $source, $dest400, 400, 400 );

        $dest700 = 'aw-uploads/images/'.$periode.'/compress700/';
        thumbnail( $img, $source, $dest700, 700, 700 );
    } else {
        $img = 'empty';
    }

    $link_gambar = 'images/'.$periode.'/';

    $id_pengguna = $_POST['id_pengguna'];
    $u = $con->query("SELECT nama_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'")->fetch_assoc();
    $pengirim_pengaduan = $u['nama_pengguna'];
    $gambar_pengirim_pengaduan = $id_pengguna.'.jpg';

    $posisi_lat_pengaduan = $_POST['lat'];
    $posisi_lng_pengaduan = $_POST['lng'];

    if (!empty($_POST['mediapengaduan'])) {
        $media_pengaduan = $_POST['mediapengaduan'];
    }else{
        $media_pengaduan = "";
    }

    $kecamatan_posisi = '';
    $kota_posisi = '';

    try {
        $k = $con->query("SELECT * FROM kategori k WHERE k.`nama_kategori`='$kategori'")->fetch_assoc();
        $id_kategori = $k['id_kategori'];
        $sk = $con->query("SELECT * FROM sub_kategori sk WHERE sk.`nama_sub_kategori`='$sub_kategori'")->fetch_assoc();
        $id_sub_kategori = $sk['id_sub_kategori'];

        $q = $con->query("INSERT INTO `pengaduan`(`id_kategori`,`kategori`,`id_sub_kategori`,`sub_kategori`,`icon_kategori`,`icon_sub_kategori`,`isi_pengaduan`,`gambar_pengaduan`,`pengirim_pengaduan`,`gambar_pengirim_pengaduan`,`waktu_pengaduan`,`posisi_lat_pengaduan`,`posisi_lng_pengaduan`,`media_pengaduan`,`status_pengaduan`,`warna_pengaduan`,`sembunyikan`,`blokir`,`periode`,`link_gambar`,`kecamatan_posisi`,`kota_posisi`,`id_perangkat`) VALUES ('$id_kategori','$kategori','$id_sub_kategori','$sub_kategori','$icon_kategori','$icon_sub_kategori','$isi_pengaduan','$img','$pengirim_pengaduan','$gambar_pengirim_pengaduan',NOW(),'$posisi_lat_pengaduan','$posisi_lng_pengaduan','$media_pengaduan','N','red','N','N','$periode','$link_gambar','$kecamatan_posisi','$kota_posisi','$id_pengguna')");
        echo "Berhasil Dikirim!";
		$time = date('Y-m-d H:i:s');
		pushNotification($pengirim_pengaduan,$isi_pengaduan,'Pengaduan Baru',$time,2,'bitungsmartcity');
    } catch (Exception $e) {
        echo "Error";
    }

    $con->close();
}

function makeDirectory(){
    $periode = date('Ym');
    if (!file_exists('aw-uploads/images/'.$periode)) {
        mkdir('aw-uploads/images/'.$periode, 0777, true);
        if (!file_exists('aw-uploads/images/'.$periode.'/compress100')) {
            mkdir('aw-uploads/images/'.$periode.'/compress100', 0777, true);
        }
        if (!file_exists('aw-uploads/images/'.$periode.'/compress400')) {
            mkdir('aw-uploads/images/'.$periode.'/compress400', 0777, true);
        }
        if (!file_exists('aw-uploads/images/'.$periode.'/compress700')) {
            mkdir('aw-uploads/images/'.$periode.'/compress700', 0777, true);
        }
    }
}

function getIconCategory($x){
    switch ($x) {
        case 'Kebersihan':
            return 'kebersihan.png';
            break;
        case 'Kebakaran':
            return 'kebakaran.png';
            break;
        case 'Kemacetan':
            return 'kemacetan.png';
            break;
        case 'Kebanjiran':
            return 'kebanjiran.png';
            break;
        case 'Kerusakan':
            return 'kerusakan.png';
            break;
        case 'Kesehatan':
            return 'kesehatan.png';
            break;
        case 'Pelanggaran':
            return 'pelanggaran.png';
            break;
        case 'Potensi Teroris':
            return 'potensi-teroris.png';
            break;
        case 'Pohon Tumbang':
            return 'pohon-tumbang.png';
            break;
        case 'Kaki Lima Liar':
            return 'kaki-lima-liar.png';
            break;
        case 'PDAM (Pelayanan Air)':
            return 'pdam.png';
            break;
        case 'PLN (Pelayanan Listrik)':
            return 'pln.png';
            break;
        case 'Bank':
            return 'bank.png';
            break;
        case 'Hotel':
            return 'hotel.png';
            break;
        case 'Kuliner':
            return 'kuliner.png';
            break;
        case 'Pelabuhan':
            return 'pelabuhan.png';
            break;
        case 'Instansi':
            return 'instansi.png';
            break;
        case 'Sekolah':
            return 'sekolah.png';
            break;
        default:
            return 'empty';
            break;
    }
}

function getIconSubCategory($x){
	switch ($x) {
		case 'Kebocoran':
			return 'broken.png';
			break;
		case 'Meter Rusak':
			return 'crash.png';
			break;
		case 'Air Tidak Jalan':
			return 'nowater.png';
			break;
		case 'Tagihan Rekening':
			return 'money.png';
			break;
		case 'Curi Air':
			return 'thief.png';
			break;
		case 'Lainnya':
			return 'question.png';
			break;
		default:
			return 'empty';
			break;
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