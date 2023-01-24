
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
        <title>IOT FLOOD MONITORING SYSTEM</title>

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

    <script src="resources/js/maps.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

    
    <!-- AMCHARTS -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
                  <input type="button" class="flex-buttons" value="CONFIRM" onclick="validateSingleForm('exportDateForm')">
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


var charts = [];
var init = 0;
var selectedID;


function reloadPage(){
    
    selectedID = $('#devID').val();
    window.location.href= "dashboard.php?devID="+selectedID;
}
$( document ).ready(function() {
    getActiveDevices()
})

/*********************************** */

function generateChart(chartID, seriesName, seriesLabel, chartData, pointColor,seriesTooltip, yAxisBounds, chartType){
    console.log("generating chart",chartID, seriesName, seriesLabel, chartData ,yAxisBounds)
  
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new(chartID);


        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
        am5themes_Animated.new(root)
        ]);


        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX"
        }));
        // root.interfaceColors.set("grid", am5.color("#767676"));
// 
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
            behavior: "zoomXY",
            xAxis: xAxis
        }));
        cursor.lineY.set("visible", false);


        
        // var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
        // xRenderer.labels.template.setAll({
        //   rotation: -45,
        //   centerY: am5.p50,
        //   centerX: am5.p100,
        //   paddingRight: 15
        // });
    
            
        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xAxis = chart.xAxes.push(am5xy.GaplessDateAxis.new(root, {
        groupData: true,
        maxDeviation: 0,
        baseInterval: {
            timeUnit: "second",
            count: 30
        },
        // gridIntervals: [
        //     { timeUnit: "day", count: 1 },
        //     { timeUnit: "hour", count: 1},
        //     { timeUnit: "minute", count: 120},
        //     { timeUnit: "second", count: 5* 60}
        // ],
        renderer: am5xy.AxisRendererX.new(root, {}),
        tooltip: am5.Tooltip.new(root, {})
        }));
        xAxis.get("dateFormats")["day"] = "MM/dd";
        xAxis.get("periodChangeDateFormats")["day"] = "MMM";


        xAxis.get("dateFormats")["hour"] = "MM/dd hh:mm";
        xAxis.get("periodChangeDateFormats")["hour"] = "MM/dd hh:mm";


        xAxis.get("dateFormats")["minute"] = "MM/dd hh:mm";
        xAxis.get("periodChangeDateFormats")["minute"] = "MM/dd hh:mm";
        
        
        xAxis.get("dateFormats")["second"] = "smm:ss";
        xAxis.get("periodChangeDateFormats")["second"] = "mm:ss";
        
        
        xAxis.set("tooltip", am5.Tooltip.new(root, {
            forceHidden: true
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 1,
            min: yAxisBounds[0],
            max: yAxisBounds[1],
            renderer: am5xy.AxisRendererY.new(root, {})
        }));
        // yAxis.get("rendered").grid.template.setAll({
        //     strokeDasharray: [10, 5]
        // });
       
        yAxis.get("renderer").grid.template.setAll({
            disabled: false,
            strokeDasharray: [5, 3],
            stroke: am5.color("#555555")
            // visible: false
        });
        yAxis.set("tooltip", am5.Tooltip.new(root, {
            forceHidden: true
        }));

        var rangeDataItem = yAxis.makeDataItem({
            value: yAxisBounds[1]
        });

        var range = yAxis.createAxisRange(rangeDataItem);
        range.get("label").setAll({
            fill: am5.color(0xffffff),
            text: "Current Value: " + chartData[chartData.length - 1][seriesTooltip],
            inside:true,
            // marginLeft:30,
            paddingTop: 10,
            paddingBottom: 10,
            paddingLeft: 10,
            paddingRight: 10,
            centerX: 0,
            dx: 10,
            dy: 30,
            // position: absolute,
            // padding: (10, 15, 10, 15),
            background: am5.Rectangle.new(root, {
                fill: am5.color("#ff6384"),
                opacity: 0.9,
                cornerRadius: 0
            })
        });
     
        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        if(chartType == "Column"){
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: seriesLabel,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: seriesName,
                valueXField: "date",
                tooltip: am5.Tooltip.new(root, {
                    
                })
            }));
            // xAxis.renderer.cellStartLocation = 0.2;
            // categoryAxis.renderer.cellEndLocation = 0.8;
            
            series.columns.template.setAll({
                templateField: pointColor,
                strokeWidth: 2,
                width: am5.percent(20)
            });
        }
        else if(chartType == "Line"){
            var series = chart.series.push(am5xy.LineSeries.new(root, {
                name: seriesLabel,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: seriesName,
                valueXField: "date",
                // tooltipDate: "dateTime",
                tooltip: am5.Tooltip.new(root, {})
                // connect: false
            }));
            series.strokes.template.setAll({
                templateField: pointColor,
                strokeWidth: 2
            });
        }
        
        // Add scrollbar
        // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
        // chart.set("scrollbarX", am5.Scrollbar.new(root, {
        // orientation: "horizontal"
        // }));

        series.get("tooltip").label.set("text", "[bold]{name}[/]\n{DateTime}: {"+seriesTooltip+"}")
        series.data.setAll(chartData);
        

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear(1000);
        chart.appear(1000, 100);

    }); // end am5.ready()
}
/*************************************** */




</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCE8oHD9whXr5bIn0NGmGcJolt54iGdT24&callback=initMap&v=weekly" async></script> -->


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCE8oHD9whXr5bIn0NGmGcJolt54iGdT24" async></script>

</body>
</html>