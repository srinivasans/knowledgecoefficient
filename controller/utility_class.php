<?php

class Utility
{

public static $Type=array("single"=>"radio","multiple"=>"checkbox");


public static function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   reset($array);
   fputcsv($df, array('ID','Name','Marks','Rank'));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

public static function download_send_headers($filename) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

public static function OpenOptionBox($key=2)
{
$html='<div class="span3" id="options">';
$html.='<div class="accordion" id="accordion'.$key.'">';
echo $html;
}

public static function CloseOptionBox()
{
$html='</div></div>';
echo $html;
}

public static function OpenQuestion()
{
$html='<div class="row-fluid pull-right row-wrap">
<div class="span9" id="question-wrapper">';
echo $html;
}

public static function OpenQuestionEvaluation()
{
$html='<div class="row-fluid pull-right row-wrap">
<div class="span11" id="question-wrapper">';
echo $html;
}

public static function AddSectionHead($section)
{
$html='<h4 id="section-head">'.$section.'</h4>';
echo $html;
}

public static function OpenGroup($group)
{
$html='<div class="row-fluid">
		<h5 id="group-head">'.$group.'</h5>
		<div class="span12" id="q_wrap">';
echo $html;
}

public static function AddQuestion($qno,$question,$options,$extra="")
{
if(!is_array($options))
{
$options=array();
}
$html='<table class="table table-striped table-bordered" id="'.$qno.'">
			<thead>
                <tr>
                  <td colspan="2">Q.'.$qno.') '.$extra.''.htmlspecialchars_decode($question).'</td>
                </tr>
              </thead>
              <tbody>';
$opt_arr=array("A","B","C","D","E");
for($i=0;$i<count($options);$i++)
{
if(isset($options[$i]))
{
$html.='<tr>
<td>'.$opt_arr[$i].') '.$options[$i]['Option'].'</td>';
}
else
{
$html.='<td>No Options available</td></tr>';
}
$i++;
if(isset($options[$i]))
{
$html.='<td>'.$opt_arr[$i].') '.$options[$i]['Option'].'</td></tr>';
}
else
{
$html.='<td></td></tr>';
}

}
$html.='</tbody></table>';
		
echo $html;
}

public static function CloseGroup()
{
$html='</div></div>';
echo $html;
}

public static function CloseQuestion()
{
$html='</div>';
echo $html;
}


public static function controlGroupText($name,$id,$type,$placeholder,$label,$class=NULL,$value=NULL)
{

if($class==NULL)
$class=array();
$extra="";
if($name=='quiz_name'||$name=='test_name')
{
$extra="maxlength=25";
}

$html='<div class="control-group">
    <label class="control-label" for="'.$id.'">'.$label.'</label>
    <div class="controls">
      <input type="'.$type.'" '.$extra.' name="'.$name.'" id="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'"/>
	  <span class="'.$id.'_validate" id="'.$id.'_validate"></span>
    </div>
  </div>';
  echo trim($html);
}


public static function controlGroupTextEditable($name,$id,$type,$placeholder,$label,$value,$class=NULL)
{
Utility::editableTextScript($id);

if($class==NULL)
$class=array();

$extra="";
if($name=='quiz_name'||$name=='test_name')
{
$extra="maxlength=25";
}

$html='<div class="control-group">
    <label class="control-label" for="'.$id.'">'.$label.'</label>
    <div class="controls">
      <input type="'.$type.'" '.$extra.' value="'.$value.'" name="'.$name.'" id="'.$id.'" class="text_editable" readonly="readonly" placeholder="'.$placeholder.'"/>
	  <span class="'.$id.'_validate" id="'.$id.'_validate"></span>
    </div>
  </div>';
  echo trim($html);
}

public static function controlGroupSelect($name,$id,$placeholder,$label,$options,$class=NULL)
{
if($options==NULL)
$options=array();
if($class==NULL)
$class=array();


$html='<div class="control-group">
    <label class="control-label" for="'.$id.'">'.$label.'</label>
    <div class="controls">
      <select name="'.$name.'" id="'.$id.'">';
	  foreach($options as $key=>$value)
	  {
	  $html.='<option value="'.$key.'">'.$value.'</option>';
	  }
	 $html.=' </select>
    </div>
  </div>';
  echo trim($html);
}


