
<?php
    session_start();
    include('resources/data/sessionLog.php');
    if(isset($_SESSION[$sessionName]['userID'])){
        userLog($sessionName, $_SESSION[$sessionName]['userID']);
    }
    else{
        
        userLog( $sessionName, null);
    }



?>

<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>POWER METER</title>

    <!--       Standard CSS-->
    <link rel="stylesheet" type="text/css" href="vendors/css/grid.css">
    <link rel="stylesheet" type="text/css" href="vendors/css/normalize.css">

    <!-- Customized CSS -->
    <link rel="stylesheet" type="text/css" href="resources/css/style.css?random=<?= uniqid() ?>">
    <link rel="stylesheet" type="text/css" href="resources/css/charts.css?random=<?= uniqid() ?>">
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300&display=swap" rel="stylesheet">
          
    <!--       ION ICONS -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
         
    <!--       FONT AWESOME-->
    <!-- <script src="https://kit.fontawesome.com/f0dbe7deea.js" crossorigin="anonymous"></script> -->
    
    <!-- JQUERY -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Customized JS -->
    <script src="resources/js/java.js?random=<?= uniqid() ?>"></script>
    <script src="resources/js/interact.js?random=<?= uniqid() ?>"></script>

        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

        
        <script src="//cdn.amcharts.com/lib/4/core.js"></script>
        <script src="//cdn.amcharts.com/lib/4/charts.js"></script>
        <script src="resources/js/maps.js"></script>
        <!--<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>-->
        
        <script src="//www.amcharts.com/lib/4/themes/material.js"></script>
</head>
<body>
    <div class="loader"></div>
    <div class="header">     
        <?php include('resources/html/header.php'); ?>
    </div>
    <div class="container with-header classic-container-bg">
        <div class="header-options">
            <h3 id="selectedDevice" class="page-title mb-0">ABC123</h3>
        </div>
        <div class="container-fluid chart-content">

        
        <div class="flex-row">
                <div class="card col span-3-of-5">
                    <div class="card-body">
                        <div class="card-header">
                            <h2 class="card-title">Alert Level</h2>
                        </div>
                        <div id="AlertLevel" class="section-cover chart-holder"></div>
                    </div>
                </div>

                <div class="card col span-2-of-5">
                    <div class="card-body">
                        <div class="card-header">
                            <h2 class="card-title">Device Location</h2>
                        </div>
                        <div id="map" class="section-cover"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h2 class="card-title">Water Level</h2>
                        </div>
                        <div id="WaterLevel" class="section-cover chart-holder"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

   
      <!-- DATE SELECTOR-->
      <div id="datePickerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>SELECT DATE RANGE</h2>
                <span class="close"  onclick="closeModal('datePickerModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="exportDateForm" >
                    <div class="textOnInput">
                        <label for="inputText" class="topLabel">Begin Date</label>
                        <input type="date" id="beginDate" name="beginDate" required>
                    </div>
                    <div class="textOnInput">
                        <label for="inputText" class="topLabel">End Date</label>
                        <input type="date" id="endDate" name="endDate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                  <input type="button" class="flex-buttons" value="CONFIRM" onclick="validateForm('exportDateForm')">
                    <input type="button" class="flex-buttons"  id="modalClose" value="CLOSE" onclick="closeModal('datePickerModal')">
            </div>
        </div>
    </div>

        <div class="floating-button" onmouseover="toggleInfo('show')" onmouseout="toggleInfo('hide')" onclick="showModal('datePickerModal')">
            <ion-icon name="download-outline" id="toggleBtn" class="add-btn" role="img" aria-label="add circle"></ion-icon>
        </div>
        <div id="info-content" class="hide">
            <p>Export Data.</p>
        </div>



<script> 

$( document ).ready(function() {
    getActiveDevices()
})

var charts = [];
var init = 0;
var selectedID;
var chart1 = am4core.create("WaterLevel", am4charts.XYChart); 
var chart2 = am4core.create("AlertLevel", am4charts.XYChart); 

