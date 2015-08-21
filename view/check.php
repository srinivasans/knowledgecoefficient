<?php
include("../controller/utility_class.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Knowledge Coefficient</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap.min.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/style.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap-responsive.min.css"/>
<script type="text/javascript" src="/jee/js/jquery.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>

<script type="text/javascript">
$('document').ready(function(){
$('#elem').tooltip('show');
});
</script>
</head>

<body>
<?php
$headMenu=array("username"=>"Srinivasan","active"=>"test");
include("header.php");
?>
<div class="container-fluid" id="container-wrap">

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  
  <ul class="nav nav-tabs">
  
  <li class="active"><a href="#tab1" data-toggle="tab">akjsdhkjashdkjahskjdhads</a></li><li ><a href="#tab2" data-toggle="tab">Microbiology</a></li>  
  
  </ul>
  
  
  <div class="tab-content">
  
	<div class="tab-pane active" id="tab1">
	
	<?php
	Utility::OpenQuestion();
	Utility::AddSectionHead("Physics");
	Utility::OpenGroup("Single Answer");
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads"));
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads"));
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads"));
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads"));
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads"));
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads"));
	Utility::AddQuestion(1,"gdgsfrgsadkhjsgdsjhdjsagdkjasdhkjasdhasdasd",array("hgfshdgasda","sadasdasdsad","asddasdasdasd","asdasdads","askdasdh]ui"));
	Utility::CloseGroup();
	Utility::CloseQuestion();
	?>

<div class="span3">
<div class="accordion" id="accordion2">
	<?php
		Utility::AddOptionsAccordion("accordion2","multiple_choice","Multiple Choice",array("in"));
		Utility::StartOptionsTable();
		Utility::AddRow("Q1","Q1","1","checkbox");
		Utility::AddRow("Q2","Q2","2","checkbox");
		Utility::AddRow("Q3","Q3","3","checkbox");
		Utility::CloseTable();
		Utility::CloseAccordion();
		Utility::AddOptionsAccordion("accordion2","single_choice","Single Choice");
		Utility::StartOptionsTable();
		Utility::AddRow("Q1","Q1","1","radio");
		Utility::AddRow("Q2","Q2","2","radio");
		Utility::AddRow("Q3","Q3","3","radio");
		Utility::CloseTable();
		Utility::CloseAccordion();
		Utility::AddOptionsAccordion("accordion2","numerical","Numerical Answers");
		Utility::AddNumericalTable("Q1","Q1","1");
		Utility::AddNumericalTable("Q2","Q2","2");
		Utility::CloseAccordion();
		Utility::AddOptionsAccordion("accordion2","match","Match the Following");
		Utility::AddMatchTable("Q1","Q1","1");
		Utility::AddMatchTable("Q2","Q2","2");
		Utility::CloseAccordion();
	?>
        
     
  
</div>
</div>
</div>

    </div>
    <div class="tab-pane" id="tab2">
      <p>Howdy, I'm in Section 2.</p>
    </div>
    </div>
    </div>
    </div>
	
</body>
	
	<?php
  include("footer_static.php");
  ?>