public static function controlGroupSelectEditable($name,$id,$placeholder,$label,$options,$val,$class=NULL)
{
if($options==NULL)
$options=array();
if($class==NULL)
$class=array();

Utility::editableSelectScript($id);
$html='<div class="control-group">
    <label class="control-label" for="'.$id.'">'.$label.'</label>
    <div class="controls">
      <select  class="text_editable"  name="'.$name.'" id="'.$id.'">';
	  foreach($options as $key=>$value)
	  {
	  $html.='<option value="'.$key.'" ';
	  if($key==$val)
	  {
	  $html.='selected="selected"';
	  }
	  $html.=' >'.$value.'</option>';
	  }
	 $html.=' </select>
    </div>
  </div>';
  echo trim($html);
}

public static function getcontrolGroupTextCode($name,$id,$type,$placeholder,$label,$class=NULL)
{

if($class==NULL)
$class=array();

$html="<div class='control-group'><label class='control-label' for='".$id."'>".$label."</label><div class='controls'><input type='".$type."' name='".$name."' id='".$id."' placeholder='".$placeholder."'/><span class='".$id."_validate' id='".$id."_validate'></span></div></div>";
  return trim($html);
}

public static function controlGroupSubmit($name,$id,$type,$placeholder,$label,$class=NULL)
{

if($class==NULL)
$class=array();

$html='<div class="control-group">
    <div class="controls">
      <button type="submit" class="btn '.join($class," ").'">'.$placeholder.'</button>
    </div>
  </div>';
  echo $html;
}



public static function AddMatchTable($name,$id,$qno,$rows=NULL,$cols=NULL,$rowvals=NULL,$colvals=NULL,$select=array())
{
$colored="";
		if(count($select)!=0 && $select[0]!="")
		{
		$colored='class="alert-success"';
		}

$html='<table class="table table-striped table-hover table-bordered">';
if($rows==NULL)
{
$rows=4;
}
if($cols==NULL)
{
$cols=4;
}

if($rowvals==NULL)
{
$rowvals=array("0"=>"P","1"=>"Q","2"=>"R","3"=>"S");
}

if($colvals==NULL)
{
$colvals=array("0"=>"A","1"=>"B","2"=>"C","3"=>"D");
}
if($cols!=count($colvals))
{
echo "Cols Values Mismatch Error";
return;
}

if(count($rowvals)!=$rows)
{
echo "Rows Values Mismatch Error";
return;
}

$html.='<tr><th id="head-'.$qno.'" '.$colored.'>Q'.$qno.'.</th>';
for($i=0;$i<$cols;$i++)
{
$html.='<th>('.$colvals[$i].')</th>';
}
for($i=0;$i<$rows;$i++)
{		
$html.='<tr>';
$html.='<th>('.$rowvals[$i].')</th>';
	for($j=0;$j<$cols;$j++)
	{
	$selected="";
	$val=$rowvals[$i].'-'.$colvals[$j];
	if(in_array($val,$select))
	{
	$selected='checked="checked"';
	}
	$html.='<td>
		<label class="checkbox inline">
		<input class="answer" type="checkbox" name="'.$name.'[]" '.$selected.' id="'.$id.'[]" value="'.$val.'">
		</label>
		</td>';
	}
$html.='</tr>';
}
$html.='</table>';
echo $html;
		
}


