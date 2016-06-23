<?php

namespace App\Http\Controllers;

use App\LinksModel;

use App\Http\Requests;

use App\UserRegisteration;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $loginEntry;
    protected $userDetail;
    protected $getUserLinks;

    function __construct(Request $request, UserRegisteration $user, LinksModel $links)
    {
    	$this->loginEntry = $request;
    	$this->userDetail = $user;
        $this->getUserLinks = $links;
    }

    /*
    *Used for authenticating the user
    *
    *@param type--name--description
    *
    *@return Illuminate\Http\Response
    */
    public function auth()
    {
    	$email = $this->loginEntry->input('email');
    	$password = md5($this->loginEntry->input('pass'));
    	
    	$rawid = $this->userDetail->getDetail($email, $password);
         
    	session(['id' => $rawid['0']->id,'name' => $rawid['0']->user_name]);

        $userId = $rawid['0']->id;

        $usersLink = $this->getUserLinks->getUserLinks($userId);
        $count = count($usersLink);
    	    	
    	return view('shortenpage',compact('usersLink','count'));  
    }
}
