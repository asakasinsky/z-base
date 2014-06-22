<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_guid'))
{
	/**
	 * Create GUID function
	 * http://en.wikipedia.org/wiki/Globally_unique_identifier
	 * @return string guid
	 */
	function create_guid()
	{
		static $guid = '';
		 $uid = uniqid("", true);
		 $data = $namespace;
		 $data .= $_SERVER['REQUEST_TIME'];
		 $data .= $_SERVER['HTTP_USER_AGENT'];
		 $data .= $_SERVER['LOCAL_ADDR'];
		 $data .= $_SERVER['REMOTE_ADDR'];
		 $data .= $_SERVER['REMOTE_PORT'];
		 $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
		 $guid = substr($hash,  0,  8).
			 '-'.substr($hash,  8,  4).
			 '-'.substr($hash, 12,  4).
			 '-'.substr($hash, 16,  4).
			 '-'.substr($hash, 20, 12);
		 return $guid;
	}
}
