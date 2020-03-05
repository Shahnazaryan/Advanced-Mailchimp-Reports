<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 22.10.2019
 * Time: 11:14
 */

/**
 * Class admcre_mailchimpAPI
 *
 *
 * $api_key = '61a2d363023224224bfa0c0843769-us20';
 * $list_id = '1f64ff7f26a';
 */
class admcre_mailchimpAPI
{
    /**
     * Get API_Key number and
     * update admcre_mailchimpAPIKEY give id ->1
     */
    function admcre_get_API_Key(){
        global $wpdb;
        $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
        if(isset($_POST['save_changes'])){
            $api_key = $_POST['api_key'];
            $wpdb->UPDATE ($table_mailchimpAPIKEY, array(
                'getAPI_key'  => $api_key
            ),
                array( 'ID' => 1 )
            );

            $api_key_id = $wpdb->get_results( "SELECT getAPI_key FROM $table_mailchimpAPIKEY");
            $firstTimeAPIKey=$api_key_id[0]->getAPI_key;

        }
        else{
            $api_key_id = $wpdb->get_results( "SELECT getAPI_key FROM $table_mailchimpAPIKEY");
            $firstTimeAPIKey=$api_key_id[0]->getAPI_key;
        }
        return $firstTimeAPIKey;
    }

    /**
     * insert campain list into MySQL date
     * date name is admcre_mailchimp_body
     * update admcre_mailchimpAPIKEY getAPI_id=>1
     * @return array All campain list in array
     *
     */
    function admcre_inputSQL_DB_mailchimp_campaigns(){
        $campaigns=$this->admcre_mailchimp_api_request('campaigns', 'GET' );
        global $wpdb;
        $table_mailchimp_body = $wpdb->prefix . 'admcre_mailchimp_body';
        for($x=0;$x<count($campaigns->campaigns);$x++) {
            if ($campaigns->campaigns[$x]->status === 'sent') {
                $wpdb->insert(
                    $table_mailchimp_body,
                    array(
                        'web_id'                            => $campaigns->campaigns[$x]->web_id,
                        'create_time'                       => $campaigns->campaigns[$x]->create_time,
                        'status'                            => $campaigns->campaigns[$x]->status,
                        'emails_sent'                       => $campaigns->campaigns[$x]->emails_sent,
                        'send_time'                         => $campaigns->campaigns[$x]->send_time,
                        'recipients_list_id'                => $campaigns->campaigns[$x]->recipients->list_id,
                        'recipients_list_name'              => $campaigns->campaigns[$x]->recipients->list_name,
                        'recipients_recipient_count'        => $campaigns->campaigns[$x]->recipients->recipient_count,
                        'settings_subject_line'             => $campaigns->campaigns[$x]->settings->subject_line,
                        'settings_preview_text'             => $campaigns->campaigns[$x]->settings->preview_text,
                        'settings_title'                    => $campaigns->campaigns[$x]->settings->title,
                        'report_summary_opens'              => $campaigns->campaigns[$x]->report_summary->opens,
                        'report_summary_unique_opens'       => $campaigns->campaigns[$x]->report_summary->unique_opens,
                        'report_summary_open_rate'          => $campaigns->campaigns[$x]->report_summary->open_rate,
                        'report_summary_clicks'             => $campaigns->campaigns[$x]->report_summary->clicks,
                        'report_summary_subscriber_clicks'  => $campaigns->campaigns[$x]->report_summary->subscriber_clicks,
                        'report_summary_click_rate'         => $campaigns->campaigns[$x]->report_summary->click_rate,
                        )
                );
            }
        }
        /**********************************************************************/
        global $wpdb;
        $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
        $wpdb->UPDATE ($table_mailchimpAPIKEY, array(
            'getAPI_id'  => 1
        ),
            array( 'ID' => 1 )
        );
        /**********************************************************************/
        return $campaigns;
    }

