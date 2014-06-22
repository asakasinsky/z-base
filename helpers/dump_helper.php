<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('dump'))
{
	/**
	 * Value type and content pretty dumper
	 * @param  mixed $data value to dumping
	 */
	function dump($data) {
		if(is_array($data)) { //If the given variable is an array, print using the print_r function.
			print "<pre>-----------------------\n";
			print_r($data);
			print "-----------------------</pre>";
		} elseif (is_object($data)) {
			print "<pre>==========================\n";
			var_dump($data);
			print "==========================</pre>";
		} else {
			print "<pre>=========&gt; ";
			var_dump($data);
			print " &lt;=========</pre>";
		}
	}
}
