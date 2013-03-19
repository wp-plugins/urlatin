<?php

class urlat_in_connector {
  
    private static $host = "urlat.in";
    private static $port = 80;
    
    //---
	
    public static function send( $_parameters, $user = null, $pass = null ) {
        
        $body = "_api=1&_1=1";
		
		if ($user != null)
			$body .= "&user=".urlencode($user)."&pass=".urlencode($pass);
		
		foreach($_parameters as $key => $value)
			$body .= '&'.$key.'='.urlencode($value);

        $header = "POST /!_a HTTP/1.0\r\n" 
                 ."Host: ".self::$host."\r\n"
                 ."Content-Type: application/x-www-form-urlencoded\r\n"
                 ."Content-Length: ".strlen($body)."\r\n\r\n"
                 .$body."\r\n";

        return self::connect($header);
    }
    
    //---
       
    public static function parseJsonResponse($jsonResponse) {
        
        return json_decode($jsonResponse, true);
    }
    
    public static function getShortUrl($jsonResponse) {
        
        $arr = self::parseJsonResponse($jsonResponse);
        return $arr['protocol'].$arr['domain'].'/'.$arr['items'][0]['shortly'];
    }
	
	//---
    
    private static function connect($header) {
        
        $socket = fsockopen(self::$host, self::$port);
        
        if (!$socket)
            return ("NO CONECTION");

        fputs($socket, $header);
        
        $response = '';
        while (!feof($socket)) {
            $response .= fgets($socket, 128);
        }

        fclose($socket);
        
        $pos = strpos($response, "\n\r");
        $response = trim(substr($response, $pos + 2));
        
        return $response;
    }

}

?>
