<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function chartsYTShortcode($atts,$content = null)
{
	extract(ytshortcode_atts( array(
		'type' =>'',
		'width'=>'100',
		'labels'=>'',
		'data'  =>'',
		'label_name'=>'',
		'color'  =>'',
		'highlight'=>'',
		'fill_color'=>'',
		'stroke_color'=>'',
		'point_color'=>'',
	),$atts));
	$idcanvas = uniqid('canvas_').rand().time();
	$script ='';
	$return ='<div class="yt-clearfix" style="width:'.$width.'%">
			<div>
				<canvas id="'.$idcanvas.'" height="450" width="600"></canvas>
			</div>
		</div>';
	JHtml::_('jquery.framework');
	JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/charts/js/Chart.min.js");
	$labelsArray = explode(',',trim($labels));
	$labelsArr = '';
	$str='';
	$name_str='';
	foreach($labelsArray as $item )
	{
		$labelsArr .= "\"".$item."\",";
	}

	$dataArr = '';
	if($data !='')
	{
		$dataArr = explode('|',$data);
	}

	$labelArr='';
	if($label_name !='')
	{
		$labelArr = explode('|',$label_name);
	}
	$fill_colorArr ='';
	if($fill_color !='')
	{
		$fill_colorArr = explode('|', $fill_color);
	}
	$stroke_colorArr ='';
	if($stroke_color !='')
	{
		$stroke_colorArr = explode('|', $stroke_color);
	}
	$point_colorArr ='';
	if($point_color !='')
	{
		$point_colorArr = explode('|', $point_color);
	}
	//TH1
	if(strtolower($type)=='doughnut' || strtolower($type)=='pie' || strtolower($type)=='polar')
	{
		if($data !='')
		{
			$dataArr = explode(',',$data);
		}
		$colorArr = explode(',',$color);
		$highlightArr = explode(',',$highlight);
		$label =explode(',',$labelsArr);
		$i=0;
		if(count($dataArr) == count($colorArr) && count($colorArr) == count($highlightArr) && count($highlightArr) == count($labelsArray))
		{
			foreach($dataArr as $item)
			{
				$str .= "{ value:$item,color:\"$colorArr[$i]\",highlight:\"$highlightArr[$i]\",label:\"$labelsArray[$i]\"},";
				$i++;
			}
		}
		else
		{
			$return .= yt_alert_box('Charts not work, Please select the correct source and settings.', 'warning');
		}
	}
	//TH2
	else
	{
		$datasets = '';

		if(count($dataArr) == count($labelArr) && count($dataArr) == count($fill_colorArr)  && count($dataArr) == count($stroke_colorArr) && count($dataArr) == count($point_colorArr) && count($dataArr) >1)
		{
			for($i=0;$i<count($dataArr); $i++)
			{
				$datasets .= '{ label: "'.$labelArr[$i].'",
							fillColor: "'.$fill_colorArr[$i].'",
							strokeColor: "'.$stroke_colorArr[$i].'",
							pointColor: "'.$point_colorArr[$i].'",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#000",
							pointHighlightStroke: "rgba(220,220,220,1)",
							data: ['.$dataArr[$i].']},';
			}
		}

		$str='labels : ['.rtrim($labelsArr,',').'],
			datasets : [
				'.$datasets.'
			]';
	}
	switch(strtolower($type))
	{
		case 'line':
			$script .='var lineChartData = {
					'.$str.'
				}

			function charts_line(){
				var ctx'.$idcanvas.' = document.getElementById("'.$idcanvas.'").getContext("2d");
				window.myLine = new Chart(ctx'.$idcanvas.').Line(lineChartData, {
					responsive: true

				});
			}
			charts_line();';
		break;
		case 'bar':
			$script .='var barChartData = {
						'.$str.'
					}
					function charts_bar(){
						var ctx = document.getElementById("'.$idcanvas.'").getContext("2d");
						window.myBar = new Chart(ctx).Bar(barChartData, {
							responsive : true
						});
					}
					charts_bar();';

				break;
		case 'doughnut':
		$script .='var doughnutData = [
							'.$str.'
						];
						function charts_doughnut() {
							var ctx = document.getElementById("'.$idcanvas.'").getContext("2d");
							window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
						};
						charts_doughnut();';
		break;
		case 'pie':
		$script .='var PieData = [
							'.$str.'
						];
						function charts_pie(){
							var ctx = document.getElementById("'.$idcanvas.'").getContext("2d");
							window.myDoughnut = new Chart(ctx).Pie(PieData, {responsive : true});
						};
						charts_pie();';
		break;
		case 'polar':
		$script .='var polarData = [
							'.$str.'
						];
						function charts_polar(){
							var ctx = document.getElementById("'.$idcanvas.'").getContext("2d");
							window.myDoughnut = new Chart(ctx).PolarArea(polarData, {responsive : true});
						};
						charts_polar();';
		break;
		case 'radar':
		$script .='var radarChartData = {
				'.$str.'
			}
			function charts_bar(){
				var ctx = document.getElementById("'.$idcanvas.'").getContext("2d");
				window.myBar = new Chart(ctx).Radar(radarChartData, {
					responsive : true
				});
			}
			charts_bar();';
		break;
	}
	$html = '<script>';
	$html .= $script;
	$html .= '</script>';
	return $return.$html;
}
?>