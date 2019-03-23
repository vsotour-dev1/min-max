<?php

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################


function jintour_build_available_tours_list_new($bkg)
	{
		
		
	
	$list = "";
	$mrConfig=getPropertySpecificSettings();


	$jrportal_taxrate = jomres_singleton_abstract::getInstance( 'jrportal_taxrate' );
	$temp_adult_price_for_header=0;
	$temp_kids_price_for_header=0;

	//rusty code
		//get all guest types
		$defaultProperty=getDefaultProperty();
		if ($defaultProperty == 0) { //rusty changes
			
		$defaultProperty = 	(int) $_REQUEST['selectedProperty'];
			
		}
			if ($defaultProperty == 0) { //rusty changes
			
		$defaultProperty = 	(int) $_REQUEST['property_uid_check'];
			
		}
		
	
		$basic_guest_type_details = jomres_singleton_abstract::getInstance( 'basic_guest_type_details' );
		
		
		$basic_guest_type_details->get_all_guest_types($defaultProperty);
			
		
	$g_types =$basic_guest_type_details->guest_types;
	
	
		

	$valid_tours = jintour_get_tours_for_arrdep_dates_new($bkg,$g_types);
	

			foreach ($basic_guest_type_details->guest_types as $guest_type) {
			$gtypes[]= $guest_type['type'];
			}
			
			
			
	if (!empty($valid_tours))
		{
		foreach ($valid_tours as $tour)
			{
		
			// rusty code 
		// avalaible  spaces 
			$spaces_available = json_decode($tour['spaces_available'] , true);
			
			//prices 
			$spaces_price = json_decode($tour['prices'], true);
          $spaces_price =$spaces_price [$tour['filtered_key']];// filter by min and max numbers
		//	print_r( $spaces_price);exit;
			
			
			
		
			
			foreach($spaces_price as $key=>$s_price){
				
			}
				foreach($gtypes as $gt){
			
				$sprice[strtolower ($gt)]= $spaces_price[strtolower ($gt)];
				}
			
				
			
			
			
		
			$currfmt = jomres_getSingleton('jomres_currency_format');
			// need a check here to figure out how many adults and kids have already been selected and adjust the dropdowns accordingly
			if ((int)array_sum($spaces_available)>0)
				{
				$tour_id = $tour['id'];
					//rusty code
			$query = "SELECT * FROM #__jomres_jintour_tours WHERE id = ".(int)$tour_id ;
			$existing_tours =  doSelectSql($query);
		
				if (isset($bkg->third_party_extras_private_data['jintour']['chosen_options'][$tour_id]))
					{
					$current_choices = $bkg->third_party_extras_private_data['jintour']['chosen_options'][$tour_id];
					
					if (isset($current_choices['adults']))
						$already_chosen_adults = (int)$current_choices['adults'];
					else
						$already_chosen_adults = 0;
					
					if (isset($current_choices['kids']))
						$already_chosen_kids = (int)$current_choices['kids'];
					else
						$already_chosen_kids = 0;
					
					$already_chosen_total = $already_chosen_adults + $already_chosen_kids;
					}
				else
					{
					$already_chosen_adults = 0;
					$already_chosen_kids = 0;
					$already_chosen_total = 0;
					}

				$tax_rate=$tour['tax_rate'];
				$rate=(float)$jrportal_taxrate->taxrates[$tax_rate]['rate'];
				$tax_output = "";
				if ($tax_rate > 0)
					$tax_output = " (".$rate."%)";
				
				$adult_price_output=$tour['price_adults'];
				$kid_price_output=$tour['price_kids'];
				
				if ( (float) $tour['price_adults'] > 0 )
					$temp_adult_price_for_header = (float) $tour['price_adults'];
				if ( (float) $tour['price_kids'] > 0 )
					$temp_kids_price_for_header = (float) $tour['price_kids'];

				foreach ($gtypes as $sp) {
					$sp_avail[strtolower ($sp)] = (int)$spaces_available['spaces_'.strtolower ($sp)];
					
					
				}
		
				if (!function_exists('output_price'))
					{
						foreach ($sprice as $key=>$sp){
				if ($mrConfig['prices_inclusive'] == 0 && $rate>0)
					{
					$tax   = ( $sp / 100 ) * $rate;
					$sp = $sp + $tax;
					
					}
							$sprice_output[$key]=$mrConfig['currency'].$currfmt->get_formatted($sp).$tax_output; 
							
						}
					//$adult_price=$mrConfig['currency'].$currfmt->get_formatted($adult_price_output).$tax_output;
					//$kid_price=$mrConfig['currency'].$currfmt->get_formatted($kid_price_output).$tax_output;
					}
				else
					{
						if ($mrConfig['prices_inclusive'] == 0 && $rate>0)
					{
					$tax   = ( $sp / 100 ) * $rate;
					$sp = $sp + $tax;
					
					}
						
						foreach ($sprice as $key=>$sp){
							$sprice_output[$key]=output_price($sp).$tax_output;
							
						}
			
					}
			
				$adult_dropdown="";

			$acitivity_guest_names= array();
		foreach (	$sp_avail as $key=>$spa) {
			
			
			
			if ((float)$sprice[$key]>0.00)
					$sp_dropdown[$key]="&nbsp;&nbsp;".jomresHTML::integerSelectList( 00, $spa, 1, "jintour_".$key."_".$tour['id'], 'size="1" class="inputbox"  AUTOCOMPLETE="OFF"    onchange="getnew(\'jintour\',this.value+\'^'.$key.'^'.$tour['id'].'\',\''.$tour['id'].'\');"', $already_chosen_kids, "%02d" ,false );
		$acitivity_guest_names[]= $key;
			
		}
		$acitivity_guest_names = implode(",", $acitivity_guest_names);
		$inp_hidden_activity = '<input type="hidden" class="guest_names_'.$tour['id'].'" name="guest_names_'.$tour['id'].'" value="'.$acitivity_guest_names.'" />'; // name of the guest to input 
			
				$list.='<tr class="jintour_third_party_extra_div"><td class="tooltip_1" data-tooltip-content="#'.$tour['id'].'_table" title="Please wait.." id="user_'.$existing_tours[0]->tour_profile_id.'_'.$tour['id'].'" data_tour="'.$existing_tours[0]->tour_profile_id.'_'.$tour['id'].'">'.jr_gettext('_JINTOUR_TOUR_TITLE_CUSTOM_TEXT'.$tour['id'],$tour['title'],false,false).'</td>';


                    $price_table = show_table($tour,  $defaultProperty);
                    $list.='<div class="tooltip_templates">'.$price_table.'</div>';
                    foreach ($sp_dropdown	as $key=>$sp_v){
if ($sp_v !="")	{
$list.='<td><span id="price_'.$key.'_'.$tour['id'].'" class="price_row">'.$sprice_output[$key].'</span><span class="price_row_1"> '.$sp_v.'</span> </td>';	

	
}	else{
		$list.='<td>&nbsp;</td>';
					
	
}		
		
			}

				
				$list.=
				
				'<td>'.outputDate(str_replace("-","/",$tour['tourdate'])).'</td>'.
				'<td>'.$tour['description'].'</td>'.
				'</tr>'
				;
				$list.=	$inp_hidden_activity 	;
				
				}
			}
	
		
		
			
	// labels
		foreach($gtypes as $gt){
		$price_str_list.= '<th>'.$gt.'</th>';	
		}
			
		
		

		$headers="<table width='100%' class='table table-condensed table-striped table-bordered'><tr><th>".jr_gettext('_JINTOUR_TOUR_TITLE','_JINTOUR_TOUR_TITLE',false).' </th>'.$price_str_list.'<th>'.jr_gettext('_JINTOUR_TOUR_DATE','_JINTOUR_TOUR_DATE',false).'</th><th>'.jr_gettext('_JINTOUR_TOUR_ITINERY','_JINTOUR_TOUR_ITINERY',false).'</th></tr>';


$extra_div = $headers.$list.'</table>';		
	//$extra_results['price_table']	= $price_table;
//$extra_results['extra_div']	= $extra_div.'<div class="tooltip_templates"> <div id="87_table" class="table_1 col-md-10" style="width: 1500px;"> <div class="head"><div class="col-md-1 table_row">MIN 	</div><div class="col-md-1 table_row">MAX 	</div><div class="col-md-1 table_row">Elder PRICES 	</div><div class="col-md-1 table_row">Adult PRICES 	</div><div class="col-md-1 table_row">Child PRICES 	</div><div class="col-md-1 table_row">XXXXX PRICES 	</div></div> <div class="clear"></div> <div class="row_1" id="row_1"><div class="col-md-1 table_row_1"> 12 	</div><div class="col-md-1 table_row_1"> 32 	</div><div class="col-md-1 table_row_1"> 454 	</div><div class="col-md-1 table_row_1"> 223 	</div><div class="col-md-1 table_row_1"> 445 	</div><div class="col-md-1 table_row_1"> 3342 	</div></div><div class="clear" style="clear:both;"></div><div class="row_2" id="row_2"><div class="col-md-1 table_row_1"> 34 	</div><div class="col-md-1 table_row_1"> 234 	</div><div class="col-md-1 table_row_1"> 4321 	</div><div class="col-md-1 table_row_1"> 4654 	</div><div class="col-md-1 table_row_1"> 2432 	</div><div class="col-md-1 table_row_1"> 2323 	</div></div><div class="clear" style="clear:both;"></div> <div class="clear"></div> </div>    </div>';
            $extra_results['extra_div']	= $extra_div;
            return $extra_results;
		
		
		
		
		
		}
	else return false;
	
	}

