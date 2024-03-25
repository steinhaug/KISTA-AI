<?php

session_start();

unset($_SESSION['error_msg']);
$_SESSION['logged_in_location'] = rawurldecode($_GET['google_login']);


header('Location: ' . $_SESSION['url_google_login']);