<?php
class Login extends CI_Controller {

	public function index()
	{
		//verify token, if token is invalid display login page else redirect to corresponding page
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

		if($httpcode == '200'){
			switch($json['role']){
				case 'admin':
					header("Location: http://localhost56/admin");
					die();
					break;
				case 'student':
					header("Location: http://localhost56/student");
					die();
					break;
			}
		}else{
			$this->load->view('login');
		}

	}
}
?>