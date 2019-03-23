<?php
/**
* Jomres CMS Agnostic Plugin
* @author Woollyinwales IT <sales@jomres.net>
* @version Jomres 9 rusty 9.11.1
* @package Jomres
* @copyright	2005-2015 Woollyinwales IT
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project.
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( 'Direct Access to this file is not allowed.' );
// ################################################################

class j06002jintour_save_profile
	{
	function __construct()
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return 
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		
		$defaultProperty=getDefaultProperty();
		
		$profile_id 	= (int)jomresGetParam( $_REQUEST, 'id', 0 );
		$title 			= (string)jomresGetParam( $_REQUEST, 'title', '' );
		$description 	= (string)jomresGetParam( $_REQUEST, 'description', '' );
		$spaces_adults 		= (int)jomresGetParam( $_REQUEST, 'spaces_adults', 0 );
		$spaces_kids 		= (int)jomresGetParam( $_REQUEST, 'spaces_kids', 0 );
		// rusty 
			$spaces_guest_types	= $_REQUEST['spaces'];
			$spaces_guest_types = json_encode($spaces_guest_types);
			$spaces_table = json_encode($_REQUEST['spaces_table']);
			$specific_date			= jomresGetParam( $_REQUEST, 'specific_date', '' );
			$calendar_dates    =  (string)jomresGetParam( $_REQUEST, 'calendar_dates', '' );
	$calendar_dates = explode(',',$calendar_dates);

$calendar_dates=json_encode($calendar_dates);
		$spaces_adults_min 		= (int)jomresGetParam( $_REQUEST, 'spaces_adults_min', 0 );
		$spaces_adults_max 		= (int)jomresGetParam( $_REQUEST, 'spaces_adults_max', 0 );
		$spaces_kids_min 		= (int)jomresGetParam( $_REQUEST, 'spaces_kids_min', 0 );
		$spaces_kids_max 		= (int)jomresGetParam( $_REQUEST, 'spaces_kids_max', 0 );
		$spaces_elders_min 		= (int)jomresGetParam( $_REQUEST, 'spaces_elders_min', 0 );
		$spaces_elders_max 		= (int)jomresGetParam( $_REQUEST, 'spaces_elders_max', 0 );
			$price_elders 	= (float)jomresGetParam( $_REQUEST, 'price_elders', 0.0 );
	
		// rusty end
		$spaces 		= (int)jomresGetParam( $_REQUEST, 'spaces', 0 );
		$tax_rate 		= (int)jomresGetParam( $_REQUEST, 'taxrate', 0 );
		$start_date 	= JSCalConvertInputDates($_POST['validfrom']);
		$end_date 		= JSCalConvertInputDates($_POST['validto']);
		$monday 		= (int)jomresGetParam( $_REQUEST, 'monday', '' );
		$tuesday 		= (int)jomresGetParam( $_REQUEST, 'tuesday', '' );
		$wednesday 		= (int)jomresGetParam( $_REQUEST, 'wednesday', '' );
		$thursday 		= (int)jomresGetParam( $_REQUEST, 'thursday', '' );
		$friday 		= (int)jomresGetParam( $_REQUEST, 'friday', '' );
		$saturday 		= (int)jomresGetParam( $_REQUEST, 'saturday', '' );
		$sunday 		= (int)jomresGetParam( $_REQUEST, 'sunday', '' );
		$price_adults 	= (float)jomresGetParam( $_REQUEST, 'price_adults', 0.0 );
		$price_kids 	= (float)jomresGetParam( $_REQUEST, 'price_kids', 0.0 );
		$main_price	= (float)jomresGetParam( $_REQUEST, 'mainprice', 0.0 );
		
		
		$days_of_week = $monday.",".$tuesday.",".$wednesday.",".$thursday.",".$friday.",".$saturday.",".$sunday;
		
		if ($profile_id == 0)
			{
				
				// rusty code
			$query = "
				INSERT INTO #__jomres_jintour_profiles SET 
				`title`='".$title."',
					`description`='".$description."',
					`days_of_week`='".$days_of_week."',
					`price_adults`='".$price_adults."',
					`price_kids`='".$price_kids."',
					`spaces_adults`='".$spaces_adults."',
					`spaces_kids`='".$spaces_kids."',
					`start_date`='".$start_date."',
					`end_date`='".$end_date."',
					property_uid = ".$defaultProperty.",
					`tax_rate`='".$tax_rate."',
					`spaces_guest_types`='".$spaces_guest_types."',
					`spaces_table`='".$spaces_table."',
					`specific_date`='".$specific_date."',
					`calendar_dates`='".$calendar_dates."',
					`main_price`='".$main_price."'
					
				";
				
				
				
				//echo $query;exit;  end rusty
			}
		else
			{
			$query = "
				UPDATE #__jomres_jintour_profiles SET 
					`title`='".$title."',
					`description`='".$description."',
					`days_of_week`='".$days_of_week."', 
					`price_adults`='".$price_adults."', 
					`price_kids`='".$price_kids."', 
					`spaces_adults`='".$spaces_adults."',
					`spaces_kids`='".$spaces_kids."',
					`start_date`='".$start_date."',
					`end_date`='".$end_date."',
					`tax_rate`='".$tax_rate."', 
					`spaces_guest_types`='".$spaces_guest_types."', 
					`spaces_table`='".$spaces_table."', 
					`specific_date`='".$specific_date."',  
					`calendar_dates`='".$calendar_dates."', 
					`main_price`='".$main_price."' 
					
				WHERE id = ".$profile_id." AND property_uid = ".$defaultProperty."
				";
			}
		
		
	//	echo $query; 		exit;	
		if (!doInsertSql($query,$auditMessage)){
			
		
			trigger_error ("Unable to create tour profile, mysql db failure", E_USER_ERROR);
		}else{
			
			
			jomresRedirect( jomresURL(JOMRES_SITEPAGE_URL."&task=jintour"), $saveMessage );

		}
		
		
		
		}
		
		
	
	
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
	
