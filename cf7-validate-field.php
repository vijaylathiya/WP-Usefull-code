<p>Reference Number (required)<br />[number*  name-number id:refno minlength:9]</p>
<p>[submit class:lsrecordrequest "Send" ]</p>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('body').delegate('.lsrecordrequest', 'click', function(){
		var $this = $(this);
		if($('#refno').val().length < 9 || $('#refno').val().length > 9)
		{
			if(!$('#lsrefspan').length > 0)
			{
			$('#refno').after('<span id="lsrefspan" role="alert" class="wpcf7-not-valid-tip ">Reference Number is required with 9 Digit.</span>');	}
			return false;
		}
	});
	
});

jQuery('#uk_phone').bind('keyup blur',function(){ 
	var node = jQuery(this);
	node.val(node.val().replace(/[^0-9]/,'') ); 
	
	if(node.val().length < 11 || node.val().length > 11)
	{
		if(!jQuery('#lsrefspan').length > 0)
		{
			node.after('<span id="lsrefspan" role="alert" class="ls-not-valid-tip ">Phone Number is required with 11 Digit.</span>');	
		}
		return false;
	}
	else
	{
		if(jQuery('#lsrefspan').length > 0)
			jQuery('#lsrefspan').remove();
	}
});
	
	
	$('.alphaonly').bind('keyup blur',function(){ 
		var node = $(this);
		node.val(node.val().replace(/[^A-Za-z_\s]/,'') ); }   // (/[^a-z]/g,''
	);
	$('.numberonly').bind('keyup blur',function(){ 
		var node = $(this);
		node.val(node.val().replace(/[^0-9]/,'') ); }   // (/[^a-z]/g,''
	);
	$('.numberandalphonly').bind('keyup blur',function(){ 
		var node = $(this);
		node.val(node.val().replace(/[^A-Za-z0-9\s]/,'') ); }   // (/[^a-z]/g,''
	);
	
	
	function numbersonly(myfield, e, dec)
{
	var key;
	var keychar;	
	if (window.event)
	   key = window.event.keyCode;
	else if (e)
	   key = e.which;
	else
	   return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || 
		(key==9) || (key==13) || (key==27) || (key==118) )
	   return true;
	
	// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
	   return true;
	
	// decimal point jump
	else if (dec && (keychar == "."))
	{
	   myfield.form.elements[dec].focus();
	   return false;
	}
	else
	{
	   return false;
	}
}
/*
	$("input[name='your-number']").keyup(function() {
    	$(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "($1)$2-$3"));
	});
*/
</script>
<?php