<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

require_once("conan.php");
require_once("layout.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php layout_header(2, "Package"); ?>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<?php layout_title(5); ?>
				</div>
				<div class="col-sm-4">
				</div>
				<div class="col-sm-4">
					<?php layout_search_form(5, true, false); ?>
				</div>
			</div>
			<div class="row">
				<hr>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php
					$package = isset($_GET["p"]) ? $_GET["p"] : "";
					$indent = layout_indent(5);
					if ($package != "")
					{
						$info = conan_info($package);
						if ($info == null)
						{
							layout_error(0, "Package '".$package."' not found!");
						}
						else
						{
							print("<h3>".$package."</h3>\n");
							print($indent."<div class=\"panel panel-default\">\n");
							print($indent."\t<div class=\"panel-body\">\n");

							$keys = ["URL", "License"];
							foreach ($keys as $key)
							{
								if (!filter_var($info[$key], FILTER_VALIDATE_URL) === false)
								{
    								print($indent."\t\t<h5 class=\"text-muted\"><a href=\"".$info[$key]."\">".$info[$key]."</a></h5>\n");
    							}
    							else
    							{
									print($indent."\t\t<h5 class=\"text-muted\">".$info[$key]."</h5>\n");
								}
							}

							$dependencies = $info["Requires"];
							if (count($dependencies) > 0)
							{
								print($indent."\t\t<br/>\n");
								print($indent."\t\t<h5>Requires</h5>\n");
								print($indent."\t\t<blockquote>\n");
								foreach ($dependencies as $dependency)
								{
									print($indent."\t\t\t<span class=\"label label-default\" style=\"padding: 5px; padding-top: 2px; margin:1px;\">".$dependency."</span>\n");
								}
								print($indent."\t\t</blockquote>\n");
							}

							print($indent."\t</div>\n");
							print($indent."</div>\n");
							print($indent."<br/>\n");

							$packages = conan_search_variants($package);

							$operating_systems = array();
							foreach ($packages as $package)
							{
								$key = $package["settings"]["os"];
								if (!isset($operating_systems[$key]))
								{
									$operating_systems[$key] = $key;
								}
							}
							sort($operating_systems);

							print($indent."<ul class=\"nav nav-tabs\">\n");
							$active = " class=\"active\"";
							foreach ($operating_systems as $os)
							{
								print($indent."\t<li".$active."><a data-toggle=\"tab\" href=\"#".$os."\">".$os."</a></li>\n");
								$active = "";
							}
							print($indent."</ul>\n");

							print($indent."<div class=\"tab-content\">\n");
							$active = " in active";
							foreach ($operating_systems as $os)
							{
								print($indent."\t<div id=\"".$os."\" class=\"tab-pane fade".$active."\">\n");
								print($indent."\t\t<br/>\n");
								$active = "";
								foreach ($packages as $package)
								{
									if ($package["settings"]["os"] == $os)
									{
										print($indent."\t\t<div class=\"panel panel-default\">\n");
										print($indent."\t\t\t<div class=\"panel-heading\">\n");
										print($indent."\t\t\t\t<strong>".$package["Package_Key"]."&nbsp;&nbsp;&nbsp;&nbsp;</strong>\n");
										print($indent."\t\t\t\t<small class=\"text-muted\">(".$package["Package_ID"].")</small>\n");
										print($indent."\t\t\t</div>\n");
										print($indent."\t\t\t<div class=\"panel-body\">\n");

										$sections = ["Settings" => "primary", "Options" => "success", "Requires" => "default"];
										foreach ($sections as $section => $style)
										{
											$key = strtolower($section);
											if (isset($package[$key]))
											{
												$properties = $package[$key];
												if (count($properties) > 0)
												{
													print($indent."\t\t\t\t<h5>".$section.":</h5>\n");
													print($indent."\t\t\t\t<blockquote>\n");
													foreach ($properties as $key => $value)
													{
														print($indent."\t\t\t\t\t<span class=\"label label-".$style."\" style=\"padding: 5px; padding-top: 2px; margin:1px;\">".$key." = ".$value."</span>\n");
													}
													print($indent."\t\t\t\t</blockquote>\n");
												}
											}
										}
										
										print($indent."\t\t\t</div>\n");
										print($indent."\t\t</div>\n");
									}
								}
								print($indent."\t</div>\n");
							}
							print($indent."</div>\n");
						}
					}
					else
					{
						layout_error(0, "No package specified!");
					}
					?>
				</div>
			</div>
			<?php layout_footer(3); ?>
		</div>
	</body>
</html>
