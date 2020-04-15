<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['status'] = "";
        $this->load->model('users_model');
    }

    public function index() {
        // $this->load->view('header');
        $this->load->view('login', $this->data);
        $this->load->view('footer');
    }

    public function signup_index() {
        // $this->load->view('header');
        $this->load->view('signup', $this->data);
        $this->load->view('footer');
    }

    public function map_index() {
        $this->load->view('header');
        $this->load->view('gmap', $this->data);
        $this->load->view('footer');
    }

    public function profile_index() {
        $this->load->view('header');
        $this->load->view('Userprofile', $this->data);
        $this->load->view('footer');
    }

    public function update_index() {
        $this->load->view('header');
        $this->load->view('Update', $this->data);
        $this->load->view('footer');
    }

    public function reset_index() {
        $this->load->view('header');
        $this->load->view('resetPassword', $this->data);
        $this->load->view('footer');
    }




    public function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $remember = $this->input->post('remember');
        // echo '<script>alert("'.$email.'")</script>';
        if ($remember) {
            setcookie("email", $_POST["email"], time() + 60*60*24, "/");
        } else {
            delete_cookie('email');
        }

        if ($this->users_model->authenticate($email, $password)) {
            $_SESSION['email'] = $email;

            redirect(substr(base_url(), 0, -1));
        } else {
            echo '<script>alert("Incorrect Password!")</script>';
            $this->data['status'] = "Your email or password is incorrect!";
            $this->index();
        }

    }

    public function signup() {
        $email = $this->input->post('reemail');
        $password = $this->input->post('repassword');
        $phone = $this->input->post('rephone');
        $name = $this->input->post('rename');

        if ($this->users_model->checkemail($email)){
          $this->users_model->insertuser($email,$password,$phone,$name);

        }else{
          echo '<script>alert("Email already exist")</script>';
          $this->data['status'] = "Your email has already been used";
          $this->signup_index();
        }
    }


    public function update() {
      // $newemail = $this->input->post('newemail');
      $newpassword = $this->input->post('newpassword');
      $renewpassword = $this->input->post('renewpassword');
      // $newphone = $this->input->post('newphone');
      // $newname = $this->input->post('newname');

      if ($newpassword === $renewpassword){
        $email = $_SESSION['email'];
        $newphone = $this->input->post('newphone');
        $newname = $this->input->post('newname');
        $_SESSION['newphone'] = $this->input->post('newphone');
        $_SESSION['newname'] = $this->input->post('newname');
        $this->users_model->updateuser($newpassword,$newphone,$newname,$email);

      }else{
        echo '<script>alert("Password not match")</script>';
        $this->update_index();
      }
    }



    public function uname_check(){
      $uname = $_POST['uname'];

      $count = $this->users_model->uname_check2($uname);
      echo $count;

    }



    public function logout() {
        session_destroy();
        redirect(substr(base_url(), 0, -1));
    }

    public function upload_file(){
      $target_dir = "uploads/";

      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      $filename = $_FILES["fileToUpload"]["name"];

      $filelocation = "http://infs3202-d49ea811.uqcloud.net/codeigniter/uploads/";
      $selection = $_POST['image_upload'];
      $watermark = "FOOD_SHIP";
      $this->users_model->file_upload($target_file,$uploadOk,$imageFileType,$filename,$filelocation,$selection,$watermark);

    }

    public function add_item(){
      $food_name = $this->input->post('food_name');
      $food_type = $this->input->post('food_type');
      $food_price = $this->input->post('food_price');
      $this->users_model->add_item2($food_name,$food_type,$food_price);

      $this->profile_index();

    }



    public function reset_password(){
      $email = $_SESSION["temp_email"];
      $Vcode = $this->input->post('Vcode');
      $newpassword = $this->input->post('newpassword');
      $renewpassword = $this->input->post('renewpassword');

      if ($Vcode === (string)$_SESSION["Verified_code"] && $newpassword === $renewpassword){
          $this->users_model->reset_password2($email,$newpassword);
          echo '<script language="javascript" type="text/javascript">
              alert("Your password has been reset successfully!");
              window.location = "'. base_url(). 'users/index";
            </script>';
        }elseif ($newpassword === $renewpassword) {
          echo '<script language="javascript" type="text/javascript">
              alert("Verified code in correct!!");
              window.location = "'. base_url(). 'users/reset_index";
            </script>';
          // echo $Vcode;
          // echo $_SESSION["Verified_code"];
        }else{
          echo '<script language="javascript" type="text/javascript">
              alert("Please enter same password!!");
              window.location = "'. base_url(). 'users/reset_index";
            </script>';
        }
    }

    public function email_verify() {
        $email = $_SESSION["email"];
        $this->users_model->email_verify2($email);
        $_SESSION["Verified"] = "yes";
        echo '<script language="javascript" type="text/javascript">
            alert("Your email has been verified");
            window.location = "'.substr(base_url(), 0, -1).'";
          </script>';

    }

}
