<?php
function get_theme_logo($n = 1){
    return get_theme_logos("logo{$n}");
}

function get_theme_favicon(){
    return get_theme_logos('favicon');
}

function get_theme_touch_icon(){
    return get_theme_logos('touch_icon');
}

function get_theme_logos($key = ''){
    $logos = get_option('theme_logos');
    $logos = $logos ? $logos: [
        'logo1' => '',
        'logo2' => '',
        'favicon' => '',
        'touch_icon' => '',
    ];
    $key = $key && isset($logos[$key]) ? $key: '';
    if($key){
        $logos = [$key => $logos[$key]];
    } 
    foreach($logos as &$logo){
        $logo = get_theme_image($logo);
    }
    return $key ? $logos[$key]: (object)$logos;
}
	
function get_theme_contact(){
    $contact = get_option('theme_contact');
    $contact = $contact ? $contact: [
        'phone1' => '',
        'phone2' => '',
        'email1' => '',
        'email2' => '',
        'address' => '',
        'map' => '',
        'schedules' => '',
        'form' => '',
    ];
    $has_contact = array_filter($contact, function($val){
        return $val;
    });
    $contact['has_contact'] = (bool)$has_contact;
    $contact['phone1_plain'] = preg_replace('/[^0-9]/', '', $contact['phone1']);
    $contact['phone2_plain'] = preg_replace('/[^0-9]/', '', $contact['phone2']);
    return (object)$contact;
}
	
function get_theme_social(){
    $social = get_option('theme_social');
    $social = $social ? $social: [
        'facebook' => '',
        'twitter' => '',
        'youtube' => '',
        'instagram' => '',
        'pinterest' => '',
        'linkedin' => '',
        'tiktok' => '',
        'spotify' => '',
    ];
    $has_social = array_filter($social, function($val){
        return $val;
    });
    $social['has_social'] = (bool)$has_social;
    return json_decode(json_encode($social));
}
	
function get_theme_texts($key = ''){
    $texts = get_option('theme_texts');
    $default = [
        'copyright',
        'powered_by',
    ];
    $texts = $texts ? $texts: [];
    foreach($default as $v){
        $texts[$v] = isset($texts[$v]) ? $texts[$v]: '';
    }
    
    $has_texts = array_filter($texts, function($val){
        return $val;
    });
    $texts['has_texts'] = (bool)$has_texts;
    $key = $key && isset($texts[$key]) ? $key: '';
    if($key){
        $texts = [$key => $texts[$key]];
    } 
    return $key ? $texts[$key]: (object)$texts;
}
	
function get_theme_links(){
    $links = get_option('theme_links');
    $links = $links ? $links: [
        'header_btn_text' => '',
        'header_btn_link' => '',
        'privacy' => '',
        'terms' => '',
    ];
    $has_links = array_filter($links, function($val){
        return $val;
    });
    $links['has_links'] = (bool)$has_links;
    return (object)$links;
}