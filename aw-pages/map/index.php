<!-- VIEW -->
<div id="show-modal" class="w3-modal">
  <div class="w3-modal-content w3-animate-top">
    <div class="w3-container w3-blue">
      <span onclick="document.getElementById('show-modal').style.display='none'" class="w3-closebtn">&times;</span>
      <h5>View</h5>
      <input type="hidden" id="image-value">
    </div>
    <div class="w3-center"><img id="item-image" src="#" style="max-height:700px;max-width:100%;"/></div>
  </div>
</div>
<!-- END VIEW -->

<script>
var map;
getmarkers = [];
var mainUrl = 'http://localhost/bsc/';

function initialize(){
    var styles = [{ stylers: [{ hue:"#0077FF" },{ saturation:100 }] }];

    var center = new google.maps.LatLng(1.4447044,125.1822173);
    map = new google.maps.Map(document.getElementById('googleMap'),{
        center: center,
        zoom: 15,
        styles: styles
    });

    loadMarkerFirst();
    setInterval(function(){loadMarkerNew()},5000);

    function loadMarkerFirst(){
        $.getJSON(mainUrl+'load-data-pengaduan.php?lastId=100&startRow=0&limitRow=100&idDevice=777',function(result){
            $.each(result,function(i,data){
                if(data.posisi_lat_pengaduan>0 || data.posisi_lng_pengaduan>0){
                    getMarker(new google.maps.LatLng(data.posisi_lat_pengaduan,data.posisi_lng_pengaduan),"aw-images/kategori-pengaduan/"+data.icon_kategori,[data.id_kategori,data.id_sub_kategori,data.id_pengaduan],data.kategori,createDescriptionInfoWindow(data.pengirim_pengaduan,data.waktu_pengaduan,data.isi_pengaduan,data.gambar_pengaduan));
                }
            });
        }).complete(function(){
            createFilterCategory();
            createFilterSubCategory();
        });
    }

    function loadMarkerNew(){
        $.getJSON(mainUrl+'load-data-pengaduan-baru.php',function(result){
            $.each(result,function(i,data){
                if(data.posisi_lat_pengaduan>0 || data.posisi_lng_pengaduan>0){
                    var result = 0;
                    for (var i = getmarkers.length - 1; i >= 0; i--) {
                        if(getmarkers[i].category[2]==data.id_pengaduan){
                            result = 1;
                        }
                    }
                    if(result < 1){
                        getMarkerAnimation(new google.maps.LatLng(data.posisi_lat_pengaduan,data.posisi_lng_pengaduan),"aw-images/kategori-pengaduan/"+data.icon_kategori,[data.id_kategori,data.id_sub_kategori,data.id_pengaduan],data.kategori,createDescriptionInfoWindow(data.pengirim_pengaduan,data.waktu_pengaduan,data.isi_pengaduan,data.gambar_pengaduan));
                        $('#jumlah'+data.id_kategori).text((parseInt($('#jumlah'+data.id_kategori).val()) || 0) + 1);
                        $('#jumlah'+data.id_kategori).val((parseInt($('#jumlah'+data.id_kategori).val()) || 0) + 1);
                        $('#jumlah'+data.id_sub_kategori).text((parseInt($('#jumlah'+data.id_sub_kategori).val()) || 0) + 1);
                        $('#jumlah'+data.id_sub_kategori).val((parseInt($('#jumlah'+data.id_sub_kategori).val()) || 0) + 1);
                    }
                }
            });
        });
    }

    function getMarker(location,icon,category,title,description){
        var marker = new google.maps.Marker({
            map: map,
            position: location,
            icon: setIcon(icon),
            category: category
        });
        google.maps.event.addListener(marker,'click',function(){
            createInfoWindow(marker,title,description);
        });
        getmarkers.push(marker);
    }

    function getMarkerAnimation(location,icon,category,title,description){
        var marker = new google.maps.Marker({
            map: map,
            position: location,
            icon: setIcon(icon),
            category: category,
            animation: google.maps.Animation.BOUNCE,
        });
        google.maps.event.addListener(marker,'click',function(){
            createInfoWindow(marker,title,description);
        });
        getmarkers.push(marker);
        google.maps.event.addListener(marker,'click',function(){
            infowindow.close();
        });
    }

    function createInfoWindow(marker,title,description){
        var infowindow = new google.maps.InfoWindow({ content: '' });
        infowindow.setContent(contentInfoWindow(title,description));
        infowindow.open(map, marker);
        map.panTo(marker.getPosition());
        google.maps.event.addListener(map,'click',function(){
            infowindow.close();
        });
    }

    function contentInfoWindow(title,description){
        var c = '';
        c += '<div class="w3-row w3-blue">',
        c +=    '<div class="w3-indigo w3-padding-small w3-large"><b>'+title+'</b></div>',
        c +=        '<div class="w3-padding-small">',
        c +=            '<div class="w3-row">'+description+'</div>',
        c +=        '</div>',
        c += '</div>';
        return c;
    }

    function createDescriptionInfoWindow(pengirim,waktupengaduan,isi,img){
        var c = '';
        c += '<div>',
        c += '<span class="w3-text-white"><b>'+pengirim+'</b></span> <span class="w3-small w3-text-light-grey">- '+waktupengaduan+'</span>',
        c += '<hr>',
        c += '<div class="w3-center"><span class="w3-text-white"><i>"'+isi+'"</i></span></div>',
        c += '<img onclick="showPicture(`'+img+'`)" src="'+mainUrl+'aw-uploads/images/201702/compress400/'+img+'" style="max-width:100%;max-height:250px;cursor:pointer">',
        c += '</div>';
        return c;
    }
}

