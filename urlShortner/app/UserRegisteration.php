<?php

namespace App;

use DateTime;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\DatabaseManager as DB;

class UserRegisteration extends Model
{
	protected $database;

    function __construct(DB $db)
    {
    	$this->database = $db;
    }

    /*
    *Makes entry of new user in the database
    *
    *@param string--name--name of the user
    *@param string--email--email addrress of the user
    *@param string--password--password of the user
    *
    *@return void
    */
    public function makeEntry($name, $email, $password)
    {
    	$entry = $this->database
    	         ->table('users')
    	         ->insert([
    	         		'user_name'=>$name,
    	         		'email'=>$email,
    	         		'password'=>$password,
    	         		'created_at'=>new DateTime,
    	         		'updated_at'=>new DateTime]
    	            );             
    }

    /*
    *Checking the details of authentic user
    *
    *@param string--email--email addrress of the user
    *@param string--password--password of the user
    *
    *@return row from database
    */
    public function getDetail($email, $password)
    {
    	$detail = $this->database
    	          ->select(
    	          	'SELECT id, user_name 
    	          	 FROM users 
    	          	 WHERE email = :email && password = :password', 
    	          	 ['email' => $email, 'password' => $password]);
        
    	return $detail;         
    }	         
}
