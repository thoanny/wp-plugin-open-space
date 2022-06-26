<?php

// [streamelements-activity]
function anthony_shortcode_streamelements_activity() {
    return '<div class="streamelement-activities"><i class="fas fa-sync fa-spin"></i></div>';
}
add_shortcode( 'streamelements-activity', 'anthony_shortcode_streamelements_activity' );


add_action( 'wp_ajax_streamelements_activity', 'anthony_ajax_streamelements_activity' );
add_action( 'wp_ajax_nopriv_streamelements_activity', 'anthony_ajax_streamelements_activity' );


function anthony_ajax_streamelements_activity() {

    $api_key = get_field('streamelements_api_key', 'option');
    $channel_id = get_field('streamelements_channel_id', 'option');

    if(!$api_key || !$channel_id) {
        echo json_encode(['error' => 'missing.attr']);
        wp_die();
    }

    $options = [
        'http' => [
            'header' => "Authorization: Bearer $api_key\r\n"
        ]
    ];

    $params = http_build_query([
        'limit' => 50,
        'types' => [
            'follow',
            'tip',
            'cheer',
            'raid',
            'subscriber',
        ],
        'after' => '2021-05-18'
    ]);

    $params = urldecode($params);

    $url = "https://api.streamelements.com/kappa/v2/activities/$channel_id?$params";

    $context = stream_context_create($options);
    $content = file_get_contents($url, false, $context);

    if($content) {
        echo json_encode(['activities' => json_decode($content)]);
        wp_die();
    }

    echo json_encode(['error' => 'api.unvailable', 'url' => $url]);
    wp_die();
}
