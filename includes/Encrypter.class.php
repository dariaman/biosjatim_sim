<?php
// You can change the salt you use in your application here
define("APPLICATION_ENCRYPTION_SALT","mametwashere..!!!");

class textEncrypter
{


	var $salt;
	var $separator;

	/**
	* @return returns value of variable $salt
	* @desc getSalt : Getting value for variable $salt
	*/
	function getSalt ()
	{
		return $this->salt ;
	}

	/**
	* @param param : value to be saved in variable $salt
	* @desc setSalt : Setting value for $salt
	*/
	function setSalt ($value)
	{
		$this->salt  = $value;
	}

	/**
	* @return returns value of variable $separator
	* @desc getSeparator : Getting value for variable $separator
	*/
	function getSeparator()
	{
		return $this->separator;
	}

	/**
	* @param param : value to be saved in variable $separator
	* @desc setSeparator : Setting value for $separator
	*/
	function setSeparator($value)
	{
		$this->separator = $value;
	}

	function textEncrypter()
	{
		$this->setSalt(APPLICATION_ENCRYPTION_SALT);
		$this->setSeparator("|||");
	}


	/**
	* @return encoded string with salt added
	* @param String to be encoded
	* @desc Adds Salt to Data and Encode it before sending back to client
	* @generationDate 2004-10-31
	* @version 1.0
	* @license GNU GPL License
	* @author Nilesh Dosooye <opensource@weboot.com>
	*/
	function encode($string)
	{
		// Write Function Code Here

		$string = $string.$this->getSeparator().$this->getSalt();
		$string = base64_encode($string);

		return $string;
	}

	/**
	* @return UnEncoded Data
	* @param String to be Decoded
	* @desc Decode Data and Exits if tampering of data is detected
	* @generationDate 2004-10-31
	* @version 1.0
	* @license GNU GPL License
	* @author Nilesh Dosooye <opensource@weboot.com>
	*/
	function decode($string)
	{
		// Write Function Code Here

		$string = base64_decode($string);
		$tokens = explode($this->getSeparator(),$string);

		if ($tokens[1]!=$this->getSalt())
		{

			echo "<span class='label label-danger'>You cant'access this web.!!!</span>";
			exit;
		}


		return $tokens[0];

	}


}


?>