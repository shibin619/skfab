<?php
error_reporting(1);
defined('BASEPATH') or exit('No direct script access allowed');

class Website extends CI_Controller {

	 public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('index', ['data' => $data]);
    }

	public function about()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('about', ['data' => $data]);
	}

	public function blog()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('blog', ['data' => $data]);
	}

	public function contact()
	{
		$data = $this->skfab->get_web_details();
		$data['page_name'] = 'contact';
		$data = array_column($data, 'content', 'code');
        $this->load->view('contact', ['data' => $data]);
	}

	public function detail()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('detail', ['data' => $data]);
	}

	public function project()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('project', ['data' => $data]);
	}

	public function service()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('service', ['data' => $data]);
	}

	public function team()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('team', ['data' => $data]);
	}

	public function testimonial()
	{
		$data = $this->skfab->get_web_details();
		$data = array_column($data, 'content', 'code');
        $this->load->view('testimonial', ['data' => $data]);
	}

	public function appoinment_for_callback() {
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('callback_date', 'Date', 'required|trim');
        $this->form_validation->set_rules('callback_time', 'Time', 'required|trim');
        $this->form_validation->set_rules('message', 'Message', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // Reload the page with validation errors
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            // Save data to database
            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'callback_date' => $this->input->post('callback_date'),
                'callback_time' => $this->input->post('callback_time'),
                'message' => $this->input->post('message'),
                'created_at' => date('Y-m-d H:i:s')
            );
			
            if ($this->db->insert_request($data)) {
                $this->session->set_flashdata('success', 'Your callback request has been submitted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again.');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

	public function send_message() {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('message', 'Message', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            // Collect data
            $data = array(
                'name'    => $this->input->post('name', TRUE),
                'email'   => $this->input->post('email', TRUE),
                'subject' => $this->input->post('subject', TRUE),
                'message' => $this->input->post('message', TRUE)
            );

            // Save to DB
            if ($this->db->insert_message($data)) {
                $this->session->set_flashdata('success', 'Message sent successfully!');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again.');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

}
