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

class j06002jintour_generate_tours
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
		
		include_once($ePointFilepath."functions.php");
		$defaultProperty=getDefaultProperty();
		
		$profile_id = (int)jomresGetParam( $_REQUEST, 'id', 0 );
		
		if ($profile_id > 0)
			{
			$profile = jintour_get_tour_profile_new($profile_id,$defaultProperty);// rusty
			if (!$profile)
				{
				echo "Error, cannot find profile";
				return;
				}
			$profile=$profile[$profile_id];
			}
		else
			return;
	
		
		$spaces_available= json_decode($profile['spaces_guest_types'], true);// rusty
		$spaces_prices = json_decode($profile['spaces_table'], true);// rusty
	
		$date_range=array();
		$unixStDate=strtotime($profile['start_date']);
		$unixEdDate=strtotime($profile['end_date']);
		$currDate = $unixStDate;
		while ($currDate <= $unixEdDate)
			{
			$date_range[]=$currDate;
			$currDate=strtotime("+1 day",$currDate);
			
			}
			
			// rusty code changes  week days
		if ($profile['specific_date'] == 0) { // rusty
		$valid_days_of_week=array();
		$days = explode(",",$profile['days_of_week']);
		
		if ($days[0] == "1")
			$valid_days_of_week[]=1;
		if ($days[1] == "1")
			$valid_days_of_week[]=2;
		if ($days[2] == "1")
			$valid_days_of_week[]=3;
		if ($days[3] == "1")
			$valid_days_of_week[]=4;
		if ($days[4] == "1")
			$valid_days_of_week[]=5;
		if ($days[5] == "1")
			$valid_days_of_week[]=6;
		if ($days[6] == "1")
			$valid_days_of_week[]=0;

		$tour_dates=array();
		foreach ($date_range as $date)
			{
			$day_of_week_this_date = date("w",$date);
			if (in_array($day_of_week_this_date,$valid_days_of_week))
				$tour_dates[]=$date;
			
			}
		}else{ // rusty
		$tour_dates=array();
		$date_pick = json_decode($profile['calendar_dates']);
			
		foreach ($date_pick as $dat)
			{
		
			
				$tour_dates[]=strtotime( str_replace('/', '-', $dat));
				
				
			}
			
		}// rusty end
		
		if (!empty($tour_dates))
			{

// rusty 
			$query = "INSERT INTO #__jomres_jintour_tours (title, description,price_adults,price_kids,prices,tourdate,spaces_available_adults,spaces_available,spaces_available_kids,spaces_min,spaces_max,property_uid,tax_rate,tour_profile_id) VALUES ";
						foreach ($tour_dates as $date)
							{
							
							
					//	foreach($spaces_prices as $prices){
							

							$query .=  '( ';
							$query .= "'".(string)$profile['title'] ."',";
							$query .= "'".(string)$profile['description'] ."',";
							$query .= "".(float)$profile['price_adults'] .",";
							$query .= "".(float)$profile['price_kids'] .",";
							$query .= "'".json_encode($spaces_prices) ."',"; // new requirement
							$query .= "'".date("Y-m-d",$date)."',";
							$query .= "".(int)$profile['spaces_adults'] .",";
							$query .= "'".$profile['spaces_guest_types']."',";
							$query .= "".(int)$profile['spaces_kids'] .",";
							$query .= "'".$prices['min']."',";
							$query .= "'".$prices['max']."',";
							$query .= "".$defaultProperty.",";
							$query .= "".(int)$profile['tax_rate'].",";
							$query .= "".(int)$profile['id']."";
							$query .=  ' ),';
					//	}
						
						
							}
							
			
						$query =  substr($query,0,strlen($query)-1);
						
									
						if (!doInsertSql($query,jr_gettext('_JINTOUR_TOUR_SAVE_AUDIT',jr_gettext('_JINTOUR_TOUR_SAVE_AUDIT','_JINTOUR_TOUR_SAVE_AUDIT',false))))
						{
							
			
				//	trigger_error ("Unable to create tours, mysql db failure", E_USER_ERROR);
					
						}else{
						jomresRedirect( jomresURL(JOMRES_SITEPAGE_URL."&task=jintour"),jr_gettext('_JINTOUR_TOUR_SAVE_MESSAGE','_JINTOUR_TOUR_SAVE_MESSAGE',false));
				
						
						
						}
			
			
			// rusty end
			}
			

		}
	
	
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
	
