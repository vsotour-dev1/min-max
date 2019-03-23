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

class j05050jintour {
	function __construct($componentArgs)
		{
			
				$ePointFilepath = get_showtime('ePointFilepath'); // rusty
		
		
		
			include_once($ePointFilepath."functions.php"); // rusty
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		$ePointFilepath = get_showtime('ePointFilepath');
		
		$bkg = $componentArgs['bkg'];
		
		$field = $componentArgs['field'];
		$value = $componentArgs['value'];
		
		$last_field = jomresGetParam( $_REQUEST, 'field', '' );
		$update_on_these_fields = array();
		$update_on_these_fields[]= "arrivalDate";
		$update_on_these_fields[]= "arrival_period";
		$update_on_these_fields[]= "departureDate";
		$update_on_these_fields[]= "departure_period";
		$update_on_these_fields[]= "undefined";
		// rusty code		
	$update_on_these_fields[]= "guesttype";


		if (in_array($last_field,$update_on_these_fields))
			{
			
				
			$tourslist = jintour_build_available_tours_list_new($bkg);// rusty
		
		$price_table =$tourslist['price_table'];
			
			
			if ($tourslist['extra_div'])
				{
					
					
				$bkg->reset_choices_for_plugin("jintour");
				$retVal="".$tourslist['extra_div']."";
				// Now to clean up the retVal before it's passed back to jquery in the booking form.
				$retVal=str_replace('"','\"',$retVal);
				$retVal=str_replace("'","\'",$retVal);
		
				// get price table 
				$price_table ="".$price_table."";
				// Now to clean up the retVal before it's passed back to jquery in the booking form.
			//	$price_table =str_replace('"','\"',$price_table );
			//	$price_table =str_replace("'","\'",$price_table );
			
				
				}
			else
				$retVal="";
			

			echo 'populateDiv(\'jintour_third_party_extra_div\',"'.$retVal.'");';
			echo 'populateDiv(\'jintour_div\',"'.$price_table.'");';
			}
		}

	function getRetVals()
		{
		return null;
		}
	}
