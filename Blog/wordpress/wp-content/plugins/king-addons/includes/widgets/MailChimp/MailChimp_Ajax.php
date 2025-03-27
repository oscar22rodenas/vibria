<?php

namespace King_Addons;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class MailChimp_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_king_addons_mailchimp_subscribe', [$this, 'mailchimp_subscribe']);
        add_action('wp_ajax_nopriv_king_addons_mailchimp_subscribe', [$this, 'mailchimp_subscribe']);
    }

    public static function mailchimp_subscribe()
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'king_addons_mailchimp_nonce')) {
            return;
        }

        // Retrieve API key from settings
        $api_key = get_option('king_addons_mailchimp_api_key', '');
        // Parse list ID
        $list_id = sanitize_text_field(wp_unslash($_POST['listId'] ?? ''));

        // Parse form fields
        parse_str($_POST['fields'] ?? '', $fields);

        // Prepare data
        $email = sanitize_text_field($fields['king_addons_mailchimp_email'] ?? '');
        $merge_fields = [
            'FNAME' => sanitize_text_field($fields['king_addons_mailchimp_firstname'] ?? ''),
            'LNAME' => sanitize_text_field($fields['king_addons_mailchimp_lastname'] ?? ''),
            'PHONE' => sanitize_text_field($fields['king_addons_mailchimp_phone_number'] ?? ''),
        ];

        // Build Mailchimp API endpoint
        $api_url = sprintf(
            'https://%s.api.mailchimp.com/3.0/lists/%s/members/%s',
            explode('-', $api_key)[1],
            $list_id,
            md5(strtolower($email))
        );

        // Set up request args
        $api_args = [
            'method'  => 'PUT',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'apikey ' . $api_key,
            ],
            'body' => json_encode([
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields'  => $merge_fields,
            ]),
        ];

        // Send request
        $response = wp_remote_post($api_url, $api_args);

        // Check response
        if (!is_wp_error($response)) {
            $body = json_decode(wp_remote_retrieve_body($response));

            if (!empty($body)) {
                if (isset($body->status) && $body->status === 'subscribed') {
                    wp_send_json(['status' => 'subscribed']);
                } else {
                    wp_send_json(['status' => $body->title ?? '']);
                }
            }
        }
    }
}

new MailChimp_Ajax();