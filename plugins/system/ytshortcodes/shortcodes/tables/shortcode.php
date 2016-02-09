<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function tablesYTShortcode($atts,$content = null)
{
	extract(ytshortcode_atts(array(
		"type" =>'',
		"cols" =>'',
		"background"=>'#fff',
		"color"  =>'#000',
		"width" =>'100%',
		"height"=>'100%',
		"row_color"=>'',
		"column_color"=>''
	),$atts));
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/tables/css/table.css",'text/css',"screen");
	//row
	$cols = ltrim ($cols,',');
	$cols = explode('|',$cols);
	$newcols = array();
	foreach ($cols as $col) $newcols[] = trim($col);
	//column
	$content = explode('|',$content);
	$newcontent = array();
	foreach ($content as $con) $newcontent[] = trim($con);
	//row color
	$row_color = explode(',',$row_color);
	$row_Number='';
	$row_NumberColor='';
	if(count($row_color)>1)
	{
		for($i=0; $i<count($row_color);$i++)
		{
			$row_Number = $row_color[0];
			$row_NumberColor = $row_color[1];
		}
	}
	//th color
	$column_color = explode(',',$column_color);
	$column_Number = '';
	$column_NumberColor = '';
	if(count($column_color)>1)
	{
		for($i=0; $i<count($column_color);$i++)
		{
			$column_Number = $column_color[0];
			$column_NumberColor = $column_color[1];
		}
	}
	//table
	$tables ="<div class='table-responsive'>";
	$tables .= "<table class='table table-".$type."' style='width:".$width."; height:".$height."; background:".$background." '>";
	$tables .="<tr ".(($row_Number=='1') ? 'style="background:'.$row_NumberColor.';color:'.$color.'"' : '' ).">";
	for($t=0; $t<count($newcols);$t++)
	{
		$tables .="<th ".(($column_Number== $t+1) ? 'style="background:'.$column_NumberColor.';color:'.$color.'"' : '' ).">".$newcols[$t]."</th>";
	}
	$tables .="</tr>";
	$tr = ceil(count($newcontent)/count($newcols));
	for($i=0;$i<$tr;$i++)
	{
		$tables .="<tr ".(($row_Number==($i+2)) ? 'style="background:'.$row_NumberColor.';color:'.$color.'"' : '' ).">";
		for($j=($i*count($newcols));$j<(count($newcols)*($i+1));$j++)
		{
			if($j <count($newcontent))
			{
				$stringArray=explode(',',$newcontent[$j]);
				$color1='';
				$background='';
				if(isset($stringArray[1]))
				{
					$color1 = 'color:'.$stringArray[1].';';

				}
				if(isset($stringArray[2]))
				{
					$background ='background:'.$stringArray[2];
				}
				$tables .="<td ".(($column_Number+($i*count($newcols))== $j+1) ? 'style="background:'.$column_NumberColor.';color:'.$color.';'.$color1.' '.$background.'"' : 'style="'.$color1.' '.$background.'"' ).">".$stringArray[0]."</td>";
			}
			else
			{
				$tables .="<td ".(($column_Number+($i*count($newcols))== $j+1) ? 'style="background:'.$column_NumberColor.';color:'.$color.'"' : '' )."></td>";
			}
		}
		$tables .="</tr>";
	}
	$tables .="</table>";
	$tables .="</div>";
	return $tables;
}
?>