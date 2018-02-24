<h4>Laporkan Informasi</h4>
<div class="w3-text-black w3-row w3-padding w3-center">
	<div class="w3-col s12 m6 l4">
		<div class="w3-padding-small"><input class="w3-input" type="text" name="telp" placeholder="No Telp/HP"></div>
		<div class="w3-padding-small"><input class="w3-input" type="text" name="pengirim" placeholder="Pengirim"></div>
		<div class="w3-padding-small"><textarea class="w3-input" rows="7" placeholder="Tulis Sesuatu..."></textarea></div>
	</div>
	<div class="w3-col s12 m6 l4">
		<div class="w3-padding-small">
			<input id="input-gambar" type="file" accept="image/*">
			<img id="preview-gambar" src="aw-images/map.png" width="80%"/>
		</div>
	</div>
	<div class="w3-col s12 m12 l4">
		<div class="w3-padding-tiny" style="height:500px">
			<div id="googleMap" style="width:100%;height:100%;color:black"></div>
		</div>
	</div>
	<div class="w3-col s12 w3-topbar w3-border-yellow w3-padding-tiny">
		<button class="w3-btn w3-red w3-large">KIRIM</button>
	</div>
</div>

<script>
function readURL(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#preview-gambar').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$("#input-gambar").change(function(){
    readURL(this);
});
</script>

<script>
function initialize(){
    var styles = [
        {
         	stylers: [
	            { hue: "#0077FF" },
	            { saturation: 100 }
          	]
        }
    ];

    var center = new google.maps.LatLng(1.4447044,125.1822173);
    var map = new google.maps.Map(document.getElementById('googleMap'),{
        center: center,
        zoom: 17,
        styles: styles
    });

    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position){
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            map.setCenter(pos);
            getMarker(map,pos,'aw-images/gis/android.ico','Lokasi Anda',pos.lat.toFixed(4)+','+pos.lng.toFixed(4));
        },function(){
            handleLocationError(true, infoWindow, map.getCenter());
        });
    }else{
        handleLocationError(false, infoWindow, map.getCenter());
    }

    function getMarker(map,location,icon,title,description){
        var marker = new google.maps.Marker({
            map: map,
            position: location,
            icon: setIcon(icon),
            animation: google.maps.Animation.BOUNCE,
            optimized: false
        });
        google.maps.event.addListener(marker,'click',function(){
            createInfoWindow(map,marker,title,description);
        });
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos){
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ? contentInfoWindow('Error:','Layanan geolocation gagal!<br>Silahkan aktifkan fitur geolocation.') : contentInfoWindow('Error:','Browser tidak mendukung geolocation!'));
}

function createInfoWindow(map,marker,title,description){
    var infowindow = new google.maps.InfoWindow({ content: '' });
    infowindow.setContent(contentInfoWindow(title,description));
    infowindow.open(map, marker);
    map.panTo(marker.getPosition());
}

function contentInfoWindow(title,description){
    var c = '';
    c += '<div class="aw-row aw-l2">',
    c +=    '<div class="aw-blue aw-padding-small aw-large"><b>'+title+'</b></div>',
    c +=        '<div class="aw-padding-small">',
    c +=            '<div class="aw-row">'+description+'</div>',
    c +=        '</div>',
    c += '</div>';
    return c;
}

function setIcon(url){
    var image = {
        url: url,
        scaledSize: new google.maps.Size(50, 50)
    };
    return image;
}

function getDirections(source,destination){
    window.open('http://maps.google.com/?saddr='+source+'&daddr='+destination);
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8TLRYpMLYcS_4JZpTVrfVwkA8e4CLOp4&callback=initialize"></script>