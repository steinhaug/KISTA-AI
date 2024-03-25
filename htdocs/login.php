<?php
session_start();
session_regenerate_id(true);
define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');
require_once 'func.inc.php';
require_once 'func.login.php';

if(isset($_SESSION['USER_GOOGLE_LOGIN'])){
    header('Location: home.php');
    exit;
}

// Creating new google client instance
$client = new Google\Client();
$client->setClientId($google_client_id);
$client->setClientSecret($google_client_secret);
$client->setRedirectUri($google_redirect_uri);

// Adding those scopes which we want to get (email & profile Information)
$client->addScope("email");
$client->addScope("profile");

if(isset($_GET['code'])):

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if(!isset($token["error"])){

        $client->setAccessToken($token['access_token']);

        // getting profile information
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
    
        // checking user already exists or not
        if( ($user_google_id = $mysqli->prepared_query1("SELECT `user_google_id` FROM `" . $kista_dp . "users__google` WHERE `account_id`=?", 's', [$google_account_info->id], 0)) !== null ){
            $_SESSION['USER_GOOGLE_LOGIN'] = [$user_google_id, $google_account_info->id, ['image'=>$google_account_info->picture, 'name'=>trim($google_account_info->name), 'email'=>$google_account_info->email]];
            logfile('user_google_id already exist, ' . $user_google_id);
            setGoogleID4Session($user_google_id);
            if(!empty($_SESSION['logged_in_location'])){
                $_SESSION['logged_in_alert'] = true;
                header('Location: ' . $_SESSION['logged_in_location']);
                unset($_SESSION['logged_in_location']);
            } else {
                header('Location: home.php');
            }
            exit;
        } else {
            $sql = [
                "INSERT INTO `" . $kista_dp . "users__google` (`account_id`,`account_name`,`account_email`,`account_picture`) VALUES (?,?,?,?)",
                "ssss",
                [$google_account_info->id, trim($google_account_info->name), $google_account_info->email, $google_account_info->picture]
            ];
            $user_google_id = $mysqli->prepared_insert($sql);
            if($user_google_id){
                $_SESSION['USER_GOOGLE_LOGIN'] = [$user_google_id, $google_account_info->id, ['image'=>$google_account_info->picture, 'name'=>trim($google_account_info->name), 'email'=>$google_account_info->email]];
                setGoogleID4Session($user_google_id);
                if(!empty($_SESSION['logged_in_location'])){
                    $_SESSION['logged_in_alert'] = true;
                    header('Location: ' . $_SESSION['logged_in_location']);
                    unset($_SESSION['logged_in_location']);
                } else {
                    header('Location: home.php');
                }
                exit;
            }

            header("Content-type: application/json; charset=utf-8");
            echo json_encode(['error'=>'Could not insert into database.']);
            exit;

        }

    } else {

        $_SESSION['error_msg'] = json_encode($token["error"]);

        if(!empty($_SESSION['logged_in_location'])){
            //header('Location: ' . $_SESSION['logged_in_location']);
            header('Location: error.php?error');
            unset($_SESSION['logged_in_location']);
        } else {
            header('Location: login.php?error');
        }
        exit;

    }
    
else: 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        body{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7ff;
            padding: 10px;
            margin: 0;
        }
        ._container{
            max-width: 400px;
            background-color: #ffffff;
            padding: 20px;
            margin: 0 auto;
            border: 1px solid #cccccc;
            border-radius: 2px;
        }
        ._container.btn{
            text-align: center;
        }
        .heading{
            text-align: center;
            color: #4d4d4d;
            text-transform: uppercase;
        }
        .login-with-google-btn {
            transition: background-color 0.3s, box-shadow 0.3s;
            padding: 12px 16px 12px 42px;
            border: none;
            border-radius: 3px;
            box-shadow: 0 -1px 0 rgb(0 0 0 / 4%), 0 1px 1px rgb(0 0 0 / 25%);
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
            background-color: #4a4a4a;
            background-repeat: no-repeat;
            background-position: 12px 11px;
            text-decoration: none;
        }
        .login-with-google-btn:hover {
            box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.25);
        }
        .login-with-google-btn:active {
            background-color: #000000;
        }
        .login-with-google-btn:focus {
            outline: none;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.25), 0 0 0 3px #c8dafc;
        }
        .login-with-google-btn:disabled {
            filter: grayscale(100%);
            background-color: #ebebeb;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 1px 1px rgba(0, 0, 0, 0.25);
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="_container">
        <h2 class="heading">Login</h2>
    </div>
    <div class="_container btn">
        
        <a type="button" class="login-with-google-btn" href="<?php echo $client->createAuthUrl(); ?>">
            Sign in with Google
        </a>
    </div>

<?php
if( !empty($_SESSION['error_msg']) ){

    echo '<div style="border:2px solid red; padding: 10px; margin: 10px;">' . htmlentities($_SESSION['error_msg']) . '</div>';

}

?>


</body>
</html>
<?php endif; ?>