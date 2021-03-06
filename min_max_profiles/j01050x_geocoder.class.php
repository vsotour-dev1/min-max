<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@jomres.net>
 *
 * @version Jomres 9.9.5 / rusty 9.16.1
 *
 * @copyright	2005-2017 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class j01050x_geocoder
{
	public function __construct($componentArgs = null)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$this->retVals ='';
		
		$siteConfig = jomres_singleton_abstract::getInstance('jomres_config_site_singleton');
		$jrConfig = $siteConfig->get();

		if (isset($componentArgs[ 'property_uid' ])) {
			$property_uid = (int) $componentArgs[ 'property_uid' ];
		} else {
			$property_uid = getDefaultProperty();
		}

		$editing = false;
		if (isset($componentArgs[ 'editing_map' ])) {
			$editing = $componentArgs[ 'editing_map' ];
		}

		add_gmaps_source();

		$output = array();
		$pageoutput = array();

		$output['MAP_STYLE'] = file_get_contents(JOMRES_ASSETS_ABSPATH.'map_styles'.JRDS.$jrConfig['map_style'].'.style');
		$output['ZOOMLEVEL'] = (int)$jrConfig['map_zoom'];
		if ( isset( $_REQUEST['map_zoom'] )) {
			$output['ZOOMLEVEL'] = (int) $_REQUEST['map_zoom'];
		}
		$output['MAPTYPE'] = strtoupper($jrConfig['map_type']);

		$output[ 'DISABLE_UI' ] = '';
		if (isset($componentArgs[ 'disable_ui' ])) {
			$output[ 'DISABLE_UI' ] = 'disableDefaultUI: true,';
		}

		$output[ 'LATLONG_DESC' ] = jr_gettext('_JOMRES_LATLONG_DESC', '_JOMRES_LATLONG_DESC', false);

		$task = jomresGetParam($_REQUEST, 'task', '');
		$map_identifier = jomresGetParam($_REQUEST, 'map-identifier', '');

		if ($map_identifier != '') {
			$output[ 'RANDOM_IDENTIFIER' ] = $map_identifier;
		} else {
			$output[ 'RANDOM_IDENTIFIER' ] = generateJomresRandomString(10);
		}

		$output[ 'MAP_WIDTH' ] = 300;
		$output[ 'MAP_HEIGHT' ] = $jrConfig['map_height'];
		$output[ 'MARKER_PATH' ] = '';
		if (isset($componentArgs[ 'width' ])) {
			$output[ 'MAP_WIDTH' ] = (int) $componentArgs[ 'width' ];
			$output[ 'MAP_HEIGHT' ] = (int) $componentArgs[ 'height' ];
		}

		if ($property_uid == 999999999) {
			$output[ 'LAT' ] = $jrConfig[ 'default_lat' ];
			$output[ 'LONG' ] = $jrConfig[ 'default_long' ];
		} else {
			$current_property_details = jomres_singleton_abstract::getInstance('basic_property_details');
			$current_property_details->gather_data($property_uid);

			$jomres_property_types = jomres_singleton_abstract::getInstance('jomres_property_types');
			$jomres_property_types->get_property_type($current_property_details->ptype_id);

			if (isset($jomres_property_types->property_type['marker_image'])) {
				$output[ 'marker_image'] = $jomres_property_types->property_type['marker_image'];
			}
			
			
			// rusty code get lat and longs
			
			$map_longs_lats = $this->get_lat_long($current_property_details,$property_uid ,$jrConfig);
			
			
          //  $propertyData[ 'lat' ] = $current_property_details->multi_query_result[ $property_uid ][ 'lat' ];
          //  $propertyData[ 'long' ] = $current_property_details->multi_query_result[ $property_uid ][ 'long' ];

		  
		  $propertyData[ 'lat' ] = $map_longs_lats[ 'lat' ];
          $propertyData[ 'long' ] = $map_longs_lats[ 'long' ];

		  // end rusty code
            if ($propertyData[ 'lat' ][1] != null) {
                $output[ 'LAT' ] = $propertyData[ 'lat' ][1];
                $output[ 'LONG' ] = $propertyData[ 'long' ][1];
            } elseif ($editing) {
                $output[ 'LAT' ] = $jrConfig[ 'default_lat' ];
                $output[ 'LONG' ] = $jrConfig[ 'default_long' ];
            } else {
                return;
            } // If we're editing it's ok to use the default data. If we're not, it isn't and it's best to simply not show the map at all
        }
		

///	print_r ($map_longs_lats);exit;


	if (!isset($jrConfig[ 'gmap_layer_transit' ])) {
			$jrConfig[ 'gmap_layer_transit' ] = '0';
		}

		if ($jrConfig[ 'gmap_layer_transit' ] == '1') {
			$output[ 'TRANSIT_LAYER' ] = '
				var transitLayer = new google.maps.TransitLayer();
				transitLayer.setMap(map);
			';
        }

        if ($jrConfig[ 'gmap_pois' ] == '0') {
            $output[ 'SUPPRESS_POIS' ] = '
			,
		styles:[{
			featureType:"poi",
			elementType:"labels",
			stylers:[{
				visibility:"off"
				}]
			}]
			';
        }
		// rusty code start check property type for tour abn show theme for tour
		$jomres_property_types = jomres_singleton_abstract::getInstance('jomres_property_types');
		$jomres_property_types->get_all_property_types(); // rusty code get all property types
	
		$property_ts = $jomres_property_types->property_types;
	
		foreach ($property_ts as $property_t){
			if ($property_t['mrp_srp_flag'] ==3){
			$p_types_des[]= $property_t['ptype_desc'];
			}
			
		}
	
			$show_tour_theme = 0;
		if (in_array($current_property_details->property_type,$p_types_des )){ // rusty code check tour properties for show the tour compare theme
					
						$show_tour_theme = 1; 
				}
			
				
				// rusty code end
        if ($editing) {
            $output[ 'DRAGABLE' ] = ',
		draggable: true,';
            $output[ 'DRAG_LISTENER' ] = '
		
		var postcodeInput = document.getElementById(\'property_postcode\');';
		
		if($show_tour_theme==0){
		            $output[ 'DRAG_LISTENER' ] .= '	
		var townInput = document.getElementById(\'property_street\');
	var streetInput = document.getElementById(\'property_town\'); 		
	';
			
		}
		
		
		  $output[ 'DRAG_LISTENER' ] .= '	google.maps.event.addDomListener(postcodeInput, \'change\', function() {
			var address = build_address();
			if (address != "") {
				codeAddress(address);
				}
			});';
			
			
				
				if($show_tour_theme==0){
	 $output[ 'DRAG_LISTENER' ] .= '		google.maps.event.addDomListener(townInput, \'change\', function() {
			var address = build_address();
			if (address != "") {
				codeAddress(address);
				}
			});
		google.maps.event.addDomListener(streetInput, \'change\', function() {
			var address = build_address();
			if (address != "") {
				codeAddress(address);
				}
			});
		';	
				}
				
					if($show_tour_theme==0){ 
		   $output[ 'DRAG_LISTENER' ] .= '	updateMarkerPosition(latLng);
			google.maps.event.addListener(marker, \'drag\', function() {
			updateMarkerPosition(marker.getPosition());
				
			});
			google.maps.event.addListener(marker, \'dragend\', function() {
			map.setCenter(marker.getPosition());
			});
				';
					}else{
						
				 $output[ 'DRAG_LISTENER' ] .= '	
			
		var row_id = 1;
			
			 updateMarkerPosition(latLng,row_id);
			google.maps.event.addListener(marker, \'drag\', function() {
			updateMarkerPosition(marker.getPosition(),row_id);
				
			});
			google.maps.event.addListener(marker, \'dragend\', function() {
			map.setCenter(marker.getPosition());
			});
				';		
						
						
						
					}
            if (!defined('UPDATE_POSITION_FUNCTION_EXISTS')) {
                define('UPDATE_POSITION_FUNCTION_EXISTS', 1);
				if($show_tour_theme==0){ 
                $output[ 'UPDATEMARKERPOSITION' ] = "function updateMarkerPosition(latLng) {
					
				
		jomresJquery('#lat').val(latLng.lat().toFixed(7));
		jomresJquery('#lng').val(latLng.lng(5).toFixed(7));
					}	";
            }else{
			  $output[ 'UPDATEMARKERPOSITION' ] = "function updateMarkerPosition(latLng,row_id) {
						
							
				if (row_id ==1){
		jomresJquery('#lat').val(latLng.lat().toFixed(7));
		jomresJquery('#lng').val(latLng.lng(5).toFixed(7));	
				}else{
	
			jomresJquery('#lat_'+row_id).val(latLng.lat().toFixed(7));
		jomresJquery('#lng_'+row_id).val(latLng.lng(5).toFixed(7));
					
				}
					}	";	
			
			

			$last_key = end(array_keys($propertyData['lat']));  // get last key
				
			foreach ($propertyData['lat'] as $k=>$lat){
				
				if ($k != 1){
				if ($k != $last_key){
				$latlng.= '{lat:'.$lat.', lng:'.$propertyData['long'][$k].'},';
					$output['MARKERS'] .="  addMarker_init (map, ".$lat.",  ".$propertyData['long'][$k].",   ".$k.");";
				}else{
				$output['MARKERS'] .="  addMarker_init (map, ".$lat.",  ".$propertyData['long'][$k].",   ".$k.");";
				}
				
					}
				
			}
			
		//	echo $output['MARKERS'] ;exit;
			/*	$latlng.= ']';

				$output['MARKERS'] ="var markersLatLng = ".$latlng.";
  for (var i = 0; i < 1; i++) {  
 var row_id =  i ;
  addMarker_init (map, markersLatLng[i].lat,  markersLatLng[i].lng,   row_id);
}



'";	*/		
			
			
			}
			}
        }

        // IE was playing silly boys and wouldn't load without using (window).load, however if we use that then the map will not run in module popups, so we need to change the loading trigger depending on the "task".
        $output['LOAD_TRIGGER'] = 'jomresJquery(window).load(function(){';
        
		if ($task == 'module_popup' || $task == 'ajax_list_properties' || $task == 'ajax_search_filter') {
            $output['LOAD_TRIGGER'] = 'jomresJquery(document).ready(function(){';
        }

        set_showtime('current_map_identifier', $output[ 'RANDOM_IDENTIFIER' ]);

        $pageoutput[ ] = $output;
        $tmpl = new patTemplate();
		if($show_tour_theme==0){
        $tmpl->setRoot(JOMRES_TEMPLATEPATH_FRONTEND);
        $tmpl->readTemplatesFromInput('geocoder_latlong.html');
		}elseif($show_tour_theme==1){
			$plugin_dir = JOMRESCONFIG_ABSOLUTE_PATH.JOMRES_ROOT_DIRECTORY.JRDS.'remote_plugins'.JRDS.'min_max_profiles';
		
			   $tmpl->setRoot( $plugin_dir.JRDS."templates".JRDS.'bootstrap3' );
        $tmpl->readTemplatesFromInput('geocoder_latlong_tour.html');
			
		}
        $tmpl->addRows('pageoutput', $pageoutput);

        $this->retVals = $tmpl->getParsedTemplate();
    }

    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return $this->retVals;
    }
	
	
	public function get_lat_long($current_property_details,$property_uid ,$jrConfig){
		//jomres_property_types::get_all_property_types(); // rusty code get all property types

		$jomres_property_types = jomres_singleton_abstract::getInstance('jomres_property_types');
		$jomres_property_types->get_all_property_types();
		
		
		
		    $propertyData[ 'lat' ] = $current_property_details->multi_query_result[ $property_uid ][ 'lat' ];
            $propertyData[ 'long' ] = $current_property_details->multi_query_result[ $property_uid ][ 'long' ];
			
	
		$property_ts = $jomres_property_types->property_types;
		
		
		foreach ($property_ts as $property_t){
			if ($property_t['mrp_srp_flag'] ==3){
			$p_types_des[]= $property_t['ptype_desc'];
			}
			
		}
	
			$show_tour_theme = 0;
		if (in_array($current_property_details->property_type,$p_types_des )){ // rusty code check tour properties for show the tour compare theme
					
						$show_tour_theme = 1; 
				}
				if ( $show_tour_theme  == 0) {
				
				if ($propertyData[ 'lat' ] != null) {
                $output[ 'lat' ] = $propertyData[ 'lat' ];
                $output[ 'long' ] = $propertyData[ 'long' ];
            } elseif ($editing) {
                $output[ 'lat' ] = $jrConfig[ 'default_lat' ];
                $output[ 'long' ] = $jrConfig[ 'default_long' ];
            } 
		
				}else{
				
					if ($propertyData[ 'lat' ][1] != null) {
                $output[ 'lat' ] = $propertyData[ 'lat' ];
                $output[ 'long' ] = $propertyData[ 'long' ];
				 $output[ 'k' ] = count($propertyData[ 'lat' ]);
				
            } elseif ($editing) {
                $output[ 'lat' ] = $jrConfig[ 'default_lat' ];
                $output[ 'long' ] = $jrConfig[ 'default_long' ];
				
				if  (count($propertyData[ 'lat' ]) !=null ){ 
				$output[ 'k' ] = count($propertyData[ 'lat' ]);
				}else{
					
					$output[ 'k' ] = 1;
				}
            } 	
					
	//print_r ($output);
	//exit;
				return 	$output;
				}
			
			
			
			
		
	}
}