    /**
     * @param $endpoint campaigns
     * @param string $type
     * @param string $body
     * @return array|bool|mixed|object
     */
    function admcre_mailchimp_api_request( $endpoint, $type = 'POST', $body = '' )
    {
        global $wpdb;
        $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
        $api_key_id = $wpdb->get_results( "SELECT getAPI_key FROM $table_mailchimpAPIKEY");
        $api_key=$api_key_id[0]->getAPI_key;

            if(strstr($api_key,'-us')) {
                $core_api_endpoint = 'https://<dc>.api.mailchimp.com/3.0/';
                list(, $datacenter) = explode('-', $api_key);
                $core_api_endpoint = str_replace('<dc>', $datacenter, $core_api_endpoint);

                $url = $core_api_endpoint . $endpoint;

                $request_args = array(
                    'method' => $type,
                    'timeout' => 20,
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'apikey ' . $api_key
                    )
                );

                if ($body) {
                    $request_args['body'] = json_encode($body);
                }
                $request = wp_remote_post($url, $request_args);
                $response = is_wp_error($request) ? false : json_decode(wp_remote_retrieve_body($request));

                return $response;
            }
        }
    /**
     *
     */
    function admcre_mailchimp_api_validation( $endpointValid, $type = 'POST', $body = '' )
    {
        global $wpdb;
        $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
        $api_key_id = $wpdb->get_results( "SELECT getAPI_key FROM $table_mailchimpAPIKEY");
        $api_key=$api_key_id[0]->getAPI_key;


        // Configure --------------------------------------

        //   $api_key = '61a2d3630e62ceab7752bfa0c0843769-us20';

        // STOP Configuring -------------------------------
        if(strstr($api_key,'-us')) {
            $core_api_endpoint = 'https://<dc>.api.mailchimp.com/3.0/';
            list(, $datacenter) = explode('-', $api_key);

            $core_api_endpoint = str_replace('<dc>', $datacenter, $core_api_endpoint);

            $url = $core_api_endpoint . $endpointValid;

            $request_args = array(
                'method' => $type,
                'timeout' => 20,
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'apikey ' . $api_key
                )
            );
            $request = wp_remote_post($url, $request_args);
            return $request;
        }
        else
        {
            $request=array(
                'response'=>array(
                    'code' =>401
                )
            );
            return $request;

        }
    }

    /**
     * update campaing List in mailchimp
     */

    function admcre_mailchimp_DB_update()
    {
        $campaigns_new = $this->admcre_mailchimp_api_request('campaigns', 'GET');
        global $wpdb;
        $table_mailchimp_body = $wpdb->prefix . 'admcre_mailchimp_body';
        $campaigns_old = $wpdb->get_results("SELECT * FROM $table_mailchimp_body");
        for ($x = 0; $x<count($campaigns_new->campaigns); $x++){
            if ($campaigns_new->campaigns[$x]->status === 'sent'){
                $key = array_search($campaigns_new->campaigns[$x]->web_id, array_column($campaigns_old, 'web_id'));
                if (!is_numeric($key)){
                    echo "Done! Mailchimp lists renewed. \n";
                    $wpdb->insert(
                        $table_mailchimp_body,
                        array(
                            'web_id'                            => $campaigns_new->campaigns[$x]->web_id,
                            'create_time'                       => $campaigns_new->campaigns[$x]->create_time,
                            'status'                            => $campaigns_new->campaigns[$x]->status,
                            'emails_sent'                       => $campaigns_new->campaigns[$x]->emails_sent,
                            'send_time'                         => $campaigns_new->campaigns[$x]->send_time,
                            'recipients_list_id'                => $campaigns_new->campaigns[$x]->recipients->list_id,
                            'recipients_list_name'              => $campaigns_new->campaigns[$x]->recipients->list_name,
                            'recipients_recipient_count'        => $campaigns_new->campaigns[$x]->recipients->recipient_count,
                            'settings_subject_line'             => $campaigns_new->campaigns[$x]->settings->subject_line,
                            'settings_preview_text'             => $campaigns_new->campaigns[$x]->settings->preview_text,
                            'settings_title'                    => $campaigns_new->campaigns[$x]->settings->title,
                            'report_summary_opens'              => $campaigns_new->campaigns[$x]->report_summary->opens,
                            'report_summary_unique_opens'       => $campaigns_new->campaigns[$x]->report_summary->unique_opens,
                            'report_summary_open_rate'          => $campaigns_new->campaigns[$x]->report_summary->open_rate,
                            'report_summary_clicks'             => $campaigns_new->campaigns[$x]->report_summary->clicks,
                            'report_summary_subscriber_clicks'  => $campaigns_new->campaigns[$x]->report_summary->subscriber_clicks,
                            'report_summary_click_rate'         => $campaigns_new->campaigns[$x]->report_summary->click_rate,
                        )
                    );
                }
            }
        }

    }




    /*****************************************************/

}





