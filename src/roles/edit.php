<?php
$depth = '../';
include_once '../php/user.class.php';
include_once '../php/session.class.php';
include_once '../php/database.class.php';
include_once '../php/query.class.php';
include_once '../php/general.class.php';
$user = Session::getUser();
if (!$user || $user->getRole() != 1) {
    header('Location: ../');
    exit();
}
$inputValues = array(
    'token' => '',
    'id' => '',
    'name' => '',
    'description' => ''
);
$errorMessages = array(
    'global' => '',
    'uri' => '',
    'token' => '',
    'id' => '',
    'name' => '',
    'description' => ''
);
$uniqueValues = array(
    'name' => ''
);
$uriError = false;
if (isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $params = array(
            'id' => $_GET['id']
        );
        $data = Query::select('SELECT `id`, `name`, `description` FROM `roles` WHERE `id` = :id', $params, 'single');
        if ($data === false) {
            $errorMessages['global'] = 'Database error';
            $errorMessages['uri'] = 'Cannot verify ID';
            $uriError = true;
        } else if (sizeof($data) === 0) {
            $errorMessages['uri'] = 'ID does not exists';
            $uriError = true;
        } else {
            foreach ($data as $key => $value) {
                $inputValues[$key] = General::output($value);
            }
            $uniqueValues['name'] = $inputValues['name'];
        }
    } else {
        $errorMessages['uri'] = 'ID does not match a numeric value';
        $uriError = true;
    }
} else {
    $errorMessages['uri'] = 'ID is missing';
    $uriError = true;
}
if (!$uriError) {
    if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
        if (isset($_POST['token']) && isset($_POST['id']) && isset($_POST['name']) && isset($_POST['description'])) {
            $inputValues['id'] = General::output($_POST['id']);
            $inputValues['name'] = General::output($_POST['name']);
            $inputValues['description'] = General::output($_POST['description']);
            $parameters = array(
                'token' => trim($_POST['token']),
                'id' => trim($_POST['id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            );
            mb_internal_encoding('UTF-8');
            $error = false;
            if (mb_strlen($parameters['token']) < 1) {
                $errorMessages['token'] = 'Form token was not supplied';
                $error = true;
            } else if (!Session::verifyToken('edit_role', $parameters['token'])) {
                $errorMessages['token'] = 'Form token is invalid or has expired';
                $error = true;
            }
            if (mb_strlen($parameters['id']) < 1) {
                $errorMessages['id'] = 'ID was not supplied';
                $error = true;
            } else if (!is_numeric($parameters['id'])) {
                $errorMessages['id'] = 'ID does not match a numeric value';
                $error = true;
            } else if ($parameters['id'] !== $_GET['id']) {
                $errorMessages['id'] = 'ID does not match URI ID';
                $error = true;
            }
            if (mb_strlen($parameters['name']) < 1) {
                $errorMessages['name'] = 'Please enter name';
                $error = true;
            } else if (mb_strlen($parameters['name']) > 20) {
                $errorMessages['name'] = 'Name is exceeding 20 characters';
                $error = true;
            } else if ($uniqueValues['name'] !== $parameters['name']) {
                $params = array(
                    'name' => strtolower($parameters['name'])
                );
                $count = Query::count('SELECT `name` FROM `roles` WHERE LOWER(`name`) = :name AND ', $params);
                if ($count === false) {
                    $errorMessages['global'] = 'Database error';
                    $errorMessages['name'] = 'Cannot verify name';
                    $error = true;
                } else if ($count > 0) {
                    $errorMessages['name'] = 'Name already exists';
                    $error = true;
                }
            }
            if (mb_strlen($parameters['description']) < 1) {
                $errorMessages['description'] = 'Please enter description';
                $error = true;
            } else if (mb_strlen($parameters['description']) > 300) {
                $errorMessages['description'] = 'Description is exceeding 300 characters';
                $error = true;
            }
            if (!$error) {
                $params = array(
                    'id' => $parameters['id'],
                    'name' => $parameters['name'],
                    'description' => $parameters['description']
                );
                if (Query::update('UPDATE `roles` SET `name` = :name, `description` = :description WHERE `id` = :id', $params) !== false) {
                    header('Location: ./');
                    exit();
                } else {
                    $errorMessages['global'] = 'Database error';
                }
            }
        }
    }
}
$inputValues['token'] = Session::generateToken('edit_role');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Edit Role | Securilla</title>
		<meta name="description" content="Edit existing role.">
		<meta name="keywords" content="HTML, CSS, PHP, JavaScript, jQuery, Securilla">
		<meta name="author" content="MOHAMMED ALDOUFANI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="../img/gor.ico">
		<link rel="stylesheet" href="../css/main1.css" hreflang="en" type="text/css" media="all">
		<script src="../js/jquery.js"></script>
		<script src="../js/main.js" defer></script>
	</head>
	<body>
		<?php include_once '../components/navigation.php'; ?>
		<div class="crud-add-edit">
			<p class="error-global"><?php echo $errorMessages['global']; ?></p>
			<p class="error-global"><?php echo $errorMessages['uri']; ?></p>
			<p class="error-global"><?php echo $errorMessages['token']; ?></p>
			<form id="edit-form" method="post" action="./edit.php?id=<?php echo $uriError ? '' : General::output($_GET['id']); ?>">
				<input name="token" id="token" type="hidden" value="<?php echo $inputValues['token']; ?>">
				<div class="data-row">
					<label for="id">ID</label>
					<input name="id" id="id" type="text" readonly="readonly" value="<?php echo $inputValues['id']; ?>">
					<p class="error"><?php echo $errorMessages['id']; ?></p>
				</div>
				<div class="data-row">
					<label for="name">Name</label>
					<input name="name" id="name" type="text" spellcheck="false" maxlength="20" required="required" value="<?php echo $inputValues['name']; ?>">
					<p class="error"><?php echo $errorMessages['name']; ?></p>
				</div>
				<div class="data-row">
					<label for="description">Description</label>
					<textarea name="description" id="description" form="edit-form" rows="6" required="required"><?php echo $inputValues['description']; ?></textarea>
					<p class="error"><?php echo $errorMessages['description']; ?></p>
				</div>
				<div class="btn">
					<input type="submit" value="Save"<?php echo $uriError ? ' disabled="disabled"' : ''; ?>>
				</div>
			</form>
		</div>
		<?php include_once '../components/footer.php'; ?>
	</body>
</html>