public static function AddMatchTableAns($name,$id,$qno,$rows=NULL,$cols=NULL,$rowvals=NULL,$colvals=NULL,$select=array(),$correct=1)
{
$colored="";

if($correct==1)
		{
		$colored='class="alert alert-success"';
		$label_class="label label-success";
		$label_add="";
		}
		else if($correct==2)
		{
		$colored='class="alert"';
		$label_class="label label-warning";
		$label_add=" (Unanswered)";
		}
		else
		{
		$colored='class="alert-error"';
		$label_class="label label-important";
		$label_add=" (Wrong)";
		}
		
		
$selected=false;

$html='<table class="table table-striped table-hover table-bordered">';
if($rows==NULL)
{
$rows=4;
}
if($cols==NULL)
{
$cols=4;
}

if($rowvals==NULL)
{
$rowvals=array("0"=>"P","1"=>"Q","2"=>"R","3"=>"S");
}

if($colvals==NULL)
{
$colvals=array("0"=>"A","1"=>"B","2"=>"C","3"=>"D");
}
if($cols!=count($colvals))
{
echo "Cols Values Mismatch Error";
return;
}

if(count($rowvals)!=$rows)
{
echo "Rows Values Mismatch Error";
return;
}

$html.='<tr><th id="head-'.$qno.'" '.$colored.'><span class="'.$label_class.'">'.$qno.$label_add.'</span></th>';
for($i=0;$i<$cols;$i++)
{
$html.='<th>('.$colvals[$i].')</th>';
}
for($i=0;$i<$rows;$i++)
{		
$html.='<tr>';
$html.='<th>('.$rowvals[$i].')</th>';
	for($j=0;$j<$cols;$j++)
	{
	$selected=false;
	$val=$rowvals[$i].'-'.$colvals[$j];
	if(in_array($val,$select))
	{
	$selected=true;
	}
	$html.='<td>
		<label class="checkbox inline">';
		if($selected==true)
		{
		$html.='<img src="/jee/img/tick_icon.png" />';
		}
		else
		{
		
		}
		
		$html.='</label>
		</td>';
	}
$html.='</tr>';
}
$html.='</table>';
echo $html;
		
}



public static function AddNumericalTable($name,$id,$qno,$values=NULL,$select=array())
{
if($values==NULL)
{
$values=array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9");
}
$opt=10;
if($opt!=count($values))
{
echo "Numerical Values Mismatch Error";
return;
}
if(!is_array($select))
{
$select=array();
}

$colored="";
		if(count($select)!=0  && $select[0]!="")
		{
		$colored='class="alert-success"';
		}

$selected="";
$html='<table class="table table-striped table-hover table-bordered ">
		<tr><th id="head-'.$qno.'" '.$colored.' rowspan="4">Q'.$qno.'.</th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th></tr>
		<tr>';
		for($i=0;$i<5;$i++)
		{
		$selected="";
		if(in_array($i,$select))
		{
		$selected=' checked="checked"';
		}
		$html.='<td>
		<label class="radio inline">
		<input type="radio" class="answer" name="'.$name.'" '.$selected.' id="'.$id.'" value="'.$values[$i].'">
		</label>
		</td>';
		}
$html.='</tr>';
$selected="";
$html.='<tr><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th></tr>
		<tr>';
		for($i=5;$i<$opt;$i++)
		{
		if(in_array($i,$select))
		{
		$selected=' checked="checked"';
		}
		$html.='<td>
		<label class="radio inline">
		<input type="radio" class="answer" name="'.$name.'" '.$selected.' id="'.$id.'" value="'.$values[$i].'">
		</label>
		</td>';
		}
$html.='</tr>
		</table>';
echo $html;
}