function setIcon(url){
    var image = {
        url: url,
        scaledSize: new google.maps.Size(40, 40)
    };
    return image;
}

function getDirections(source,destination){
    window.open('http://maps.google.com/?saddr='+source+'&daddr='+destination);
}

function createFilterCategory(){
    $.getJSON('filter-kategori.php',function(result){
        $.each(result,function(i,data){
            var c = '';
            c += '<button onclick="accordion(`'+data.id_kategori+'`)" class="'+data.id_kategori+' w3-accordion-btn w3-btn-block w3-left-align w3-blue w3-small">'+data.nama_kategori+' <span id="jumlah'+data.id_kategori+'" class="w3-badge w3-indigo"></span></button>',
            c += '<ul id="'+data.id_kategori+'" class="w3-ul w3-accordion-content w3-container"></ul>',
            $('#filter-menu').append(c);
        });
    }).complete(function(){
        getCategoryRows();
    });
}

function createFilterSubCategory(){
    $.getJSON('filter-sub-kategori.php',function(result){
        $.each(result,function(i,data){
            var c = '';
            c += '<li class="w3-small">',
            c +=    '<input onclick="filter(this)" type="checkbox" class="w3-check" value="'+data.id_sub_kategori+'" checked/> '+data.nama_sub_kategori+' <span id="jumlah'+data.id_sub_kategori+'" class="w3-badge w3-indigo"></span>',
            c += '</li>';
            $('#'+data.id_kategori).append(c);
        });
    }).complete(function(){
        getSubCategoryRows();
    });
}

function getCategoryRows(){
    $.getJSON('jumlah-filter-kategori.php',function(result){
        $.each(result,function(i,data){
            $('#jumlah'+data.id_kategori).text(data.jumlah);
            $('#jumlah'+data.id_kategori).val(data.jumlah);
        });
    });
}

function getSubCategoryRows(){
    $.getJSON('jumlah-filter-sub-kategori.php',function(result){
        $.each(result,function(i,data){
            $('#jumlah'+data.id_sub_kategori).text(data.jumlah);
            $('#jumlah'+data.id_sub_kategori).val(data.jumlah);
        });
    });
}

function showPicture(url){
    document.getElementById("show-modal").style.display = "block";
    document.getElementById("item-image").src = mainUrl+'aw-uploads/images/201702/compress700/'+url;
}

function filter(event){
    if(event.checked){
        show(event.value);
    }else{
        hide(event.value);
    }
}

function show(category){
    for (i = 0; i < getmarkers.length; i++) {
        marker = getmarkers[i];
        if(marker.category.indexOf(category) >= 0){
            marker.setVisible(true);
        }
    }
}

function hide(category){
    for (i = 0; i < getmarkers.length; i++) {
        marker = getmarkers[i];
        if(marker.category.indexOf(category) >= 0){
            marker.setVisible(false);
        }
    }
}

function accordion(id){
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-indigo w3-text-white";
    } else {
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className =
        x.previousElementSibling.className.replace(" w3-indigo w3-text-white", "");
    }
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8TLRYpMLYcS_4JZpTVrfVwkA8e4CLOp4&callback=initialize"></script>

<div id="googleMap" style="width:100%;height:100%;color:black"></div>

<span id="search-btn" class="w3-tag w3-large w3-round-xlarge w3-green w3-hover-yellow" style="position:fixed;top:90px;left:10px;cursor:pointer"><b>==</b></span>
<span id="filter-menu" class="w3-round-large w3-light-grey w3-accordion w3-light-grey" style="display:none;position:fixed;top:120px;left:10px;overflow:auto;width:250px;max-height:77%"></span>

<script>
    $('#search-btn').click(function(){
        $('#filter-menu').slideToggle();
    });
</script>