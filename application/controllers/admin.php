<?php

class Admin extends CI_Controller {

	public function index()
	{
		$this->load->library('requestapi');
		// verify token class redirects request to root page if token verification failed
		// pass role to parameter, redirect request to root page if role doesn't match
		$this->requestapi->verifyToken(['role' => 'admin']);
		
		$data = $this->requestapi->post($this->config->item('api_host').'/api/subjects');
		if($data['status'] == '404'){
			show_404();
			die();
		}

		$this->load->view('admin/index', ['subjects'=>$data['data']]);
	}

	public function subject($id)
	{
		$this->load->library('requestapi');
		// verify token class redirects request to root page if token verification failed
		// pass role to parameter, redirect request to root page if role doesn't match
		$this->requestapi->verifyToken(['role' => 'admin']);
		
		$data = $this->requestapi->post($this->config->item('api_host').'/api/subjects', ['subject_id'=>$id]);
		if($data['status'] == '404'){
			show_404();
			die();
		}

		$this->load->view('admin/subject', ['subject_id'=>$id, 'subjects'=>$data['data']]);
	}

	public function ranking(){
		$this->load->library('requestapi');
		// verify token class redirects request to root page if token verification failed
		// pass role to parameter, redirect request to root page if role doesn't match
		$this->requestapi->verifyToken(['role' => 'admin']);
		
		$data = $this->requestapi->post($this->config->item('api_host').'/api/subjects');
		if($data['status'] == '404'){
			show_404();
			die();
		}

		$this->load->view('admin/ranking', ['subjects'=>$data['data']]);
	}

}

?>