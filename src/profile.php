<?php
include_once './php/user.class.php';
include_once './php/session.class.php';
include_once './php/general.class.php';
$user = Session::getUser();
if (!$user) {
    header('Location: ./');
    exit();
}
$userData = array(
    'id' => General::output($user->getId()),
    'username' => General::output($user->getUsername()),
    'role' => General::output($user->getRole())
);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $userData['username']; ?> | Profile | Securilla</title>
		<meta name="description" content="User profile.">
		<meta name="keywords" content="HTML, CSS, PHP, JavaScript, jQuery, Securilla">
		<meta name="author" content="MOHAMMED ALDOUFANI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="./img/gor.ico">
		<link rel="stylesheet" href="./css/main1.css" hreflang="en" type="text/css" media="all">
		<script src="./js/jquery.js"></script>
		<script src="./js/main.js" defer></script>
	</head>
	<body>
		<?php include_once './components/navigation.php'; ?>
		<div class="profile">
			<div class="layout">
				<p>Congratulations  <span><?php echo $userData['username']; ?></span>
          You Have Seccessfully rigester in our system
        </p>
			</div>
		</div>
		<?php include_once './components/footer.php'; ?>
	</body>
</html>
