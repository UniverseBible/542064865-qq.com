<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Search_model');
		$this->load->helper('url_helper');
		$this->load->helper('form');
    }

	public function index()
	{
		$data = array("keyword"=>null, "sort_by"=>null, "food_result"=>null, "count"=>null);

		$keyword = $this->input->get('keyword');

    // echo $keyword;

		if(isset($keyword)){
			$data['keyword'] = $keyword;

			$data['food_result'] = $this->Search_model->food_search($keyword);


		}

		$this->load->view('Search_result',$data);

		//Don't change the following lines, which are used for automarking.
		// error_reporting(0);
		// $file = fopen("/var/www/htdocs/automarking.txt","w");
		// fwrite($file,json_encode($data['count']));
		// foreach($this->Quiz3_model->student_gpa($keyword, $sort_by) as $row){
		// 	fwrite($file,json_encode($row));
		// }
	}

}
