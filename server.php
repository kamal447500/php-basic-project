<?php 
session_start();

// initialize variables
$username = "";
$email = "";
$errors = array();
$name = "";
$salary = "";
$id = 0;
$update = false;

//connect to db

$db = mysqli_connect('localhost','root','','practise') or die("can't connect db");

//Register users
if (isset($_POST['reg_user'])) {
	//receives all inputs from the form
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	//form validation

	if(empty($username)) {array_push($errors, "Username is required");}
	if(empty($email)) {array_push($errors, "Email is required");}
	if(empty($password_1)) {array_push($errors, "password is required");}
	if($password_1 != $password_2) {array_push($errors, "passwords dont match");}

	//check db for existing user with same username and email

	$user_check_query = "SELECT * FROM employees WHERE username = '$username' OR email = '$email' LIMIT 1";

	$result = mysqli_query($db, $user_check_query);
	$user = mysqli_fetch_assoc($result);

	if ($user) {
		if ($user['username'] === $username) {
		  array_push($errors, "Username is already exit");
		}
		if ($user['email'] === $email) {
			array_push($errors, "email is already Registered");
		}
	}

	//register the user if no error

	if(count($errors) == 0) {
		$password = md5($password_1); //this will encrypt the password

		$query = "INSERT INTO users (username, email, password)
				  VALUES ('$username', '$email', '$password')";
		mysqli_query($db,$query);
		$_SESSION['username'] = $username;
		$_SESSION['success'] = "you are now logged in";
		header('location : index.php');
	}
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}	

//Details of Employee

if (isset($_POST['save'])) {
	$name = $_POST['name'];
	$salary = $_POST['salary'];

	mysqli_query($db, "INSERT INTO employees (name, salary) VALUES ('$name', '$salary')"); 
	$_SESSION['message'] = "Employee Details saved"; 
	header('location: index.php');
}

//Update Details

if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$salary = $_POST['salary'];

	mysqli_query($db, "UPDATE employees SET name='$name', salary='$salary' WHERE id=$id");
	$_SESSION['message'] = "salary updated!"; 
	header('location: index.php');
}

//Delete details

if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM employees WHERE id=$id");
	$_SESSION['message'] = "Employee Details deleted!"; 
	header('location: index.php');
}
?>