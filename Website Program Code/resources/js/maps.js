// import colors from './colors';

const colors = [
    {name: "red",
    fillColor: "#FF6D6D",
    strokeColor: "#FF0000"
    },
    {name: "orange",
    fillColor: "#FFC56D",
    strokeColor: "#FFB037"
    },
    {name: "yellow",
    fillColor: "#FFF06D",
    strokeColor: "#FAFF00"
    },
    {name: "green",
    fillColor: "#6DFF7C",
    strokeColor: "#00ff00"
    },
    {name: "blue",
    fillColor: "#706DFF",
    strokeColor: "#0047FF"
    },
    {name: "cyan",
    fillColor: "#6DF6FF",
    strokeColor: "#00F0FF"
    },
    {name: "violet",
    fillColor: "#D06DFF",
    strokeColor: "#CC00FF"
    },
]
function initMap() {
    
    
    
    var lastCoords = prevCoords = null;
    cityCircle = new google.maps.Circle();
    infowindow = new google.maps.InfoWindow(); 
    
    prevCoords = {lat: 0, lng: 0}
    const myLatLng = { lat: 14.620890, lng: 121.161296 };
    var markers = [], circles = [];
    map = new google.maps.Map(document.getElementById("map"), {
        center:  { lat: myLatLng.lat, lng: myLatLng.lng },
        zoom: 15,
        disableDefaultUI: true
    });
    
    loadMarker( "init");
    setInterval(function() {loadMarker()}, 15000);
    function loadMarker( instance = "reload"){
        $.ajax({
            cache:false,
            url: 'resources/data/getMonitors.php',
            type: 'GET',
            dataType: 'JSON',
            data: {"DeviceID": selectedID},
            success: function(msg) {
                 const locations = msg;
                //  console.log(msg)
                locations.forEach(placeMarker,instance);
            } ,
            error: function(req, err){
            }		
        });
        
    }
    
 
    function placeMarker( loc ) {
        
        var lastDateUpdate = loc.DateTime;
        // console.log("alert",loc.alert)
        // if (loc.alert == 0){
        //     $("#notifAlert").addClass("hide");
        // }
        // else if (loc.alert == 1){
        //     $("#notifAlert").removeClass("hide");
        // }
        if (loc.Latitude == null){
            var marker = null;
            console.log("undefined lat long")
            return
        }
        
        if (prevCoords.lat != loc.Latitude || prevCoords.lng != loc.Longitude ){
        
            prevCoords = {lat: loc.Latitude, lng: loc.Longitude}
          var content = "<div id='infowindow'>"+
        //   var content = "<div id='infowindow'><b>Device ID: </b>"+loc.DeviceID+"<br>"+
                                            "<b>Last Update: </b>"+loc.DateTime+"<br>"+
                                            "<b>Latitude: </b>"+loc.Latitude+"<br>"+
                                            "<b>Longitude: </b>"+loc.Longitude+"<br></div>";
        // var nameSplit = loc.Name.split(" ");
        // var markerName = "D";
        // var initialsCount = nameSplit.length;
        // for (i = 0; i < initialsCount; i++){
        //     markerName = loc.devID;
        //     // markerName += nameSplit[i].substr(0,1);
        // }

        if (this == "init"){
         marker = new google.maps.Marker({
            position: new google.maps.LatLng( parseFloat(loc.Latitude), parseFloat(loc.Longitude) ),
            // label: {text: markerName, color: "white", fontSize: "10px"},
            map: map
          });
          
        google.maps.event.addListener(marker, 'click', function(){
            // console.log(this)
            infowindow.close(); // Close previously opened infowindow
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });  
        
        
            infowindow.close(); // Close previously opened infowindow
            infowindow.setContent(content);
            infowindow.open(map, marker);
        marker.setMap( map );
                map.panTo( new google.maps.LatLng( parseFloat(loc.Latitude), parseFloat(loc.Longitude) ),
                );
                map.setZoom(15);
        }
          else{
                if(marker == null){
                  marker = new google.maps.Marker({
                    position: new google.maps.LatLng( parseFloat(loc.Latitude), parseFloat(loc.Longitude) ),
                    label: {text: markerName, color: "white", fontSize: "10px"},
                    map: map
                  });
                    
                }
                // console.log("reposition marker")
                marker.setPosition( new google.maps.LatLng( parseFloat(loc.Latitude), parseFloat(loc.Longitude) ),
                );
                map.panTo( new google.maps.LatLng( parseFloat(loc.Latitude), parseFloat(loc.Longitude) ),
                );
                map.setZoom(15);
                // google.maps.event.addListener(marker, 'click', function(){
                    infowindow.close(); // Close previously opened infowindow
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                // });
                
                
                google.maps.event.addListener(marker, 'click', function(){
                    infowindow.close(); // Close previously opened infowindow
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                });
            }
        }
        
        
      
    
    }


    
  
}


