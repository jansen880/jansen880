<!-- SIGNUP PHP FILE -->

<?php 

if(isset($_POST['fname']) && 
   isset($_POST['uname']) &&
   isset($_POST['stuid']) &&
   isset($_POST['course']) &&
   isset($_POST['year']) &&
   isset($_POST['bdate']) &&
   isset($_POST['address']) &&
   isset($_POST['cno']) &&
   isset($_POST['ecp']) &&
   isset($_POST['ecno']) &&
   isset($_POST['pass'])){

    include "../db_conn.php";

    $fname = $_POST['fname'];
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $stuid=$_POST['stuid'];
	 $bdate=$_POST['bdate'];
	 $cno=$_POST['cno'];
	 $address=$_POST['address'];
	 $course=$_POST['course'];
	 $year=$_POST['year'];
	 $ecp=$_POST['ecp'];
	 $ecno=$_POST['ecno'];
    

    $data = "fname=".$fname."&uname=".$uname."&stuid=".$stuid."&course=".$course."&year=".$year."&bdate=".$bdate."&address=".$address."&cno=".$cno."&ecp=".$ecp."&ecno=".$ecno;
    
    if (empty($fname)) {
    	$em = "Full name is required";
    	header("Location: ../index.php?error=$em&$data");
	    exit;
    }else if(empty($uname)){
    	$em = "User name is required";
    	header("Location: ../index.php?error=$em&$data");
	    exit;
    
    }else if(empty($stuid)){
      $em = "School ID is required";
      header("Location: ../edit.php?error=$em&$data");
	   exit;
    }else if(empty($course)){
      $em = " Course is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    }else if(empty($year)){
      $em = "Year is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    }else if(empty($bdate)){
      $em = "Birth Date is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    
    }else if(empty($address)){
      $em = "Address is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    }else if(empty($cno)){
      $em = "COntact No. is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    }else if(empty($ecp)){
      $em = "Emergency Contact Person is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    }else if(empty($ecno)){
      $em = "Emergency Contact No. is required";
      header("Location: ../edit.php?error=$em&$data");
      exit;
    }else if(empty($pass)){
    	$em = "Password is required";
    	header("Location: ../index.php?error=$em&$data");
	   exit;
    }else {
      // hashing the password
      $pass = password_hash($pass, PASSWORD_DEFAULT);

      if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
        // Profile picture upload
        $img_name = $_FILES['pp']['name'];
        $tmp_name = $_FILES['pp']['tmp_name'];
        $error = $_FILES['pp']['error'];

        if ($error === 0) {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_to_lc = strtolower($img_ex);
            $allowed_exs = array('jpg', 'jpeg', 'png');

            if (in_array($img_ex_to_lc, $allowed_exs)) {
                $new_img_name = uniqid($uname, true).'.'.$img_ex_to_lc;
                $img_upload_path = '../upload/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

                // Insert with profile picture
                $sql = "INSERT INTO `user_tbl`(`stuid`, `fname`, `username`, `password`, `pp`, `course`, `year`, `bdate`, `address`, `cno`, `ecp`, `ecno`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);

                if ($stmt->execute([$stuid, $fname, $uname, $pass, $new_img_name, $course, $year, $bdate, $address, $cno, $ecp, $ecno])) {
                    header("Location: ../index.php?success=Your account has been created successfully");
                    exit;
                } else {
                    $em = "Database error: " . $stmt->errorInfo()[2];
                    header("Location: ../index.php?error=$em&$data");
                    exit;
                }
            } else {
                $em = "You can't upload files of this type";
                header("Location: ../index.php?error=$em&$data");
                exit;
            }
        } else {
            $em = "Unknown error occurred!";
            header("Location: ../index.php?error=$em&$data");
            exit;
        }
      } else {
        // Insert without profile picture
        $sql = "INSERT INTO `user_tbl`(`stuid`, `fname`, `username`, `password`, `course`, `year`, `bdate`, `address`, `cno`, `ecp`, `ecno`) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$stuid, $fname, $uname, $pass, $course, $year, $bdate, $address, $cno, $ecp, $ecno])) {
            header("Location: ../index.php?success=Your account has been created successfully");
            exit;
        } else {
            $em = "Database error: " . $stmt->errorInfo()[2];
            header("Location: ../index.php?error=$em&$data");
            exit;
        }
      }

    }


}
else {
	header("Location: ../index.php?error=");
	exit;
}
