<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

require_once("conan.php");
require_once("config.php");
require_once("version.php");

define("LAYOUT_TITLE", "Conan");
define("LAYOUT_SUBTITLE", "C++ Package Manager");
define("LAYOUT_THEME", "darkly");

// Return a string to use as the indentation.
function layout_indent($indent)
{
	return str_repeat("\t", $indent);
}

// Layout the page header.
function layout_header($indent, $title = "")
{
	if ($title != "")
	{
		$title = " - ".$title;
	}

	$indent = layout_indent($indent);
	print("<title>".LAYOUT_TITLE.": ".LAYOUT_SUBTITLE.$title."</title>\n");
	print($indent."<meta charset=\"utf-8\"/>\n");
	print($indent."<meta http-equiv=\"Pragma\" content=\"no-cache\"/>\n");
	print($indent."<meta http-equiv=\"Expires\" content=\"-1\"/>\n");
	print($indent."<link rel=\"shortcut icon\" href=\"https://www.conan.io/favicon.ico\" type=\"image/x-icon\"/>\n");
	print($indent."<link rel=\"stylesheet\" href=\"https://bootswatch.com/3/".LAYOUT_THEME."/bootstrap.min.css\"/>\n");
	print($indent."<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js\"></script>\n");
	print($indent."<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>\n");
	print($indent."<style>h1 a:hover { text-decoration: none; }</style>");
}

// Layout the application title.
function layout_title($indent)
{
	$indent = layout_indent($indent);
	print("<h1><a href=\"index.php\">CONAN</a></h1>\n");
	print($indent."<h6>C++ Package Manager</h6>\n");
}

// Layout the package search form.
function layout_search_form($indent, $remote = true, $autofocus = true, $query = "")
{
	$input = "";
	if ($autofocus == true)
	{
		$input = $input." autofocus=\"true\"";
	}
	if ($query != "")
	{
		$input = $input." value=\"".$query."\"";
	}

	$indent = layout_indent($indent);
	if ($remote == true)
	{
		if (CONAN_REMOTE == "")
		{
			$remote = "local";
			$url = "";
		}
		else
		{
			$remote = CONAN_REMOTE;
			$url = (CONAN_REMOTE_URL == "" ? conan_remote_list()[$remote] : CONAN_REMOTE_URL);
		}

		print("<h6 class=\"text-muted text-right\"><strong class=\"text-danger\">".$remote."</strong>&nbsp;&nbsp;".$url."</h6>\n".$indent);
	}
	print("<form action=\"search.php\">\n");
	print($indent."\t<div class=\"input-group\">\n");
	print($indent."\t\t<input id=\"search\" type=\"text\" class=\"form-control input-md\" name=\"query\" placeholder=\"Search\"".$input."/>\n");
	print($indent."\t\t<span class=\"input-group-btn\">\n");
	print($indent."\t\t\t<button class=\"btn btn-md btn-primary\" type=\"submit\">\n");
	print($indent."\t\t\t\t<i class=\"glyphicon glyphicon-search\"></i>\n");
	print($indent."\t\t\t</button>\n");
	print($indent."\t\t</span>\n");
	print($indent."\t</div>\n");
	print($indent."</form>\n");
}

// Layout an error message.
function layout_error($indent, $message)
{
	$indent = layout_indent($indent);
	print($indent."<div class=\"alert alert-danger\">".$message."</div>\n");
}

// Layout page footer.
function layout_footer($indent, $flush = true)
{
	$indent = layout_indent($indent);
	print("<div class=\"row text-muted text-center\">\n");
	if ($flush == true)
	{	
		print(str_repeat($indent."\t<br/>\n", 8));	
	}
	print($indent."\t<h6>Powered by <a href=\"".CONAN_WEB_URL."\">conan-web</a> version ".CONAN_WEB_VERSION." | Running on ".conan_version()."</h6>\n");
	print($indent."</div>\n");
}
?>
