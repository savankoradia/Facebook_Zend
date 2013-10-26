<?php

/*
 * Config class for facebook
 */

include 'facebook.php';

class Facebook_Fb {

    private $_api;
    private $_key;
    private $_returnUrl;
    private $_homeUrl;
    private $_fbPermission;
    private $_userAccessToken;
    private $_isLoggedIn;
    private $_fbUser;
    private $_fb;

    public function __construct() {
        $apiDb = new Admin_Model_Api();
        $apiData = $apiDb->getApiByName('facebook');
        $this->_api = $apiData[0]['api_key'];
        $this->_key = $apiData[0]['api_secret'];
        $this->_homeUrl = $_SERVER['SERVER_NAME'];
        $this->_returnUrl = $_SERVER['SERVER_NAME'] . '/users/register';
        $this->_fbPermission = 'publish_stream,user_about_me,email';
        $this->_fb = new Facebook(array(
            'appId' => $this->_api,
            'secret' => $this->_key,
            'cookie' => true
        ));

        $this->_fbUser = $this->_fb->getUser();
    }

    public function setReturnUrl($url) {
        $this->_returnUrl = $_SERVER['SERVER_NAME'] .$url;
    }
    
    public function getLoginUrl() {
        return $this->_fb->getLoginUrl(array('scope' => $this->_fbPermission));
    }

    public function getLogoutUrl() {
        $this->_fb->getLogoutUrl();
    }

    public function getUser() {
        return $this->_fbUser;
    }

    public function getUserProfile() {
        return $this->_fb->api('/me');
    }

    public function getAccessToken() {
        return $this->_fb->getAccessToken();
    }

    public function getUserEmail() {        
        return $this->_fb->api(array(
                    'method' => 'fql.query',
                    'access_token' => $this->getAccessToken(),
                    'query' => 'select email from user where uid = me()'
        ));
    }

}
