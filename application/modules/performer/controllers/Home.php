<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->view('home');
	}
	
	public function request_songs($show_id)
	{
		if(!empty($show_id)){
		$data['show_id']=$show_id;
		$this->load->view('request_songs',$data);
		}
	}
	
	public function songs_list()
	{
		
		$this->load->view('songslist');
	}
	
	public function performer_shows()
	{
		
		$this->load->view('my-show');
	}
	
	public function performer_show_detail()
	{
		
		$this->load->view('show_details_new');
	}
	
	public function forgot_password_page()
	{
		
		$this->load->view('forgot-password');
	}
	public function request_details()
	{
		
		$this->load->view('request_details');
	}
	public function payment_success_page()
	{
		
		$this->load->view('payment-success');
	}
	
	public function mySongList()
	{
		
		$this->load->view('mySongList');
	}
	
	public function AllSongList()
	{
		
		$this->load->view('allSongList');
	}
	
	
	public function create_profile(){
		$this->load->view('create_profile');
	}
	
	
	
	public function check_tiny_url($tiny_url){
		$response = $this->get_web_page("http://socialmention.com/search?q=iphone+apps&f=json&t=microblogs&lang=fr");
		$resArr = array();
		$resArr = json_decode($response);
		echo "<pre>"; print_r($resArr); echo "</pre>";
		$this->load->view('songslist');
	}
	
		public function get_web_page($url) {
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // return web page
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "test", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
		); 

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);

		$content  = curl_exec($ch);

		curl_close($ch);

		return $content;
		}

	
}