public static function AddNumericalTableAns($name,$id,$qno,$values=NULL,$select=array(),$correct=1)
{

if($values==NULL)
{
$values=array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9");
}
$opt=10;
if($opt!=count($values))
{
echo "Numerical Values Mismatch Error";
return;
}

$colored="";

if($correct==1)
		{
		$colored='class="alert alert-success"';
		$label_class="label label-success";
		$label_add="";
		}
		else if($correct==2)
		{
		$colored='class="alert"';
		$label_class="label label-warning";
		$label_add=" (Unanswered)";
		}
		else
		{
		$colored='class="alert-error"';
		$label_class="label label-important";
		$label_add=" (Wrong)";
		}
		
		
$selected=false;
$html='<table class="table table-striped table-hover table-bordered ">
		<tr><th id="head-'.$qno.'" '.$colored.' rowspan="4"><span class="'.$label_class.'">'.$qno.$label_add.'</span></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th></tr>
		<tr>';
		for($i=0;$i<5;$i++)
		{
		$selected=false;
		if(in_array($i,$select) && $select[0]!="")
		{
		$selected=true;
		}
		$html.='<td>
		<label class="radio inline">';
		if($selected==true)
		{
		$html.='<img src="/jee/img/tick_icon.png" />';
		}
		else
		{
		}
		$html.='</label>
		</td>';
		}
$html.='</tr>';
$selected=false;
$html.='<tr><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th></tr>
		<tr>';
		for($i=5;$i<$opt;$i++)
		{
		$selected=false;
		if(in_array($i,$select) && $select[0]!="")
		{
		$selected=true;
		}
		$html.='<td>
		<label class="radio inline">';
		if($selected==true)
		{
		$html.='<img src="/jee/img/tick_icon.png" />';
		}
		else
		{
		
		}
		$html.='</label>
		</td>';
		}
$html.='</tr>
		</table>';
echo $html;
}


public static function AddOptionsAccordion($parent,$collapseId,$heading,$class=array())
{
$html='<div class="accordion-group" id="accord_group'.$collapseId.'">
		<div class="accordion-heading">
			<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#'.$parent.'" data-target="#opt'.$collapseId.'">
			'.$heading.'
		</a>
	   </div>
    <div id="opt'.$collapseId.'" class="accordion-body collapse '.join($class," ").'">
      <div class="accordion-inner">';
	  
echo $html;
}

public static function StartOptionsTable($opt=NULL,$values=NULL)
{
if($opt==NULL)
{
$opt=4;
}
if($values==NULL)
{
$values=array("0"=>"A","1"=>"B","2"=>"C","3"=>"D");
}
if($opt!=count($values))
{
echo "Heading Count Mismatch Error";
return;
}

$html='<table class="table table-striped table-hover table-bordered"><tr><th></th>';
		for($i=0;$i<$opt;$i++)
		{
		$html.='<th>('.$values[$i].')</th>';
		}
$html.='</tr>';
echo $html;
}

public static function CloseTable()
{
$html='</table>';
echo $html;
}

public static function CloseAccordion()
{
$html='</div>
    </div>
  </div>';
echo $html;
}



public static function AddRow($name,$id,$qno,$type,$values=NULL,$opt=NULL,$select=array())
{
if($values==NULL)
{
$values=array("0"=>"A","1"=>"B","2"=>"C","3"=>"D");
}
if($opt==NULL)
{
$opt=4;
}
if($opt!=count($values))
{
echo "Radio Id Count Mismatch Error";
return;
}
$colored="";
		if(count($select)!=0  && $select[0]!="")
		{
		$colored='class="alert-success"';
		}

$selected="";

		$head_id='head-'.$qno;
$html='<tr><th id="'.$head_id.'" '.$colored.'>Q'.$qno.'.</th>';
for($i=0;$i<$opt;$i++)
{
		$html.='<td>';
		$html.='<label class="'.$type.' inline">';
		$selected="";
		if(in_array($values[$i],$select))
		{
		$selected=' checked="checked" ';
		}
		if($type=="radio")
		{
		$html.='<input class="answer" type="'.$type.'" name="'.$name.'" '.$selected.' id="'.$id.'" value="'.$values[$i].'">';
		}
		else if($type=="checkbox")
		{
		$html.='<input class="answer" type="'.$type.'" name="'.$name.'[]" '.$selected.' id="'.$id.'[]" value="'.$values[$i].'">';
		}
		$html.='</label>';
		$html.='</td>';
}		
$html.='</tr>';

echo $html;

}