function jintour_get_tours_for_arrdep_dates_new($bkg,$g_types)
	{
	// rusty
$total_guest_spaces = (int)jomresGetParam($_REQUEST, 'ng_values', ''); 

$guest_values = jomresGetParam($_REQUEST, 'guest_values', '');
 
  //echo "<br>guest values sp =";print_r($guest_values);
 $guest_values = html_entity_decode($guest_values) ;
 
 //echo "<br>guest values sp =";print_r($guest_values); 
 
$guest_values = json_decode($guest_values,true);

//echo "<br>guest values sp =";print_r($guest_values); 


$total_guest_spaces = (int)array_sum($guest_values); // total amount of the guests

	//echo "<br>total sp =";print_r($total_guest_spaces); exit;


foreach ($guest_values as $key=>$gtype)  {
	$gt = explode('_',$key);


	$req_gtypes[strtolower($g_types[$gt[1]]['type'])] = $gtype;
	
}


//print_r ($req_gtypes);exit;

$session = JFactory::getSession();

//if ($total_guest_spaces!='') $session->set('ng_values', $total_guest_spaces);// put to session the total number of the guest


//If (empty($total_guest_spaces)) $total_guest_spaces = $session->get('ng_values'); // retrieve from the session the total number of the guest


	$unixArrivalDate=strtotime($bkg->arrivalDate);
	$unixDepartureDate=strtotime($bkg->departureDate);
/*	print_r ($bkg->arrivalDate);echo "<br />";	print_r ($bkg->departureDate);
	print_r ($unixArrivalDate);echo "<br />";	print_r ($unixDepartureDate);
*/	
	$valid_tours = array();
	$all_tours = jintour_get_all_tours_new($bkg->property_uid);
	// echo "<pre >";	print_r ($all_tours );exit;
	if (!empty($all_tours))
		{
		foreach ($all_tours as $tour)
			{
			$prices= json_decode($tour['prices'], true);	// prices of activity
			$spaces_available = json_decode($tour['spaces_available'], true);
				$show_tour_by_guest = 0;
			
			$unixTourDate=strtotime($tour['tourdate']);
		//echo "tour date ".	$unixTourDate;
			if (!get_showtime('include_room_booking_functionality'))
				{
					
					
				//	echo $unixTourDate;
				//	echo "<pre>";		print_r ($tour);
			//	if ( ($unixTourDate >= $unixArrivalDate && $unixTourDate < $unixDepartureDate) && ($tour['published'] == 1) &&($total_guest_spaces>= $prices['min'] && $total_guest_spaces <= $prices['max']) )// filter
 if ( ($unixTourDate >= $unixArrivalDate && $unixTourDate < $unixDepartureDate) && ($tour['published'] == 1)  )// filter


                    {
						
		
				
						foreach ($req_gtypes as $k=>$v) { // filter guest types selection  number of the quest of the activity val
							if ($spaces_available['spaces_'.$k] < $v  )
							{
							
								$show_tour_by_guest = 1; // no show
							}
									
						}
						
					//	echo "show tour = <pre>"; print_r ($show_tour_by_guest);exit;
					/*
						if ($show_tour_by_guest !=1){ // filter guest types selection  number of the quest of the activity val
					$tour_id=$tour['id'];
					$valid_tours[$tour_id]=$tour;  // show
						}
					*/
				
					
						$show_tour_by_min_max = 0;// show by filter min and max
					
					//echo "price ="; print_r ( $prices); exit;
					
					foreach ($prices as $key=>$s_pr) { // check for min and max filter
			
					if ($total_guest_spaces>= $s_pr['min'] && $total_guest_spaces <= $s_pr['max']) {
						$show_tour_by_min_max =1;
						$tour['filtered_key'] = $key; // filtered key for prices slection
						
					}
				
					}
					
				//echo "min_max= ".$show_tour_by_min_max."<br>";
			//	echo "show tour by guest= ".$show_tour_by_guest."<br>";
				
					if (($show_tour_by_min_max==1) && ($show_tour_by_guest !=1)) {
						$tour_id=$tour['id'];
						$valid_tours[$tour_id]=$tour; 
						
					}
			
					}
					
				
					
				}
		
		else
				{
	//			if ( ($unixTourDate >= $unixArrivalDate && $unixTourDate <= $unixDepartureDate) && ($total_guest_spaces>= $prices['min'] && $total_guest_spaces <= $prices['max']) && ($tour['published'] == 1)) // filter
 if ( ($unixTourDate >= $unixArrivalDate && $unixTourDate <= $unixDepartureDate) && ($tour['published'] == 1)) // filter

                    {
						
			
					foreach ($req_gtypes as $k=>$v) { // filter guest types sel val
							if ($spaces_available['spaces_'.$k] < $v  ) $show_tour_by_guest = 1; //no show
							
						}
						/*
						if ($show_tour_by_guest !=1){
					$tour_id=$tour['id'];
					$valid_tours[$tour_id]=$tour;
						}
					*/
				
						$show_tour_by_min_max = 0;// show by filter min and max
					foreach ($prices as $key=>$s_pr) { // check for min and max filter
			
					if ($total_guest_spaces>= $s_pr['min'] && $total_guest_spaces <= $s_pr['max']) {
						$show_tour_by_min_max =1;
						$tour['filtered_key'] = $key; // filtered key for prices slection
						
					}
				
					}
					
				
					if (($show_tour_by_min_max==1) && ($show_tour_by_guest !=1)) {
						$tour_id=$tour['id'];
						$valid_tours[$tour_id]=$tour; 
						
					}

				}

				}
			}
			
			
		}


		
//	echo "<pre>"; print_r ($valid_tours);exit;
	$bkg->third_party_extras_private_data['jintour']['validtours']=$valid_tours;

	return $valid_tours;
	}
	
