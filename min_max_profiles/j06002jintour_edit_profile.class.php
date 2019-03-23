<?php
/**
* Jomres CMS Agnostic Plugin
* @author Woollyinwales IT <sales@jomres.net>
* @version Jomres 9  rust 9.11.1
* @package Jomres
* @copyright	2005-2015 Woollyinwales IT
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project.
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( 'Direct Access to this file is not allowed.' );
// ################################################################

class j06002jintour_edit_profile
	{
	function __construct()
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return 
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		$ePointFilepath = get_showtime('ePointFilepath');
		$eLiveSite = get_showtime('eLiveSite');
		$thisJRUser=jomres_getSingleton('jr_user');
		if (!$thisJRUser->userIsManager)
			return;

// rusty 
$ePointFilepath = get_showtime('ePointFilepath');
	if (file_exists($ePointFilepath.'language'.JRDS.get_showtime('lang').'.php')) {
			require_once($ePointFilepath.'language'.JRDS.get_showtime('lang').'.php');
	}		else
			{
			if (file_exists($ePointFilepath.'language'.JRDS.'en-GB.php'))
				require_once($ePointFilepath.'language'.JRDS.'en-GB.php');
			}
		
			
			
			


		
			include_once($ePointFilepath."functions.php"); // end rusty
		$profile_id = (int)jomresGetParam( $_REQUEST, 'id', 0 );
		$defaultProperty=getDefaultProperty();
		
		$jrportal_taxrate = jomres_singleton_abstract::getInstance( 'jrportal_taxrate' );
			
		$output=array();
		$pageoutput=array();
		
		if ($profile_id > 0)
			{
			$profile = jintour_get_tour_profile_new($profile_id,$defaultProperty); // rusty
	
	
			if (!$profile)
				{
				echo "Error, cannot find profile";
				return;
				}
			$profile=$profile[$profile_id];
			$output['TAXRATEDROPDOWN'] = $jrportal_taxrate->makeTaxratesDropdown( $profile['tax_rate']);
		
			$output['MAINPRICE'] = $profile['main_price']; // rusty
			}
		else
			{
				
			$output['HD']= 'checked';  // rusty
			$profile['title'] = "Changeme";
			$profile['description'] = "Changeme";
			$profile['days_of_week'] = "1,1,1,1,1,1,1";
			$profile['price_adults'] = "20";
			$profile['price_kids'] = "10";
			// rusty codes
			$profile['spaces_adults_min'] = 1;
			$profile['spaces_kids_min'] = 1;
			$profile['spaces_adults_max'] = 10;
			$profile['spaces_kids_max'] = 10;
			$profile['spaces_elders_min'] =1;
			$profile['spaces_elders_min'] =10;
			// rusty codes end
			$profile['start_date'] = "";
			$profile['end_date'] = "";
			$output['TAXRATEDROPDOWN'] = $jrportal_taxrate->makeTaxratesDropdown( 0 );
			$output['MAINPRICE'] = 0; // rusty 
			}

		
		$output['PAGETITLE']=jr_gettext('_JINTOUR_PROFILES_TITLE','_JINTOUR_PROFILES_TITLE');
		$output['HPROFILE_TITLE']= jr_gettext('_JINTOUR_PROFILE_TITLE','_JINTOUR_PROFILE_TITLE') ;
		$output['HDESCRIPTION']= jr_gettext('_JINTOUR_PROFILE_DESCRIPTION','_JINTOUR_PROFILE_DESCRIPTION') ;
		$output['HDAYS_OF_WEEK']= jr_gettext('_JINTOUR_PROFILE_DAYS_OF_WEEK','_JINTOUR_PROFILE_DAYS_OF_WEEK') ;
		$output['HPRICE_ADULTS']= jr_gettext('_JINTOUR_PROFILE_PRICE_ADULTS','_JINTOUR_PROFILE_PRICE_ADULTS') ;
		$output['HPRICE_KIDS']= jr_gettext('_JINTOUR_PROFILE_PRICE_KIDS','_JINTOUR_PROFILE_PRICE_KIDS') ;
		$output['HADULTSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_ADULTS','_JINTOUR_PROFILE_SPACES_ADULTS') ;
		// rusty code
		$output['HELDERSPACES']= jr_gettext('_JOMRES_PROFILE_SPACES_ELDERS','_JOMRES_PROFILE_SPACES_ELDERS') ;
		$output['HPRICE_ELDERS']= jr_gettext('_JOMRES_PROFILE_PRICE_ELDERS','_JOMRES_PROFILE_PRICE_ELDERS') ;
		
		// end rusty codes
		$output['HCHILDSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_KIDS','_JINTOUR_PROFILE_SPACES_KIDS') ;
		$output['HTAXCODE']= jr_gettext('_JRPORTAL_TAXRATES_CODE','_JRPORTAL_TAXRATES_CODE') ;
		$output['HSTART_DATE']= jr_gettext('_JINTOUR_PROFILE_START_DATE','_JINTOUR_PROFILE_START_DATE') ;
		$output['HEND_DATE']= jr_gettext('_JINTOUR_PROFILE_END_DATE','_JINTOUR_PROFILE_END_DATE') ;
		$output['HREPEATING']= jr_gettext('_JINTOUR_PROFILE_REPEATING','_JINTOUR_PROFILE_REPEATING') ;

		$output['DESCRIPTION_INFO'] = jr_gettext('_JINTOUR_PROFILE_DESCRIPTION_INFO','_JINTOUR_PROFILE_DESCRIPTION_INFO');
		$output['KIDS_INFO'] = jr_gettext('_JINTOUR_PROFILE_PRICE_KIDS_INFO','_JINTOUR_PROFILE_PRICE_KIDS_INFO');

		$output['ID']=$profile_id ;
		$output['TITLE']=$profile['title'];
		$output['DESCRIPTION']=$profile['description'];

		$validfrom = str_replace("-","/",$profile['start_date']);
		$validto =str_replace("-","/",$profile['end_date']);
		
		$output['VALIDFROM']=generateDateInput("validfrom",$validfrom);
		$output['VALIDTO']=generateDateInput("validto",$validto);
		
		// rusty changes
		$output['SPACES_ELDER_MIN']=jomresHTML::integerSelectList( 1,500,1, 'spaces_elders_min','class="inputbox" size="1"', $profile['spaces_elders_min']);
		$output['SPACES_ELDER_MAX']=jomresHTML::integerSelectList( 1,500,1, 'spaces_elders_max','class="inputbox" size="1"', $profile['spaces_elders_max']);
		//$output['SPACES_ELDER']=jomresHTML::integerSelectList( 1,500,1, 'spaces_elders_max','class="inputbox" size="1"', $profile['spaces_elders_max']);
		
		if ($profile['specific_date'] ==1) {
			
			$output['HD'] = '';
			$output['SD'] = 'checked';
		}else{
			$output['HD'] = 'checked';
			$output['SD'] = '';
			
		}
		
		$defaultProperty = (int)getDefaultProperty();
		
		$mrConfig = getPropertySpecificSettings();

		//get all guest types
		$basic_guest_type_details = jomres_singleton_abstract::getInstance( 'basic_guest_type_details' );
		$basic_guest_type_details->get_all_guest_types($defaultProperty);
		$output_spaces ='';
		$price_spaces = '';
		
		$price_input_spaces_javascript='';
		$spaces_guest_types =  (array) json_decode($profile['spaces_guest_types']);
	$spaces_table =   json_decode($profile['spaces_table'], true);
	

	$price_input_spaces='';
	$gtypes=array('min','max');
		foreach ($basic_guest_type_details->guest_types as $guest_type) {
			$gtypes[]= $guest_type['type'];
			$guest_spaces=jomresHTML::integerSelectList( 1,500,1, 'spaces[spaces_'.strtolower($guest_type['type']).']','class="inputbox" size="1"', $spaces_guest_types['spaces_'.strtolower($guest_type['type'])]);
		$output_spaces .= '<div class="form-group">
	<label class="col-md-2 control-label" for="spaces_adults">'.$guest_type['type'].' spaces</label>
	<div class="col-md-1" style="text-align: center;
    padding-left;
    width: 50px;">
		<div style="padding: 5px;font-weight: bold;"></div>
	</div>
	<div class="col-md-1">
		'.$guest_spaces.'
	</div>
	
</div>';
$price_spaces .= '<div class="col-md-1 table_row">'.$guest_type['type'].' '.jr_gettext('PRICES','PRICES').' 	</div>';
$price_input_spaces_javascript .= "<div class='col-md-1 table_row_1'> <input name='spaces_table[\"+row_idi+\"][".strtolower($guest_type['type'])."]' id='".strtolower($guest_type['type'])."_spaces_\"+row_idi+\"' class='space_row' /></div>";


		}
	
		$lastKey = end(array_keys($spaces_table));
		if(!empty($spaces_table)){
foreach ( $spaces_table as $key=>$spaces_tab){
	$price_input_spaces .= '<div class="row_'.$key.'" id="row_'.$key.'"><div class="col-md-1 table_row_1 first_row"> <span onclick=\'myFunction('.$key.')\' class="minus_img" id="button_remove" data-row="'.$key.'"></span></div>';
	$price_input_spaces_no_html .='<div class="row_'.$key.'" id="row_'.$key.'">';
//	foreach ($spaces_tab as $kv=>$spac){
		
		foreach ($gtypes as $guest_type) {
	$kv=strtolower($guest_type);
$price_input_spaces .= '
<div class="col-md-1 table_row_1"> 	<input value="'.$spaces_table[$key][$kv].'" 
name="spaces_table['.$key.']['.$kv.']" id="'.$kv.'_spaces_1" 
class="space_row"/> 	</div>';
	
$price_input_spaces_no_html .= 	'<div class="col-md-1 table_row_1"> '.$spaces_table[$key][$kv].' 	</div>';


	}
$price_input_spaces .= '</div>';
$price_input_spaces_no_html .='</div>';
if ($lastKey != $key) {
	
	$price_input_spaces .= '<div class="clear" ></div>';
	$price_input_spaces_no_html .='<div class="clear" ></div>';
	
}
if ($lastKey == $key){
	$price_input_spaces .= '<div class="button_add col-md-2" id="button_add" data-row="'.$key.'"> Add New Row </div>';
	
	
	}

}

}else{
	$price_input_spaces .= '<div class="row_1" id="row_1"> <div class="col-md-1 table_row_1 first_row"> <span onclick=\'myFunction(1)\' class="minus_img" id="button_remove" data-row="1"></span></div>';
	$price_input_spaces_no_html .='<div class="row_1" id="row_1">';
	foreach ($gtypes as $guest_type) {
	$kv=strtolower($guest_type);
$price_input_spaces .= '
<div class="col-md-1 table_row_1"> 	<input value="" 
name="spaces_table[1]['.$kv.']" id="'.$kv.'_spaces_1" 
class="space_row"/> 	</div>';
	
$price_input_spaces_no_html .= 	'<div class="col-md-1 table_row_1"> '.$spaces_table[1][$kv].' 	</div>';


	}
	$price_input_spaces .= '</div>';
$price_input_spaces_no_html .='</div>';

	$price_input_spaces_no_html .='<div class="clear" ></div>';
	$price_input_spaces .= '<div class="button_add col-md-2" id="button_add" data-row="1"> Add New Row </div>';
}


	
		
		
		
		
		$output['PRICE_SPACES'] =$price_spaces;
		$output['PRICE_INPUT_SPACES_JAVASCRIPT'] = $price_input_spaces_javascript;
		$output['PRICE_INPUT_SPACES'] =$price_input_spaces;
		$output['PRICE_TABLE_SPACES'] = $price_input_spaces_no_html;
		$output['OUTPUT_SPACES'] = $output_spaces;
		$output['SPACES_ADULTS_MIN']=jomresHTML::integerSelectList( 1,500,1, 'spaces_adults_min','class="inputbox" size="1"', $profile['spaces_adults_min']);
		$output['SPACES_ADULTS']=jomresHTML::integerSelectList( 1,500,1, 'spaces_adults','class="inputbox" size="1"', $profile['spaces_adults']);
		
		$output['SPACES_KIDS_MIN']=jomresHTML::integerSelectList( 1,500,1, 'spaces_kids_min','class="inputbox" size="1"', $profile['spaces_kids_min']);
		$output['SPACES_KIDS']=jomresHTML::integerSelectList( 1,500,1, 'spaces_kids','class="inputbox" size="1"', $profile['spaces_kids']);
		$output['PRICE_ELDERS']=(float)$profile['price_elders'];
		
		// rusty changes
		$output['PRICE_ADULTS']=(float)$profile['price_adults'];
		$output['PRICE_KIDS']=(float)$profile['price_kids'];
		$output['MIN']=jr_gettext('MIN','MIN');
		$output['MAX']=jr_gettext('MAX','MAX');
		$output['SPACES']=jr_gettext('SPACES','SPACES');
			$output['PRICECLASS']=jr_gettext('PRICECLASS','PRICECLASS');
		$output['SPECIFICDATE']=jr_gettext('SPECIFICDATE','SPECIFICDATE');	
			
			
		$output['MONDAY']= jr_gettext('_JOMRES_COM_MR_WEEKDAYS_MONDAY','_JOMRES_COM_MR_WEEKDAYS_MONDAY');
		$output['TUESDAY']=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_TUESDAY','_JOMRES_COM_MR_WEEKDAYS_TUESDAY');
		$output['WEDNESDAY']=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_WEDNESDAY','_JOMRES_COM_MR_WEEKDAYS_WEDNESDAY');
		$output['THURSDAY']=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_THURSDAY','_JOMRES_COM_MR_WEEKDAYS_THURSDAY');
		$output['FRIDAY']=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_FRIDAY','_JOMRES_COM_MR_WEEKDAYS_FRIDAY');
		$output['SATURDAY']=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_SATURDAY','_JOMRES_COM_MR_WEEKDAYS_SATURDAY');
		$output['SUNDAY']=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_SUNDAY','_JOMRES_COM_MR_WEEKDAYS_SUNDAY');
		$output['SELECTED_DATE'] = jr_gettext('SELECTED_DATE','SELECTED_DATE');
		$output['MONDAY_CHECKED']="";
		$output['TUESDAY_CHECKED']="";
		$output['WEDNESDAY_CHECKED']="";
		$output['THURSDAY_CHECKED']="";
		$output['FRIDAY_CHECKED']="";
		$output['SATURDAY_CHECKED']="";
		$output['SUNDAY_CHECKED']="";
		$output['_MAIN_PRICE'] = jr_gettext('_MAIN_PRICE','_MAIN_PRICE');
		$blam = explode (",",$profile['days_of_week']);
		if (!empty($blam))
			{
			
			if ($blam[0] == "1")
				$output['MONDAY_CHECKED']=" checked ";
			if ($blam[1] == "1")
				$output['TUESDAY_CHECKED']=" checked ";
			if ($blam[2] == "1")
				$output['WEDNESDAY_CHECKED']=" checked ";
			if ($blam[3] == "1")
				$output['THURSDAY_CHECKED']=" checked ";
			if ($blam[4] == "1")
				$output['FRIDAY_CHECKED']=" checked ";
			if ($blam[5] == "1")
				$output['SATURDAY_CHECKED']=" checked ";
			if ($blam[6] == "1")
				$output['SUNDAY_CHECKED']=" checked ";
			}
		// rusty code changes
$output['TD']=date('d/m/');
$cal= $profile['calendar_dates'];
$output['CALENDAR_DATES']="'" . implode("','",json_decode($profile['calendar_dates'])) . "'";;
$output['CALENDAR_DATES'] = str_replace(' ', '', $output['CALENDAR_DATES']);

if ($cal !='') {
	$output['ADDDATE'] = "addDates: [".$output['CALENDAR_DATES']."],";
}else{
	$output['ADDDATE'] = '';
	
} 




		$jrtbar =jomres_getSingleton('jomres_toolbar');
		$jrtb  = $jrtbar->startTable();
		
		$jrtb .= $jrtbar->toolbarItem('cancel',jomresURL(JOMRES_SITEPAGE_URL."&task=jintour"),"");
		$jrtb .= $jrtbar->toolbarItem('save','','',true,'jintour_save_profile');
		if ($profile_id>0)
			$jrtb .= $jrtbar->toolbarItem('delete',jomresURL(JOMRES_SITEPAGE_URL."&task=delete_jintour_profile&no_html=1&popup=1&id=".$profile_id),'');
		$jrtb .= $jrtbar->endTable();
		$output['JOMRESTOOLBAR']=$jrtb;
		$output['JOMRES_SITEPAGE_URL']=JOMRES_SITEPAGE_URL;
		
		$pageoutput=array();
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		
		$tmpl->setRoot( $ePointFilepath.'templates'.JRDS.find_plugin_template_directory() );
		
		
		$show = (string)jomresGetParam( $_REQUEST, 'show');
		
	
		if ($_REQUEST['show'] == 'no_html' ) {
		$tmpl->readTemplatesFromInput( 'jintours_tourprofile_table.html');
		}else{
			
			
			$tmpl->readTemplatesFromInput( 'jintours_edit_tourprofile.html');
		}
		$tmpl->addRows( 'pageoutput',$pageoutput);
		$tmpl->displayParsedTemplate();
		
		jomres_cmsspecific_addheaddata("javascript",JOMRES_ROOT_DIRECTORY.'/remote_plugins/min_max_profiles/js/','jquery-ui.multidatespicker.js');
			jomres_cmsspecific_addheaddata("css",JOMRES_ROOT_DIRECTORY.'/remote_plugins/min_max_profiles/js/','jquery-ui.multidatespicker.css');
				jomres_cmsspecific_addheaddata("css",JOMRES_ROOT_DIRECTORY.'/remote_plugins/min_max_profiles/css/','custom.css');
		}
	
	
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
	
