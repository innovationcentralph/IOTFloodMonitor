
function getActiveDevices(){
    
    $.ajax({
        url: 'resources/data/fetchDevices.php',
        type: 'GET',
        // data: formData,
        dataType: "JSON",
        success: function (msg) {
            // console.log(msg)
            if (msg.data.length > 0){
                // $("#deviceSelector").empty();
                
                $("#deviceSelector").append(`<ul class="nav-options" id="devicelist" >
                            
                        </ul>`);
                msg.data.forEach((value) =>{
                    // console.log(value.DeviceID)
                   
                    $("#devicelist").append(` 
                    <li>
                        <a class="sidebar-link" id="device-`+value.DeviceID+`" href="dashboard.php?id=`+value.DeviceID+`" aria-expanded="false">
                        `+value.DeviceID+`
                        </a>
                    </li>`);
                    
                    // <option value="`+value.DeviceID+`">`+value.DeviceID+`</option>`);
                });
                // $('#devID option[value="ABC123"]').prop("selected",true);
                
                // $('#devID option[value="'+selectedID+'"]').attr("selected",true);
                selectedID = msg.data[0].DeviceID;

            }
        },
        error: function(req, err){
            console.log(err);
        },
        complete: function(msg){
                // console.log("successfully completed requests")
            // console.log($('#devicelist').val(),selectedID );
            
            $("#devicelist li:first-child a").addClass("active");
                $(".selectIndex").html("SELECTED DEVICE:   " +selectedID+`<ion-icon name="caret-down-outline"></ion-icon>`);
        //    selectedID = $('#devID').val();
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if (urlParams.get('id')){
                selectedID = urlParams.get('id');
                
                $("#devicelist a").removeClass("active");
                $("#devicelist #device-"+selectedID ).addClass("active");
                
                $(".selectIndex").html("SELECTED DEVICE:   " +selectedID+`<ion-icon name="caret-down-outline"></ion-icon>`);
                // $('#DeviceID').val(selectedID)
            }

            $('#selectedDevice').html("Device Readings");
            // $('#selectedDevice').html(selectedID + " Device Readings");
            loadChartData();
            // charts.push =[chart1, chart2];
            // chartGen(chart1, "WaterLevel","%", "#ed3434");
            // chartGen(chart2, "AlertLevel","", "#ed3434");
            initMap()
            // chartGen(chart2, "Current","A", "#38C9C9");
            // chartGen(chart3, "Power","W","#49c920");
            // chartGen(chart4, "Energy","W","#be2596");
            
            // currentDataCount = chart1.data.length;
            

        }
    });
}


function loadChartData() {
    var tmp = [];
    var ajaxurl = 'resources/data/fetchData.php';
    // var data = {'device': selectedID};
    var data = {'DeviceID': selectedID};
    $.get(ajaxurl, data, function (response) {
    // Response div goes here.]
        var data = new Array();
        data = jQuery.parseJSON(response);
        // console.log(data);
   
        // return chartData;
    // }
    })
    .always(function(data) { 

        var jsondata = jQuery.parseJSON(data)
        if(jsondata.length > 0){

            var chartData = [];
            var maxValues = jsondata[jsondata.length - 1]
            console.log(maxValues);
            console.log(maxValues["AlertLevelMax"],maxValues["AlertLevelMin"]);
            // console.log(maxValues);
            jsondata.pop();
            jsondata.forEach((value, index) => {
                    // console.log(value)
                    chartData.push({
                        "date": parseInt(value.timeStamp * 1000), 
                        "WaterLevel": parseInt(value.WaterLevel), 
                        "AlertLevel": parseInt(value.AlertLevel)
                    }
                        )
            })
        }
console.log(jsondata)
        // generateChart("WaterLevel", "WaterLevel", "Water Level", jsondata, "WaterLevelColor", "Line")
        generateChart("AlertLevel", "AlertLevel", "Alert Level", jsondata, "AlertLevelColor", "AlertLevelTooltip",[0 ,3],"Column")
        generateChart("WaterLevel", "WaterLevel", "Water Level", jsondata, "WaterLevelColor", "WaterLevelTooltip",[maxValues["WaterLevelMin"] - 10,maxValues["WaterLevelMax"] + 10],"Line")
     
        // generateChart("OxygenSaturation", "OxygenSaturation", "Oxygen Saturation", jsondata)
        
        // document.getElementById("tabledashboard").style.display = "block";
        // document.getElementById("loader").style.display = "none";
        

    })
}