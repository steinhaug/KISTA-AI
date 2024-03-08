<?php




// Compute and set our REALPATH_RELATIVE_PATH_OFFSET for better readability for the error messages
$relative_path_for_this_file = '/htdocs/inc_appdata/error_handling.php';
if (DIRECTORY_SEPARATOR == '\\') {
    $relative_path_for_this_file = str_replace('/', DIRECTORY_SEPARATOR, $relative_path_for_this_file);
}
if (($pos = strpos(__FILE__, $relative_path_for_this_file)) !== false) {
    $real_part_of_path = substr(__FILE__, 0, $pos);
    define('REALPATH_RELATIVE_PATH_OFFSET', strlen($real_part_of_path));
} else {
    define('REALPATH_RELATIVE_PATH_OFFSET', 0);
}

function debug_log_error($reference, $trace = null){
    global $mysqli, $USER_ID, $kista_dp;

    if (null === $trace) {
        $trace = debug_backtrace(0);
    }
    $toi = interpret_toi($trace);

    $state_arrays = [
        'toi' => $toi['trace'],
        'reference' => $reference,
        'mysqli' => is_object($mysqli) ? json_encode($mysqli) : null,
        '_SESSION' => is_array($_SESSION) ? json_encode($_SESSION) : null,
        '_GET' => is_array($_GET) ? json_encode($_GET) : null,
        '_POST' => is_array($_POST) ? json_encode($_POST) : null,
        '_FILES' => is_array($_FILES) ? json_encode($_FILES) : null,
        '_REQUEST' => is_array($_REQUEST) ? json_encode($_REQUEST) : null,
        '_ENV' => is_array($_ENV) ? json_encode($_ENV) : null,
        '_COOKIE' => is_array($_COOKIE) ? json_encode($_COOKIE) : null,
    ];


    $sql = new sqlbuddy();
    $sql->que('user_id', $USER_ID, 'int');
    $sql->que('created', 'now()', 'raw');
    $sql->que('closed', 'NULL', 'raw');
    $sql->que('status', 1, 'int');
    $sql->que('sourcepath', $toi['trace'], 'string:255');
    $sql->que('reference', $reference, 'string:255');
    $sql->que('state', json_encode($state_arrays), 'text');
    $sql->que('backtrace', json_encode($trace), 'text');
    $mysqli->query($sql->build('insert', $kista_dp . 'debug__errors'));
    $ErrorID = $mysqli->insert_id;

    return $ErrorID;

}








/**
 * Return a string, similar to output by debug_print_backtrace().
 *
 * @param mixed      $traces_to_ignore
 * @param null|mixed $traces
 */
function get_debug_print_backtrace($traces_to_ignore = 1, $traces = null)
{
    if (null === $traces) {
        $traces = debug_backtrace(0);
    }

    $ret = [];
    foreach ($traces as $i => $call) {
        if ($i < $traces_to_ignore) {
            continue;
        }

        $object = '';
        if (isset($call['class'])) {
            $object = $call['class'] . $call['type'];
            if (is_array($call['args'])) {
                foreach ($call['args'] as &$arg) {
                    get_arg($arg);
                }
            }
        }

        $ret[] = '#' . str_pad($i - $traces_to_ignore, 3, ' ')
        . $object . $call['function'] . '(' . implode(', ', $call['args'])
        . ') called at [' . $call['file'] . ':' . $call['line'] . ']';
    }

    return implode("\n", $ret);
}

function get_arg(&$arg)
{
    if (is_object($arg)) {
        $arr = (array) $arg;
        $args = [];
        foreach ($arr as $key => $value) {
            if (str_contains($key, chr(0))) {
                $key = '';    // Private variable found
            }
            $args[] = '[' . $key . '] => ' . get_arg($value);
        }

        $arg = $arg::class . ' Object (' . implode(',', $args) . ')';
    }
}

/**
 * Get the "trace of interest", toi, from the backtrace.
 *
 * @param array $backtrace The PHP backtrace
 *
 * @return array Single array with the most likely error
 */
function interpret_toi($backtrace)
{
    if( $backtrace === null )
        return '';

    $def = [];

    // files to ignore as error source, use forward slashes
    $ignores = ['/mysqli_connect.php', '/www/inc/functions.php', '/www/index.php'];

    // The most likely file with the error
    $c = count($backtrace);
    if ($c > 3) {
        $def[] = [
            'file' => $backtrace[($c - 2)]['file'],
            'line' => $backtrace[($c - 2)]['line'],
            'function' => $backtrace[($c - 2)]['function'],
        ];
    }

    // Make sure needles math the backtrace
    if (DIRECTORY_SEPARATOR == '\\') {
        $ignores = array_map(function ($v) {
            return str_replace('/', DIRECTORY_SEPARATOR, $v);
        }, $ignores);
    }

    // Remove all the common wrong places
    foreach ($backtrace as $call) {
        $ignore = false;
        foreach ($ignores as $ending) {
            if (str_ends_with($call['file'], $ending)) {
                $ignore = true;
            }
        }
        if (false === $ignore) {
            $def[] = [
                'file' => substr($call['file'], REALPATH_RELATIVE_PATH_OFFSET),
                'line' => $call['line'],
                'function' => $call['function'],
                'trace' => substr(get_debug_print_backtrace(0, [$call]), 4),
            ];
        }
    }


    $ret_toi = array_pop($def);
    return $ret_toi;

    if(isset($ret_toi['trace']))
        return (string) $ret_toi['trace'];

    return (string) $ret_toi;
}
