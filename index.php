<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

require_once("conan.php");
require_once("layout.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php layout_header(2); ?>
		<style>
			.row-vcenter
			{
				min-height: 90%;
				min-height: 90vh;
				display: flex;
				align-items: center;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row row-vcenter">
				<div class="col-sm-3">
				</div>
				<div class="col-sm-6 text-center">
					<?php layout_title(5); ?>
					<br/>
					<?php layout_search_form(5, false); ?>
  					<br/>
  					<br/>
  					<br/>
  					<br/>
  					<?php
  						$packages = conan_search("");
  						$channels = array();
  						foreach ($packages as $package)
  						{
  							$channel = explode("@", $package)[1];
  							array_push($channels, "<a href=\"search.php?query=*@".$channel."\">".$channel."</a>\n");
  						}

						$channels = array_unique($channels);
						print(join(layout_indent(5)."&nbsp;|&nbsp;\n".layout_indent(5), $channels));
  					?>
  					<br/>
  					<br/>
  					<br/>
  					<br/>
				</div>
				<div class="col-sm-3">
				</div>
			</div>
			<?php layout_footer(3, false); ?>
		</div>
	</body>
</html>
