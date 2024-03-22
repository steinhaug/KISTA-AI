<?php

$USER_ID = 0;

/*
if( !isset($_COOKIE['PHPSESSID']) ){
    session_destroy();
    header("Location: reciepe.php?action=reset&return=" . rawurlencode(prepareLocation()));
    exit;
}
*/

function considered_crawler(){
    
    if( strpos($_SERVER['HTTP_USER_AGENT'], 'facebookexternalhit') !== false )
        return true;

    return false;
}


if( considered_crawler() ){
    $_SESSION['logged_in'] = true;
    $USER_ID = 0;
} else if( isset($_SESSION['USER_ID']) and isset($_SESSION['USER_SESSION']) ){
    $sql = "SELECT * 
            FROM `" . $kista_dp . "users__sessions` `us` 
            WHERE `us`.`user_id`=? AND `us`.`session_id`=?
            ";
    $res = $mysqli->prepared_query($sql, 'is', [$_SESSION['USER_ID'],$_SESSION['USER_SESSION']]);
    if (count($res)) {
        // We are logged in
        $_SESSION['logged_in'] = true;
        $USER_ID = $_SESSION['USER_ID'];
    } else {
        header("Location: /auto-login.php?action=login_error&return=" . rawurlencode(prepareLocation()));
        exit;
    }
} else {
    header("Location: /auto-login.php?action=login&return=" . rawurlencode(prepareLocation()));
    exit;
}


if( !considered_crawler() and empty($_SESSION['url_google_login']) ){

    $client = new Google_Client();
    $client->setClientId($google_client_id);
    $client->setClientSecret($google_client_secret);
    $client->setRedirectUri($google_redirect_uri);
    $client->addScope("email");
    $client->addScope("profile");
    //$google_url = $client->createAuthUrl();
    $_SESSION['url_google_login'] = $client->createAuthUrl();
    logfile('Generating google url: ' . $_SESSION['url_google_login']);
}
