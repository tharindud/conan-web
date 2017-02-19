<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

require_once("config.php");

// Convert a cache resource name to a cache file name.
function cache_filename($resource)
{
	$file = $resource;
	$bad = array("\"", "*", "/", ":", "=", "<", ">", "?", "\\", "|", " ", "@", ".", "-");
	
	return CACHE_PATH.str_replace($bad, "_", $resource).".cache";
}

// Read a resource from the cache.
function cache_get($resource, $freshness = CACHE_DURATION)
{
	$filename = cache_filename($resource);
	$content = NULL;
	if (file_exists($filename))
	{
		$now = new DateTime('now');
		$timestamp = new DateTime('@'.stat($filename)['mtime']);
		
		if ($now > $timestamp)
		{
			$age = $now->diff($timestamp);
			if ($age->i < $freshness)
			{
				$file = fopen($filename, 'r');
				$header = strlen($resource."\n\n");
				if (trim(fread($file, $header)) == $resource)
				{
					$content = fread($file, filesize($filename) - $header);
				}
				fclose($file);
			}
		}
	
	}
	
	return $content;
}

// Write a resource to the cache.
function cache_put($resource, $content)
{
	$filename = cache_filename($resource);
	$file = fopen($filename, 'w');
	fwrite($file, $resource."\n\n");
	fwrite($file, $content);
	fclose($file);	
}
?>
