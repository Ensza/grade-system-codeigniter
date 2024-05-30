<?php
class Student extends CI_Controller {

	public function index()
	{
		$this->load->library('requestapi');
		// verify token class redirects request to root page if token verification failed
		// pass role to parameter, redirect request to root page if role doesn't match
		$this->requestapi->verifyToken(['role' => 'student']);

		$data = $this->requestapi->get($this->config->item('api_host').'/api/student/grades');

		$this->load->view('student/index', $data);
	}

}

?>