<?php

/*
 * example:
 *     $lastModified = filemtime(__FILE__);
 *     $etag = md5_file(__FILE__);
 *     $age = 86400 * 30
 *     1.
 *     Langwan_304::execute($lastModified, $etag, $age);
 *
 *     response Cache-Control:max-page and Expires
 *     
 *     2.
 *     Langwan_304::execute($lastModified, $etag, 0);
 */

class Langwan_304 {
	static public function execute($lastModified, $etag, $age = 0) {
		$ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false); 
		$etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
		header("Etag: $etag");
		if($age != 0) {
			header("Expires: " . gmdate("D, d M Y H:i:s", time() + $age) . " GMT");
			header("Cache-Control: max-age=$age"); 
		} else {
			header("Cache-Control: public");
		}
		if (@strtotime($ifModifiedSince) == $lastModified || $etagHeader == $etag) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
		echo "This page was last modified: ".date("d.m.Y H:i:s",time());
	}
}