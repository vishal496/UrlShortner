<?php

namespace App\Http\Controllers;

use App\LinksModel;

use App\Http\Requests;

use Illuminate\Http\Request;

class LinkController extends Controller
{
    protected $url;
    protected $linkTable;

    function __construct(Request $request, LinksModel $links)
    {
    	$this->url = $request;
    	$this->linkTable = $links;
    }

    /*
    *Making entry in the database and check whether the hash
    *of particular url already exists in database
    *
    *@param type--name--description
    *
    *return Illuminate\Http\Response
    */
    public function make()
    {
    	$getLink = $this->url->input('url');
    	$user_id = session('id');
            	
    	$usersLink = $this->linkTable->getLinkDetail($user_id, $getLink);
    	$count = count($usersLink);
    	if ($count == 0){
            $hash = md5($getLink);
            $code = $user_id."-".substr($hash, 0,6);
    		$this->linkTable->makeLinkEntry($user_id, $getLink, $code);
    		$usersLink = $this->linkTable->getUserLinks($user_id);
            $count = count($usersLink);
                
            return view('shortenpage',compact('usersLink','count'));  

    	}else {
    		return view('shortenpage',compact('usersLink','count'));
    	}

    }

    /*
    *Used for redirection and activating and deactivating
    *a hash
    *
    *@param string--hash--hashed code of the url
    *
    *@return Redirect
    */
    public function get($hash)
    {
    	$action = $this->url->input('action');
        if(isset($action)){
           switch ($action) {
                case 'Enable':
                    $value = 0;
                    break;
                
                case 'Disable':
                    $value = 1;
                    break;
            }
            $this->linkTable->updateAction($hash, $value); 
        }
        $getUrl = $this->linkTable->getUrlOfHash($hash);
        if($getUrl['0']->action == 1) {
            echo "disabled";
        }else{
            $redirect = $this->linkTable->updateRedirect($hash);
            return redirect($getUrl['0']->url);
        }
    }
}
