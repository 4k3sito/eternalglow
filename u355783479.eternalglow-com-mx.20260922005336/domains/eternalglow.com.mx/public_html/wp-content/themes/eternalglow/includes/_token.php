<?php
	
/**** TOKEN ****/

/**
 * Build a random token string
 * @return string
 */
function buildToken(){
    return random_string('unique');
}
 
/**
 * Get token previously saved in php session
 * @return string
 */
function getSessionToken(){
    return isset($_SESSION[SESSION_AUTH_TOKEN]) ? $_SESSION[SESSION_AUTH_TOKEN] : null;
}
 
/**
 * Compare session token with a token (request)
 * @param string $requestToken
 * @return bool
 */
function isValidateToken($requestToken){
    return getSessionToken() == $requestToken;
}
 
/**
 * Save new token in php session (to be validated in next request)
 * @return void
 */
function registerNextToken(){
    $_SESSION[SESSION_AUTH_TOKEN] = buildToken();	
}

function get_jss_token($action = 'jss-token'){
    return wp_create_nonce($action);
}

function jss_token($action = 'jss-token'){
    echo get_jss_token($action);
}

function is_jss_token_valid($token = '', $action = 'jss-token'){
    return wp_verify_nonce($token, $action);
}