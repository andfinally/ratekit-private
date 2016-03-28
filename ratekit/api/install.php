<?php

/**
 * RateKit installation script
 * https://ratekit.com
 *
 * This script creates a SQLite database and adds tables to it.
 * You only need to run this once - please delete this file afterwards.
 */

include '../config.php';
include 'DB.class.php';
?>
<html>
<head>
	<title>RateKit install</title>
	<style>
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-size: 18px;
			line-height: 1.42857143;
			color: #333;
			background-color: #fff;
		}

		.content {
			max-width: 700px;
			margin: 20px auto;
		}

		.error {
			color: red;
		}

		.success {
			color: green;
		}
	</style>
</head>
<body>

<div class="content">

	<a href="https://ratekit.com"><img src="../img/ratekit-logo-520x138.gif" /></a>

	<h1>Installing RateKit</h1>
	<?php

	try {
		$db = DB::get_instance();
	} catch ( Exception $e ) {
	$message = $e->getMessage();
	if ( strpos( $message, 'unable to open database file' ) !== false ) { ?>
		<h2 class="error">Sorry, this script isn't able to create the database.</h2>
		<p>Please make sure your server can write to the "data" folder inside ratekit. This is a vital step - RateKit won't work without it.</p>
		<p>There are different ways to do this, depending on what kind of computer you're using and whether you're using FTP or a local file manager. If you're on a shared hosting environment, the easiest way is ask your host to give the data folder server write permissions.</p>
		<p>If you're trying RateKit on a home Mac or Linux machine, you can:</p>
		<ul>
			<li>Launch Terminal.</li>
			<li>Switch to the location of the "ratekit" folder in your site, for example type
				<code>cd /Applications/MAMP/htdocs/mysite/ratekit</code> and hit Enter.
			</li>
			<li>Once inside the folder, type
				<code>sudo chmod -fR go+w data</code> and Enter. You'll be prompted for your password.
			</li>
			<li>Close Terminal.</li>
		</ul>
		<p>Search online for more details on how to set file permissions. There are some instructions at
			<a href="http://simplepie.org/wiki/faq/file_permissions">SimplePie</a>, and there's a detailed explanation in the
			<a href="https://codex.wordpress.org/Changing_File_Permissions">WordPress Codex</a>. (Basically the `data` folder in RateKit needs the same permissions as the `wp-content` folder in WordPress.)
		</p>
		<p>Here's the full error message:</p>
		<p><code><?php echo $message; ?></code></p>
		<?php
	} else { ?>
	<h2 class="error">Sorry, we haven't been able to create the database.</h2>
	<p>The script returned this error:</p>
	<p><code><?php echo $message; ?></code></p>
</div>
</body>
</html>
<?php
}

exit();
}

try {
	$db->create_tables();
} catch ( Exception $e ) { ?>
	<h2 class="error">Sorry, there was an error</h2>
	<p>The script created the database, but was unable to create the tables.</p>
	<p>Here's the full error message:</p>
	<p><code><?php echo $e->getMessage(); ?></code></p>
	</div>
	</body>
	</html>
	<?php
	exit();
}

?>
<h2 class="success">Success</h2>
<p>RateKit has created the SQLite database in its data folder and set up the tables it needs. Please delete this file.</p>
</div>

</body>
</html>