public static function AddRowAns($name,$id,$qno,$type,$values=NULL,$opt=NULL,$select=array(),$correct=1)
{
if($values==NULL)
{
$values=array("0"=>"A","1"=>"B","2"=>"C","3"=>"D");
}
if($opt==NULL)
{
$opt=4;
}
if($opt!=count($values))
{
echo "Radio Id Count Mismatch Error";
return;
}

$colored="";
		if($correct==1)
		{
		$colored='class="alert alert-success"';
		$label_class="label label-success";
		$label_add="";
		}
		else if($correct==2)
		{
		$colored='class="alert"';
		$label_class="label label-warning";
		$label_add=" (Unanswered)";
		}
		else
		{
		$colored='class="alert-error"';
		$label_class="label label-important";
		$label_add=" (Wrong)";
		}
		$head_id='head-'.$qno;
$html='<tr><th id="'.$head_id.'" '.$colored.'><span class="'.$label_class.'">'.$qno.$label_add.'</span></th>';
for($i=0;$i<$opt;$i++)
{
		$html.='<td>';
		$html.='<label class="'.$type.' inline">';
		$selected=false;
		if(in_array($values[$i],$select))
		{
		$selected=true;
		}
		if($type=="radio")
		{
		if($selected==true)
		{
		$html.='<img src="/jee/img/tick_icon.png" />';
		}
		else
		{
		
		}
		
		}
		else if($type=="checkbox")
		{
		if($selected==true)
		{
		$html.='<img src="/jee/img/tick_icon.png" />';
		}
		else
		{
		
		}
		}
		$html.='</label>';
		$html.='</td>';
}		
$html.='</tr>';

echo $html;

}




public static function getColor($input)
{
$color='#FFFFFF';
switch($input)
{
case '1':$color='#F8B0DB';
break;
case '2':$color='#D3B6FA';
break;
case '3':$color='#B6C9FA';
break;
case '4':$color='#B6FAD3';
break;
case '5':$color='#DCFAB6';
break;
case '6':$color='#FAEBB6';
break;
case '7':$color='#FACCB6';
break;
}

return $color;
} 


public static function getDay($input)
{
$day='';
switch($input)
{
case '1':$day='Mon';
break;
case '2':$day='Tue';
break;
case '3':$day='Wed';
break;
case '4':$day='Thu';
break;
case '5':$day='Fri';
break;
case '6':$day='Sat';
break;
case '7':$day='Sun';
break;
}
return $day;
}

public static function inputEmailEditable($value,$id,$name,$placeholder,$class=NULL)
{
Utility::editableTextScript($id);
if($class==NULL)
{
$class="text_editable";
}
$html='<input type="email" readonly="readonly" value="'.$value.'" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" class="'.$class.'"/>';
echo $html;
}

public static function datePickerEditable($value,$id,$name,$class=NULL)
{
Utility::editableTextScript($id);
Utility::includeDateScriptEditable($id);
if($class==NULL)
{
$class="text_editable";
}

$html='<input type="text" value="'.$value.'" readonly="readonly" id="'.$id.'" class="'.$class.'" name="'.$name.'" value=""/>';
echo $html;
}


public static function dateTimePickerEditable($value,$id,$name,$class=NULL)
{
Utility::editableTextScript($id);
Utility::includeDateTimeScriptEditable($id);
if($class==NULL)
{
$class="text_editable";
}

$html='<input type="text" value="'.$value.'" readonly="readonly" id="'.$id.'" class="'.$class.'" name="'.$name.'" value=""/>';
echo $html;
}

public static function TimePickerEditable($value,$id,$name,$class=NULL)
{
Utility::editableTextScript($id);
Utility::includeTimeScriptEditable($id);
if($class==NULL)
{
$class="text_editable";
}

$html='<input type="text" value="'.$value.'" readonly="readonly" id="'.$id.'" class="'.$class.'" name="'.$name.'" value=""/>';
echo $html;
}


public static function inputEmail($id,$name,$placeholder,$class=NULL)
{
if($class==NULL)
{
$class="text";
}
$html='<input type="email" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" class="'.$class.'"/>';
echo $html;
}

public static function inputText($id,$name,$placeholder,$class=NULL,$script=NULL,$value="")
{
if($class==NULL)
{
$class="text";
}
if($script==NULL)
{
$script="";
}
$html='<input type="text" value="'.$value.'" name="'.$name.'" id="'.$id.'" '.$script.' placeholder="'.$placeholder.'" class="'.$class.'"/>';
echo $html;
}

