<?php
use Intervention\Image\ImageManagerStatic as Image;

$jsondata = ['status'=>100];

$CMD = $_POST['data']['CMD'] ?? '';
$CSSID = $_POST['data']['CSSID'] ?? '';
$image_id = $_POST['data']['IMID'] ?? 0;
$post_user_id = $_POST['data']['UserID'] ?? 0;

try {

    if( $post_user_id != $USER_ID )
        throw new Exception('Authentication error');

    $jsondata['message'] = print_r($_POST['data'], true);


    $user_google_id = $_SESSION['USER_GOOGLE_LOGIN'][0] ?? 0;
    if ($user_google_id) {
        $p_sql = [
            "SELECT `reim`.`reid`, `reim`.`filename`, `reim`.`extension`, `reim`.`filesize` 
             FROM `" . $kista_dp . "replicate__images` `reim` 
             INNER JOIN `" . $kista_dp . "replicate__uploads` `reup` ON `reim`.`reid` = `reup`.`reid` AND `reim`.`image_id` = ? 
             INNER JOIN `" . $kista_dp . "users__sessions` `s` ON `reup`.`user_id` = `s`.`user_id` 
             WHERE `reim`.`deleted` = 0 AND (`s`.`user_id` = ? OR `s`.`google_id` = ?)", 
            'iii', 
            [$image_id, $USER_ID, $user_google_id], 
            true
        ];
    } else {
        $p_sql = [
            "SELECT `reim`.`reid`, `reim`.`filename`, `reim`.`extension`, `reim`.`filesize` 
             FROM `" . $kista_dp . "replicate__images` `reim` 
             INNER JOIN `" . $kista_dp . "replicate__uploads` `reup` ON `reim`.`reid` = `reup`.`reid` 
             WHERE `reim`.`deleted` = 0 AND `reim`.`image_id` = ? AND `reup`.user_id = ?", 
            'ii', 
            [$image_id, $USER_ID], 
            true
        ];
    }

    if (($item = $mysqli->prepared_query1($p_sql)) === null)
        throw new Exception('Image not found');


    $dirPath = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'ri' . DIRECTORY_SEPARATOR;
    $imgPath = $dirPath . $item[0]['filename'];

    // Deleting .jpg, _s.jpg, _m.jpg, .identify-result
    $mainJpg = $dirPath . get_name_only($imgPath) . '.jpg';
    if (file_exists($mainJpg)) {
        logfile('- Deleting ' . $mainJpg);
        unlink($mainJpg);
    }
    $thumb1 = $dirPath . get_name_only($imgPath) . '_m.jpg';
    if (file_exists($thumb1)) {
        logfile('- Deleting ' . $thumb1);
        unlink($thumb1);
    }
    $thumb2 = $dirPath . get_name_only($imgPath) . '_s.jpg';
    if (file_exists($thumb2)) {
        logfile('- Deleting ' . $thumb2);
        unlink($thumb2);
    }
    $identifyFile = $imgPath . '.identify-result';
    if (file_exists($identifyFile)) {
        logfile('- Deleting ' . $identifyFile);
        unlink($identifyFile);
    }

    $img = Image::make($imgPath)->rotate(90)->save($imgPath);

    createThumbnail(
        $imgPath,
        $dirPath . get_name_only($imgPath) . '_m.png',
        ['resize' => [512, 768]]
    );
    logfile('Created replicate thumb 1/3: ' . $dirPath . get_name_only($imgPath) . '_m.png');
    createThumbnail(
        $dirPath . get_name_only($imgPath) . '_m.png',
        $dirPath . get_name_only($imgPath) . '_s.jpg',
        ['resize' => [150, 224]]
    );
    logfile('Created replicate thumb 2/3: ' . $dirPath . get_name_only($imgPath) . '_s.jpg');
    convertImage(
        $dirPath . get_name_only($imgPath) . '_m.png',
        $dirPath . get_name_only($imgPath) . '_m.jpg'
    );
    if( file_exists($dirPath . get_name_only($imgPath) . '_m.png') )
        unlink($dirPath . get_name_only($imgPath) . '_m.png');
    logfile('Created replicate thumb 3/3: ' . $dirPath . get_name_only($imgPath) . '_m.jpg');

    $sucess = $mysqli->query("UPDATE `" . $kista_dp . "replicate__images` SET `updated` = CURRENT_TIMESTAMP WHERE `image_id` = " . (int) $image_id);
    $noCache = $mysqli->query1("SELECT UNIX_TIMESTAMP(updated) FROM `" . $kista_dp . "replicate__images` WHERE `image_id`=" . $image_id,0);
 
    $jsondata['CSSID'] = $CSSID;
    $jsondata['src'] = $webUrl = '/uploaded_files/ri/' . get_name_only($imgPath) . '_m.jpg?' . $noCache;
    $jsondata['message'] = 'Image succesfully rotated and thumbnails recreated.';

} catch (Exception $e) {
    $error = $e->getMessage();
    $jsondata['errorcode'] = 1;
    $jsondata['errormsg'] = $e->getMessage();
}
