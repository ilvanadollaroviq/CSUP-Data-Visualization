<?php

if (isset($_POST['startdate']) && isset($_POST['enddate'])){
    $date_timestamp1 = strtotime($_POST['startdate']);
    $date_timestamp2 = strtotime($_POST['enddate']);
}

if (isset($_POST['startdate']) && isset($_POST['enddate']) &&  $date_timestamp1<= $date_timestamp2) {
    $end = $_POST['enddate'];
    $start = $_POST['startdate'];
    $old_date_timestamp = strtotime($end);
    $enddate = date('d/m/Y', $old_date_timestamp);

    $old_date_timestamp = strtotime($start);
    $startdate = date('d/m/Y', $old_date_timestamp);


    $response = file_get_contents('http://213.149.113.86:8000/api/measurement_by_date/?node_id=4&start_date=' . $startdate . '&end_date=' . $enddate . '');
}
else if (isset($_POST['startdate'])){
    $start = $_POST['startdate'];
    $old_date_timestamp = strtotime($start);
    $startdate = date('d/m/Y', $old_date_timestamp);
    $today=date("d/m/Y");
    $response = file_get_contents('http://213.149.113.86:8000/api/measurement_by_date/?node_id=4&start_date=' . $startdate . '&end_date=' . $today . '');


}
else{
    $today=date("d/m/Y");
    $response = file_get_contents('http://213.149.113.86:8000/api/measurement_by_date/?node_id=4&start_date=' . $today . '&end_date=' . $today . '');


}
$res = json_decode($response, true);
$loop = count($res['data']);
$date = $res['data'][0]['timestamp'];
$oldlastdate = $res['data'][$loop - 1]['timestamp'];


$old_date_timestamp = strtotime($date);
$new_date = date('m/d/Y', $old_date_timestamp);

$old_date_timestamp = strtotime($oldlastdate);
$last_date = date('m/d/Y', $old_date_timestamp);
$dataPointss = array();
for ($x = 0; $x < $loop; $x++) {
    $old = $res['data'][$x]['timestamp'];
    $old_date_timestamp = strtotime($old);
    $new = date('m/d/Y H:i:s', $old_date_timestamp);
    $dataPointss[] = array("label" => $new, "y" => $res['data'][$x]['sensor_1_val']);
}
$dataPointss2 = array();
for ($x = 0; $x < $loop; $x++) {
    $old = $res['data'][$x]['timestamp'];
    $old_date_timestamp = strtotime($old);
    $new = date('m/d/Y H:i:s', $old_date_timestamp);
    $dataPointss2[] = array("label" => $new, "y" => $res['data'][$x]['sensor_2_val']);
}
$dataPointss3 = array();
for ($x = 0; $x < $loop; $x++) {
    $old = $res['data'][$x]['timestamp'];
    $old_date_timestamp = strtotime($old);
    $new = date('m/d/Y H:i:s', $old_date_timestamp);
    $dataPointss3[] = array("label" => $new, "y" => $res['data'][$x]['sensor_3_val']);

}

?>
<!DOCTYPE HTML>
<html>
<head>
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 6px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button4 {
            background-color: white;
            color: black;
            border: 2px solid #e7e7e7;
            border-radius: 12px;
        }

        .button4:hover {
            background-color: #e7e7e7;
            box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
        }
        .button5 {
            background-color: #666666;
            color: #ffffff;
            border: 2px solid #e7e7e7;
            border-radius: 12px;
        }

        .button5:hover {
            background-color: #7e7e7e;
            box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
        }

        .wrapper {
            display: flex;
            font-family: Georgia;

        }

        .left {
            flex: 0 0 20%;
        }

        .right {
            flex: 1;
        }
        .right {
            flex: 2;
        }

        input[type=text] {
            width: 20%;
            padding: 8px 15px;
            margin: 4px 0;
            box-sizing: border-box;
            border: 3px solid #ccc;
            -webkit-transition: 0.5s;
            transition: 0.5s;
            outline: none;
            font-family: "Georgia";
        }

        input[type=text]:focus {
            border: 3px solid #555;
        }

        select {
            width: 40%;
            height: 70%;
            padding: 20px 30px;
            margin: 6px 0;
            box-sizing: border-box;
            border: 4px solid #ccc;
            -webkit-transition: 0.5s;
            transition: 0.5s;
            outline: none;
            font-family: "Georgia";
        }

        select:focus {
            border: 3px solid #555;
        }
        body{


        }

    </style>

    <script>
        window.onload = function () {
            CanvasJS.addColorSet("Shades",
                [//colorSet Array


                    "#ee766e"
                ]);
            var chart = new CanvasJS.Chart("chartContainer", {
                colorSet: "Shades",
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Average Wind Speed <?php echo $new_date; ?>-<?php echo $last_date; ?> "
                },
                axisX: {
                    title: "Time",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "(1min) [m/s]",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPointss, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            var chart2 = new CanvasJS.Chart("chartContainer2", {
                colorSet: "Shades",
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Maximum Wind Speed <?php echo $new_date; ?>-<?php echo $last_date; ?> "
                },
                axisX: {
                    title: "Time",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "(5min) [m/s]",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPointss2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();
            var chart3 = new CanvasJS.Chart("chartContainer3", {
                colorSet: "Shades",
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Wind Direction <?php echo $new_date; ?>-<?php echo $last_date; ?> "
                },
                axisX: {
                    title: "Time",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "deg°",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPointss3, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart3.render();


        }


    </script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>
        $(function () {    // Makes sure the code contained doesn't run until
            //     all the DOM elements have loaded

            $('#colorselector').change(function () {
                $('.colors').hide();
                $('#' + $(this).val()).show();
            });

        });


    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RTEPMS (Real-Time Environmental Parameters Monitoring System)</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#startdate").datepicker();
        });
        $(function () {
            $("#enddate").datepicker();
        });

    </script>

</head>
<body>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<div class="wrapper">
    <div class="left">Wind info type
        <Select id="colorselector">
            <option value="blue">Wind Direction</option>
            <option value="red"> Average Wind Speed</option>
            <option value="yellow">Maximum Wind Speed</option>

        </Select>
    </div>
    <div class="right">
        <form method="post">From: <input type="text" id="startdate" name="startdate" value="<?php if(isset($start))echo $start; ?>" required>
            To: <input type="text" id="enddate" name="enddate" value="<?php if(isset($end))echo $end; ?>">
            <input  class="button button4" type="submit" value="Submit">

        </form>
    </div>
    <div class="rightt">
        <input type="button" class="button button5" onClick="Javascript:window.location.href = 'air.php';" value="Air" />
        <input type="button" class="button button5" onClick="Javascript:window.location.href = 'rainfall.php';" value="Rainfall"/>
    </div>
</div>

<br><br><br>


<div style="width:100%; " id="red" class="colors" style="display:none">
    <div id="chartContainer"></div>
</div>
<div style="width:100%;" id="yellow" class="colors" style="display:none">
    <div id="chartContainer2"></div>
</div>
<div style="width:100%;" id="blue" class="colors" style="display:none">
    <div id="chartContainer3"></div>
</div>


</body>

</html>