<?php

session_start();

$_SESSION['logged_in_location'] = rawurldecode($_GET['google_login']);

header('Location: ' . $_SESSION['url_google_login']);