function jintour_get_all_tours_new($property_uid)
	{
		
	
	$result = array();
	$query = "SELECT * FROM #__jomres_jintour_tours WHERE property_uid =".$property_uid." OR property_uid = 0";
	$tours = doSelectSql($query);
	if (!empty($tours))
		{
		foreach ($tours as $p)
			{
			$result[$p->id]['id'] = $p->id;
			$result[$p->id]['title'] = $p->title;
			$result[$p->id]['description'] = $p->description;
			$result[$p->id]['price_adults'] = $p->price_adults;
				$result[$p->id]['prices'] = $p->prices;
				
			$result[$p->id]['price_kids'] = $p->price_kids;
			$result[$p->id]['spaces_available_adults'] = $p->spaces_available_adults;
			$result[$p->id]['spaces_available'] = $p->spaces_available;
			$result[$p->id]['spaces_available_kids'] = $p->spaces_available_kids;
			$result[$p->id]['tourdate'] = $p->tourdate;
			$result[$p->id]['tax_rate'] = $p->tax_rate;
			$result[$p->id]['published'] = $p->published;
			$result[$p->id]['property_uid'] = $p->property_uid;
			}
		}
	return $result;
	}

function jintour_get_tour_new($tour_id =0,$property_uid = 0)
	{
	
	if ($tour_id ==0)
		return false;
	
	$result = array();
	$query = "SELECT * FROM #__jomres_jintour_tours WHERE ( property_uid =".$property_uid." OR property_uid = 0) AND id =".$tour_id." LIMIT 1";
	$tours = doSelectSql($query);
	if (!empty($tours))
		{
		foreach ($tours as $p)
			{
			$result[$p->id]['id'] = $p->id;
			$result[$p->id]['title'] = $p->title;
			$result[$p->id]['description'] = $p->description;
			$result[$p->id]['price_adults'] = $p->price_adults;
			$result[$p->id]['price_kids'] = $p->price_kids;
			$result[$p->id]['spaces_available_adults'] = $p->spaces_available_adults;
			$result[$p->id]['spaces_available_kids'] = $p->spaces_available_kids;
			$result[$p->id]['tourdate'] = $p->tourdate;
			$result[$p->id]['tax_rate'] = $p->tax_rate;
			$result[$p->id]['published'] = $p->published;
			}
		}
	return $result;
	}
