jomresJquery(document).ready(function(){

    // initialize tooltip
    jomresJquery( ".jintour_third_party_extra_div" ).tooltip({
				
		track:true,
        open: function( event, ui ) {
              var id = this.id;
            //  var split_id = id.split('_');
            //  var userid = split_id[1];
            var content_ht =jomresJquery("#"+id+"_table").html()


            jomresJquery("#"+id).tooltip('option','content', content_ht);

         /*
              jomresJquery.ajax({
                  url:'index.php?option=com_jomres&Itemid=103&lang=en&show=no_html&task=jintour_edit_profile&id='+userid,
                  type:'post',
                  data:{userid:userid},
				 success: function(response){
          var content_ht =jomresJquery(response).find("#table_price").html();
                      // Setting content option

                        
                  }
              });
              */
        }
    });

   jomresJquery(".jintour_third_party_extra_div").mouseout(function(){
        // re-initializing tooltip
	
       jomresJquery(this).attr('title','Please wait...');
        jomresJquery(this).tooltip();
        jomresJquery('.ui-tooltip').hide();
    });

    
});

