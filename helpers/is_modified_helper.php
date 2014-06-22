<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_modified'))
{
	function is_modified($mtime, $etag)
	{
		$result = TRUE;
		$any_etag_matched = any_etag_matched($etag) ;
		if( $any_etag_matched || ( ( null === $any_etag_matched ) && !is_expired($mtime) ) )
		{
			// Not Modified
			$result = FALSE;
		}
		return $result;
	}
}

if ( ! function_exists('send_304'))
{
	function send_304()
	{
		header('HTTP/1.0 304 Not Modified');
		exit ;
	}
}

if ( ! function_exists('any_etag_matched'))
{
	/**
	 * TRUE if any tag matched
	 * FALSE if none matched
	 * NULL if header is not specified
	 */
	function any_etag_matched($etag)
	{
		$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
			stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) :
			FALSE ;

		if( FALSE !== $if_none_match )
		{
			// $tags = preg_split( ", ", $if_none_match ) ;
			$tags = preg_split("/[,]+/", $if_none_match) ;
			foreach( $tags as $tag )
			{
				if( $tag == $etag ) return TRUE ;
			}
			return FALSE ;
		}
		return NULL ;
	}
}

if ( ! function_exists('is_expired'))
{
	function is_expired($mtime)
	{
		$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
			stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
			FALSE;

		if( FALSE !== $if_modified_since )
		{
			// Compare time here; pseudocode.
			return ( strtotime($if_modified_since) < $mtime ) ;
		}

		return TRUE ;
	}
}

