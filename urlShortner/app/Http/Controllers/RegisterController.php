<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use App\UserRegisteration;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $registerationRequest;
    protected $userTableEntry;


    function __construct(Request $request, UserRegisteration $user)
    {
        $this->registerationRequest = $request;
    	$this->userTableEntry = $user;
    }

    /*
    *Register a new user
    *
    *@param type--name--description
    *
    *@return Illuminate\Http\Response
    */
    public function register()
    {
    	$name = $this->registerationRequest->input('uname');
    	$email = $this->registerationRequest->input('email');
    	$password = md5($this->registerationRequest->input('pass'));

    	$this->userTableEntry->makeEntry($name, $email, $password);
        
        $rawid = $this->userTableEntry->getDetail($email, $password);
        session(['id' => $rawid['0']->id,'name' => $rawid['0']->user_name]);

        return view('shortenpage');
    }
}
?>
