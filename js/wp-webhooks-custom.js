jQuery(document).ready(function(){
	
})

jQuery(document).on("click",".sendHooksRequest",function(evt){
	evt.preventDefault();
	var formdata = jQuery(this).closest("form");
	var serialized = formdata.serialize();
	jQuery.ajax({
    url: ajaxurl,
    method: 'POST',
    data: serialized,
    success: function(data){
			//var response = jQuery.parseJSON(data);
		  var response = data.split("||||");
      //console.log(response.header);
			jQuery(".headerResponse").val(response[0]);
			jQuery(".bodyResponse").val(response[1]);
    }
  });
	
});

jQuery(document).on("click",".urlkey,.urlvalue",function(){
	if(jQuery(this).closest("tr").is(':last-child')){
		var html = "";
		var remove = "";
		html += '<tr>';
		html += '<td>';
		html += '<input style="width:300px;" type="text" name="urlparams[key][]" placeholder="Key" class="urlkey">';
		html += '</td>';
		html += '<td>';
		html += '<input style="width:300px;" type="text" name="urlparams[value][]" placeholder="Value" class="urlvalue">';
		html += '</td>';
		remove += '<td><a style="cursor:pointer" class="removeDiv">Remove</a></td>';
		remove += '</tr>';
		jQuery(this).closest("tr").append(remove)
		jQuery(".appendUrlDiv").append(html);
	}
});

jQuery(document).on("click",".headerkey,.headervalue",function(){
	if(jQuery(this).closest("tr").is(':last-child')){
		var html = "";
		var remove = "";
		html += '<tr>';
		html += '<td>';
		html += '<input style="width:300px;" type="text" name="header[key][]" placeholder="Key" class="headerkey">';
		html += '</td>';
		html += '<td>';
		html += '<input style="width:300px;" type="text" name="header[value][]" placeholder="Value" class="headervalue">';
		html += '</td>';
		remove += '<td><a style="cursor:pointer" class="removeDiv">Remove</a></td>';
		remove += '</tr>';
		jQuery(this).closest("tr").append(remove)
		jQuery(".appendHeaderDiv").append(html);
	}
});

jQuery(document).on("click",".bodykey,.bodyvalue",function(){
	if(jQuery(this).closest("tr").is(':last-child')){
		var html = "";
		var remove = "";
		html += '<tr>';
		html += '<td>';
		html += '<input style="width:300px;" type="text" name="body[key][]" placeholder="Key" class="bodykey">';
		html += '</td>';
		html += '<td>';
		html += '<input style="width:300px;" type="text" name="body[value][]" placeholder="Value" class="bodyvalue">';
		html += '<input type="checkbox" name="body[is_array][]" value="1" class="is_array">Is array';
		html += '</td>';
		remove += '<td><a style="cursor:pointer" class="removeDiv">Remove</a></td>';
		remove += '</tr>';
		jQuery(this).closest("tr").append(remove)
		jQuery(".appendBodyDiv").append(html);
	}
});

jQuery(document).on("click",".removeDiv",function(){
  jQuery(this).closest("tr").remove();
})

jQuery(document).on("click",".hookClick",function(){
  var rel = jQuery(this).attr("rel");
})

jQuery(document).on("click",".validateJson",function(){
	var jsonInput   = jQuery(".raw-form-data-area");
	var jsonResults = jQuery(".Validateresult");

	jsonInput.validateJSON({
		'onSuccess': function(json) {
			jsonResults.removeClass('ui-state-error').html("<span style='color:green'>Valid JSON</span>");
		},
		'onError': function(error) {
			jsonResults.addClass('ui-state-error').html(error.message || "<span style='color:red'>Invalid</span>");
		}
	});
})

jQuery(document).on("click",".applied_on",function(){
	var total = jQuery(".applied_on").length;
	var count = jQuery(".applied_on:checked").length;
	if(jQuery(this).is(":checked")){
		if(jQuery(this).val() == "all"){
			jQuery(".applied_on").prop('checked', true);
		}
	}else{
		if(jQuery(this).val() == "all"){
			jQuery(".applied_on").prop('checked', false);
		}
	}
})