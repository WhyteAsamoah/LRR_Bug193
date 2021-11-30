<?php
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');

include "get_mysql_credentials.php";
$con = mysqli_connect("localhost", $mysql_username, $mysql_password, "lrr");

// Check database connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>

<!DOCTYPE html>

<html>

<head>
 
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link href="./font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" /><!-- Font-awesome CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"><!-- Bootstrap CSS -->

  <link href="./css/Styles.css" rel="stylesheet" type="text/css" />

  <script src="./css/jquery.min.js" type="text/javascript"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  
  <script src="./css/jquery.datetimepicker.min.js" type="text/javascript"></script>
  <script src="./js/CustomDropdown.js" type="text/javascript"></script>
 
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      /*Rule to include a block of CSS properties only if a certain condition is true.*/
      /*https://www.w3schools.com/css/css_rwd_mediaqueries.asp*/
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
  <script>
    function updatePass(id) {

      var pass = prompt("Enter your new password : ", "Enter a strong password");

      if (!confirm('Are you sure you want to reset your password?')) {
        return;
      }

      window.location.href = "\Script.php\?action=passchange&uid=" + id + "&pass=" + pass;
    }

    function blockUser(id, status) {
      if (!confirm('Are you sure you want to change user status?')) {
        return;
      }
      window.location.href = "\Script.php\?action=statuschange&uid=" + id + "&status=" + status;
    }
  </script>
</head>

<body>

 
<nav class="navbar navbar-expand-md navbar-dark bg-primary sticky-top">
  <div class="container-fluid"> 
    <a class="navbar-brand" href="~\..\index.php"> <img src="logo.png" width="30" height="30"> LRR </a>

        <?php  
          if (isset($_SESSION["user_fullname"])) 
          { 
        ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
            </button>

        <?php
          }
        ?>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      
        <?php
        if (isset($_SESSION["user_fullname"])) {
          echo "<ul class=\"navbar-nav mr-auto\">";
          echo "<li class=\"nav-item active\">";
          echo "<a class='nav-link' href='~\..\Courses.php'><i class='fa fa-book'></i> Courses</a>";
          echo "</li></ul>"; 
        ?>
        
    <ul class="nav navbar-nav navbar-right">
        <li class="nav-item">
          <a class="nav-link text-light" href="#">Welcome <b> <?php echo $_SESSION['user_fullname']; ?> </b>

          <?php
            $c_date =  date("Y-m-d H:i");
            if (isset($_SESSION['user_student_id']))
              echo "(" . $_SESSION['user_type'] . " - " . $_SESSION['user_student_id'] . ")   ";
            else
              echo "(" . $_SESSION['user_type'] . ")   ";
          ?>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-light"  id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Menu </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a href="#" class="dropdown-item" onclick="updatePass(<?php echo $_SESSION['user_id']; ?>)"><i class="fa fa-user ">Update passwor</i></a>
           
            <?php
                if ($_SESSION['user_type'] == "Lecturer") {
                  echo  "<a class=\"dropdown-item\" href=\"~\..\Admin.php\"><i class=\"fa fa-cog\" >Admin</i> </a>";
                }
              ?>  
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="~\..\logout.php"><i class="fa fa-lock" > Logout</i> </a>
        </li>
      </ul>

      <?php
        }
      ?>

    </div>

  </div>
</nav> 