<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_auth
{
    public function login($username, $password) {
        if($username == 'byte' && $password == 'guillen2020')
        {
            return true;            
        }
        else
        {
            return false;           
        }           
    }
}