<?php
$page='Home';
require 'Header.php';
session_start();
?>

<?php
  // if the user has already logged in, then clicking the LRRS icon should not display the login page (i.e., index.php).
if (isset($_SESSION["user_fullname"])) {
    header("Location: Courses.php");
}
?>
<div class="album py-5 bg-white">
        <div class="container bg-white">

          <div class="row align-items-center"> 

            <div class="col-md-6">
              <div class="mb-6 box-shadow">
                 <img class="mx-auto d-block" src="logo.png" />  
                <div class="card-body">
                  <h1 style="font-family: Poppins-Regular;">Lab Report Repository</h1>
                </div>
              </div>
            </div> 

            <div class="col-md-6">
              <div class="card mb-6 box-shadow">
                    <div class="card-header bg-primary" >  
                        <h4 class="list-group-item active" style="font-weight:normal;font-family: Poppins-Regular;"> Sign in </h4>
                    </div> 
                 <div class="card-body">
                   <form  method="post" action="Script.php" name="frm_login">
                    <input type="hidden" name="frm_login" value="true"/>
                    <p>Student ID / Instructor Email</p>
                    <input type="text" name="user" placeholder="Email / Student Number" class="form-control" required="required" />
                    <br>
                    <p>Password</p>
                    <input type="password" class="form-control"  name="password" placeholder="Password" required="required" />
                    <div class="text-center">
                        <br><input type="submit" class="btn btn-primary my-2 btn-lg btn-block" value="Login">
                    </div>
                    <br> <a href="recover_password.php">Reset my password</a>
                    <div class="text-center">
                        <br><span >Don't have an account?</span>
                        <a href="signup.php" >Sign Up</a> 
                    </div> 

            <?php 

            error_reporting(E_ALL);

            if(isset($_SESSION['info_login'])) {
                echo  '<hr><div class="alert alert-danger" role="alert">'.$_SESSION['info_login'].'</div>';
                $_SESSION['info_login']=null;
            }


    // wrong pass
            if(isset($_SESSION['wrong_pass'])) {
                echo  '<hr><div class="alert alert-danger" role="alert">'.$_SESSION['wrong_pass'].'</div>';
                $_SESSION['wrong_pass']=null;
            }


            if(isset($_SESSION['infoChangePassword'])) {
                echo  '<hr><div class="alert alert-danger" role="alert">'.$_SESSION['infoChangePassword'].'</div>';
                $_SESSION['infoChangePassword']=null;
            }
            ?>
 
                </form>
                </div>
              </div>
            </div> 
          </div>
        </div>
      </div>

<?php 
require 'Footer.php'; 
?>

</body>


</html>
