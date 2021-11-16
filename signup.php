<?php
include 'NoDirectPhpAcess.php';
?>

<?php
include 'Header.php';
?>

<div class="album py-5 bg-white">
    <div class="container bg-white">
 
    <div class="col-md-6 panel panel-primary" >

        <br>

        <h4 class="list-group-item active"> Please fill in each field below </h4>
        <div class="list-group-item">

            <div class="panel panel-primary">

                <form method="post" action="Script.php">
                    <input type="hidden" name="frm_signup_2" value="true" />
                    Full Name
                    <input type="text" name="fullname" placeholder="Your full name" class="form-control" value="<?php echo $_SESSION['user_fullname']; ?>" required="required" />

                    Student ID
                    <input type="text" name="user_student_id" placeholder="Entre your student ID" class="form-control" value="<?php echo $_SESSION['user_student_id']; ?>" required="required">

                    Email
                    <input type="text" name="email" placeholder="Email" class="form-control" value="<?php echo $_SESSION['user_email']; ?>" required="required" />

                    Password (<i>must include uppercase and lowercase letters, digits and special characters</i>)
                    <input type="password" class="form-control" name="password" placeholder="Enter password" required="required" />

                    Confirm Password
                    <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm password" required="required" />
                    <br>
                    <input type="submit" class="btn btn-primary" value="Sign up">
                    <?php
                    error_reporting(E_ALL);
                    if (isset($_SESSION['info_signup2'])) {
                        echo  '<hr><div class="alert alert-danger" role="alert">' . $_SESSION['info_signup2'] . '</div>';
                        $_SESSION['info_signup2'] = null;
                    }
                    ?>
                </form>

            </div>
        </div>
    </div>
</div>
</div>

