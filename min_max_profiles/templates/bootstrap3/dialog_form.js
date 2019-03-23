function myFunction(row_id){
var row = "row_"+row_id;

var htm = "";


  document.getElementById(row).innerHTML = htm ;
    removeMarkers(map,row_id);
  
}

function remove(e){


//console.log( e.parentNode.parentNode.parentNode);
 e.parentNode.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode.parentNode)
//var row = "draggable_"+row_id;

//var htm = "";


//  document.getElementById(row).innerHTML = htm ;
  
  
}

	
jomresJquery( "#button_remove_1" ).click(function() {
var row_id = jomresJquery(this).attr('data-row');

var row = "#row_"+row_id;

var htm = "";

  jomresJquery( row ).text( htm );

});
// start add days javascript code pop up dialog and draggable element
  


  jomresJquery( function() {
  
    var dialog, form,
  
    dialog = jomresJquery( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 900,
      width: 1000,
      modal: true,
      buttons: {
        "Save": function() {
			sv();
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
	
	
 function sv(){
var activity = jomresJquery('[name^="activity"]');
var day_title = jomresJquery('input[name^="day_title"]');
var inputValue = '';
var day_idis = jomresJquery('#day_id').val();
var in_p = {};
day_idis  = day_idis.toString()
in_p[day_idis] = {};

for (var row_f = 0; row_f <10; row_f++){

var row = 'row_'+row_f.toString();
in_p[day_idis][row] = {};



}


day_title.each(function() {
 in_p[day_idis][this.name] = jomresJquery(this).val();
});

  tinyMCE.triggerSave();

//console.log(jomresJquery('#act_des_1').val());


activity.each(function() {

var row_i = jomresJquery(this).attr('row_i');
var tst = jomresJquery(this).val(); 
var dess = tst.replace(/\"/g,'\"');
if (this.name =="activity_act_description") {
 in_p[day_idis]['row_'+row_i][this.name] = encodeURIComponent(escapeHtml(dess));
 
 
}else{
in_p[day_idis]['row_'+row_i][this.name] = dess;	
	
}
 console.log(this.name);

});

console.log(in_p);

var day_value = JSON.stringify(in_p);



	var div_html = "<div id='button_edit_days"+day_idis+"' data-row='"+day_idis+"' class='btn btn-md btn-primary add_days' ><i class='fa fa-check'></i>"+in_p[day_idis]['day_title'];		
 div_html  += "<input type='hidden' name='tour_days["+day_idis+"]' id='tour_days_"+day_idis+"' value='"+day_value+"' /></div><div class='inp_move'><div class='move_del'><span class='minus_img' onclick='remove(this)'>remove</span></div></div> "
	
	populateDiv("row_day_"+day_idis,div_html);
	
			
edit_days(day_idis);

}
   
 
    jomresJquery( "#button_add_days" ).button().on( "click", function() {
      
	  var row_id = jomresJquery(this).attr('data-row');
var row = "#row_day_"+row_id;
var row_idi = parseInt(row_id)+1;
jomresJquery(this).attr('data-row',row_idi);
jomresJquery('#day_id').val(row_idi);
dialog.dialog( "open" );
var htm = "<div style='clear:both;' />	<div class='row_1' id='row_day_"+row_idi+"'>"+row_idi+"</div>	";
  jomresJquery( row ).after( htm );

    });
	var vl = '';
	
	function edit_days(r){
		
		console.log("#button_edit_days"+r);
	   jomresJquery( "#button_edit_days"+r ).button().on( "click", function() {
      
	  var row_id = jomresJquery(this).attr('data-row');
var row = "#row_day_"+row_id;
var row_idi = parseInt(row_id);
jomresJquery(this).attr('data-row',row_idi);
jomresJquery('#day_id').val(row_idi);
vl = jomresJquery('#tour_days_'+row_idi).val();
	
var obj = JSON.parse(vl);


var rows = obj[row_idi];

var ht = '';
var k =1;

jomresJquery('#day_t').val(rows['day_title']);
 for (var j = 1; j < 12; j++){
			
		jomresJquery("#section_"+j).remove();
		
		}
jQuery.each(rows, function(i, row) {
 

if ((typeof rows[i]['activity']) != "undefined"  ){

		
		var row_id = k-1;
	   var row = "#section_"+row_id;
	  if (row_id ==0){
		  var row = "#add_b_s";
		  
	  }
		var row_idi = parseInt(row_id)+1;
		jomresJquery(this).attr('data-row',row_idi);
		var htm = "<div style='clear:both;' />";
		htm += "<div id='section_"+row_idi+"' class='section' ><div class='drag' ><div id='draggable' class='ui-widget-content draggable'>";
		
		htm += "<div id='dr_ch'>";
		
		htm += "<div class='inp_1'>	Please Upload Image <input type='file' id='fileselect_"+row_idi+"' name='fileselect_"+row_idi+"' multiple='multiple'  row_i='"+row_idi+"' />";
		htm += "<input type='hidden' id='activity_fileselect_"+row_idi+"' name='activity_fileselect'  row_i='"+row_idi+"' value='"+rows[i]['activity_fileselect']+"' /> 	</div>";
	
		htm += "<div id='progress_"+row_idi+"' class='inp_2 progress_1'>"+rows[i]['activity_fileselect']+"</div>";
		htm += "<div style='clear:both;margin-bottom:15px;'> </div>";
				
		htm += "<div class='inp_1'><input name='activity'  type='text' placeholder='Activity' value='"+rows[i]['activity']+"'  id='act_"+row_idi+"'  row_i='"+row_idi+"' /></div>";
	//	htm += "<div class='inp_2'><input type='text'  class='input_1' name='activity_act_description' value='"+rows[i]['activity_act_description']+"' id='act_des_"+row_idi+"'  row_i='"+row_idi+"' value='' placeholder='Activity description'/></div><div class='inp_move'><div class='move_par'><span class='move_img'>move</span></div></div>";
		
		htm += "<div class='inp_2 editor js-editor-tinymce'> <textarea   class='input_1 mce_editable joomla-editor-tinymce'  id='act_des_"+row_idi+"' name='activity_act_description'   row_i='"+row_idi+"'>"+decodeURIComponent(rows[i]['activity_act_description'])+"</textarea> </div><div class='inp_move'><div class='move_par'><span class='move_img'>move</span></div></div>";

		htm += "<div class='inp_move'><div class='move_del'><span class='minus_img' onclick='remove(this)'>remove</span></div></div></div>";
		htm += "</div>";
		
		
		htm += " </div> <div class='clear_me' ></div>";
	
		htm += "	</div>  </div>";
		
	
		if ( jomresJquery( row  ).length ) {
		console.log(row);	

		}
		jomresJquery( "#dialog-form" ).append(htm );

				//jomresJquery( row  ).after( htm );
		//tinymce.EditorManager.execCommand('mceRemoveEditor', false, 'act_des_'+row_idi);
		//tinymce.EditorManager.execCommand('mceAddEditor', false, 'act_des_'+row_idi);
		//enable_editor();
	
	
		jomresJquery('#add_s').attr('data-row',k);
		drag_drop();
		tinymce.remove('#act_des_'+row_idi);
		tinymce.init({
            theme: 'modern',
            selector: '#act_des_'+row_idi
        });
Init(row_idi);

k++;

}
	
	});


dialog.dialog( "open" );

    });
	
	
	}
	var r ='';
	edit_days(r);
    jomresJquery( "#add_s" ).button().on( "click", function() {
	var row_id = jomresJquery(this).attr('data-row');
    var row = "#section_"+row_id;
	 if (row_id ==0){
		  var row = "#add_b_s";
		  
	  }
		var row_idi = parseInt(row_id)+1;
		jomresJquery(this).attr('data-row',row_idi);
		var htm = "<div style='clear:both;' />";
		htm += "<div id='section_"+row_idi+"' class='section' ><div class='drag' ><div id='draggable' class='ui-widget-content draggable'>";
		
				
		htm += "<div id='dr_ch'>";
		
			htm += "<div class='inp_1'>	Please Upload Image <input type='file' id='fileselect_"+row_idi+"' name='fileselect_"+row_idi+"' multiple='multiple'  row_i='"+row_idi+"' />";
		htm += "<input type='hidden' id='activity_fileselect_"+row_idi+"' name='activity_fileselect'  row_i='"+row_idi+"' value='' /> 	</div>";
	
		htm += "<div id='progress_"+row_idi+"' class='inp_2 progress_1'></div>";
		htm += "<div style='clear:both;margin-bottom:15px;'> </div>";
		
		htm += "<div class='inp_1'><input name='activity' value='' type='text' placeholder='Activity' id='act_"+row_idi+"'  row_i='"+row_idi+"' /></div>";
		
		htm += "<div class='inp_2 editor js-editor-tinymce'> <textarea   class='input_1 mce_editable joomla-editor-tinymce'  id='act_des_"+row_idi+"' name='activity_act_description'   row_i='"+row_idi+"'></textarea> </div><div class='inp_move'><div class='move_par'><span class='move_img'>move</span></div></div>";

		
		
		htm += "<div class='inp_move'><div class='move_del'><span class='minus_img' onclick='remove(this)'>remove</span></div></div></div>";
		htm += "</div>";
		
		
		htm += " </div> <div class='clear_me' ></div>";
	
		htm += "	</div>  </div>";
		
		jomresJquery( "#dialog-form" ).append(htm );
		
	Init(row_idi);
	tinymce.remove('#act_des_'+row_idi);
		tinymce.init({
            theme: 'modern',
            selector: '#act_des_'+row_idi
        });
	 drag_drop();
  
  
    });
	
	var ds = jomresJquery('#day_idis').val();
	
	if (ds !='null'){
	var dys = JSON.parse(ds);
	
	var max = Math.max.apply(Math, dys);

	
	var nex = max;
	if (nex !=NaN) { 	jomresJquery('#button_add_days').attr('data-row',nex); }
	//	console.log(nex ); console.log(dys);
	
	jQuery.each(dys, function(i, k) {
	edit_days(k);
		
	});
	}
	
	
	
	// upload functions starts 
		// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
		m.innerHTML = msg + m.innerHTML;
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {
		var idi = e.target.id;
		
	var row_id = idi.split("_");
	
	var rw_id = row_id[1];
		
		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			//ParseFile(f);
			UploadFile(f,rw_id);
		}

	}


	// output file information
	function ParseFile(file) {

		Output(
			"<p>File information: <strong>" + file.name +
			"</strong> type: <strong>" + file.type +
			"</strong> size: <strong>" + file.size +
			"</strong> bytes</p>"
		);

		// display an image
		if (file.type.indexOf("image") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p><strong>" + file.name + ":</strong><br />" +
					'<img src="' + e.target.result + '" /></p>'
				);
			}
			reader.readAsDataURL(file);
		}

		// display text
		if (file.type.indexOf("text") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p><strong>" + file.name + ":</strong></p><pre>" +
					e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
					"</pre>"
				);
			}
			reader.readAsText(file);
		}

	}


	// upload JPEG files
	function UploadFile(file,rw_id) {

		// following line is not necessary: prevents running on SitePoint servers
		if (location.host.indexOf("sitepointstatic") >= 0) return

		var xhr = new XMLHttpRequest();
		if (xhr.upload   && ( file.type == "image/jpeg" || file.type == "image/png"))  {
			
			
		var ranumber = 	Math.floor(Math.random() * 10000);
			// create progress bar
			var o = $id("progress_"+rw_id);
			o.innerHTML='';
			var progress = o.appendChild(document.createElement("p"));
			progress.appendChild(document.createTextNode("upload " + file.name));

			document.getElementById("activity_fileselect_"+rw_id).value = ranumber+"_"+file.name;
			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);

			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "success" : "failure");
				}
			};

			// start upload
			xhr.open("POST", "jomres/remote_plugins/min_max_profiles/filedrag/upload.php?fname="+ranumber, true);
			xhr.setRequestHeader("X-FILENAME", file.name);
			xhr.send(file);
			
		

		}

	}


	// initialize
	function Init(i) {
		
		

		var fileselect = $id("fileselect_"+i),
			filedrag = $id("filedrag"),
			submitbutton = $id("submitbutton");
	console.log("initialize_"+i);
		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
		//	filedrag.addEventListener("dragover", FileDragHover, false);
		//	filedrag.addEventListener("dragleave", FileDragHover, false);
		//	filedrag.addEventListener("drop", FileSelectHandler, false);
		//	filedrag.style.display = "block";

			// remove submit button
			//submitbutton.style.display = "none";
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init(1);
		
		
	}
