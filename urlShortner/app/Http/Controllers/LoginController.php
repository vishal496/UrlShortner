<?php

namespace Url\Http\Controllers;

use Url\Common;
use Url\Http\Requests;
use Url\Models\LinksModel;
use Illuminate\Http\Request;
use Url\Models\UserRegisteration;

class LoginController extends Controller
{
    protected $loginEntry;
    protected $userDetail;
    protected $getUserLinks;
    protected $commonFunction;

    function __construct(Request $request, UserRegisteration $user, LinksModel $links, Common $common)
    {
    	$this->userDetail = $user;
        $this->loginEntry = $request;
        $this->getUserLinks = $links;
        $this->commonFunction = $common;
    }

    /**
     * Used for authenticating the user
     *
     * @return Illuminate\Http\Response
     */
    public function auth()
    {
        $email = $this->loginEntry->input('email');
    	$password = $this->commonFunction->generateHash($this->loginEntry->input('pass'));
    	
    	$info = $this->userDetail->getDetail($email, $password);

    	if (count($info) == 1) {
            $this->commonFunction->setSession($info['0']->id, $info['0']->user_name); 
        } else {
            echo '<script language="javascript">';
            echo 'alert("Username/Password does not match.")';
            echo '</script>';
;
            return view('login');
        }
        
        if(count(session('id'))) {
            $userId = $info['0']->id;
            $usersLink = $this->getUserLinks->getUserLinks($userId);
            $count = count($usersLink);
    	    	
    	    return view('shortenpage',compact('usersLink','count'));  
        }
    }

    /**
     * Register a new user
     *
     * @return Illuminate\Http\Response
     */
    public function register()
    {
        $name = $this->loginEntry->input('uname');
        $email = $this->loginEntry->input('email');
        $password = $this->commonFunction->generateHash($this->loginEntry->input('pass'));

        $this->userDetail->makeEntry($name, $email, $password);
        
        $info = $this->userDetail->getDetail($email, $password);
        
        $this->commonFunction->setSession($info['0']->id, $info['0']->user_name);
        $count = 0;
        
        return view('shortenpage',compact('count'));
    }

    
    /**
     * Sign out a user
     *
     * @return redirect
     */
    public function signout()
    {
        session()->flush();
        return redirect('/');
    }

    /**
     * Display the registeration form page
     *
     * @return Illuminate\Http\Response
     */
    public function registerView()
    {
        return view('register');
    }

    /**
     * Display the login form page
     *
     * @return Illuminate\Http\Response
     */
    public function home()
    {
        return view('login');
    }
}
