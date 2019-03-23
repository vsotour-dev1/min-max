<?php
/**
* Jomres CMS Agnostic Plugin
* @author Woollyinwales IT <sales@jomres.net>
* @version Jomres 9 
* @package Jomres
* @copyright	2005-2015 Woollyinwales IT
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project.
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( 'Direct Access to this file is not allowed.' );
// ################################################################

class j16000jintour_tourprofiles
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
		
		$editIcon	='<IMG SRC="'.JOMRES_IMAGES_RELPATH.'jomresimages/small/EditItem.png" border="0" alt="editicon">';
		$generateIcon	='<IMG SRC="'.JOMRES_IMAGES_RELPATH.'next.png" border="0" alt="generateicon">';

		
		$output=array();
		$pageoutput=array();
		$rows = array();
		
		$output['PAGETITLE']=jr_gettext('_JINTOUR_PROFILES_TITLE','_JINTOUR_PROFILES_TITLE',false);
		$output['GENERATEINFO']=jr_gettext('_JINTOUR_PROFILE_GENERATE_INFO','_JINTOUR_PROFILE_GENERATE_INFO',false);
		
		$output['HPROFILE_TITLE']= jr_gettext('_JINTOUR_PROFILE_TITLE','_JINTOUR_PROFILE_TITLE',false) ;
		$output['HDESCRIPTION']= jr_gettext('_JINTOUR_PROFILE_DESCRIPTION','_JINTOUR_PROFILE_DESCRIPTION',false) ;
		$output['HDAYS_OF_WEEK']= jr_gettext('_JINTOUR_PROFILE_DAYS_OF_WEEK','_JINTOUR_PROFILE_DAYS_OF_WEEK',false) ;
		$output['HPRICE_ADULTS']= jr_gettext('_JINTOUR_PROFILE_PRICE_ADULTS','_JINTOUR_PROFILE_PRICE_ADULTS',false) ;
		$output['HPRICE_KIDS']= jr_gettext('_JINTOUR_PROFILE_PRICE_KIDS','_JINTOUR_PROFILE_PRICE_KIDS',false) ;
		$output['HCHILDSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_KIDS','_JINTOUR_PROFILE_SPACES_KIDS',false) ;
		$output['HADULTSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_ADULTS','_JINTOUR_PROFILE_SPACES_ADULTS',false) ;
		
		$output['HSTART_DATE']= jr_gettext('_JINTOUR_PROFILE_START_DATE','_JINTOUR_PROFILE_START_DATE',false) ;
		$output['HEND_DATE']= jr_gettext('_JINTOUR_PROFILE_END_DATE','_JINTOUR_PROFILE_END_DATE',false) ;

		$jrtbar =jomres_getSingleton('jomres_toolbar');
		$jrtb  = $jrtbar->startTable();
		
		$jrtb .= $jrtbar->toolbarItem('cancel',JOMRES_SITEPAGE_URL_ADMIN,jr_gettext('_JRPORTAL_CANCEL','_JRPORTAL_CANCEL',false));
		$jrtb .= $jrtbar->toolbarItem('new',jomresURL(JOMRES_SITEPAGE_URL_ADMIN."&task=jintour_edit_profile"),'');
		$jrtb .= $jrtbar->spacer();
		$jrtb .= $jrtbar->endTable();
		$output['JOMRESTOOLBAR']=$jrtb;
		
		$defaultProperty=0;
		$all_profiles = jintour_get_all_tour_profiles($defaultProperty);
		
		if (!empty($all_profiles))
			{
			foreach ($all_profiles as $p)
				{
				$r=array();
				$days = explode(",",$p['days_of_week']);
				$dow="";
				if ($days[0] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_MONDAY','_JOMRES_COM_MR_WEEKDAYS_MONDAY',false)." ";
				if ($days[1] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_TUESDAY','_JOMRES_COM_MR_WEEKDAYS_TUESDAY',false)." ";
				if ($days[2] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_WEDNESDAY','_JOMRES_COM_MR_WEEKDAYS_WEDNESDAY',false)." ";
				if ($days[3] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_THURSDAY','_JOMRES_COM_MR_WEEKDAYS_THURSDAY',false)." ";
				if ($days[4] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_FRIDAY','_JOMRES_COM_MR_WEEKDAYS_FRIDAY',false)." ";
				if ($days[5] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_SATURDAY','_JOMRES_COM_MR_WEEKDAYS_SATURDAY',false)." ";
				if ($days[6] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_SUNDAY','_JOMRES_COM_MR_WEEKDAYS_SUNDAY',false)." ";
				$r['DAY_OF_WEEK'] = $dow;
				
				$r['EDITLINK']=  '<a href="'.JOMRES_SITEPAGE_URL_ADMIN."&task=jintour_edit_profile&id=".$p['id'].'">'.$editIcon.'</a>';
				$r['GENERATELINK']=  '<a href="'.JOMRES_SITEPAGE_URL_ADMIN."&task=jintour_generate_tours&no_html=1&id=".$p['id'].'">'.$generateIcon.'</a>';
				
				$r['TITLE']=$p['title'];

				$r['PRICE_ADULTS']	=$p['price_adults'];
				$r['PRICE_KIDS']	=$p['price_kids'];
				$r['SPACES_ADULTS']		=$p['spaces_adults'];
				$r['SPACES_KIDS']		=$p['spaces_kids'];

				$r['START_DATE']	=str_replace("-","/",$p['start_date']);
				$r['END_DATE']		=str_replace("-","/",$p['end_date']);
				
				$rows[]=$r;
				}
			}
		
		
		$pageoutput=array();
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot( $ePointFilepath.'templates'.JRDS.find_plugin_template_directory() );
		$tmpl->readTemplatesFromInput( 'admin_jintours_tourprofiles.html');
		$tmpl->addRows( 'pageoutput',$pageoutput);
		$tmpl->addRows( 'rows',$rows);
		$tmpl->displayParsedTemplate();
		}
	
	
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
	