function jintour_get_all_tour_profiles_new($property_uid)
	{
	$result = array();
	$query = "SELECT * FROM #__jomres_jintour_profiles WHERE property_uid =".$property_uid;
	$profiles = doSelectSql($query);
	if (!empty($profiles))
		{
		foreach ($profiles as $p)
			{
			$result[$p->id]['id'] = $p->id;
			$result[$p->id]['title'] = $p->title;
			$result[$p->id]['description'] = $p->description;
			$result[$p->id]['days_of_week'] = $p->days_of_week;
			$result[$p->id]['price_adults'] = $p->price_adults;
			$result[$p->id]['price_kids'] = $p->price_kids;
			$result[$p->id]['spaces_adults'] = $p->spaces_adults;
			$result[$p->id]['spaces_kids'] = $p->spaces_kids;
			$result[$p->id]['start_date'] = $p->start_date;
			$result[$p->id]['end_date'] = $p->end_date;
			$result[$p->id]['repeating'] = $p->repeating;
			$result[$p->id]['property_uid'] = $p->property_uid;
			$result[$p->id]['tax_rate'] = $p->tax_rate;
			// rusty changes
			$result[$p->id]['spaces_guest_types'] = $p->spaces_guest_types;
			$result[$p->id]['spaces_table'] = $p->spaces_table;
			
			$result[$p->id]['specific_date'] = $p->specific_date;
			$result[$p->id]['calendar_dates'] = $p->calendar_dates;
				$result[$p->id]['main_price'] = $p->main_price;
			}
		}
	return $result;
	}

