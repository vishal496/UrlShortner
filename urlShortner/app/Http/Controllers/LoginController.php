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
            $userId = $info['0']->id;
            $usersLink = $this->getUserLinks->getUserLinks($userId);
            $total = count($usersLink);

            $limit = 10;
            $page = 0;
            $paginate = $this->commonFunction->paginate($total, $limit, $page);

            $start = 0;
            $usersPerPageLink = $this->getUserLinks->getUsersPerPageLink($userId, $start, $limit);
            $count = count($usersPerPageLink);    
            return view('shortenpage',compact('usersPerPageLink','count','paginate'));  
        } 
        echo '<script language="javascript">';
        echo 'alert("Username/Password does not match.")';
        echo '</script>';

        return view('login');    
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

    public function paginatedView()
    {
        echo 1;
        exit;
        $page = $this->loginEntry->input('page');
        echo $page;
        
        $limit = 10;
        $start = ($page - 1);
        $usersLink = $this->getUserLinks->getUserLinks(session('id'));
        $total = count($usersLink);
        $limit = 10;
        $paginate = $this->commonFunction->paginate($total, $limit, $page);

        $usersPerPageLink = $this->getUserLinks->getUsersPerPageLink($userId, $start, $limit);
        $count = count($usersPerPageLink);    
        return view('shortenpage',compact('usersPerPageLink','count','paginate'));  
    }
}