public static function inputPassword($id,$name,$placeholder,$class=NULL)
{
if($class==NULL)
{
$class="text";
}
$html='<input type="password" value="" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" class="'.$class.'"/>';
echo $html;
}

public static function inputHidden($id,$name,$value,$class=NULL)
{
if($class==NULL)
{
$class="text";
}
$html='<input type="hidden" name="'.$name.'" id="'.$id.'" value="'.$value.'" class="'.$class.'"/>';
echo $html;
}

public static function inputTextArea($id,$name,$placeholder,$class=NULL)
{
if($class==NULL)
{
$class="text";
}
$html='<textarea name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" class="'.$class.'" style="resize:none;width:250px;" /></textarea>';
echo $html;
}

public static function includeMCEScript()
{
echo '<script type="text/javascript" src="/jee/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
$script='<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "latex,jbimages,autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		remove_script_host : false,
		document_base_url : "/jee/",
		
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselecttablecontrols,|,hr,|,sub,sup,|,emotions|,|,ltr,rtl,",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,link,unlink,anchor,image,help,code,jbimages,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage,latex",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,
		language:"en",

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",
		relative_urls:false,
		 setup : function(ed)
			{
			ed.onInit.add(function(ed)
			{
            ed.getDoc().body.style.fontSize = "12px";
			});
    },

});
</script>';
echo $script;
}


public static function inputTextAreaMCE($id,$name,$placeholder,$class=NULL)
{
if($class==NULL)
{
$class="text";
}
$html='<textarea name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" class="'.$class.'" style="resize:none;width:100%;" /></textarea>';
echo $html;
}

public static function label($for,$value,$required=NULL,$class=NULL)
{
if($class==NULL)
{
$class="";
}
if($required=="required")
{
$value.='<span style="color:#FF0000"> *</span>';
}
$html='<label style="cursor:pointer" class="'.$class.'" for="'.$for.'">'.$value.' : </label>';

echo $html;
}

public static function datePicker($name,$id,$class=NULL)
{

Utility::includeDateScript($id);
if($class==NULL)
{
$class="text";
}

$html='<input type="text" id="'.$id.'" class="'.$class.'" name="'.$name.'" value=""/>';
echo $html;
}


public static function dateTimePicker($name,$id,$class=NULL)
{

Utility::includeDateTimeScript($id);
if($class==NULL)
{
$class="text";
}

$html='<input type="text" id="'.$id.'" class="'.$class.'" name="'.$name.'" value=""/>';
echo $html;
}

public static function TimePicker($name,$id,$class=NULL)
{

Utility::includeTimeScript($id);
if($class==NULL)
{
$class="text";
}

$html='<input type="text" id="'.$id.'" class="'.$class.'" name="'.$name.'" value=""/>';
echo $html;
}


public static function dropDownList($id,$name,$type,$class=NULL,$input=NULL)
{
GLOBAL $objPDO;

$type_array=array("single"=>"Single Option","multiple"=>"Multiple Option","numerical"=>"Numerical","match"=>"Match");
if($class==NULL)
{
$class="text";
}
$list=array();
if($type=="type")
{
$list=$type_array;
}
else if(is_array($input))
{
$list=$input;
}
$html='<select id="'.$id.'" class="'.$class.'" name="'.$name.'" >';
foreach($list as $key=>$value)
{
if($key==$input)
{
$html.='<option selected="selected" value="'.$key.'">'.$value.'</option>'; 
}
else
{
$html.='<option value="'.$key.'">'.$value.'</option>'; 
}
}
$html.='</select>';
echo $html;
}

public static function getStudentSec($stu)
{
GLOBAL $objPDO;
require_once($_SERVER['DOCUMENT_ROOT'].'/cloud/model/student_section_class.php');
$section=new Studentsection($objPDO);
if($section->getByStudentId($stu)==true)
return true;
else
return false;

} 



