<?php
add_action( 'wpcf7_before_send_mail', 'ls_on_submit', 10, 3 );
//function ls_on_submit( $form)
function ls_on_submit( $form,&$abort, $submission)
{
    
	if ( $abort === TRUE || $form->ID()!==15906){  // replace 242 by your Form ID 
	    return;
    }
	
    //$title = $form->title;
	//if($title === 'Internet inquiry'){
    	//$submission = WPCF7_Submission::get_instance();
		if( $submission ){
    		$data 		= $submission->get_posted_data();
			$text_val 	= $data['message']; // get value of your text area field
			if ($text_val != strip_tags($text_val))
			{
				// if HTML found in your Text area 
				//echo "inside";
				$abort = TRUE;
				$submission->set_response('Something wrong with your enter message');
				$submission->set_status('api_failed');
				return;
			}
			
    	}
}
	
		