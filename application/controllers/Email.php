<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends CI_Controller {
  // $config = Array{
  //   'protocol' => 'smtp',
  //   'smtp_host' => 'mailhub.eait.uq.edu.au',
  //   'smtp_port' => '24'
  //   'smtp_user' => 'no-reply@example.com',
  //   'smtp_pass' => '12345!',
  //   'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
  //   'mailtype' => 'html',
  //   'starttls' => true,
  //   'newine' => "\r\n" //plaintext 'text' mails or 'html'
  //   'smtp_timeout' => '4', //in seconds
  //   'charset' => 'iso-8859-1',
  //   'wordwrap' => true
  //
  // }





  public function __construct()
    {
        parent::__construct();
        // $mail = new PHPMailer;
        $this->load->library('session');
        $this->load->helper('form');


        $config = Array(
          'protocol' => 'smtp',
          'smtp_host' => 'mailhub.eait.uq.edu.au',
          'smtp_port' => 25,
          // 'smtp_user' => 'no-reply@example.com',
          // 'smtp_pass' => '12345!',
          // 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
          'mailtype' => 'html',
          // 'starttls' => true,
          'newine' => "\r\n", //plaintext 'text' mails or 'html'
          'smtp_timeout' => '4', //in seconds
          // 'charset' => 'UTF-8',
          'charset' => 'iso-8859-1',
          'wordwrap' => true

        );
        $this->load->library('email',$config);





        // $mail = $this->load->library('email')
        // $this->email->Charset = 'UTF-8';
        // $this->email->SMTPDebug = 0;
        // $this->email->IsSMTP();
        // $this->email->Host = 'mailhub.eait.uq.edu.au';
        // $this->email->Port = 25;
        // $this->email->SMTPSecure = 'tls';
        // $this->email->SMTPAuth = false;
        // $this->email->SMTPAutoTLS = true;

    }


  public function verifyEmail(){
    $code = mt_rand(10000, 90000);

    // $this->load->library('email',$config)
    $this->email->from('', 'Your Name');
    // $this->email->from('no-reply@example.com', 'Your Name');
    $this->email->to($_SESSION["email"]);
    $this->email->subject('Email Verification');
    $msg = base_url().'users/email_verify';
    $this->email->message("Please Click on the link to verify: <a href=\"https://infs3202-d49ea811.uqcloud.net/codeigniter/index.php/users/email_verify\" title=\"Email verify\">Email verify link</a>");
    $_SESSION["Verified_code"] = $code;
    // $this->email->send();
    if ($this->email->send()) {
            // echo 'Your Email has successfully been sent.';
            echo '<script language="javascript" type="text/javascript">
                alert("Email has successfully been sent");
                window.location = "'. base_url(). 'users/profile_index/";
              </script>';
        } else {
            show_error($this->email->print_debugger());
        }
      }

  public function ForgetPassword(){
    $email = $this->input->post('email');
    $code = mt_rand(10000, 90000);
    $_SESSION["Verified_code"] = $code;
    $_SESSION["temp_email"] = $email;
    // $this->load->library('email',$config)
    $this->email->from('', 'Your Name');
    // $this->email->from('no-reply@example.com', 'Your Name');
    $this->email->to($email);
    $this->email->subject('Reset Password');
    $this->email->message('Your verified code is '.$code.' ');
    // $this->email->send();
    if ($this->email->send()) {
      echo '<script language="javascript" type="text/javascript">
          alert("Email sent successfully!");
          window.location = "'. base_url(). 'users/reset_index";
        </script>';
        } else {
            show_error($this->email->print_debugger());
        }
      }





}