// var chart2 = am4core.create("AlertLevelLevel", am4charts.XYChart); 
// var chart3 = am4core.create("LatitudeLevel", am4charts.XYChart); 
// var chart4 = am4core.create("LongitudeLevel", am4charts.XYChart); 
function chartGen(chart, dataLabel, seriesUnit, seriesColor, series){

    var ajaxurl = 'resources/data/fetchData.php';
    var data = {'device': selectedID};
    
    function getMaxVal() {
        var tmp = [];
        $.get(ajaxurl, data, function (response) {
        // Response div goes here.]
        var data = new Array();
        data = jQuery.parseJSON(response);

        if(data.length > 0){
            var chartlength = data.length - 2;
            tmp = {
                "WaterLevel":
                {
                    "min":data[chartlength + 1]["WaterLevelMax"],
                    "max":data[chartlength + 1]["WaterLevelMin"],
                    "last":data[chartlength]["WaterLevel"]
                },
                "AlertLevel":
                {
                    "min":data[chartlength + 1]["AlertLevelMax"],
                    "max":data[chartlength + 1]["AlertLevelMin"],
                    "last":data[chartlength]["AlertLevel"]
                },
                "Latitude":
                {
                    "min":data[chartlength + 1]["LatitudeMax"],
                    "max":data[chartlength + 1]["LatitudeMin"],
                    "last":data[chartlength]["Latitude"]
                },
                "Longitude":
                {
                    "min":data[chartlength + 1]["LongitudeMax"],
                    "max":data[chartlength + 1]["LongitudeMin"],
                    "last":data[chartlength]["Longitude"]
                }};
            data.pop();
            chart.data = data;
        }
        // else{
        //     exit;
        // }
    }).always(function() { 
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.grid.template.location = 0;
        dateAxis.skipEmptyPeriods = true; 
        dateAxis.renderer.minGridDistance = 50;
        dateAxis.dateFormats.setKey("minute", "MMM dd HH:mm");
        dateAxis.periodChangeDateFormats.setKey("minute", "MM/dd HH:mm"); 
        dateAxis.title.text = "Date";
        dateAxis.cursorTooltipEnabled = false;
        dateAxis.dataFields.dateX = "date";
        dateAxis.groupData = true;
        console.log(chart.data.length)
        if (chart.data.length >  1000){
            var groupCount = Math.ceil(chart.data.length / 10);
            // var groupCount = chart.data.length;
        }
        if (chart.data.length >  1000){
            var groupCount = Math.ceil(chart.data.length / 10);
            // var groupCount = chart.data.length;
        }
        else if (chart.data.length >  750){
            var groupCount = Math.ceil(chart.data.length / 5);
        }
        else if (chart.data.length >  500){
            var groupCount = Math.ceil(chart.data.length / 5);
        }
        else if (chart.data.length >  250){
            var groupCount = Math.ceil(chart.data.length / 5);
        }
        else if (chart.data.length >  100){
            var groupCount = Math.ceil(chart.data.length / 5);
        }
        else{
            var groupCount = chart.data.length;
        }
        dateAxis.groupCount = groupCount;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min =  tmp[dataLabel]["min"];
            valueAxis.max= tmp[dataLabel]["max"];
            valueAxis.title.text = dataLabel + " (" + seriesUnit + ")";
            valueAxis.cursorTooltipEnabled = false;
            
        var range = valueAxis.axisRanges.create();
        range.value = tmp[dataLabel]["last"];
        range.grid.stroke = am4core.color("#396478");
        range.grid.strokeOpacity = 0;
        range.label.inside = true;
        range.label.right = true;
        range.label.text = "Current Value: " + range.value;
        range.label.background.fill = seriesColor;
        range.label.background.opacity = 0.8;
        range.label.fill = am4core.color("#fff");
        range.label.padding(10, 15, 10, 15);
        
        
        // console.log("defined valueaxis",valueAxis)
        // console.log("defined range",range)
        createSeries(dataLabel, dataLabel, seriesColor, seriesUnit, valueAxis, dateAxis);
           
        chart.legend = new am4charts.Legend();
        chart.cursor = new am4charts.XYCursor();
       
      
        chart.svgContainer.autoResize = false;

    });
    }

    var maxVal = getMaxVal();

    function createSeries(field, name, color, unit, newAxis = am4charts.ValueAxis(), dateAxis) {
        
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = field;
        series.dataFields.dateX = "date";
        // series.dataFields.axisLabel = "axisLabel";
        series.name = name;
      
        series.tooltipText = "{date}: [bold]{valueY}"+unit+"[/]";
        series.strokeWidth = 2;
        
        series.stroke = am4core.color(color); 

        series.tooltip.getFillFromObject = false;
        series.tooltip.background.fill = am4core.color(color);
        return series;
}


}



    // setInterval(reloadTable, 10000);
    function reloadPage(){
        
        selectedID = $('#devID').val();
        window.location.href= "dashboard.php?devID="+selectedID;
    }
    function reloadTable(){
        
        $('#selectedDevice').html(selectedID + " Device Readings");
        $.get('resources/data/fetchData.php', {'device': selectedID}, function (response) {
        var data = new Array();
        data = jQuery.parseJSON(response);

        if(data.length > 0){
            data.pop();
            if (data.length != chart1.data.length){
                
                chart1.data = data;
                chart2.data = data;
                chart3.data = data;
                console.log(data)
                
                currentDataCount = chart1.data.length;
            }
        }
    });
    
    }
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCE8oHD9whXr5bIn0NGmGcJolt54iGdT24&callback=initMap&v=weekly" async></script> -->


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCE8oHD9whXr5bIn0NGmGcJolt54iGdT24" async></script>

</body>
</html>