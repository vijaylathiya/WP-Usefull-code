<?php

/**
 *
 * Plugin Name:       CF7 Sender
 * Plugin URI:        https://lathiyasolutions.com/
 * Description:       contect form 7 data to Choicesltd CRM.
 * Version:           1.1
 * Author:            lathiyasolutions
 * Author URI:        https://lathiyasolutions.com/
 * Tested up to:      5.6
 *
 */
defined('ABSPATH') or die('Unauthorized access!');


function on_submit( $form, &$abort, $submission )
{
    if ( $abort === TRUE || $form->ID() !== 999 ) {
        return;
    }

    $data = $submission->get_posted_data();

    $email = sanitize_text_field($data['email']);
    $name = sanitize_text_field($data['name']);

    $response = wp_safe_remote_post(/* your API endpoint */, [
        'body' => json_encode([
            'email' => $email,
            'name' => $name,
        ]),
    ]);

    if ( is_wp_error($response) ) {
        $abort = TRUE;

        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body);

        $submission->set_response($result->error);
        $submission->set_status('api_failed');
    }
}
add_action('wpcf7_before_send_mail', 'on_submit', 10, 3);

//////////////////////////////

add_action('wpcf7_before_send_mail', 'ls_on_submit', 15, 3);
function ls_on_submit($form, &$abort, $submission)
{
    if ($form->id() == 131) {
        $submission = WPCF7_Submission::get_instance();

        if ($submission) {
            $data = $submission->get_posted_data();
            //echo '<pre>';print_r($data);

            $firstnamex = sanitize_text_field($data['firstnamex']);
            $emailx = sanitize_text_field($data['emailx']);
            $homephonex = sanitize_text_field($data['homephonex']);
            $besttimetocontactx = sanitize_text_field(
                $data['besttimetocontactx'][0]
            );
            $officelocationx = sanitize_text_field($data['officelocationx'][0]);
            $primaryinterestx = sanitize_text_field(
                $data['primaryinterestx'][0]
            );
            $Message = sanitize_text_field($data['Message']);

            $sdata_ar = [
                'firstnamex' => $firstnamex,
                'emailx' => $emailx,
                'homephonex' => $homephonex,
                'besttimetocontactx' => $besttimetocontactx,
                'officelocationx' => $officelocationx,
                'primaryinterestx' => $primaryinterestx,
                'Message' => $Message,
            ];

            //echo '<pre>';print_r($sdata_ar);
            $postvars = '';
            foreach ($sdata_ar as $posts => $val) {
                $postvars .= $posts . '=' . $val . '&';
            }
            $postvars .= 'end=true';

            $sub_req_url ='';
            $ch = curl_init($sub_req_url);
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);

            $resp = curl_exec($ch);
 			curl_close($ch);
            return $form;

            $errMsg = false;

            if ($errMsg) {
                $abort = true; // mail will not sent
                $submission->set_status('validation_failed');
                $submission->set_response($form->filter_message('Something went wrong with API CALL')); //custom msg;
            }
            $abort = false;
        }
    }
}

