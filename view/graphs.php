<?php
function drawGraph($title,$arCols,$data,$keys,$div,$type="BarChart",$width=500,$height=400)
{
$script='
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load("visualization", "1.0", {"packages":["corechart"]});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        ';
		foreach($arCols as $k=>$val)
		{
        $script.='data.addColumn("'.$val.'", "'.$k.'");';
		}
		
$script.='data.addRows([';
		foreach($data as $k=>$value)
		{
          $script.="['".$k."'";
		  foreach($keys as $a=>$key)
		  {
		  $script.=", ".$value[$key];
		  }
		  $script.="],";
		}
$script.=']);

        // Set chart options
        var options = {"title":"'.$title.'",
                       "width":'.$width.',
                       "height":'.$height.'};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.'.$type.'(document.getElementById("'.$div.'"));
        chart.draw(data, options);
      }
    </script>';
	echo $script;
}

?>
