<?php
require_once __DIR__ . '/auth.php';
require_auth();
require_admin();

$connection = auth_db_connect();
auth_initialize($connection);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif (!in_array($role, ['admin', 'user'], true)) {
        $error = 'Invalid role selected.';
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $statement = mysqli_prepare(
            $connection,
            "INSERT INTO auth_users (username, password_hash, role) VALUES (?, ?, ?)"
        );

        if ($statement === false) {
            $error = 'Unable to prepare user insert.';
        } else {
            mysqli_stmt_bind_param($statement, 'sss', $username, $passwordHash, $role);
            if (!mysqli_stmt_execute($statement)) {
                $error = 'Unable to create user. Username may already exist.';
            } else {
                $message = 'User created successfully.';
            }
            mysqli_stmt_close($statement);
        }
    }
}

$users = [];
$result = mysqli_query($connection, "SELECT username, role, created_at FROM auth_users ORDER BY username");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

mysqli_close($connection);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Software Asset Management - Manage Users</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/topnav.css" type="text/css"> <!--Top Navigation-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/form.css" type="text/css"> <!--Forms-->
	<link rel=stylesheet href="css/table.css" type="text/css"> <!--Tables-->
</head>
<body>

<div class="topnav">
	<a href="../"><img src="images\house.png" alt="home" width="32" height="32"></a>
</div>

<div class="container1">
	<h2>Manage Users</h2>
	<p>Create additional users for this instance.</p>
</div>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	<div class="container3">
		<div class="row">
			<div class="col-25">
				<label>Username</label>
			</div>
			<div class="col-75">
				<input type="text" name="username" required>
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				<label>Password</label>
			</div>
			<div class="col-75">
				<input type="password" name="password" required>
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				<label>Role</label>
			</div>
			<div class="col-75">
				<select name="role" required>
					<option value="user">User</option>
					<option value="admin">Admin</option>
				</select>
			</div>
		</div>
		<?php if ($message !== '') { ?>
		<div class="row">
			<div class="col-25">
				<label></label>
			</div>
			<div class="col-75">
				<span><?php echo $message; ?></span>
			</div>
		</div>
		<?php } ?>
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
				<input type="submit" value="Create User">
			</div>
		</div>
	</div>
</form>

<div class="container3">
	<h3>Existing Users</h3>
	<table id="table3">
		<tr>
			<th>Username</th>
			<th>Role</th>
			<th>Created</th>
		</tr>
		<?php if (count($users) === 0) { ?>
		<tr>
			<td colspan="3">No users found.</td>
		</tr>
		<?php } ?>
		<?php foreach ($users as $userRow) { ?>
		<tr>
			<td><?php echo htmlspecialchars($userRow['username']); ?></td>
			<td><?php echo htmlspecialchars($userRow['role']); ?></td>
			<td><?php echo htmlspecialchars($userRow['created_at']); ?></td>
		</tr>
		<?php } ?>
	</table>
</div>

</body>
</html>
