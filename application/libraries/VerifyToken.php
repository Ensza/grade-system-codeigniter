<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyToken {
    // verify token class redirects request to root page if token verification failed or if role doesn't match
    public function __construct($params)
    {
        $token = $_COOKIE['api_token'];
		$ch = curl_init("http://localhost/api/user");

		curl_setopt_array($ch, array(
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
            	'Authorization: Bearer ' . $token
			),
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_RETURNTRANSFER => true,
		));

		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$json = json_decode($result, 1);
		curl_close($ch);

		if($httpcode != '200' || $json['role'] != $params['role']){
			header("Location: http://localhost56/");
			die();
		}
    }
}

?>