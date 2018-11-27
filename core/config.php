<?php

if ( ! defined( 'ABSPATH' ) ) exit;

return [
    'telog' => false,
    'product_id' => '',
    'api_token' => '',
    'db_version' => SL_DB_VERSION,
    'email_risk_level' => 3, // High
    'email_notifications' => false,
    'email' => get_option( 'admin_email' ),
    'exception_fields' => [
        'REQUEST.comment',
        'POST.comment',
        'REQUEST.permalink_structure',
        'POST.permalink_structure',
        'REQUEST.selection',
        'POST.selection',
        'REQUEST.content',
        'POST.content',
        'REQUEST.__utmz',
        'COOKIE.__utmz',
        'REQUEST.s_pers',
        'COOKIE.s_pers',
        'REQUEST.user_pass',
        'POST.user_pass',
        'REQUEST.pass1',
        'POST.pass1',
        'REQUEST.pass2',
        'POST.pass2',
        'REQUEST.password',
        'POST.password',
    ],
    'html_fields' => [

    ],
    'json_fields' => [

    ],
    'new_intrusions_count' => 0,
    'enable_admin' => 1,
    'warning_threshold' => 40,
    'warning_wp_admin' => 0,
    'ban_enabled' => 0,
    'ban_threshold' => 70,
    'attack_repeat_limit' => 5,
    'ban_time' => 300,
    'enable_intrusion_logs' => 1,
    'enable_automatic_updates' => 1,
];