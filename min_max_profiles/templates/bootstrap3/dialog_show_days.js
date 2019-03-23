

  jomresJquery( function() {
  
    var dialog, form,
  
    dialog = jomresJquery( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 900,
      width: 1000,
      modal: true,
      buttons: {
        "Save": function() {
			
          dialog.dialog( "close" );
        },
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
       
      //  allFields.removeClass( "ui-state-error" );
      }
    });
	
	
 
  } );
  