public static function includeDateScript($id)
{
$script='

  <script type="text/javascript">

  $(document).ready(function() {
  $("#'.$id.'").datepicker();
  $( "#'.$id.'" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
  });

  </script>';

  echo $script;
}



public static function includeDateTimeScript($id)
{
$script='

  <script type="text/javascript">

  $(document).ready(function() {
  $("#'.$id.'").datetimepicker();
  $( "#'.$id.'" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
  });

  </script>';

  echo $script;
}


public static function includeTimeScript($id)
{
$script='

  <script type="text/javascript">

  $(document).ready(function() {
  $("#'.$id.'").timepicker();
  $( "#'.$id.'" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
  });

  </script>';

  echo $script;
}


public static function dropDownListEditable($val,$id,$name,$type,$class=NULL,$input=NULL,$textVal=NULL,$class_select=NULL)
{
Utility::editableSelectScript($id);
GLOBAL $objPDO;
include_once($_SERVER['DOCUMENT_ROOT'].'/cloud/model/subject_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/cloud/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/cloud/model/teacher_class.php');
$sec=new Section($objPDO);
$section=$sec->getSectionArray();
$tea=new Teacher($objPDO);
$teacher=$tea->getTeacherArray($input);
$sub=new Subject($objPDO);
$subject=$sub->getSubjectArray();
$numeric_level=array("select"=>"--Select--","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","10"=>"10","11"=>"11","12"=>"12");
$blood_group=array("select"=>"--Select--","A+"=>"A+","B+"=>"B+","O+"=>"O+","AB+"=>"AB+","A-"=>"A-","B-"=>"B-","O-"=>"O-","AB-"=>"AB-");
$category=array("select"=>"--Select--","OC"=>"General(OC)","SC"=>"SC","ST"=>"ST","OBC"=>"OBC","NRI"=>"NRI","Defence"=>"Defence");

if($class==NULL)
{
$class="text_editable";
}
$list=array();
if($type=="numeric_level")
{
$list=$numeric_level;
}
else if($type=="blood_group")
{
$list=$blood_group;
}
else if($type=="category")
{
$list=$category;
}
else if($type=="subject")
{
$list=$subject;
}
else if($type=="section")
{
$list=$section;
}
else if($type=="teacher")
{
$list=$teacher;
}
$textVal="";
if(array_key_exists($val,$list))
{
$textVal=$list[$val];
}
$html='<input type="text" readonly="readonly" id="'.$id.'" class="'.$class.'" value="'.$textVal.'"/>';
$html.='<select class="'.$class_select.'" style="display:none" id="'.$id.'"  name="'.$name.'" >';
foreach($list as $key=>$value)
{
if($key==$val)
{
$html.='<option class="'.$class_select.'" selected="selected" value="'.$key.'">'.$value.'</option>'; 
}
else
{
$html.='<option value="'.$key.'">'.$value.'</option>'; 
}
}
$html.='</select>';
echo $html;

}


public static function includeDateScriptEditable($id)
{
$script='

  <script type="text/javascript">

  $(document).ready(function() {
  $("#'.$id.'").dblclick(function(){
  $("#'.$id.'").datepicker();
  $( "#'.$id.'" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
  });
  });

  </script>';

  echo $script;
}


public static function includeDateTimeScriptEditable($id)
{
$script='

  <script type="text/javascript">

  $(document).ready(function() {
  $("#'.$id.'").dblclick(function(){
  $("#'.$id.'").datetimepicker();
  $( "#'.$id.'" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
  });
  });

  </script>';

  echo $script;
}


public static function includeTimeScriptEditable($id)
{
$script='

  <script type="text/javascript">

  $(document).ready(function() {
  $("#'.$id.'").dblclick(function(){
  $("#'.$id.'").timepicker();
  $( "#'.$id.'" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
  });
  });

  </script>';

  echo $script;
}


public static function radioButton($id,$name,$type,$class=NULL)
{
if($class=NULL)
$class="text";
$gender=array("male"=>"Male","female"=>"Female");
if($type=="gender")
{
$list=$gender;
}
$html="";
foreach($list as $key=>$value)
{
$html.='<span>&nbsp;&nbsp;'.$value.'&nbsp;&nbsp;</span>';
$html.='<input type="radio" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$key.'" />';
}

echo $html;

}

public static function radioButtonEditable($val,$id,$name,$type,$class=NULL)
{
Utility::editableRadioScript($id);
if($class==NULL)
$class="text_editable";
$gender=array("male"=>"Male","female"=>"Female");
if($type=="gender")
{
$list=$gender;
}
$html="";
$html.='<input type="text" id="'.$id.'" value="'.$val.'" class="'.$class.'" />';
foreach($list as $key=>$value)
{
$html.='<span id="'.$id.'" style="display:none">&nbsp;&nbsp;'.$value.'&nbsp;&nbsp;</span>';
if($key==$val)
{
$html.='<input type="radio" style="display:none" checked="checked" name="'.$name.'" id="'.$id.'" value="'.$key.'" />';
}
else
{
$html.='<input type="radio" style="display:none" name="'.$name.'" id="'.$id.'"  value="'.$key.'" />';
}
}

echo $html;

}

public static function checkBox($id,$name,$type=NULL,$checked=NULL)
{
$presence=array('present'=>'');
$same_as_above=array("same"=>"Same as Above");
$list=array("");
if($type=="same_as_above")
{
$list=$same_as_above;
}
else if($type=="presence")
{
$list=$presence;
}
$html="";
foreach($list as $key=>$value)
{
$html.='<span>&nbsp;&nbsp;'.$value.'&nbsp;&nbsp;</span>';
$html.='<input type="checkbox" id="'.$id.'" name="'.$name.'" ';
if($checked==1)
{
$html.='checked="checked"';
}
$html.=' value="'.$key.'" />';
}

echo $html;
}

public static function submitButton($id,$name,$value,$class=NULL)
{
if($class==NULL)
{
$class="submit_btn";
}

$html='<input type="submit" name="'.$name.'" id="'.$id.'" value="'.$value.'" class="'.$class.'" />';

echo $html;

}


public static function editableTextScript($id)
{
$script='<script type="text/javascript">';
$script.="$('document').ready(function(){
$('#".$id."').dblclick(function(){
$('#".$id."').attr('readonly',false);
$('#".$id."').removeClass('text_editable');
$('#".$id."').addClass('text');
});
});";

$script.="</script>";
echo $script;
}

public static function editableSelectScript($id)
{
$script='<script type="text/javascript">';
$script.="$('document').ready(function(){
$('input#".$id."').dblclick(function(){
$('select#".$id."').show();
$('input#".$id."').hide();
$('#".$id."').removeClass('text_editable');
$('#".$id."').addClass('text');
});
});";

$script.="</script>";
echo $script;
}

public static function editableRadioScript($id)
{
$script='<script type="text/javascript">';
$script.="$('document').ready(function(){
$('input#".$id."').dblclick(function(){
$('input#".$id."').show();
$('span#".$id."').show();
$('input[type=text]#".$id."').hide();
$('#".$id."').removeClass('text_editable');
});
});";

$script.="</script>";
echo $script;
}


public static function inputTextEditable($value,$id,$name,$placeholder,$class=NULL)
{
Utility::editableTextScript($id);
if($class==NULL)
{
$class="text_editable";
}
$html='<input type="text" readonly="readonly" value="'.$value.'" name="'.$name.'" id="'.$id.'" placeholder="'.$placeholder.'" class="'.$class.'"/>';
echo $html;
}


public static function inputTextAreaEditable($value,$id,$name,$placeholder,$class=NULL)
{
Utility::editableTextScript($id);
if($class==NULL)
{
$class="text_editable";
}
$html='<textarea name="'.$name.'"  id="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'" class="'.$class.'" style="resize:none;width:250px;" />'.$value.'</textarea>';
echo $html;
}

public static function addAnother($id,$value,$class=NULL)
{
if($class==NULL)
{
$class="simple_button";
}
$html='<div style="margin-left:10px;font-family:tahoma;font-size:12px;color:#334499;cursor:pointer" id="'.$id.'" class="'.$class.'" value="2" >'.$value.'</button>';
echo $html;

}

};

?>