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

class j06002jintour_manager_list_tours
	{
	function __construct($componentArgs)
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
			
		$editIcon	='<IMG SRC="'.JOMRES_IMAGES_RELPATH.'jomresimages/small/ListBookings.png" border="0" alt="editicon">'; // 9.9.19
		$deleteIcon	='<IMG SRC="'.JOMRES_IMAGES_RELPATH.'jomresimages/small/WasteBasket.png" border="0" alt="deleteicon">'; // 9.9.19
		$publishedIcon = '<IMG SRC="'.JOMRES_IMAGES_RELPATH.'jomresimages/small/Tick.png" border="0" alt="publishedicon">'; // 9.9.19
		$unPublishedIcon = '<IMG SRC="'.JOMRES_IMAGES_RELPATH.'jomresimages/small/Cancel.png" border="0" alt="unpublishedicon">'; // 9.9.19
		
		$output=array();
		$pageoutput=array();
		$rows = array();
		
		$output['PAGETITLE']=jr_gettext('_JINTOUR_PROFILES_TITLE','_JINTOUR_PROFILES_TITLE');
		$output['GENERATEINFO']=jr_gettext('_JINTOUR_PROFILE_GENERATE_INFO','_JINTOUR_PROFILE_GENERATE_INFO');
		
		$output['HPROFILE_TITLE']= jr_gettext('_JINTOUR_TOUR_TITLE','_JINTOUR_TOUR_TITLE') ;
		$output['HDESCRIPTION']= jr_gettext('_JINTOUR_PROFILE_DESCRIPTION','_JINTOUR_PROFILE_DESCRIPTION') ;
		$output['HDAYS_OF_WEEK']= jr_gettext('_JINTOUR_PROFILE_DAYS_OF_WEEK','_JINTOUR_PROFILE_DAYS_OF_WEEK') ;
		$output['HPRICE_ADULTS']= jr_gettext('_JINTOUR_PROFILE_PRICE_ADULTS','_JINTOUR_PROFILE_PRICE_ADULTS') ;
		$output['HPRICE_KIDS']= jr_gettext('_JINTOUR_PROFILE_PRICE_KIDS','_JINTOUR_PROFILE_PRICE_KIDS') ;
		$output['HADULTSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_ADULTS','_JINTOUR_PROFILE_SPACES_ADULTS') ;
		$output['HCHILDSPACES']= jr_gettext('_JINTOUR_PROFILE_SPACES_KIDS','_JINTOUR_PROFILE_SPACES_KIDS') ;
		$output['HDATE']= jr_gettext('_JINTOUR_TOUR_DATE','_JINTOUR_TOUR_DATE') ;
		$output['HAVLSPACES']= jr_gettext('_JINTOUR_TOUR_SPACES_CURRENTLY_AVAILABLE','_JINTOUR_TOUR_SPACES_CURRENTLY_AVAILABLE') ;
		$output['_JINTOUR_PROFILES_TITLE_LIST']= jr_gettext('_JINTOUR_PROFILES_TITLE_LIST','_JINTOUR_PROFILES_TITLE_LIST',false) ;

		$defaultProperty=getDefaultProperty();

		if ( isset ( $componentArgs['tours' ] ) ) {
			$all_tours = $componentArgs['tours' ];
			
		}
		else{
			$all_tours = jintour_get_all_tours_new($defaultProperty); // rusty
		
		
		}

            $price_spaces = '';
            $gtypes_p=array('min','max');


            $min = jr_gettext('MIN','MIN');
           $max =  jr_gettext('MAX','MAX');

            $price_spaces .= '<div class="col-md-1 table_row">'.$min.' 	</div>';
            $price_spaces .= '<div class="col-md-1 table_row">'.$max.' 	</div>';
            //rusty codes
		//get all guest types
		$basic_guest_type_details = jomres_singleton_abstract::getInstance( 'basic_guest_type_details' );
		$basic_guest_type_details->get_all_guest_types($defaultProperty);
		
			foreach ($basic_guest_type_details->guest_types as $guest_type) {
			$gtypes[]= $guest_type['type'];
                $gtypes_p[] = $guest_type['type'];
 $price_spaces .= '<div class="col-md-1 table_row">'.$guest_type['type'].' '.jr_gettext('PRICES','PRICES').' 	</div>';




            }

// rusty end


		$counter = 0;
		if (!empty($all_tours))
			{
			foreach ($all_tours as $p)
				{
				$r=array();
				if ($p['property_uid'] != "0")
					{
					$r['DELETELINK']=  '<a href="'.JOMRES_SITEPAGE_URL."&task=jintour_delete_tour&id=".$p['id'].'&no_html=1">'.$deleteIcon.'</a>';
					$r['EDITLINK']=  '<a href="'.JOMRES_SITEPAGE_URL."&task=jintour_view_tour_bookings&id=".$p['id'].'">'.$editIcon.'</a>';
					$r['ID'] = $p['id'];
					$r['TITLE']=jr_gettext('_JINTOUR_TOUR_TITLE_CUSTOM_TEXT'.$p['id'],$p['title']);
					// rusty codes
					
					$sprice = json_decode($p['prices'], true);
				$sp =$space_price= Null;
				// show the price table
                        $price_input_spaces_no_html ='';
                        $lastKey = end(array_keys($sprice));
                        foreach ( $sprice as $key=>$spaces_tab){


  $price_input_spaces_no_html .='<div class="row_'.$key.'" id="row_'.$key.'">';
//	foreach ($spaces_tab as $kv=>$spac){

                            foreach ( $gtypes_p as $guest_type) {
                                $kv=strtolower($guest_type);


                                $price_input_spaces_no_html .= 	'<div class="col-md-1 table_row_1" > '.$sprice[$key][$kv].' 	</div>';
                            }

                            $price_input_spaces_no_html .='</div>';


                                $price_input_spaces_no_html .='<div class="clear" style="clear:both;"></div>';




                        }




                        $r['PRICE_TABLE'] ='<div class="tooltip_templates">
                        <div id="'.$p['id'].'_table" class="table_1 col-md-10 hiding"  style="width: 1500px;" >
                        <div class="head">
                       '.$price_spaces.'
                               </div>
                               <div class="clear" style="clear:both;"></div>

                               '.$price_input_spaces_no_html.'

        <div class="clear" style="clear:both;"></div>

        </div>    </div> ';


				
				foreach ($gtypes as $gt) {
				$space_price .= '<td>'.$sprice[strtolower($gt)].'</td>';	
					
					
				}
					 unset($sprice);
			
				$r['SPACE_PRICE']	=$space_price;
				
				
				$spaces = json_decode($p['spaces_available'], true);
				$space = Null;
				foreach ($spaces as $sp){
				$space .= '<td>'.$sp.'</td>';
									
					
				}
				// rusty spcaes values   end
				$r['SPACES']	= $space;
					$r['PRICE_ADULTS']	=$p['price_adults'];
					$r['PRICE_KIDS']	=$p['price_kids'];
					$r['SPACES_ADULTS']		=$p['spaces_available_adults'];
					$r['SPACES_KIDS']		=$p['spaces_available_kids'];
					$r['TOURDATE']		=outputDate(str_replace("-","/",$p['tourdate']));
					$r['CHECKBOX']='<input type="checkbox" id="cb'.$counter.'" name="idarray[]" value="'.$p['id'].'" onClick="jomres_isChecked(this.checked);">';
					$counter++;
					$rows[]=$r;
					}
				}
			}
		
		$output['TOTALINLISTPLUSONE']= $counter+1;
		$output['JOMRES_SITEPAGE_URL']=JOMRES_SITEPAGE_URL;
		
		$jrtbar =jomres_getSingleton('jomres_toolbar');
		$jrtb  = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid("/".JOMRES_ROOT_DIRECTORY."/images/jomresimages/small/WasteBasket.png");
		$jrtb .= $jrtbar->customToolbarItem('delete',JOMRES_SITEPAGE_URL."&task=jintour_batch_delete_tours&no_html=1",jr_gettext('_JOMRES_COM_MR_ROOM_DELETE','_JOMRES_COM_MR_ROOM_DELETE',false),$submitOnClick=true,$submitTask="jintour_batch_delete_tours",$image);
		$jrtb .= $jrtbar->endTable();
		$output['JOMRESTOOLBAR']=$jrtb;
		
			
	// rusty code
		foreach($gtypes as $gt){
		$hprice .='			<th>'.$gt.' price</th>';
		 $hspaces .='	<th>'.$gt.' spaces</th>	';
		}
		
		
		$output['PRICE_LABEL']	=$hprice;
		$output['SPACES_LABEL']	=  $hspaces;

// rusty end
		if ( !empty($rows))
			{
			$pageoutput=array();
			$pageoutput[]=$output;
			$tmpl = new patTemplate();
			$tmpl->setRoot( $ePointFilepath.'templates'.JRDS.find_plugin_template_directory() );
			$tmpl->readTemplatesFromInput( 'jintours_managertour_list.html');
			$tmpl->addRows( 'pageoutput',$pageoutput);
			$tmpl->addRows( 'rows',$rows);
            $tmpl->addRows( 'table_price',$rows); // rusty end
			$tmpl->displayParsedTemplate();
			}
		}
	
	
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
	
