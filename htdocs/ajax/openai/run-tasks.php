<?php
class OpenAIException extends Exception {};

$error = null;
$upload_id = (int) $_SESSION['task']['aiid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID);
if ($res->num_rows) {
 
    $log = [];
    $item = $res->fetch_assoc();

    if( $item['status'] == 'start' ){

        try {

            if (!($item['extension'] == 'png' or $item['extension'] == 'jpg')) {
                throw new Exception('Image format not supported');
            }

            #require AJAX_FOLDER_PATH . '/openai/task01-createThumbnail.php';
            #require AJAX_FOLDER_PATH . '/openai/task02-OpenAIVision.php';

$list_of_ingredients = '- Eggs - Approximately 6 eggs (visible in the transparent egg holder on the top shelf)
- Boxed Raisins - 1 box (visible on the top shelf on the left side)
- Quaker Oats - 1 box of whole grain oats (visible on the top shelf)
- Condiment Bottles - Various types including soy sauce, vinegar, and other sauces (multiple bottles, difficult to count precisely, but approximately 15-20 bottles spread over the door shelves and inside)
- Beverage Cans - At least 2 cans (visible on the middle shelf, potentially Coca-Cola or similar soda)
- Stick Butter or Margarine - 1 package (visible in the door shelf compartment)
- Cooking Oil - 1 large bottle (visible on the bottom door shelf)
- Creamer or Condensed Milk - 1 bottle (visible on the middle door shelf)
- Plastic Container - Appears to contain a food item, perhaps leftovers or dairy product (1 visible on the second shelf, details unclear)
- Gelatin Powder - 1 packet (visible on the bottom door shelf)
- Yogurt or Pudding Cups - Multiple small containers located on the second shelf (quantity unclear, potentially 4 or more)
- Packaged Cheese or Processed Cheese Slices - Located in the drawer compartment in the middle (details are unclear)
- Boxed Beverage or Milk - 1 carton (visible on the second shelf to the right)
- Aluminum Foil Covered Items - At least 1, possible leftovers or prepared food (visible on the top and second shelves)';


            require AJAX_FOLDER_PATH . '/openai/task03-OpenAIChatYouAreChef.php';

            $sql = new sqlbuddy;
            $sql->que('status', 'complete', 'string');
            $sql->que('log', json_encode($log), 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));

        } catch (OpenAIException $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', 'OpenAI error: ' . $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            var_dump($success);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            var_dump($success);
        }

    } else {
        echo $item['status'];
        exit;
    }

} else {
    $error = 'An APP error has occured. Task ' . (int) $upload_id . ' does not exist.';
    $_SESSION['error_msg'] = $error;
}

echo '<hr><div style="text-align:center;">COMPLETE</div><hr>';

if( $error === null ){

    echo '<fieldset><legend>chatgpt_result1</legend>';
    echo '<p style="color:blue;">' . $chatgpt_result1 . '</p>';
    echo htmlentities( $log['vision_m1'] );
    echo '<br>' . "\n";
    echo htmlentities( $log['vision_q1'] );
    echo '</fieldset>' . "\n";
    echo '<fieldset><legend>chatgpt_result2</legend>';
    echo '<p style="color:blue;">' . $chatgpt_result2 . '</p>';
    echo htmlentities( $log['vision_m2'] );
    echo '<br>' . "\n";
    echo htmlentities( $log['vision_q2'] );
    echo '</fieldset>' . "\n";

    echo '<fieldset><legend>completion1</legend>';
    echo '<p style="color:blue;">' . $completion1 . '</p>';
    echo htmlentities( $log['chat_m1'] );
    echo '<br>' . "\n";
    echo htmlentities( $log['chat_q1'] );
    echo '</fieldset>' . "\n";
    echo '<fieldset><legend>completion2</legend>';
    echo '<p style="color:blue;">' . $completion2 . '</p>';
    echo htmlentities( $log['chat_m2'] );
    echo '<br>' . "\n";
    echo htmlentities( $log['chat_q2'] );
    echo '</fieldset>' . "\n";

    echo '<h4>$list_of_ingredients</h4>';
    echo '<pre>' . $list_of_ingredients . '</pre>';
} else {
    echo '<h2>error</h2>';
    echo $error;

    echo '<h4>$list_of_ingredients</h4>';
    echo '<pre>' . $list_of_ingredients . '</pre>';

    if (isset($chatgpt_result1)) {
        echo '<fieldset><legend>chatgpt_result1</legend>';
        echo '<p style="color:blue;">' . $chatgpt_result1 . '</p>';
        echo htmlentities($log['vision_m1']);
        echo '<br>' . "\n";
        echo htmlentities($log['vision_q1']);
        echo '</fieldset>' . "\n";
    }
    if (isset($chatgpt_result2)) {
        echo '<fieldset><legend>chatgpt_result2</legend>';
        echo '<p style="color:blue;">' . $chatgpt_result2 . '</p>';
        echo htmlentities($log['vision_m2']);
        echo '<br>' . "\n";
        echo htmlentities($log['vision_q2']);
        echo '</fieldset>' . "\n";
    }
    if (isset($completion1)) {
    echo '<fieldset><legend>completion1</legend>';
    echo '<p style="color:blue;">' . $completion1 . '</p>';
    echo htmlentities($log['chat_m1']);
    echo '<br>' . "\n";
    echo htmlentities($log['chat_q1']);
    echo '</fieldset>' . "\n";
    }
    if (isset($completion2)) {
    echo '<fieldset><legend>completion2</legend>';
    echo '<p style="color:blue;">' . $completion2 . '</p>';
    echo htmlentities($log['chat_m2']);
    echo '<br>' . "\n";
    echo htmlentities($log['chat_q2']);
    echo '</fieldset>' . "\n";
    }
}