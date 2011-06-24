<?php

class RemoteFiles{
	
	
	/**
	 * Sets up the class
	 */
	public function RemoteFiles(){
		
	}	
	
	/**
	 * Alternate download remote file.
	 */
	public function downloadRemoteFile($url,$dir,$file_name = NULL){
	    if($file_name == NULL){ $file_name = basename($url);}
	    $url_stuff = parse_url($url);
	    $port = isset($url_stuff['port']) ? $url_stuff['port'] : 80;
	
	    $fp = fsockopen($url_stuff['host'], $port);
	    if(!$fp){ return false;}
	
	    $query  = 'GET ' . $url_stuff['path'] . " HTTP/1.0\n";
	    $query .= 'Host: ' . $url_stuff['host'];
	    $query .= "\n\n";
	
	    fwrite($fp, $query);
	
	    while ($tmp = fread($fp, 8192))   {
	        $buffer .= $tmp;
	    }
	
	    preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
	    $file_binary = substr($buffer, - $parts[1]);
	    if($file_name == NULL){
	        $temp = explode(".",$url);
	        $file_name = $temp[count($temp)-1];
	    }
	    $file_open = false;
	   
	    if($dir=="../../"){
	    	 $file_open = fopen($dir . $file_name,'w');
	    }else if(empty($dir)){
	   		 $file_open = fopen($file_name,'w');
	    }else{
	    	$file_open = fopen($dir . "/" . $file_name,'w');
	    }
	    if(!$file_open){ return false;}
	    fwrite($file_open,$file_binary);
	    fclose($file_open);
	    return true;
	}  
	
	/**
	 * Tries to retrieve the contents of a remote url
	 */
	public function getURL($url){
		
		$data = $this->getRemoteFile($url);
		
		return $data;
	}
	
	private function getRemoteFile($url)
	{
	   // get the host name and url path
	   $parsedUrl = parse_url($url);
	   $host = $parsedUrl['host'];
	   if (isset($parsedUrl['path'])) {
	      $path = $parsedUrl['path'];
	   } else {
	      // the url is pointing to the host like http://www.mysite.com
	      $path = '/';
	   }
	
	   if (isset($parsedUrl['query'])) {
	      $path .= '?' . $parsedUrl['query'];
	   }
	
	   if (isset($parsedUrl['port'])) {
	      $port = $parsedUrl['port'];
	   } else {
	      // most sites use port 80
	      $port = '80';
	   }
	
	   $timeout = 5;
	   $response = '';
	
	   // connect to the remote server
	   $fp = @fsockopen($host, $port, $errno, $errstr, $timeout );
	
	   if( !$fp ) {
	      echo "Cannot retrieve $url";
	   } else {
	      // send the necessary headers to get the file
	      fputs($fp, "GET $path HTTP/1.0\r\n" .
	                 "Host: $host\r\n" .
	                 "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
	                 "Accept: */*\r\n" .
	                 "Accept-Language: en-us,en;q=0.5\r\n" .
	                 "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
	                 "Keep-Alive: 300\r\n" .
	                 "Connection: keep-alive\r\n" .
	                 "Referer: http://$host\r\n\r\n");
	
	      // retrieve the response from the remote server
	      while ( $line = fread( $fp, 4096 ) ) {
	         $response .= $line;
	      }
	
	      fclose( $fp );
	
	      // strip the headers
	      $pos      = strpos($response, "\r\n\r\n");
	      $response = substr($response, $pos + 4);
	   }
	
	   // return the file content
	   return $response;
	}
}

?>