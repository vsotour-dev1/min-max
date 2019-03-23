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

class j06002jintour_tourprofiles
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
		
		$output['PAGETITLE']=jr_gettext('_JINTOUR_PROFILES_TITLE','_JINTOUR_PROFILES_TITLE');
		$output['GENERATEINFO']=jr_gettext('_JINTOUR_PROFILE_GENERATE_INFO','_JINTOUR_PROFILE_GENERATE_INFO');
		
		$output['HPROFILE_TITLE']= jr_gettext('_JINTOUR_PROFILE_TITLE','_JINTOUR_PROFILE_TITLE') ;
		$output['HDESCRIPTION']= jr_gettext('_JINTOUR_PROFILE_DESCRIPTION','_JINTOUR_PROFILE_DESCRIPTION') ;
		$output['HDAYS_OF_WEEK']= jr_gettext('DAYS','Days');
		$output['HPRICE_ADULTS']= jr_gettext('_JINTOUR_PROFILE_PRICE_ADULTS','_JINTOUR_PROFILE_PRICE_ADULTS') ;
		$output['HPRICE_KIDS']= jr_gettext('_JINTOUR_PROFILE_PRICE_KIDS','_JINTOUR_PROFILE_PRICE_KIDS') ;
		$output['HCHILDSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_KIDS','_JINTOUR_PROFILE_SPACES_KIDS') ;
		$output['HADULTSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_ADULTS','_JINTOUR_PROFILE_SPACES_ADULTS') ;
		
		$output['HSTART_DATE']=jr_gettext('_JINTOUR_PROFILE_START_DATE','_JINTOUR_PROFILE_START_DATE')  ;
		$output['HEND_DATE']= jr_gettext('_JINTOUR_PROFILE_END_DATE','_JINTOUR_PROFILE_END_DATE') ;
		$output['HREPEATING']=jr_gettext('_JINTOUR_PROFILE_REPEATING','_JINTOUR_PROFILE_REPEATING')  ;

		$jrtbar =jomres_getSingleton('jomres_toolbar');
		$jrtb  = $jrtbar->startTable();
		
		if (!jomres_cmsspecific_areweinadminarea())
			$jrtb .= $jrtbar->toolbarItem('cancel',JOMRES_SITEPAGE_URL,jr_gettext('_JRPORTAL_CANCEL','_JRPORTAL_CANCEL',false)); 
		else
			$jrtb .= $jrtbar->toolbarItem('cancel',JOMRES_SITEPAGE_URL_ADMIN,jr_gettext('_JRPORTAL_CANCEL','_JRPORTAL_CANCEL',false));
		$jrtb .= $jrtbar->toolbarItem('new',jomresURL(JOMRES_SITEPAGE_URL."&task=jintour_edit_profile"),'');

		$jrtb .= $jrtbar->endTable();
		$output['JOMRESTOOLBAR']=$jrtb;
		
		$defaultProperty=getDefaultProperty();
		$all_profiles = jintour_get_all_tour_profiles_new($defaultProperty); // rusty code
		//rusty codes
		

		//get all guest types
		$basic_guest_type_details = jomres_singleton_abstract::getInstance( 'basic_guest_type_details' );
		$basic_guest_type_details->get_all_guest_types($defaultProperty);
		
			foreach ($basic_guest_type_details->guest_types as $guest_type) {
			$gtypes[]= $guest_type['type'];
			}
			
	
		foreach($gtypes as $gt){
		$hprice .='			<th>'.$gt.' price</th>';
		 $hspaces .='	<th>'.$gt.' spaces</th>	';
		}
		
	
		$output['PRICE_LABEL']	=$hprice;
		$output['SPACES_LABEL']	=  $hspaces;
				
// rusty end
		if (!empty($all_profiles))
			{
			foreach ($all_profiles as $p)
				{
					
					// rusty code get specific date
				$r=array();
				$days = explode(",",$p['days_of_week']);
				$dow="";
				
				if ($p['specific_date'] == 0) {
				if ($days[0] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_MONDAY','_JOMRES_COM_MR_WEEKDAYS_MONDAY')." ";
				if ($days[1] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_TUESDAY','_JOMRES_COM_MR_WEEKDAYS_TUESDAY')." ";
				if ($days[2] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_WEDNESDAY','_JOMRES_COM_MR_WEEKDAYS_WEDNESDAY')." ";
				if ($days[3] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_THURSDAY','_JOMRES_COM_MR_WEEKDAYS_THURSDAY')." ";
				if ($days[4] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_FRIDAY','_JOMRES_COM_MR_WEEKDAYS_FRIDAY')." ";
				if ($days[5] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_SATURDAY','_JOMRES_COM_MR_WEEKDAYS_SATURDAY')." ";
				if ($days[6] == "1")
					$dow.=jr_gettext('_JOMRES_COM_MR_WEEKDAYS_SUNDAY','_JOMRES_COM_MR_WEEKDAYS_SUNDAY')." ";
				$r['DAY_OF_WEEK'] = $dow;
				}else{
					//rusty changes
					
					
				$r['DAY_OF_WEEK'] = implode(',',json_decode($p['calendar_dates']));	
				}
				if ($p['repeating']=="1")
					$r['REPEATING']=jr_gettext('_JOMRES_COM_MR_YES','_JOMRES_COM_MR_YES');
				else
					$r['REPEATING']=jr_gettext('_JOMRES_COM_MR_NO','_JOMRES_COM_MR_NO');
					
				$r['EDITLINK']=  '<a href="'.JOMRES_SITEPAGE_URL."&task=jintour_edit_profile&id=".$p['id'].'">'.$editIcon.'</a>';
				$r['GENERATELINK']=  '<a href="'.JOMRES_SITEPAGE_URL."&task=jintour_generate_tours&no_html=1&id=".$p['id'].'">'.$generateIcon.'</a>';
				
				$r['TITLE']=$p['title'];
				$spaces = json_decode($p['spaces_guest_types'], true);
				$space = Null;
				foreach ($spaces as $sp){
				$space .= '<td>'.$sp.'</td>';
									
					
				}
				// rusty spcaes values 
				$r['SPACES']	= $space;
				$spaces_table = json_decode($p['spaces_table'], true);
			
			
			foreach($spaces_table as $key=>$stable){
				foreach($gtypes as $gt){
			
				$sprice[strtolower ($gt)][]= $stable[strtolower ($gt)];
				}
				
				
			}
			$space_price='';
			
			
			foreach ($sprice as $spi){
				
				foreach ($spi as $sp) {
					if ($sp == '') $sp = 0;
					
					$sps[] = $sp;
					
				}
				
			
				$space_price .= '<td>'.implode(",<br />",$sps).'</td>';
				unset($sps);
			}
		
			 unset($sprice);
			
				$r['SPACE_PRICE']	=$space_price;
				
				$r['PRICE_ADULTS']	=$p['price_adults'];
				$r['PRICE_ADULTS']	=$p['price_adults'];
				$r['PRICE_KIDS']	=$p['price_kids'];
				$r['SPACES_ADULTS']	= $space['spaces_adult'];
				$r['SPACES_KIDS']		=$space['spaces_child'];

				$r['START_DATE']	=outputDate(str_replace("-","/",$p['start_date']));
				$r['END_DATE']		=outputDate(str_replace("-","/",$p['end_date']));
				
				$rows[]=$r;
				}
			}
		
		
		$pageoutput=array();
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot( $ePointFilepath.'templates'.JRDS.find_plugin_template_directory() );
		$tmpl->readTemplatesFromInput( 'jintours_tourprofiles.html');
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
	