// end
	
  } );
  function drag_drop(){

	/*
	  jomresJquery( ".droppable" ).draggable({
     helper:"clone",
       handle: "div.move_par",
       cursor: "crosshair",
        start: function( event, ui ) {
                   // console.log(ui.helper.context)
                  //  console.log('cl',ui.helper.clone().context.innerHTML);
				   enable_editor();
                }
    });
	
	jomresJquery( ".droppable" ).droppable(
	
	{
       hoverClass: "ui-state-over",
	   
      drop: function( event, ui ) {
        jomresJquery( this )
          .addClass( "ui-state-highlight" )
          .find( "div" )
            .html( ui.helper.clone().context.innerHTML );
         enable_editor();
            
      }
    });
	*/
	jomresJquery( ".draggable" ).droppable(
	
	{
       hoverClass: "ui-state-over",
	   
      drop: function( event, ui ) {
		  jomresJquery( this )
          .addClass( "ui-state-highlight" )
          .find( "div" )
            .html( '' );
        jomresJquery( this )
          .addClass( "ui-state-highlight" )
          .find( "div" )
            .html( ui.helper.clone().context.innerHTML );
			console.log( ui.helper.clone().context.innerHTML);
			 
				console.log("dr");
			 jomresJquery(ui.draggable).remove();
			
            

      }
    });
	
		
	
	  jomresJquery( ".draggable" ).draggable({
     helper:"clone",
       handle: "div.move_par",
       cursor: "crosshair",
        start: function( event, ui ) {
                tinyMCE.triggerSave();
                 var html = jomresJquery(ui.helper.context);
				 
				 
			var row = jomresJquery(this).find('textarea').attr('id');
			var in_put = jomresJquery(this).find('input').val();
			
			
	tinymce.remove('#'+row);
	//console.log('start',row);
	console.log('start_val', ui.helper.clone().context.innerHTML);
		            },
		stop:function( event, ui ) {
                  tinyMCE.triggerSave();
                 var html = jomresJquery(ui.helper.context);
			var row = jomresJquery(this).find('textarea').attr('id');
			var text_val= jomresJquery(this).find('textarea').val();
			var inp_val = jomresJquery(this).find('input').val();
			var inp_id = jomresJquery(this).find('input').attr('id');
			jomresJquery("#"+inp_id).val(inp_val);
			
			jomresJquery("#"+row ).val(text_val);
			
		console.log('stop', ui.helper.clone().context.innerHTML);
	
	tinymce.remove('#'+row);
		tinymce.init({
            theme: 'modern',
            selector: '#'+row
        });

	console.log('stop',row);
		            }
    });
  
 
  }
  
  function enable_editor(){
	  
	  
	  
	 var drop_row = 40;
for (var i = 0; i <= drop_row; i++) {
  	tinymce.EditorManager.execCommand('mceRemoveEditor', false, 'act_des_'+i);
		tinymce.EditorManager.execCommand('mceAddEditor', false, 'act_des_'+i);
}  
	  
  }
  
  var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
  };

  function escapeHtml(string) {
	  console.log(string);
    return String(string).replace(/[&<>"'\/]/g, function (s) {
		
      return entityMap[s];
    });
  }

  jomresJquery( function() {
  
 drag_drop();
	    
  } );