function jintour_get_tour_profile_new($profile_id =0,$property_uid = 0)
	{
	
	if ($profile_id ==0)
		return false;
	
	$result = array();
	$query = "SELECT * FROM #__jomres_jintour_profiles WHERE property_uid =".$property_uid." AND id =".$profile_id." LIMIT 1";
	$profiles = doSelectSql($query);
	//print_r (	$profiles);	echo 	$query ;exit;
	if (!empty($profiles))
		{
		foreach ($profiles as $p)
			{
			$result[$p->id]['id'] = $p->id;
			$result[$p->id]['title'] = $p->title;
			$result[$p->id]['description'] = $p->description;
			$result[$p->id]['days_of_week'] = $p->days_of_week;
			$result[$p->id]['price_adults'] = $p->price_adults;
			$result[$p->id]['price_kids'] = $p->price_kids;
			$result[$p->id]['spaces_adults'] = $p->spaces_adults;
			$result[$p->id]['spaces_kids'] = $p->spaces_kids;
			
			// rusty code
				$result[$p->id]['price_elders'] = $p->price_elders;
			$result[$p->id]['spaces_adults_min'] = $p->spaces_adults_min;
			$result[$p->id]['spaces_adults_max'] = $p->spaces_adults_max;
			
			$result[$p->id]['spaces_kids_min'] = $p->spaces_kids_min;
				$result[$p->id]['spaces_kids_max'] = $p->spaces_kids_max;
				
				$result[$p->id]['spaces_elders_min'] = $p->spaces_elders_min;
				$result[$p->id]['spaces_elders_max'] = $p->spaces_elders_max;
				
				// end rusty code
			$result[$p->id]['start_date'] = $p->start_date;
			$result[$p->id]['end_date'] = $p->end_date;
			$result[$p->id]['repeating'] = $p->repeating;
			$result[$p->id]['property_uid'] = $p->property_uid;
			$result[$p->id]['tax_rate'] = $p->tax_rate;
			// rusty changes
			$result[$p->id]['spaces_guest_types'] = $p->spaces_guest_types;
			$result[$p->id]['spaces_table'] = $p->spaces_table;
			$result[$p->id]['specific_date'] = $p->specific_date;
			$result[$p->id]['calendar_dates'] = $p->calendar_dates;
			$result[$p->id]['main_price'] = $p->main_price;
			}
		}
	return $result;
	

	
	
	}
	
	function show_table($tour,  $defaultProperty){
		     $price_spaces = '';
            $gtypes_p=array('min','max');


            $min = jr_gettext('MIN','MIN');
           $max =  jr_gettext('MAX','MAX');

            $price_spaces .= '<div class="col-md-1 table_row">'.$min.' 	</div>';


            $price_spaces .= '<div class="col-md-1 table_row">'.$max.' 	</div>';

				$basic_guest_type_details = jomres_singleton_abstract::getInstance( 'basic_guest_type_details' );
		$basic_guest_type_details->get_all_guest_types($defaultProperty);
		
			foreach ($basic_guest_type_details->guest_types as $guest_type) {
			$gtypes[]= $guest_type['type'];
                $gtypes_p[] = $guest_type['type'];
 $price_spaces .= '<div class="col-md-1  table_row">'.$guest_type['type'].' '.jr_gettext('PRICES','PRICES').' 	</div>';
            }
		$sprice = json_decode($tour['prices'], true);
		
	
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

	$price_table_1  = $price_spaces.$price_input_spaces_no_html;


$price_table ='<div id="'.$tour['id'].'_table" class="table_1  col-md-10"  style="width:1500px;"><div class="head">'.$price_spaces.'</div>';
                              $price_table .='<div class="clear" style="clear:both;"></div>'.$price_input_spaces_no_html.'<div class="clear" style="clear:both;"></div></div>';
		return 	$price_table;
		
		
	}
	
	function activity_guest_prices($number_of_guest_tourid){ // rusty code activity gyest prices filter min max
		
				
			//		$activity_guest_values = jomresGetParam($_REQUEST, 'activity_guest_values', ''); 
				
			//		$activity_guest_values= html_entity_decode($activity_guest_values) ;
				
		//	$activity_guest_values_1 = json_decode($activity_guest_values,true);
		
		$activity_guest_values_1 = $number_of_guest_tourid;
				//print_r($activity_guest_values_1 );
				
					$total_activity_guest_values = (int)array_sum(	$activity_guest_values_1); // total values of the guests
					$keys = array_keys($activity_guest_values_1);
					$tour=  explode("_", $keys[0]);
				$tour_id = $tour[1];
			
						$query = "SELECT * FROM #__jomres_jintour_tours WHERE id = ".(int)$tour_id ;
			$existing_tours =  doSelectSql($query);
			$prices = json_decode($existing_tours[0]->prices );
			
				$show_tour_by_min_max = 0;// show by filter min and max
					foreach ($prices as $key=>$s_pr) { // check for min and max filter
			
					if ($total_activity_guest_values >= $s_pr->min && $total_activity_guest_values <= $s_pr->max) {
						$show_tour_by_min_max =1;
						$show_key = $key; // filtered key for prices slection
						
					}
				
					}
			
			
		if ( !empty($prices->$show_key ))      $selected_price_row = $prices->$show_key ;// selected price_row from table
	
		
		foreach ($activity_guest_values_1 as $price_key=>$value)
		{
			$guest_name=  explode("_",$price_key);
			$g_n[$guest_name[0]] = $value;
	
			
		}	
		$return['activity_guest'] = 	$g_n; // guest types name
		$return['selected_price_row'] = $selected_price_row;
		return $return ;
		
	}