<?php
require_once __DIR__ . '/auth.php';

$error = '';
$redirect = auth_safe_redirect($_GET['redirect'] ?? '/');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $redirect = auth_safe_redirect($_POST['redirect'] ?? '/');

    if (auth_login($username, $password)) {
        header('Location: ' . $redirect);
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Software Asset Management - Login</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/form.css" type="text/css"> <!--Forms-->
</head>
<body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<div class="container1">
		<h2>Sign in</h2>
		<p>Enter your credentials to edit data.</p>
	</div>
	<div class="container3">
		<div class="row">
			<div class="col-25">
				<label>Username</label>
			</div>
			<div class="col-75">
				<input type="text" name="username" autocomplete="username" required>
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				<label>Password</label>
			</div>
			<div class="col-75">
				<input type="password" name="password" autocomplete="current-password" required>
			</div>
		</div>
		<?php if ($error !== '') { ?>
		<div class="row">
			<div class="col-25">
				<label class="error">Error</label>
			</div>
			<div class="col-75">
				<span class="error"><?php echo $error; ?></span>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-25">
				<label></label>
			</div>
			<div class="col-75">
				<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
				<input type="submit" value="Login">
			</div>
		</div>
	</div>
</form>

</body>
</html>
