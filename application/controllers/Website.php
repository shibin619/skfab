<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

	 public function __construct() {
        parent::__construct();
    }

    public function index() {

        $data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('index', ['data' => $data]);
    }

	public function about()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('about', ['data' => $data]);
	}

	public function blog()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('blog', ['data' => $data]);
	}

	public function contact()
	{
		$data = $this->skfabmodel->get_web_details();
		$data['page_name'] = 'contact';
		$data = array_column($data, 'content', 'code');
        $this->load->view('contact', ['data' => $data]);
	}

	public function detail()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('detail', ['data' => $data]);
	}

	public function project()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('project', ['data' => $data]);
	}

	public function service()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('service', ['data' => $data]);
	}

	public function team()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('team', ['data' => $data]);
	}

	public function testimonial()
	{
		$data = $this->skfabmodel->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('testimonial', ['data' => $data]);
	}

}
