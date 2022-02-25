<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


function module_color($param)
{
    switch ($param) {
        case 'files':
            return 'info';
            break;
          
        default:
            return 'default';
            break;
    }
}

function replace_email_tags($tag, $value, $string)
{
    return str_replace('{'.$tag.'}', $value, $string);
}

function create_email_logo()
{
    return '<img style="width:'.config_item('invoice_logo_width').'px" alt="'.config_item('company_name').'" src="'.base_url().'resource/images/logos/'.config_item('invoice_logo').'"/>';
}

function get_tags($template)
{
    switch ($template) {
        case 'sms':
            return array('CLIENT', 'REF', 'RENEWAL_DATE', 'SERVICE', 'RENEWAL', 'AMOUNT', 'SITE_NAME', 'CURRENCY', 'PAID_AMOUNT');
            break;
        case 'activate_account':
            return array('INVOICE_LOGO', 'USERNAME', 'ACTIVATE_URL', 'ACTIVATION_PERIOD', 'EMAIL', 'PASSWORD', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'hosting_account':
            return array('INVOICE_LOGO', 'CLIENT', 'ACCOUNT_USERNAME', 'ACCOUNT_PASSWORD', 'DOMAIN', 'RENEWAL_DATE', 'PACKAGE', 'RENEWAL', 'AMOUNT', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'service_suspended':
            return array('CLIENT', 'DOMAIN', 'RENEWAL_DATE', 'PACKAGE', 'RENEWAL', 'AMOUNT', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'change_email':
            return array('INVOICE_LOGO', 'NEW_EMAIL', 'NEW_EMAIL_KEY_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'forgot_password':
            return array('INVOICE_LOGO', 'PASS_KEY_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'registration':
            return array('INVOICE_LOGO', 'USERNAME', 'SITE_URL', 'EMAIL', 'PASSWORD', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'reset_password':
            return array('INVOICE_LOGO', 'USERNAME', 'EMAIL', 'NEW_PASSWORD', 'SITE_NAME', 'SIGNATURE');
            break;       
        case 'invoice_message':
            return array('INVOICE_LOGO', 'REF', 'CLIENT', 'CURRENCY', 'AMOUNT', 'INVOICE_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'invoice_reminder':
            return array('INVOICE_LOGO', 'REF', 'CLIENT', 'CURRENCY', 'AMOUNT', 'INVOICE_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'payment_email':
           return array('INVOICE_LOGO', 'REF', 'INVOICE_CURRENCY', 'PAID_AMOUNT', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_client_email':
            return array('INVOICE_LOGO', 'CLIENT_EMAIL', 'SUBJECT', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_closed_email':
            return array('INVOICE_LOGO', 'REPORTER_EMAIL', 'SUBJECT', 'STAFF_USERNAME', 'TICKET_CODE', 'TICKET_STATUS', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_reply_email':
           return array('INVOICE_LOGO', 'SUBJECT', 'TICKET_CODE', 'TICKET_STATUS', 'TICKET_REPLY', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_staff_email':
            return array('INVOICE_LOGO', 'USER_EMAIL', 'SUBJECT', 'REPORTER_EMAIL', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'auto_close_ticket':
            return array('INVOICE_LOGO', 'REPORTER_EMAIL', 'SUBJECT', 'TICKET_CODE', 'TICKET_STATUS', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_reopened_email':
            return array('INVOICE_LOGO', 'RECIPIENT', 'SUBJECT', 'USER', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'message_received':
            return array('INVOICE_LOGO', 'RECIPIENT', 'SENDER', 'MESSAGE', 'SITE_URL', 'SITE_NAME', 'SIGNATURE');
            break;

        default:
            return array();
            break;
    }
}
function extract_tags($string, $start = '{', $end = '}')
{
    $matches = array();
    $regex = "/$start([a-zA-Z0-9_]*)$end/";
    preg_match_all($regex, $string, $matches);

    return $matches[1];
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function is_json($str) {
 return is_string($str) && is_array(json_decode($str, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
