function loadData(trayek,color){
	var dataTrip = [];
    $.getJSON("data-jalur-trayek.php?id="+trayek,function(result){
        $.each(result,function(i,data){
            var pos = new google.maps.LatLng(data.lat,data.lng);
            dataTrip.push(pos);
        });
    }).complete(function(){
    	var arrow = {
          path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
          scale:2,
          strokeColor:'#000000',
          strokeWeight:2
        };
        var trip = new google.maps.Polyline({
            map:map,
            path:dataTrip,
            strokeColor:color,
            strokeOpacity:0.8,
            strokeWeight:7,
            category:trayek,
            icons: [{
	            icon:arrow,
	            offset:'1%',
	            repeat:'100px'
	        }]
        });
        trip.setVisible(false);
        getmarkers.push(trip);
        google.maps.event.addListener(trip, 'click', function(e){
            $.getJSON("info-trayek.php?id="+trip.category,function(result){
                $.each(result,function(i,data){
                    infowindow.setContent(contentInfoWindow('Info '+data.nama_trayek,data.info));
                });
            });
            infowindow.setPosition(e.latLng);
            infowindow.open(map);
        })
    });
}