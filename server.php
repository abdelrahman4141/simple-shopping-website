<?php include 'config.php';?>
<?php

session_start();
$errors = [];
//registeration
if (isset($_POST['register'])) {
    $name =mysqli_real_escape_string($conn,$_POST['name']);
    $email =mysqli_real_escape_string($conn,$_POST['email']);
    $pass =mysqli_real_escape_string($conn,md5($_POST['password']));
    $cpass =mysqli_real_escape_string($conn,md5($_POST['cpassword']));
    
    if(empty($name)) {
        array_push($errors , 'Username is required');
    }
    if(empty($email)) {
        array_push($errors , 'Email is required');
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        array_push($errors , 'Email is Not Valid');
    }
    if(empty($pass) || empty($cpass)) {
        array_push($errors , 'Password is required');
    }
    if ($pass !== $cpass) {
       array_push($errors, 'The two passwords do not match');
    }
    if(count($errors) == 0) {
        $query="SELECT * FROM  users WHERE email='$email' AND name = '$name' "; 
        $result= mysqli_query($conn,$query) or die('Qurey failed');
      
        if (mysqli_num_rows($result) > 0) {
            $message=' User already exist';
         } else {
            mysqli_query($conn,"INSERT INTO users (name,email,password)
            VALUES ('$name','$email','$pass')") or die('Qurey failed');
            $message='registered success';
            header('location: login.php');
         } 
    }
}
//end registeration

//login 
if (isset($_POST['login'])) {
    $email =mysqli_real_escape_string($conn,$_POST['email']);
    $pass =mysqli_real_escape_string($conn,md5($_POST['password']));
  
    
    if(empty($email)) {
        array_push($errors , 'Email is required');
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        array_push($errors , 'Email is Not Valid');
    }
    if(empty($pass)) {
        array_push($errors , 'Password is required');
    }
    if(count($errors) == 0) {
        $query="SELECT * FROM  users WHERE email='$email' AND password = '$pass' "; 
        $result= mysqli_query($conn,$query) or die('Qurey failed');
      
        if (mysqli_num_rows($result) > 0) {
            $row =mysqli_fetch_assoc( $result );
            $_SESSION['user_id'] =$row['id'];
            header('location: index.php');
         } else {
            $message='incorrect password or email';
         } 
    }
}
//end login