<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

require_once("cache.php");
require_once("config.php");

// Execute a Conan command.
function conan_exec($command, $args, $remote = true)
{
	if ($remote == true)
	{
		$remote = (CONAN_REMOTE == "" ? "" : " -r ".CONAN_REMOTE);
	}
	else
	{
		$remote = "";
	}

	$command = CONAN_PATH."conan ".$command.$remote." ".$args;

	$bad = array("`", "~", "!", "#", "$", "%", "^", "&", "(", ")", "=", "+", ";", ":", "'", "\"", "[", "]", "{", "}", "|", "<", ">");
	foreach ($bad as $word)
	{
		if (strpos($command, $word) != false)
		{
			return "";
		}
	}

	if (CACHE_PATH != "" && CACHE_DURATION != 0)
	{
		$result = cache_get($command);
		if ($result == null)
		{
			$result = shell_exec($command);
			cache_put($command, $result);
		}

		return $result;
	}
	else
	{
		return shell_exec($command);
	}	
}

// List remotes.
function conan_remote_list()
{
	$result = conan_exec("remote", "list", false);
	$remotes = array();
	foreach (explode("\n", $result) as $line)
	{
		if ($line != "")
		{
			$name = explode(":", $line)[0];
			$url = trim(explode("[", substr($line, strlen($name) + 1))[0]);
			$remotes[$name] = $url;
		}
	}
	return $remotes;
}

// Search for packages by name pattern.
function conan_search($query)
{
	$result = conan_exec("search", $query);
	$packages = array();
	foreach (explode("\n", $result) as $line)
	{
		if (strpos($line, "/") === false or strpos($line, "@") == false)
		{
			continue;
		}
		array_push($packages, trim($line));
	}

	return $packages;
}

// Search for package variants.
function conan_search_variants($package)
{
	$result = conan_exec("search", $package);
	$packages = array();
	$variant = null;
	$section = "";
	foreach (explode("\n", $result) as $line)
	{
		if (strpos($line, "Package_ID:") != false)
		{
			$tokens = explode(":", $line);
			$variant = array(trim($tokens[0]) => trim($tokens[1]));
			$variant["outdated"] = false;
		}
		else if ($variant != null)
		{
			if (strpos($line, "[") != false)
			{
				$line = trim($line);
				$section = substr($line, 1, strlen($line) - 2);
				$variant[$section] = array();
			}
			else if (strpos($line, ":") != false)
			{
				$tokens = explode(":", $line);
				if (strpos($tokens[0], "outdated from recipe") === false)
				{
					$variant[$section][trim($tokens[0])] = trim($tokens[1]);
				}
				else if (strpos($tokens[1], "False") === false)
				{
					$variant["outdated"] = true;
				}
			}
			else if ($line == "")
			{
				if (isset($variant["settings"]))
				{
					$settings = $variant["settings"];
					$key = "";
					if (isset($settings["os"]))
					{
						$key = $settings["os"];
					}
					if (isset($settings["arch"]))
					{
						$key = $key."/".$settings["arch"];
					}
					if (isset($settings["compiler"]))
					{
						$key = $key."/".$settings["compiler"];
					}
					if (isset($settings["compiler.version"]))
					{
						$key = $key." ".$settings["compiler.version"];
					}
					if (isset($settings["build_type"]))
					{
						$key = $key."/".$settings["build_type"];
					}
					if ($key != "")
					{
						$variant["Package_Key"] = $key;
						$key = $key.":".$variant["Package_ID"];
						$packages[$key] = $variant;
					}
				}
				$variant = null;
			}
		}
	}

	ksort($packages);
	return $packages;
}

// Get package info.
function conan_info($package)
{
	$result = conan_exec("info", $package);
	if ($result == "" || substr($result, 0, 6) == "ERROR:")
	{
		return null;
	}

	$keys = ["License", "URL"];
	$requires_key = "Requires";
	$info = array($requires_key => array());
	foreach ($keys as $key)
	{
		$info[$key] = $key." not available.";
	}

	$active = false;
	$requires = false;
	foreach (explode("\n", $result) as $line)
	{
		if ($line == $package)
		{
			$active = true;
		}
		else if ($active == true)
		{
			if (substr($line, 0, 1) != " ")
			{
				$active = false;
				break;
			}
			else if (strpos($line, $requires_key.":") != false)
			{
				$requires = true;
			}
			else if ($requires == true && strpos($line, ":") === false)
			{
				$dependency = trim($line);
				if ($dependency != "None")
				{
					$info[$requires_key][count($info[$requires_key])] = $dependency;
				}
			}
			else
			{
				$requires = false;
				foreach ($keys as $key)
				{
					if (strpos($line, $key.":") != false)
					{
						$info[$key] = substr(trim($line), strlen($key.": "));
					}
				}
			}
		}
	}

	sort($info[$requires_key]);
	return $info;
}
?>
