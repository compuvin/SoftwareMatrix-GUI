<?php
require_once __DIR__ . '/auth.php';

auth_log_out_user();

header('Location: auth-login.php');
exit;
