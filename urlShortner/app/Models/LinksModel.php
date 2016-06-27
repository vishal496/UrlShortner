<?php

namespace Url\Models;

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

   /**
    * Makes entry in the database for all the links shortened
    *
    * @param integer  $userId
    * @param string  $getLink
    * @param string  $code
    *
    * @return void
    */
    public function makeLinkEntry($userId, $getLink, $code)
    {
    	$date = new DateTime;
        $expiry = $date->modify('+30 day');
        
        $entry = $this->database
    	         ->table('links')
    	         ->insert(
                        [
    	         		    'user_id'=>$userId,
    	         		    'url'=>$getLink,
                            'hash'=>$code,
                            'expiry'=>$expiry
                        ]
    	            );                     
    }

   /**
    * Gets all the detail related to the user and the link
    * to display on home page
    *
    * @param integer  $userId
    * @param string  $getLink
    *
    * @return Array
    */
    public function getLinkDetail($userId, $getLink)
    {
    	$detail = $this->database
                  ->table('links')
                  ->select('url','hash','redirect','action')
    	          ->where(
                        [
                            'user_id' => $userId,
                            'url' => $getLink
                        ]
                    )
                   ->get();

    	return $detail;          
    }

   /**
    * Gets all the detail related to the user to display on home page
    *
    * @param integer  $userId
    *
    * @return Array
    */
    public function getUserLinks($userId)
    {
    	$linkDetail = $this->database
                      ->table('links')
    	          	  ->select('url','hash','redirect','action')
                      ->where('user_id',$userId)
                      ->get();

    	return $linkDetail;          	  
	}

    public function getUsersPerPageLink($userId, $start, $limit)
    {
        $linkDetail = $this->database
                      ->table('links')
                      ->select('url','hash','redirect','action')
                      ->where('user_id',$userId)
                      ->skip($start)
                      ->take($limit)
                      ->get();
                      
        return $linkDetail;
    }

   /**
    * Gets the url and action related to the hash
    *
    * @param string  $hash
    *
    * @return Array
    */
	public function getUrlOfHash($hash)
	{
		$getUrl = $this->database
    	          	  ->table('links')
                      ->select('url','action')
    	          	  ->where('hash',$hash)
                      ->get();

    	return $getUrl;          	  
	}

   /**
    * Updates the number of redirects
    *
    * @param string  $hash
    *
    * @return void
    */
    public function updateRedirect($hash)
    {
        $redirect = $this->database
                    ->table('links')
                    ->where('hash',$hash)
                    ->increment('redirect');        
    }                

   /**
    * Updates the action related to a hash
    *
    * @param string  $hash
    * @param integer  $value
    *
    * @return void
    */
    public function updateAction($hash, $value)
    {
        $redirect = $this->database
                    ->table('links')
                    ->where('hash',$hash)
                    ->update(['action' => $value]);
    }
}
