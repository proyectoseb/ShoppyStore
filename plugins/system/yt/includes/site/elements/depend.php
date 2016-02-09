<?php
   /**
    * @package YT Framework
    * @author Smartaddons http://www.Smartaddons.com
    * @copyright Copyright (c) 2009 - 2014 Smartaddons
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

class JFormFieldDepend extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'depend';
	protected function getInput(){
		
		$func 	= (string)$this->element['function'] ? (string)$this->element['function'] : '';
		$value 	= $this->value ? $this->value : (string) $this->element['default'];

		if (substr($func, 0, 1) == '@'){
			$func = substr($func, 1);
			if (method_exists($this, $func)) {
				return $this->$func();
			}
		} else {
			$subtype = ( isset( $this->element['subtype'] ) ) ? trim($this->element['subtype']) : '';
			if (method_exists ($this, $subtype)) {
				return $this->$subtype ();
			}
		}
		return; 
	}
	function radio(){ 
		preg_match_all('/jform\\[([^\]]*)\\]/', $this->name, $matches);
		$group_name = 'jform';
		if(isset($matches[1]) && !empty($matches[1])):
		
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				arr = new Array();
				<?php 
				$option = $this->element->children();
				$i = 0;
				foreach ($this->element->children() as $option):
					$elms = preg_replace('/\s+/', '', (string)$option[0]);
				?>
					arr['<?php echo $i; ?>'] = new Array('<?php echo $option['value']; ?>', '<?php echo $elms?>');
				<?php
				$i++;
				endforeach; ?>
				YTDepend.radio('#jform_params_<?php echo $option[0]['for']; ?>', arr);
			});
		</script>
		<?php
		endif;
	}
	
	function radio2(){ 
		preg_match_all('/jform\\[([^\]]*)\\]/', $this->name, $matches);
		$group_name = 'jform';
		if(isset($matches[1]) && !empty($matches[1])):
		
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				arr = new Array();
				<?php 
				$option = $this->element->children();
				$i = 0;
				foreach ($this->element->children() as $option):
					$elms = preg_replace('/\s+/', '', (string)$option[0]);
				?>
					arr['<?php echo $i; ?>'] = new Array('<?php echo $option['value']; ?>', '<?php echo $elms?>');
				<?php
				$i++;
				endforeach; ?>
				YTDepend.radio2('#jform_params_<?php echo $option[0]['for']; ?>', arr);
			});
		</script>
		<?php
		endif;
	}
} 