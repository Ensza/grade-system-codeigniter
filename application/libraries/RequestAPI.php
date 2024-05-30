<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RequestAPI {

    // verify token class redirects request to root page if token verification failed or if role doesn't match
    public function verifyToken($params)
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

    public function get($url, $data = []){
        $token = $_COOKIE['api_token'];
		$ch = curl_init($url);

		curl_setopt_array($ch, array(
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
            	'Authorization: Bearer ' . $token
			),
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $data,
		));

		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$json = json_decode($result, 1);
		curl_close($ch);

		$response = [
            'data' => $json,
            'status' => $httpcode
        ];

        return $response;
    }

    public function post($url, $data = []){
        $token = $_COOKIE['api_token'];
		$ch = curl_init($url);

		curl_setopt_array($ch, array(
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
            	'Authorization: Bearer ' . $token
			),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data,
		));

		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$json = json_decode($result, 1);
		curl_close($ch);

		$response = [
            'data' => $json,
            'status' => $httpcode
        ];

        return $response;
    }

}

?>