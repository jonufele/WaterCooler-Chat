<?php ob_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
		<!-- Change paths to match your server's -->
		<LINK rel="stylesheet" href="themes/simple_blue/style.css" type="text/css">
		<script type="text/javascript" src="script.js"></script>
		<title>Chat</title>
	</head>
	<body>
		<h1 style="text-align: center; font-weight: normal">Chat</h1>
		<?php

			include 'wcchat.class.php';    # Change this to match your server's path to "wcchat.class.php"
			$chat = new WcChat();
                  echo $chat->printIndex('EMBED');
		?>	
	</body>
</html>
<?php ob_end_flush(); ?>