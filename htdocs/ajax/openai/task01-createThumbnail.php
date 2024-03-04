<?php

        $imgIn  = UPLOAD_PATH . DIRECTORY_SEPARATOR . $item['filename'];
        $imgOut = UPLOAD_PATH . DIRECTORY_SEPARATOR . '_thumbs' . DIRECTORY_SEPARATOR . basename($item['filename'], "." . $item['extension']) . '.jpg';

        createThumbnail(
            $imgIn,
            $imgOut,
            ['resize' => [150, 150]]
        );
        $log[] = 'Thumbnail, created.';

        $sql = new sqlbuddy;
        $sql->que('thumbnail', '_thumbs' . DIRECTORY_SEPARATOR . basename($item['filename'], "." . $item['extension']) . '.jpg', 'string');
        $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
        if (!$success) {
            throw new Exception('Thumbnail creation error');
        }
        $log[] = 'Thumbnail, DB updated.';
