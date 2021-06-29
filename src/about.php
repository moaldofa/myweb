<?php
include_once './php/user.class.php';
include_once './php/session.class.php';
$user = Session::getUser();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>About | Securilla</title>
		<meta name="description" content="About author.">
		<meta name="keywords" content="HTML, CSS, PHP, JavaScript, jQuery, Securilla">
		<meta name="author" content="MOHAMMED ALDOUFANI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="./img/gor.ico">
		<link rel="stylesheet" href="./css/main1.css" hreflang="en" type="text/css" media="all">
		<script src="./js/jquery.js"></script>
		<script src="./js/main.js" defer></script>
	</head>
	<body class="background-img">
		<?php include_once './components/navigation.php'; ?>
		<div class="about">
			<p>Made by MOHAMMED ALDOUFANI.</p>
			<p>I hope you like it!</p>
		</div>
		<?php include_once './components/footer.php'; ?>
	</body>
</html>
