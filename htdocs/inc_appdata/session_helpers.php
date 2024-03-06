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

function setSessionKey($key, $value){
    global $mysqli, $kista_dp;
    $_SESSION[$key] = $value;

    if(USER_ID){
        $sql = [
            "INSERT INTO `" . $kista_dp . "users__sessions_data` (`data`) VALUES (?)",
            "s",
            [json_encode($_SESSION)]
        ];
        $id = $mysqli->prepared_insert($sql);
    }

}

function unsetSessionKey($key){
    global $mysqli, $kista_dp;
    unset($_SESSION[$key]);

    if(USER_ID){
        $sql = [
            "INSERT INTO `" . $kista_dp . "users__sessions_data` (`data`) VALUES (?)",
            "s",
            [json_encode($_SESSION)]
        ];
        $id = $mysqli->prepared_insert($sql);
    }
}
