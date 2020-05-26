<?php
include 'database.php';
include 'session.php';

$file_name = $_GET['file'];
if(is_file($file_name)) {
    if (isset($user_check))
    $sql = "UPDATE photos SET counter_download = counter_download + 1 WHERE name_file='$file_name'";
    $db->query($sql);

    if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}

    switch(strtolower(substr(strrchr($file_name, '.'), 1))) {
        case 'png': $mime = 'image/png'; break;
        case 'gif': $mime = 'image/gif'; break;
        case 'jpeg':
        case 'jpg': $mime = 'image/jpg'; break;
        default: $mime = 'application/force-download';
    }
    header('Pragma: public'); 	// required
    header('Expires: 0');		// no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
    header('Cache-Control: private',false);
    header('Content-Type: '.$mime);
    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: '.filesize($file_name));	// provide file size
    header('Connection: close');
    readfile($file_name);		// push it out
    exit();

}
?>