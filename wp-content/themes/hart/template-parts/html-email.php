<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>The Hart Attack</title>
		<style type="text/css">
			body {
				margin: 0;
				background-color: #20232a;
			}
			table {
				border-spacing: 0;
				border-collapse: separate;
			}
			td {
				padding: 0;
			}
			img {
				border: 0;
			}
			.wrapper {
				width: 100%;
				table-layout: fixed;
				background-color: #a01d26;
			}
			.main {
				background-color: #a01d26;
				margin: 0 auto;
				width: 100%;
				max-width: 600px;
				border-spacing: 0;
				font-family: "Montserrat", sans-serif;
				color: #20232a;
			}
		</style>
	</head>
	<body>
		<center class="wrapper">
			<table class="main" width="100%" style="border-spacing: 0;">

			<!--Header-->
				<tr style="border-spacing: 0; border-color: #20232a;">
					<td style="background-color: #20232a; border-spacing: 0; border: 16px solid #20232a;">
						<center>
							<a href="<?php echo get_home_url(); ?>">
								<img src="<?php echo get_template_directory_uri().'/images/logo.png'; ?>" alt="<?php echo get_bloginfo('name'); ?>" style="width: 100%; max-width: 160px; margin: 0 auto" />
							</a>
						</center>
					</td>
				</tr>

				<!-- Body -->
				<tr style="border-spacing: 0; border-color: #a01d26;">
					<td style="background-color: #f4f4ef; border-spacing: 0; border: 16px solid #a01d26;">
						<center style="border: 16px solid #f4f4ef;"><?php echo $args['content']; ?></center>
					</td>
				</tr>

				<!-- Footer -->
				<tr style="border-spacing: 0; border-color: #20232a;">
					<td style="background-color: #20232a; border-spacing: 0; border: 16px solid #20232a; color: #f4f4ef; font-size: 10px; font-weight: 600; color: #a01d26;">
						<center>&copy; Dan Hart <?php echo date('Y'); ?></center>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>
