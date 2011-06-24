<?php

echo parse_css($_POST['css']);

function parse_css($css_url) {
	$result	= array();

	if(strlen($css_url) && strpos($css_url, '://') === false) {
		if(strpos($css_url, '/') === 0) // against '/main.css'
			$css_url = substr($css_url, 1);
		
		if(($css = file_get_contents($css_url)) !== false) {
			// strip comments
			$css = preg_replace("/\/\*(.*)?\*\//Usi", "", $css);
			// parse css
			$parts = explode("}", $css);
			if(sizeof($parts) > 0) {
				foreach($parts as $part) {
					list($s_key, $s_code) = explode("{", $part);
					$keys = explode(",", trim($s_key));

					if(sizeof($keys) > 0) {
						foreach($keys as $key) {
							//if(strlen($key) > 0) {
							list($tmp, $key) = explode(".", $key);
							list($key, $tmp) = explode(" ", $key);
							list($key, $tmp) = explode(":", $key);
							list($key, $tmp) = explode("#", $key);
							
							$key = trim($key);
								
							if(strlen($key))
								$result[$key]	= true;
						}
					}
				}
			}

			$result = array_keys($result);
		}
	}

	return implode(',', $result);
}
?>