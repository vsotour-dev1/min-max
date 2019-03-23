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

class j05030jintour {
	function __construct($bkg)
		{
			
			$ePointFilepath = get_showtime('ePointFilepath'); // rusty code
	

			include_once($ePointFilepath."functions.php"); // rusty code
			
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}

		$ePointFilepath = get_showtime('ePointFilepath');
		if ( isset($bkg->third_party_extras_private_data['jintour']['chosen_options']) )
			unset($bkg->third_party_extras_private_data['jintour']['chosen_options']);
		$bkg->reset_choices_for_plugin("jintour");

$tourslist = jintour_build_available_tours_list_new($bkg); // rusty code
		
	
	
		if ($tourslist)
			{
			$bkg->reset_choices_for_plugin("jintour");
			$retVal="".$tourslist."";
			}
		else
			$retVal="";
		$this->retVal="<td colspan=\"5\"><div id='jintour_third_party_extra_div'>".$retVal."</div></td>";
		}

	function getRetVals()
		{
		return $this->retVal;
		}
	}
