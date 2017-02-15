<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

require_once("conan.php");
require_once("layout.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php layout_header(2, "Search"); ?>
	</head>
	<?php
	$query = isset($_GET["query"]) ? $_GET["query"] : "";
	$view = isset($_GET["view"]) ? $_GET["view"] : "list";
	if ($query != "")
	{
		if (strpos($query, "*") === false)
		{
			$query = "*".$query."*";
		}
	}
	?>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<?php layout_title(5); ?>
				</div>
				<div class="col-sm-4">
				</div>
				<div class="col-sm-4">
					<?php layout_search_form(5, true, false, $query); ?>
				</div>
			</div>
			<div class="row">
				<hr>
			</div>
			<?php
			$packages = conan_search($query);
			$indent = layout_indent(3);
			if (count($packages) > 0)
			{
				print("<div class=\"row\">\n");
				print($indent."\t<div class=\"col-sm-10\">\n");
				print($indent."\t\t<div>".count($packages)." matching package(s) found.</div>\n");
				print($indent."\t</div>\n");
				print($indent."\t<div class=\"col-sm-2 text-right\">\n");
				if ($view == "matrix")
				{
					print($indent."\t\t<a href=\"search.php?query=".$query."\"><span class=\"glyphicon glyphicon-th-list\"></span></a>\n");
					print($indent."\t\t<span class=\"glyphicon glyphicon-th\"></span>\n");
				}
				else
				{
					print($indent."\t\t<span class=\"glyphicon glyphicon-th-list\"></span>\n");
					print($indent."\t\t<a href=\"search.php?query=".$query."&view=matrix\"><span class=\"glyphicon glyphicon-th\"></span></a>\n");	
				}
				print($indent."\t</div>\n");
				print($indent."</div>\n");

				print($indent."<div class=\"row\">\n");
				print($indent."\t<div class=\"col-sm-12\">\n");
				print($indent."\t\t<br/>\n");
				if ($view == "matrix")
				{
					$variants = array();
					foreach ($packages as $package)
					{
						foreach (conan_search_variants($package) as $key => $variant)
						{
							$key = explode(":", $key)[0];
							if (array_key_exists($key, $variants) == false)
							{
								$variants[$key] = array();
							}
							$variants[$key][$package] = 1;
						}
					}

					ksort($variants);

					print($indent."\t\t<table class=\"table table-hover\">\n");
					print($indent."\t\t\t<thead>\n");
					print($indent."\t\t\t\t<tr>\n");
					print($indent."\t\t\t\t\t<th></th>\n");
					foreach ($variants as $key => $entry)
					{
						print($indent."\t\t\t\t\t<th class=\"text-center\">".str_replace("/", "<br/>", $key)."</th>\n");
					}
					print($indent."\t\t\t\t<tr>\n");
					print($indent."\t\t\t</thead>\n");
					print($indent."\t\t\t<tbody>\n");
					foreach ($packages as $package)
					{
						print($indent."\t\t\t\t<tr>\n");
						print($indent."\t\t\t\t\t<td>\n");
						print($indent."\t\t\t\t\t\t<a style=\"text-decoration:none;\" href=\"package.php?package=".$package."\">\n");
						print($indent."\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-gift\"></span>&nbsp;&nbsp;&nbsp;".$package."\n");
						print($indent."\t\t\t\t\t\t</a>\n");
						print($indent."\t\t\t\t\t</td>\n");
						foreach ($variants as $key => $entry)
						{
							if (array_key_exists($package, $entry))
							{
								print($indent."\t\t\t\t\t<th class=\"text-center\"><span class=\"glyphicon glyphicon-ok\"></span></th>\n");
							}
							else
							{
								print($indent."\t\t\t\t\t<th></th>\n");	
							}
						}
						print($indent."\t\t\t\t</tr>\n");
					}

					print($indent."\t\t\t</tbody>\n");
					print($indent."\t\t</table>\n");
				}
				else
				{
					print($indent."\t\t<table class=\"table table-hover\">\n");
					print($indent."\t\t\t<tbody>\n");
					foreach ($packages as $package)
					{
						print($indent."\t\t\t\t<tr>\n");
						print($indent."\t\t\t\t\t<td>\n");
						print($indent."\t\t\t\t\t\t<a style=\"text-decoration:none;\" href=\"package.php?package=".$package."\">\n");
						print($indent."\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-gift\"></span>&nbsp;&nbsp;&nbsp;".$package."\n");
						print($indent."\t\t\t\t\t\t</a>\n");
						print($indent."\t\t\t\t\t</td>\n");
						print($indent."\t\t\t\t</tr>\n");
					}

					print($indent."\t\t\t</tbody>\n");
					print($indent."\t\t</table>\n");
				}
				print($indent."\t</div>\n");
				print($indent."</div>\n");
			}
			else
			{
				print("<div class=\"row\">\n");
				print($indent."\t<div class=\"col-sm-12\">\n");
				layout_error(5, "No matching packages found!");
				print($indent."\t</div>\n");
				print($indent."</div>\n");
			}
			?>
			<?php layout_footer(3); ?>
		</div>
	</body>
</html>
