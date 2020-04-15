<?php
class Users_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function authenticate($email, $password) {
		// $query = $this->db->get_where("users", array('email' => $email));
		$sql = "SELECT * FROM user WHERE Email = ? ";
		$query = $this->db->query($sql,array($email));

		$row = $query->row_array();

		$_SESSION['username'] = $row['Name'];

		if (isset($row)) {
			$_SESSION["email"] = $_POST["email"];
			$_SESSION["username"] = $row['Name'];
			$_SESSION["phone"] = $row['Phone'];
			$_SESSION["password"] = $row['Name'];
			$_SESSION["imgelocation"] = $row['Location'];
			$_SESSION["control_mode"] = $row['control_mode'];
			$_SESSION["Verified"] = $row['Verified'];
			return ($password == $row['Password']);
		} else {
			return FALSE;
		}

	}


	public function checkemail($email){
		// $query = $this->db->query("SELECT * FROM user WHERE Email = '" . $email . "'");
		$query = "SELECT * FROM user WHERE email = ? ";

    $result = $this->db->query($query,array($email));
		$row = $result->row_array();
        /*while($row = mysqli_fetch_array($result)) {
            print "Name: {$row['username']} has ID: {$row['userId']}";
        }*/
    if (isset($row)) {
            // echo '<script>alert("Email already exist")</script>';

						return FALSE;
	 }else{
		 return TRUE;
	 }
  }

	public function insertuser($reemail,$repassword,$rephone,$rename){
		// $query = $this->db->query("SELECT * FROM user WHERE Email = '" . $email . "'");
		$query2 = "INSERT INTO user (Email, Password, Name, Phone, Status) VALUES (?,?,?,?,'registered')";
            if ($this->db->query($query2,array($reemail,$repassword,$rename,$rephone)) === TRUE) {
              echo '<script language="javascript" type="text/javascript">
                  alert("Register succeed");
                  window.location = "'. base_url(). 'users/";
                </script>';
              // echo '<script>alert("Register succeed"); window.location = Signin.php</script>';
              // header("Location: Signin.php");

              // header("Location: Signin.php");

            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
              }
  }

	public function updateuser($newpassword,$newphone,$newname,$email){
		// $query = $this->db->query("SELECT * FROM user WHERE Email = '" . $email . "'");
		$query = "UPDATE user SET password=?, Name = ?, Phone = ? WHERE Email = ?";
    // $result = $this->db->query($query);
    if ($this->db->query($query,array($newpassword,$newname,$newphone,$email)) === TRUE) {
          echo '<script language="javascript" type="text/javascript">
              alert("Update succeed");
              window.location = "'. base_url(). 'users/profile_index/";
            </script>';
    } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
          }
  }

	public function uname_check2($uname){
		$query = "select count(*) as cntUser from user where Email='".$uname."'";

		$result = $this->db->query($query);
    // $result = mysqli_query($this->db,$query);
		$row = $result->row_array();

		$count = $row['cntUser'];

		// Return total rows found
		echo $count;
	}

	public function add_item2($food_name,$food_type,$food_price){
			$query2 = "INSERT INTO item (food_name, food_type, food_price, food_link) VALUES (?,?,?,'')";

			if ($this->db->query($query2,array($food_name,$food_type,$food_price)) === TRUE) {
	          echo '<script language="javascript" type="text/javascript">
	              alert("Add item succeed");
	              window.location = "'. base_url(). 'users/profile_index";
	            </script>';
	    } else {
	          echo "Error: " . $sql . "<br>" . $conn->error;
	          }


	}


	public function file_upload($target_file,$uploadOk,$imageFileType,$filename,$filelocation,$selection,$watermark){
		$pkey = $_SESSION["email"];
		$temp = explode(".", $_FILES["fileToUpload"]["name"]);
		$newfilename = round(microtime(true)) . '.' . end($temp);
		$filelocation = $filelocation.$newfilename;
		$query = "UPDATE user SET Location='$filelocation' WHERE Email='$pkey'";
		$src = "uploads/".$newfilename;

		// Check if image file is a actual image or fake image
		echo $selection;
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        echo "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        echo "File is not an image.";
		        $uploadOk = 0;
		    }
		}
		// Check if file already exists
		if (file_exists($target_file)) {
		    echo "Sorry, file already exists.";
		    $uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 1000000) {
		    echo "Sorry, your file is too large.";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "uploads/".$newfilename)) {
		        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";


						echo $selection;
						if ($selection == 'text_watermark'){
								// echo '<script language="javascript" type="text/javascript">
								// 		alert("enter selection");
								// 		window.location = "'. base_url(). 'users/profile_index/";
								// 	</script>';
								list($width, $height) = getimagesize($src);
								$image_color = imagecreatetruecolor($width, $height);
								$image = imagecreatefromjpeg($src);
								imagecopyresampled($image_color, $image, 0, 0, 0, 0, $width, $height, $width, $height);
								$txtcolor = imagecolorallocate($image_color, 255, 255, 255);
								// $font = 'https://infs3202-d49ea811.uqcloud.net/codeigniter/system/fonts/texb.ttf';
								$font = 'system/fonts/texb.ttf';

								$font_size = 50;
								imagettftext($image_color, $font_size, 0, 50, 150, $txtcolor, $font, $watermark);
								// if ($save<>'') {
								// 	imagejpeg ($image_color, $save, 100);
								// } else {
								// 	header('Content-Type: image/jpeg');
								// 	imagejpeg($image_color, null, 100);
								// }
								imagejpeg ($image_color, $src, 100);
								imagedestroy($image);
								imagedestroy($image_color);
								echo "endendend";
							}




		        // $result = $db->query($query);
		        if ($this->db->query($query) === TRUE) {
		          $_SESSION["imgelocation"] = $filelocation;

		          echo '<script language="javascript" type="text/javascript">
		              alert("Imge update succeed");
		              window.location = "'. base_url(). 'users/profile_index/";
		            </script>';
		        } else {
		          echo "Error: " . $sql . "<br>" . $conn->error;
		          }


		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
}
	}


	public function reset_password2($email,$password){
		$query = "UPDATE user SET password='$password' WHERE Email = ?";

		$result = $this->db->query($query,array($email));
    // $result = mysqli_query($this->db,$query);
		// $row = $result->row_array();

	}

	public function email_verify2($email) {
			$query = "UPDATE user SET Verified='yes' WHERE Email = ?";
			$result = $this->db->query($query,array($email));

	}









}
