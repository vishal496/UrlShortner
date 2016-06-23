<?php

namespace App;

use DateTime;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\DatabaseManager as DB;

class LinksModel extends Model
{
    protected $database;

    function __construct(DB $db)
    {
    	$this->database = $db;
    }

    /*
    *Makes entry in the database for all the links shortened
    *
    *@param integer--user_id--id of the logged in user
    *@param string--getLink--url to be shortened
    *@param string--code--hash generated of the url
    *
    *@return void
    */
    public function makeLinkEntry($user_id, $getLink, $code)
    {
    	$expiry = new DateTime;
        $expiry->modify('+30 day');
        
        $entry = $this->database
    	         ->table('links')
    	         ->insert([
    	         		'user_id'=>$user_id,
    	         		'url'=>$getLink,
                        'hash'=>$code,
    	         		'created_at'=>new DateTime,
    	         		'updated_at'=>new DateTime,
                        'expiry'=>$expiry->format('y-m-d')]
    	            );                     
    }

    /*
    *Gets all the detail related to the user and the link
    *to display on home page
    *
    *@param integer--user_id--id of the logged in user
    *@param string--getLink--url to be shortened
    *
    *@return Row from database
    */
    public function getLinkDetail($user_id, $getLink)
    {
    	$detail = $this->database
    	          ->select(
    	          	'SELECT * 
    	          	 FROM links 
    	          	 WHERE user_id = :user_id && url = :url', 
    	          	 ['user_id' => $user_id, 'url' => $getLink]);

    	return $detail;          
    }

    /*
    *Gets all the detail related to the user to display on home page
    *
    *@param integer--userId--id of the logged in user
    *
    *@return Rows from database
    */
    public function getUserLinks($userId)
    {
    	$linkDetail = $this->database
    	          	  ->select(
    	          	  'SELECT url,hash,redirect
    	          	  FROM links 
    	          	  WHERE user_id = :user_id', 
    	          	  ['user_id' => $userId]);          	  

    	return $linkDetail;          	  
	}

    /*
    *Gets the url and action related to the hash
    *
    *@param string--hash--hashed code of the url
    *
    *@return Row from database
    */
	public function getUrlOfHash($hash)
	{
		$getUrl = $this->database
    	          	  ->select(
    	          	  'SELECT url,action 
    	          	  FROM links 
    	          	  WHERE hash = :hash', 
    	          	  ['hash' => $hash]);
    	return $getUrl;          	  
	}

    /*
    *Updates the number of redirects
    *
    *@param string--hash--hashed code of url
    *
    *@return void
    */
    public function updateRedirect($hash)
    {
        $redirect = $this->database
                    ->update('UPDATE links SET redirect = redirect+1 WHERE hash = ?', [$hash]);
    }

    /*
    *Updates the action related to a hash
    *
    *@param string--hash--hashed code of url
    *@param integer--value--value of action to be set
    *
    *@return void
    */
    public function updateAction($hash, $value)
    {
        $redirect = $this->database
                    ->update('UPDATE links SET action = :action WHERE hash = :hash', ['hash'=>$hash, 'action'=>$value]);
    }
}
