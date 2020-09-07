<?php 
    // include('server.php');
    function Createdb(){
        // Initialize Database  
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kamal";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password);

        // Check connection
        if (!$conn) {
            die("Connection Failed: " . mysqli_connect_error());
        }

        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if (mysqli_query($conn, $sql)) {
            $conn = mysqli_connect($servername, $username, $password, $dbname);

            $sql = "CREATE TABLE IF NOT EXISTS users(
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255),
                mobile_number VARCHAR(100) NOT NULL,
                gender CHAR(100) NOT NULL
            )";

            if(mysqli_query($conn, $sql)){
                return $conn;
            }else{
                echo "Error Creating Table...!";
            }
        }
        else{
            echo "Error Creating Database" . mysqli_error($conn);
        }
    }
?>