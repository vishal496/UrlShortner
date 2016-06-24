<?php

namespace Url;


Class Common
{
	
	function __construct()
	{
		
	}

   /**
    * Generates the hash code for the string
    *
    * @param string $string
    *
    * @return string
	*/
	public function generateHash($string)
	{
		$hash = md5($string);
		return $hash;
	}

   /**
    * Sets the id and name in session
    *
    * @return void
    */	
	public function setSession($id, $name)
	{
		session(
			[
				'id' => $id,
				'name' => $name
			]
		);
	}
}