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
			<div class="row">
				<div class="col-sm-12">
				<?php
				$packages = conan_search($query);
				$indent = layout_indent(5);
				if (count($packages) > 0)
				{
					print("\t<div>".count($packages)." matching package(s) found.</div>\n");
					print($indent."<br/>\n");
					print($indent."<table class=\"table table-hover\">\n");
					print($indent."\t<tbody>\n");
					foreach ($packages as $package)
					{
						print($indent."\t\t<tr>\n");
						print($indent."\t\t\t<td>\n");
						print($indent."\t\t\t\t<a style=\"text-decoration:none;\" href=\"package.php?package=".$package."\">\n");
						print($indent."\t\t\t\t\t<span class=\"glyphicon glyphicon-gift\"></span>&nbsp;&nbsp;&nbsp;".$package."\n");
						print($indent."\t\t\t\t</a>\n");
						print($indent."\t\t\t</td>\n");
						print($indent."\t\t</tr>\n");
					}

					print($indent."\t</tbody>\n");
					print($indent."</table>\n");
				}
				else
				{
					layout_error(1, "No matching packages found!");
				}
				?>
				</div>
			</div>
			<?php layout_footer(3); ?>
		</div>
	</body>
</html>
