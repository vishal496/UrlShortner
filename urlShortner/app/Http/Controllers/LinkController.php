<?php

namespace Url\Http\Controllers;

use Url\Common;
use Url\Http\Requests;
use Url\Models\LinksModel;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    protected $request;
    protected $linkTable;
    protected $commonFunction;

    function __construct(Request $request, LinksModel $links, Common $common)
    {
    	$this->request = $request;
    	$this->linkTable = $links;
        $this->commonFunction = $common;
    }

    /**
     * Making entry in the database and check whether the hash
     * of particular url already exists in database
     *
     * @return Illuminate\Http\Response
     */
    public function createCode()
    {
    	$getLink = $this->request->input('url');
    	$userId = session('id');
            	
    	$usersLink = $this->linkTable->getLinkDetail($userId, $getLink);
    	$count = count($usersLink);
    	if ($count == 0){
            $hash = $this->commonFunction->generateHash($getLink);
            $code = $userId."-".substr($hash, 0,6);
    		$this->linkTable->makeLinkEntry($userId, $getLink, $code);
    		$usersLink = $this->linkTable->getUserLinks($userId);
            $count = count($usersLink);
                
            return view('shortenpage',compact('usersLink','count'));  

    	}
    	return view('shortenpage',compact('usersLink','count'));
    }

    /**
     * Used for redirection and activating and deactivating
     * a hash
     *
     * @param string  $hash
     *
     * @return Redirect
     */
    public function redirect($hash)
    {
        $getUrl = $this->linkTable->getUrlOfHash($hash);
        if($getUrl['0']->action == 0) {
            echo '<script language="javascript">';
            echo 'alert("Disabled")';
            echo '</script>';
            exit;
        }
        $redirect = $this->linkTable->updateRedirect($hash);
        return redirect($getUrl['0']->url);
    }

    /**
    * Change the state of link i.e. enable or disable
    *
    * @param string  $hash
    *
    * @return Illuminate\Http\Response
    */
    public function action($hash)
    {
        $action = $this->request->input('action');
        if(isset($action)){
           switch ($action) {
                case 'enable':
                    $value = 1;
                    break;
                
                case 'disable':
                    $value = 0;
                    break;
            }
            $this->linkTable->updateAction($hash, $value); 
        }
        $usersLink = $this->linkTable->getUserLinks(session('id'));
        $count = count($usersLink);
                
        return view('shortenpage',compact('usersLink','count'));  

    }
}
