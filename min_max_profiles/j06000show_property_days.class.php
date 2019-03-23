<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@jomres.net>
 *
 * @version Jomres 9.9.19
 *
 * @copyright	2005-2018 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class j06000show_property_days
{
    public function __construct($componentArgs)
    {
        // Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
        $MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;
            $this->shortcode_data = array(
                'task' => 'show_property_days',
                'info' => '_JOMRES_SHORTCODES_06000SHOW_PROPERTY_DAYS',
                'arguments' => array(0 => array(
                        'argument' => 'property_uid',
                        'arg_info' => '_JOMRES_SHORTCODES_06000SHOW_PROPERTY_DAYS_ARG_PROPERTY_UID',
                        'arg_example' => '4',
                        )
                    )
                );

            return;
        }
        $output = array();
        $this->retVals = '';

        if (isset($componentArgs[ 'property_uid' ])) {
            $property_uid = (int)$componentArgs[ 'property_uid' ];
        } else {
			$property_uid = (int)jomresGetParam($_REQUEST, 'property_uid', 0);
        }
		
		if ($property_uid == 0) {
            return;
        }

        if (!user_can_view_this_property($property_uid)) {
            return;
        }

        if (isset($componentArgs[ 'output_now' ])) {
            $output_now = $componentArgs[ 'output_now' ];
        } else {
            $output_now = true;
        }

        $current_property_details = jomres_singleton_abstract::getInstance('basic_property_details');
        $current_property_details->gather_data($property_uid);
        
        jr_import('jomres_markdown');
        $jomres_markdown = new jomres_markdown();
		$days_array = json_decode($current_property_details->property_days); 
		
	//	print_r ($days_array );exit;
		$days ='<div class="bt-accordion bt-accordion-plus " data-active-first="1"><p></p>';
		$idk = 0;
		foreach ($days_array as $key=>$day) {
			$day = (array)$day;
			
			
			
			foreach ($day as $k=>$d){
				
				
			$days .= '<div class="bt-spoiler">	<div class="bt-spoiler-title"> <div class="day-box swingimage"> <label class="title">DAY</label> <label class="day">'.$d->day_title.'</label>    <span class="bt-spoiler-collapse"></span></div></div>';
$days .= '<div class="bt-spoiler-content" style="display: none;">';


foreach ($d as $h){
	
	
$days.= '<div class="ac_rows">';


if (!empty($h->activity_fileselect)){
	$days.= '

<!-- Modal -->
  <div class="modal fade" id="myModal_'.$idk.'" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
          <img class="p_width" src="images/jomres/'.$h->activity_fileselect.'" />
      </div>
      
    </div>
  </div>

';
	$days.= '<div class="act_left_img" data-toggle="modal" data-target="#myModal_'.$idk.'">';
$days.= '<img class="file_img" src="images/jomres/'.$h->activity_fileselect.'" /> </div>';

}

$days.= '<div class="act_left">'.html_entity_decode(rawurldecode($h->activity)).'</div><div class="act_right">   '.html_entity_decode(rawurldecode($h->activity_act_description)).' </div><div style="clear:both;"></div>';
$idk++;

$days.= '</div>';
}
$days.= '</div></div>';
			}	
		}
		//print_r (json_decode($current_property_details->property_days)); 		exit;;
		
		$days .='</div>';
//$days .=' <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>  ';

        $output['PROPERTY_DAYS'] = jomres_cmsspecific_parseByBots($jomres_markdown->get_markdown($days));

        $pageoutput = array($output);
        $tmpl = new patTemplate();
       
		  $plugin_dir = JOMRESCONFIG_ABSOLUTE_PATH.JOMRES_ROOT_DIRECTORY.JRDS.'remote_plugins'.JRDS.'min_max_profiles';
		$tmpl->setRoot( $plugin_dir.JRDS."templates".JRDS.'bootstrap3' );
		
        $tmpl->addRows('pageoutput', $pageoutput);
        $tmpl->readTemplatesFromInput('show_property_days.html');
        $template = $tmpl->getParsedTemplate();
        if ($output_now) {
            echo $template;
        } else {
            $this->retVals = $template;
        }
    }

    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return $this->retVals;
    }
}
