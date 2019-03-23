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

class j05040jintour {
	function __construct($bkg)
		{
			
	
				$ePointFilepath = get_showtime('ePointFilepath');// rusty
		
			include_once($ePointFilepath."functions.php");// rusty
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		
		$this->retVal = '';
		
		$mrConfig=getPropertySpecificSettings();
		$data = explode("^",$_GET['value']);
		// first some data validation
		if (count($data)!=3)
			return;
		$quantity = (int)$data[0];
		$type = (string)$data[1];
	//	if ($type != "adults" && $type != "kids") 			return;
		$tour_id = (int)$data[2];
		
		$valid_tours =$bkg->third_party_extras_private_data['jintour']['validtours'];
	//echo "<pre>";
	//	print_r ($bkg->third_party_extras_private_data['jintour']);exit;
		if (empty($valid_tours))
			//return;
		if (!array_key_exists($tour_id,$valid_tours))
	//	return;
		// Finish validation
		
		// Now to record the chosen option
	$bkg->third_party_extras_private_data['jintour']['chosen_options'][$tour_id]['tour_id']=$tour_id;
	$bkg->third_party_extras_private_data['jintour']['chosen_options'][$tour_id][$type]=$quantity;
	//echo "<pre>";
	//echo $tour_id;
	

	$bkg->third_party_extras_private_data['jintour']['chosen_options'][$tour_id]['property_uid']=$valid_tours[$tour_id]['property_uid'];
		// Now we'll calculate the prices and save them against the booking
	
		$jrportal_taxrate = jomres_singleton_abstract::getInstance( 'jrportal_taxrate' );
		$session = JFactory::getSession();
//print_r ($bkg->third_party_extras_private_data['jintour']['chosen_options']);	exit;
// calculate total rusty
	if (!empty($bkg->third_party_extras_private_data['jintour']['chosen_options']))
			{
		
			foreach ($bkg->third_party_extras_private_data['jintour']['chosen_options'] as $tour)
			{
				
		//	echo "<pre>";
		//print_r ($bkg);
					
					
				$grand_total =$guest_total= 0.00;
				$tour_id = $tour['tour_id'];
				//rusty code
				
				
			$query = "SELECT * FROM #__jomres_jintour_tours WHERE id = ".(int)$tour_id ;
			$existing_tours =  doSelectSql($query);
			$ps =$existing_tours[0];
		
			$spaces_price = json_decode($ps->prices , true);
			$spaces_available = json_decode($ps->spaces_available  , true);
			//print_r ($spaces_available);
	unset($number_of_guest_tourid);

			foreach ($spaces_available as $key=>$value) {
				
				$g_key = explode ('_', $key);
		$number_of_guest_tourid[$g_key[1].'_'.$tour_id] = (int)$tour[$g_key[1]];
				
				if (($g_key[1] == $type )) {
					
					
					
				}else {
			//$number_of_guest[$g_key[1]] = (int)$session->get($g_key[1].'_'.$tour_id);
				
					
				}
		

			}

//print_r ($number_of_guest_tourid);echo "<pre>";
		$acti_guest = activity_guest_prices($number_of_guest_tourid);
$number_of_guest = $acti_guest['activity_guest']; 
$spaces_price = $acti_guest['selected_price_row']; 
	
		//	print_r ($activity_guest_values);
				
				$tax_rate_id = $valid_tours[$tour_id]['tax_rate'];
				$rate = (float)$jrportal_taxrate->taxrates[$tax_rate_id]['rate'];
				foreach ($number_of_guest as $key=>$sp_pr){
					$guest_price[$key] = $spaces_price->$key;
					
				}
		
				
				if ($mrConfig['prices_inclusive'] == 0)
					{
						foreach ($guest_price as $key=>$g_price)	{			
					$tax   = ( $g_price / 100 ) * $rate;
					$guest_price[$key] = $g_price + $tax;
					
						}
					}
		
				$guest_total = 0.00;
	// 	print_r (	$number_of_guest); 		echo $tour_id;
				// total calculations
			foreach ($number_of_guest as $key=>$n_guest) 
			
					{
					
					if ($n_guest>0) 	$guest_total += $guest_price[$key]  * $n_guest;
			$n_guest =0;
					}
					
					
				
				/* echo 	$guest_total;
			echo "hh";exit;	 */	
				$grand_total = $guest_total ;
				
//print_r ($number_of_guest); echo "<br>"; echo $guest_total;
//exit;
				
		
				
				
					$tour_date = outputDate(str_replace("-","/",$valid_tours[$tour_id]['tourdate']));
					$num_ads = jr_gettext('_JINTOUR_TOUR_ADULTS','_JINTOUR_TOUR_ADULTS',false). " x ".$number_of_adults;
					$num_kids = "";
					if ($number_of_kids >0)
						$num_kids = jr_gettext('_JINTOUR_TOUR_KIDS','_JINTOUR_TOUR_KIDS',false). " x ".$number_of_kids;
						
					$extra_description = $valid_tours[$tour_id]['title']." ".$num_ads." ".$num_kids."  ".jr_gettext('_JINTOUR_TOUR_DATE','_JINTOUR_TOUR_DATE',false)." :: ".outputDate($valid_tours[$tour_id]['tourdate']);
					
					$bkg->setErrorLog("j05040jintour:: Adult price : ".$adult_total.  " Kids total ".$kids_total);
					$bkg->setErrorLog("j05040jintour:: Description ".$extra_description);
					$bkg->setErrorLog("j05040jintour:: Grand total ".$grand_total);
					
	
					$bkg->add_third_party_extra("jintour",$tour_id,$extra_description,$grand_total,$valid_tours[$tour_id]['tax_rate']);
			//	$bkg->remove_third_party_extra("jintour",$tour_id);
				if (array_sum($number_of_guest) == 0) $bkg->remove_third_party_extra("jintour",$tour_id);
			
				else
					{
			//		$bkg->remove_third_party_extra("jintour",$tour_id);
					}
					
				
				}
			}
		// Finally we'll rebuild the tours list using the new data found by all the above
		$tourslist = jintour_build_available_tours_list_new($bkg);

		if ($tourslist)
			{
			$retVal="<td colspan=\"5\"><table>".$tourslist."</table></td>";
			$retVal=str_replace('"','\"',$retVal);
			$retVal=str_replace("'","\'",$retVal);
			}
		else
			$retVal="<td colspan=\"5\">&nbsp;</td>";
			
	//	$this->retVal=array('reply_to_echo'=>'populateDiv("jintour_third_party_extra_div",\''.$retVal.'\')');
		}

	function getRetVals()
		{
		return $this->retVal;
		}
	}
