<?php



/**
 * Associate the Google login with current session
 *
 * @param int $user_google_id The user_google-id
 * 
 * @return mixed Null if ignored and update result as boolean.
 */
function setGoogleID4Session($user_google_id){
    global $mysqli;

    if(!empty($_SESSION['USER_ID']))
        return null;

    $sql = [
        "UPDATE `" . $kista_dp . "users__sessions` SET `google_id`=? WHERE `user_id`=?",
        "ii",
        [$user_google_id, $_SESSION['USER_ID']]
    ];
    $success = $mysqli->prepared_insert($sql);
    return $success;
}


/**
 * Load the saved session data from your saved session
 *
 * @param int $session_id The id from the session table.
 * @return void
 */
function loadSessionData($session_id){
    global $mysqli, $kista_dp;
    $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "users__sessions_data` WHERE `session_id`=" . (int) $session_id);
    if ($res->num_rows) {
        $item = $res->fetch_assoc();
        if( !empty($item['data']) ){
            $data = json_decode($item['data']);
            if( !empty($data) and is_array($data) ){
                foreach($data as $k=>$v){
                    $_SESSION[$k] = $v;
                }
            }
        }
    }

}

/**
 * SetSessionKey if conition
 *
 * @param string $key
 * @param string $value
 * @param mixed $if Boolean or null to always set.
 * @return void
 */
function setSessionKeyIf($key, $value, $if=false){

    if($if===false){
        if( !isset($_SESSION[$key]) ){
            $_SESSION[$key] = $value;
            saveSessionKeys();
        }
    } else if($if===true){
        if( isset($_SESSION[$key]) ){
            $_SESSION[$key] = $value;
            saveSessionKeys();
        }
    } else {
        $_SESSION[$key] = $value;
        saveSessionKeys();
    }

}
function setSessionKey($key, $value){
    setSessionKeyIf($key, $value, null);
}

/**
 * getSessionKeyIfOr
 *
 * @param mixed ...$vals 1-3 Mixed, BOOL, mixed. 
 * 
 * @return mixed The value returned
 */
function getSessionKeyIfOr(...$vals){

    $or = array_pop($vals);
    $if = array_pop($vals);

    if (count($vals) == 3) {
        [$v1,$v2,$v3] = $vals;
        if ($if===false) {
            if (!isset($_SESSION[$v1][$v2][$v3])) {
                return $_SESSION[$v1][$v2][$v3];
            } else {
                return $or;
            }
        } elseif ($if===true) {
            if (isset($_SESSION[$v1][$v2][$v3])) {
                return $_SESSION[$v1][$v2][$v3];
            } else {
                return $or;
            }
        }
    } else if(count($vals) == 2){
        [$v1,$v2] = $vals;
        if ($if===false) {
            if (!isset($_SESSION[$v1][$v2])) {
                return $_SESSION[$v1][$v2];
            } else {
                return $or;
            }
        } elseif ($if===true) {
            if (isset($_SESSION[$v1][$v2])) {
                return $_SESSION[$v1][$v2];
            } else {
                return $or;
            }
        }
    } else {
        [$v1] = $vals;
        if ($if===false) {
            if (!isset($_SESSION[$v1])) {
                return $_SESSION[$v1];
            } else {
                return $or;
            }
        } elseif ($if===true) {
            if (isset($_SESSION[$v1])) {
                return $_SESSION[$v1];
            } else {
                return $or;
            }
        }
    }

}

function getSessionKey($k1, $k2=null, $k3=null){
    if($k3===null and $k2===null)
        return $_SESSION[$k1];
        else if($k3===null)
        return $_SESSION[$k1][$k2];
        else
        return $_SESSION[$k1][$k2][$k3];
}

function unsetSessionKey($key){
    unset($_SESSION[$key]);
    saveSessionKeys();
}


/**
 * Save the _SESSION into the DB
 *
 * @return void
 */
function saveSessionKeys($session_id = null){
    global $mysqli, $kista_dp, $USER_ID;

    if($session_id === null)
        $session_id = $USER_ID;

    if( $mysqli->query1("SELECT count(`session_id`) FROM `" . $kista_dp . "users__sessions_data` WHERE `session_id`=" . (int) $session_id,0) ){
        $sql = [
            "UPDATE `" . $kista_dp . "users__sessions_data` SET `data`=? WHERE `session_id`=?",
            "si",
            [json_encode($_SESSION), $session_id]
        ];
        $id = $mysqli->prepared_insert($sql);
    } else {
        $sql = [
            "INSERT INTO `" . $kista_dp . "users__sessions_data` (`session_id`,`data`) VALUES (?,?)",
            "is",
            [$session_id, json_encode($_SESSION)]
        ];
        $id = $mysqli->prepared_insert($sql);
    }
}


/**
 * Push task into the session tasks
 *
 * @param array $task The task, []
 * 
 * @return boolean On successfull add true, else false
 */
function addSessionTask($task){

    if( !isset($_SESSION['task']) )
        $_SESSION['task'] = [];

    $mode = null;
    if( is_array($task) ){
        if( isset($task['aiid']) )
            $mode = 'openai';
        if( isset($task['reid']) )
            $mode = 'replicate';
    }

    if( getSessionTaskKey($task) === null ){
        $_SESSION['task'][] = $task;
        return true;
    }

    return false;
}


/**
 * Undocumented function
 *
 * @param array $x 'what_id'=>int, define what task to look for
 * 
 * @return mixed A positive integer upon success, null on fail
 */
function getSessionTaskKey($x){

    $mode = null;
    if( is_array($x) ){
        if( isset($x['aiid']) )
            $mode = 'openai';
        if( isset($x['reid']) )
            $mode = 'replicate';
    }

    foreach($_SESSION['task'] as $k=>$task){
        if($mode == 'openai'){
            if(isset($task['aiid']) and ($task['aiid'] == $x['aiid'])){
                logfile('Task found (' . $k . '): openai');
                return $k;
            }
        } else if($mode == 'replicate'){
            if(isset($task['reid']) and ($task['reid'] == $x['reid'])){
                logfile('Task found (' . $k . '): replicate');
                return $k;
            }
        }
    }

    return null; // fail

}


/**
 * Undocumented function
 *
 * @param array $x 'what_id'=>int, define what task to look for
 * 
 * @return mixed A positive integer upon success, null on fail
 */
function removeSessionTask($x){

    if( ($currentTask = getSessionTaskKey($x)) !== null ){
        unset($_SESSION['task'][$currentTask]);
        return $currentTask;
    }

    return null;
}


/**
 * Usefull when doing background stuff, this will close the connection for the browser so execution can complete in background
 *
 * @param [type] $message
 * @return void
 */
function end_connection($message){
    // Start output buffering
    ob_start();

    // Your message to the user
    echo $message;

    // Calculate the size of the output
    $size = ob_get_length();

    // Send headers to tell the browser to close the connection
    header("Content-Length: $size");
    header('Connection: close');

    // Flush all output buffers to the client
    ob_end_flush();
    flush();

    // Continue processing after the client disconnects
    ignore_user_abort(true);
    set_time_limit(0); // Remove time limit for script execution if needed

    // Close session write if needed
    if (session_id()) session_write_close();

    // Send additional data to ensure the browser considers the response complete
    echo str_repeat(' ', 1024*64); // Send 64KB of whitespace
    flush();

}