<?php defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('verify_time'))
{
	function verify_time($from, $to, $date = 'now')
	{
		$result = 'ok';
		$date = is_int($date) ? $date : strtotime($date); // convert to timestamps
		$from = is_int($from) ? $from : strtotime($from); // convert to timestamps
		$to = is_int($to) ? $to : strtotime($to);          // convert to timestamps

		if ( ! ($date > $from && $date < $to) )
		{
			if ( $date < $from && $date > strtotime('00:00') )
			{
				$result = 'today';
			}
			else
			{
				$result = 'tomorrow';
			}
		}

		return $result;
	}
}






