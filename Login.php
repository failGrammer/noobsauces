<?php
class Login{
    private $loginState;
    private $userName = Anonymous;
    private $passWord = anonymoususer;
    private $adminLevel = 0;
    
    function __construct(){
        $this->loginState = FALSE;
        $this->adminLevel = 0;
    }
    function get_loginState(){
        return $this->loginState;
    }
    function set_userName($new_userName){
        $this->userName = $new_userName;
    }
    function get_userName(){
        return $this->userName;
    }
    function set_passWord($new_passWord){
        $this->passWord = $new_passWord;
    }
    function get_passWord(){
        return $this->passWord;
    }
    function get_adminLevel(){
        return $this->adminLevel;
    }
    
    function logIn($new_userName, $new_passWord) {
        $this->userName = $new_userName;
        $this->passWord = $new_passWord;
        $this->loginState = TRUE;       
    }
}