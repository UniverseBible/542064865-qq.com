<?php

class Search_model extends CI_Model{
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
	// public function student_count(){
	// 	// --------------------------------
	// 	// START WRITING YOUR OWN CODE HERE
  //
  //
  //
	// 	// --------------------------------
	// 	// Uncomment the following line when you finished your Query builder
	// 	//return $query->row();
	// }

	public function food_search($keyword){

		// --------------------------------
		
		// START WRITING YOUR OWN CODE HERE
    $query = $this->db->query("SELECT food_name, food_type, food_price from item where food_name Like '%$keyword%' ");
    // echo "$keyword";
    //
    // foreach($query->result() as $row){
    //   echo "111";
		// 	echo $row->food_name;
		// 	echo $row->food_type;
		// 	echo $row->food_price;
    // }

    return $query->result();



		// --------------------------------
		// Uncomment the following line when you finished your Query builder
		//return $query->result();

	}

}
