<?php

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
