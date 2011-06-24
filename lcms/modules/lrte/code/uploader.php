<?php
session_start();
if(!isset($_SESSION['login'])){
	echo "No Login.";
}

$dir = '../../../data/files';

echo upload_process($dir);

function upload_process($dir) {
	$file = current($_FILES); // we handle the only file in time

	if($file['error'] == UPLOAD_ERR_OK) {
		if(@move_uploaded_file($file['tmp_name'], "{$dir}/{$file['name']}"))
			$file['error']	= ''; //no errors, 0 - is our error code for 'moving error'
	}

	$arr = array(
		'error' => $file['error'], 
		'file' => str_replace("modules/lrte/code/uploader.php","data/files",getenv("SCRIPT_NAME"))."/{$file['name']}",
		'tmpfile' => $file['tmp_name'], 
		'size' => $file['size']
	);

	if(function_exists('json_encode'))
		return json_encode($arr);
	
	$result = array();
	foreach($arr as $key => $val) {
		$val = (is_bool($val)) ? ($val ? 'true' : 'false') : $val;
		$result[] = "'{$key}':'{$val}'";
	}

	return '{' . implode(',', $result) . '}';
}
?>