<?php
include 'NoDirectPhpAcess.php';
?>

<?php

$page = 'Courses';
include 'Header.php';

echo "<main role='main' class='container bg-white' style='margin-top: 20px;'>";

$user_d = $_SESSION['user_id'];

if ($_SESSION['user_type'] == "Lecturer" || $_SESSION['user_type'] == "TA") {
    ?>


        <!--    FOR LECTURER-->
 
        <div class="row" style="width:80%;margin:auto; text-align:left;">

                <script>
                function extend_deadline(id) {

                    var dropstudents = $("#dropstudents").html();

                    try {

                        $('<form id="frm" method="get" action="Script.php">\n\
                            <input type="hidden" name="extenddeadline" value="true" >\n\
                            <input type="hidden" name="id" value="' + id + '" > \n\
                            New Date/Time <br><input type="date" name="date" required=""> <input type="time" name="time" required=""> \n\
                            \n\
                            <br><input type="radio" value="1" name="type" required=""> Extend for All<hr>  \n\
                            <input type="radio" value="2" name="type" required=""> Extend for these Individual Students \n\
                            ' + dropstudents + '   \n\
                            </form>').dialog({
                                modal: true,
                                title: 'Extend Deadline',
                                buttons: {
                                    'Submit': function() {
                                        $('#frm').submit();

                                        $(this).dialog('close');
                                    },
                                    'X': function() {

                                        $(this).dialog('close');
                                    }

                                }
                            });

                        } catch (e) {
                            alert(e);
                        }
                    }
                </script>

                <?php
                if (!empty($_GET["course"])) {
                    $course_url = $_GET["course"];
                    $result = mysqli_query($con, "SELECT `Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`,"
                        . " `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`  "
                        . " , users_table.Full_Name  FROM `courses_table` INNER JOIN users_table"
                        . " ON users_table.User_ID=courses_table.Lecturer_User_ID where URL='$course_url' ");

                    if (mysqli_num_rows($result) == 0) {
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $name = $row['Course_Name'];
                            $code = $row['Course_Code'];
                            $faculty = $row['Faculty'];
                            $lecturer = $row['Full_Name'];
                            $academic = $row['Academic_Year'];
                            $url = $row['URL'];
                            $id = $row['Course_ID'];
                            $course_id = $row['Course_ID'];
                            echo    "  

                            <div class='alert> <a href='~\..\Courses.php?course=$url'>   <div class='panel'>
                            ($code) - $name 
                            <br> <span style='font-size:8pt'>Faculty: $faculty | Year: $academic | Lecturer: $lecturer </span>
                            </div></a>
                            <hr></div></div> <div class='row' style='width:80%;margin:auto; text-align:left;'>
                            ";

                            echo "<div class='col-md-5'>";
                        }

                // ------------------------------Editing Lab Assignment by Lecturer ------------------------------------

                        if ($_GET['act'] == "edit") {
                            $getid = $_GET["cid"];
                            $result1 = mysqli_query($con, "SELECT * from lab_reports_table WHERE Lab_Report_ID = '$getid'");

                            while ($row1 = mysqli_fetch_assoc($result1)) {
                                $Deadline = $row1['Deadline'];
                                $_SESSION['Date'] = trim(strstr($Deadline, ' ', true));
                                $_SESSION['Time'] = trim(strstr($Deadline, ' '));
                                $_SESSION['Instructions'] = $row1['Instructions'];
                                $_SESSION['Title'] = $row1['Title'];
                                $_SESSION['Marks'] = $row1['Marks'];
                                $_SESSION['Type'] = $row1['Type'];
                            }

                            if (isset($_POST['frm_uploadlab'])) {
                        $deadlinedate = trim($_POST["deadlinedate"]); // remove spaces
                        $deadlinetime = trim($_POST["deadlinetime"]); // remove spaces
                        $instructions = $_POST["instructions"];
                        $title = $_POST["title"];
                        $marks = $_POST["marks"];
                        $type  = $_POST["type"];
                        $Deadline = $deadlinedate . " " . $deadlinetime;
                        $date =  date("Y-m-d H:i");

                        $sql = "UPDATE `lab_reports_table` SET `Deadline` = ('" . $Deadline . "'), `Instructions` = ('" . $instructions . "'), `Title` = ('" . $title . "'), `Marks` = ('" . $marks . "'), `Type` = ('" . $type . "') WHERE `lab_reports_table`.`Lab_Report_ID` = '$getid'";
                            if ($con->query($sql) === TRUE) {
                                $_SESSION["info_Updated"] = "Assignment information updated successfully.";
                            } else {
                            // echo "Error: " . $sql . "<br>" . $con->error;
                                echo "Serious error happened whiling updating assignment information.";
                            }
                        }

                        if ($_SESSION['user_type'] == "Lecturer") {
                            $Date = $_SESSION['Date'];
                            $Time = $_SESSION['Time'];
                            $Instructions = $_SESSION['Instructions'];
                            $Title = $_SESSION['Title'];
                            $Marks = $_SESSION['Marks'];
                            $Type = $_SESSION['Type'];

                            echo "  <h3><a href='Courses.php?course=" . $url . "'> Editing Lab Assignment </a></h3>";
                            ?>
                            <form method='post' enctype='multipart/form-data' action=''>
                                <input type='hidden' name='frm_uploadlab' value='true' required='' />
                                <input type='hidden' name='course_id' value='<?php echo "$id" ?>' required='' />
                                <input type='hidden' name='url' value='<?php echo ".$course_url." ?>' required='' />

                                Dealine Date/Time
                                <div class='row'>
                                    <div class='col-md-7'><input type='date' id='date' name='deadlinedate' placeholder='' class='form-control' required='' value="<?php echo isset($_GET['act']) && $_GET['act'] == "edit" ? $Date : ""; ?>"> </div>
                                    <div class='col-md-5'> <input type='text' id='time' class='form-control' name='deadlinetime' value="<?php echo isset($_GET['act']) && $_GET['act'] == "edit" ? $Time : ""; ?>"> </div>
                                </div>

                                Title
                                <input type='text' name='title' placeholder='Ttle' class='form-control' required='' value="<?php echo isset($_GET['act']) && $_GET['act'] == "edit" ? $Title : ""; ?>">
                                Instructions
                                <textarea name='instructions' placeholder='Assignment Instructions' class='form-control' required=''><?php echo isset($_GET['act']) && $_GET['act'] == 'edit' ? $Instructions : ''; ?></textarea>
                                Marks
                                <input type='text' name='marks' placeholder='Marks' class='form-control' required='' value="<?php echo isset($_GET['act']) && $_GET['act'] == "edit" ? $Marks : ""; ?>">
                                Attachment 1
                                <input type='file' name='attachment1' placeholder='Attachment 1' class='form-control'>

                                Attachment 2
                                <input type='file' name='attachment2' placeholder='Attachment 1' class='form-control'>

                                Attachment 3
                                <input type='file' name='attachment3' placeholder='Attachment 1' class='form-control'>

                                Attachment 4
                                <input type='file' name='attachment4' placeholder='Attachment 4' class='form-control'>
                                <br>

                                <?php
                                if ($Type == "Individual") {
                                    echo "Submission Type  <input type='radio' name='type' value='Individual' checked /> Individual  <input type='radio' name='type' value='Group' /> Group";
                                } else {
                                    echo "Submission Type  <input type='radio' name='type' value='Individual' /> Individual  <input type='radio' name='type' value='Group' checked> Group";
                                }
                                ?>

                                <hr>
                                <input type='submit' class='btn btn-primary' value='Post Lab Assignment'><br>
                            </form><br><br><br><br>
                            <?php
                        }
                    } else {

                    // ------------------------------Posting New Lab Assignment------------------------------------

                    // Mysql to split 1 string into 2 similar to the tsrstr in php
                    // SELECT SUBSTRING_INDEX(Deadline, ' ', 1) as Date, SUBSTRING_INDEX(Deadline, ' ', -1) as Time from lab_reports_table

                        if ($_SESSION['user_type'] == "Lecturer") {

                            ?>

                            <h3> Post new Lab Assignment </a></h3>

                            <form method='post' enctype='multipart/form-data' action='Script.php'>
                                <?php
                                $_SESSION['url'] = $url;
                                ?>
                                <input type='hidden' name='frm_uploadlab' value='true' required='' />
                                <input type='hidden' name='course_id' value='<?php echo "$id" ?>' required='' />
                                <input type='hidden' name='url' value='<?php echo ".$course_url." ?>' required='' />

                                Dealine Date/Time
                                <div class='row'>
                                    <div class='col-md-7'><input type='date' id='date' name='deadlinedate' placeholder='' class='form-control' required='' value=""> </div>
                                    <div class='col-md-5'> <input type='time' class='form-control' name='deadlinetime' value=""> </div>
                                </div>

                                Title
                                <input type='text' name='title' placeholder='Ttle' class='form-control' required='' value="">
                                Instructions
                                <textarea name='instructions' placeholder='Assignment Instructions' class='form-control' required='' value=""></textarea>
                                Marks
                                <input type='text' name='marks' placeholder='Marks' class='form-control' required='' value="">
                                Attachment 1
                                <input type='file' name='attachment1' placeholder='Attachment 1' class='form-control'>

                                Attachment 2
                                <input type='file' name='attachment2' placeholder='Attachment 1' class='form-control'>

                                Attachment 3
                                <input type='file' name='attachment3' placeholder='Attachment 1' class='form-control'>

                                Attachment 4
                                <input type='file' name='attachment4' placeholder='Attachment 4' class='form-control'>
                                <br>
                                Submission Type <input type='radio' name='type' value='Individual' required=''> Individual

                                <input type='radio' name='type' value='Group' required=''> Group
                                <hr>
                                <input type='submit' class='btn btn-primary' value='Post Lab Assignment'><br>
                            </form><br><br><br><br>
                            <?php
                        }
                    }
                }
                echo "</div>";

                echo "<div class='col-md-7'>  <h3> Lab Report Assignment list </h3>";

                error_reporting(0);
                if (isset($_SESSION["info_Updated"])) {
                    echo '<hr><div class="alert alert-info" role="alert">' . $_SESSION['info_Updated'] . '</div>';
                    $_SESSION['info_Updated'] = null;
                }
                if (isset($_SESSION['info_courses'])) {
                    echo '<hr><div class="alert alert-info" role="alert">' . $_SESSION['info_courses'] . '</div>';
                    $_SESSION['info_courses'] = null;
                }
                if (isset($_SESSION['info_courses'])) {
                    echo '<hr><div class="alert alert-info" role="alert">' . $_SESSION['info_courses'] . '</div>';
                    $_SESSION['info_courses'] = null;
                }

                $result = mysqli_query($con, " SELECT `Lab_Report_ID`,Type,Marks, `Course_ID`, `Posted_Date`, `Deadline`, `Instructions`, `Title`, `Attachment_link_1`, `Attachment_link_2`, `Attachment_link_3`, "
                    . "`Attachment_link_4` FROM `lab_reports_table` WHERE Course_ID=$id ORDER by Lab_Report_ID DESC");

                if ($_SESSION['user_type'] == "TA") {
                    echo "<b style='color:gray'>*Only Lecturer can post a new lab report assignment</b><br>";
                }
                if (mysqli_num_rows($result) == 0) {
                    echo "No assignments posted so far.";
                } else {
                    echo "<div class='row'><div class='col-sm-6'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $marks = $row['Marks'];
                        $title = $row['Title'];
                        $ins = $row['Instructions'];
                        $posted = $row['Posted_Date'];
                        $deadline = $row['Deadline'];
                        $att1 = $row['Attachment_link_1'];
                        $att2 = $row['Attachment_link_2'];
                        $att3 = $row['Attachment_link_3'];
                        $att4 = $row['Attachment_link_4'];
                        $id = $row['Lab_Report_ID'];
                        $cours_id = $row['Course_ID'];
                        $as_type = $row['Type'];
                        $full_link = "<a href='~\..\Lab_Report_Assignments\\$att1'>$att1</a>";

                        if ($att2 != "") {
                            $full_link = $full_link . " <a class='card-link' href='~\..\Lab_Report_Assignments\\$att2'>$att2</a>";
                        }
                        if ($att3 != "") {
                            $full_link = $full_link . " <a class='card-link' href='~\..\Lab_Report_Assignments\\$att3'>$att3</a>";
                        }

                        if ($att4 != "") {
                            $full_link = $full_link . " <a class='card-link' href='~\..\Lab_Report_Assignments\\$att4'>$att4</a>";
                        }

                        $resultx1 = mysqli_query($con, "Select Count(*) as cnt from lab_report_submissions where lab_report_submissions.Lab_Report_ID=$id");
                        while ($row = mysqli_fetch_assoc($resultx1)) {
                            $count_subs = $row['cnt'];
                        }

                        $resultx2 = mysqli_query($con, "Select COUNT(*) as cnt from lab_report_submissions where lab_report_submissions.Lab_Report_ID=$id and Marks is not null");
                        if (mysqli_num_rows($resultx2) == 0) {
                            $count_marked = 0;
                        } else {
                            while ($row = mysqli_fetch_assoc($resultx2)) {
                                $count_marked = $row['cnt'];
                            }
                        }

                        $header = "Courses > " . $name . "($code) > Assignments > " . $title;
 

                        echo"<div class='card' style='width: 18rem;'> 
                              <div class='card-body'>
                                <h5 class='card-title'>$title ($as_type)</h5>
                                <p class='card-text'>$ins</p>
                              </div>

                              <ul class='list-group list-group-flush'>
                                <li class='list-group-item'>Posted : $posted</li>
                                <li class='list-group-item'>Deadline : <b> $deadline </b></li>

                                <li class='list-group-item'>
                                <div class='dropdown show'>
                                  <a class='btn btn-secondary dropdown-toggle'  id='dropdownMenuLink' data-toggle='dropdown'  >
                                    Action
                                  </a>
                                  <div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
                                    <a class='dropdown-item' href='Courses.php?course= $url &act=edit&cid= $id' >Edit</button>
                                    <a class='dropdown-item' href='~\..\Submissions.php?id=$id&header=$header&total=$a' onclick='' >View action</a>
                                    <a class='dropdown-item' href='#' onclick='extend_deadline($id)'>Extend Deadline</a>
                                  </div>
                                </div>
                                </li>  
                              </ul>
                              <div class='card-body'>
                                $full_link
                              </div>
                            </div> ";
                    }
                }
                echo "</div></div></div>";

                $resultx1 = mysqli_query($con, "SELECT course_students_table.Student_ID,users_table.Full_Name FROM 
                    `course_students_table`
                    INNER JOIN users_table on users_table.Student_ID=course_students_table.Student_ID
                    WHERE Course_ID=$course_id");

                echo "<span id='dropstudents' style='display:none;'> <select name='stdid'>";
                while ($row = mysqli_fetch_assoc($resultx1)) {
                    $stdid = $row['Student_ID'];
                    $stdname = $row['Full_Name'];

                    echo "<option value='$stdid'> $stdname($stdid) </option> ";
                }
                echo "</select><br>Reason <input type='text' name='reason'>"
                . "<input type='hidden' name='url' value='$course_url'>"
                . " </span>";

                return;
            }

            ?>

            <div class="col-md-8">

                <?php
                $user_name = $_SESSION['user_fullname'];

                echo    "  <div class='alert' style='margin-left:20px;border-bottom:2px solid #1D91EF;'> <a href='~\..\Courses.php?course=$url'>
                Course Portal  > $user_name (Lecturer) > Course Listing
                <br> <span style='font-size:8pt'> </span>
                </a></div>
                ";

                $result = mysqli_query($con, "SELECT `Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`, "
                    . "`Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`   , users_table.Full_Name  FROM `courses_table` INNER JOIN users_table ON users_table.User_ID=courses_table.Lecturer_User_ID where courses_table.Lecturer_User_ID=$user_d");

                if ($_SESSION['user_type'] == "TA") {
                    $result = mysqli_query($con, "SELECT course_ta.Course_ID, `Course_Name`, 
                      `Academic_Year`, `Faculty`, `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`   FROM `courses_table` 
                      INNER JOIN 
                      course_ta ON course_ta.Course_ID=courses_table.Course_ID where course_ta.TA=$user_d");
                }
            // $result = mysqli_query($con,"SELECT `Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`, `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`   , users_table.Full_Name  FROM `courses_table` INNER JOIN users_table ON users_table.User_ID=courses_table.Lecturer_User_ID");

                if (mysqli_num_rows($result) == 0) {
                } else {
                     
                        echo "<div class='list-group'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['Course_ID'];
                        $name = $row['Course_Name'];
                        $code = $row['Course_Code'];
                        $faculty = $row['Faculty'];
                        $lecturer = $row['Full_Name'];
                        $academic = $row['Academic_Year'];
                        $url = $row['URL'];

                        $resultTA = mysqli_query($con, "SELECT `Course_ID`, `TA`,users_table.Full_Name as TA_NAME FROM `course_ta`
                            INNER JOIN users_table on users_table.User_ID=course_ta.TA
                            where course_ta.Course_ID=$id");

                        $ta = "";
                        while ($rowTA = mysqli_fetch_assoc($resultTA)) {
                            $ta = $ta . "  - " . $rowTA['TA_NAME'];
                        }

                        echo "    
                        <a href='~\..\Courses.php?course=$url' class='list-group-item list-group-item-action flex-column align-items-start'>
                            <div class='d-flex w-100 justify-content-between'>
                              <h5 class='mb-1'>($code) - $name </h5> 
                            </div>
                            <p class='mb-1'>Faculty : $faculty   Year :  $academic   Lecturer  :$lecturer    TA:$ta </p> 
                        </a> ";
                    }

                } ?>
            </div>
            </div>
            <div class="col-md-4">
                <br>
                <b> Course Joining Requests </b>

                <?php
                $lecturer_id = $_SESSION['user_id'];
                $result = mysqli_query($con, "SELECT  course_students_table.ID,users_table.Full_Name,  courses_table.Course_ID, `Course_Name`, `Academic_Year`, `Faculty`, `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members` FROM `courses_table` 
                    INNER JOIN course_students_table on  course_students_table.Course_ID=courses_table.Course_ID
                    INNER JOIN users_table on users_table.Student_ID=course_students_table.Student_ID
                    WHERE  Lecturer_User_ID=$lecturer_id and course_students_table.Status='Pending'");

                if (mysqli_num_rows($result) == 0) {

                    echo "<br>  <i class='fa fa-info-circle'></i> No Course joining request so far for all your courses <hr>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['ID'];

                        $name = $row['Course_Name'];
                        $code = $row['Course_Code'];
                        $faculty = $row['Faculty'];
                        $std_name = $row['Full_Name'];
                        $academic = $row['Academic_Year'];

                        echo "<div class='btn btn-default'>
                        $std_name is Requesting to join <br> [($code) - $name ] &nbsp;&nbsp;&nbsp;&nbsp; <br><a href='~\..\Script.php?AcceptStudent=y&id=$id&rs=yes' class='btn-sm btn-success' onclick=return confirm(\"are you sure to join this course?\")' > Accept </a>
                        &nbsp;&nbsp;<a href='~\..\Script.php?AcceptStudent=y&id=$id&rs=no' class='btn-sm btn-danger' onclick=return confirm(\"are you sure to join this course?\")' > Decline </a>                     
                        </div>";
                    }
                }
                ?>

                <?php
                if ($_SESSION['user_type'] == "TA") {
                    echo "<center>Only Lecturers can Post new Lab report Assignments</center>";
                }
                if ($_SESSION['user_type'] == "Lecturer") { ?>

                    <b>Create new Course Portal </b>

                    <form method="post" action="Script.php">
                        <input type="hidden" name="frm_createCourse" value="true" required="" />
                        <input type="hidden" name="l" value="l" required="" />
                        Course Name
                        <input type="text" name="name" placeholder="Course Name" class="form-control" required="">

                        Course Code
                        <input type="text" name="code" placeholder="Course Code" class="form-control" required="">

                        URL (Leave blank to use Course Code & Year)
                        <input type="text" name="url" placeholder="Choose Custom URL " class="form-control">

                        Academic Year
                        <input type="text" name="academic" placeholder="Academic Year" class="form-control" required="">

                        Faculty <br>
                        <input type="text" name="faculty" placeholder="Faculty" class="form-control" required="">

                        <input type="hidden" name="lecturer" value="<?php echo $_SESSION['user_id'];  ?>">

                        Verify Joining Students
                        <input type="radio" name="verify" value="1"> Yes
                        <input type="radio" name="verify" value="0" checked=""> No

                        <br>
                        <input type="submit" class="btn btn-primary" value="Create Portal"><br>

                    </form>

                <?php }  ?>

            </div>

            <!--   END LECTURER   -->

            <?php
        }

        if ($_SESSION['user_type'] == "Student") {
            ?>

            <!--STUDENT CODE-->
            <div class="row">
                <div class='col-md-1'></div> 
                <div class="col-md-6">
                    <br> Course Portal > Students <br>
                    <?php

                    error_reporting(0);
                    if (isset($_SESSION['info_Courses_student'])) {
                        echo '<hr><p class="alert alert-success" role="alert">' . $_SESSION['info_Courses_student'] . '</p>';
                        $_SESSION['info_Courses_student'] = null;
                    }
                    ?>
                    <br><br>
                </div>
                <div class="col-md-5"></div>
            </div> 

            <div class="p-4 my-4 bg-purple rounded shadow-sm"> 
     
                <div class="col-md-12">
                <?php
                    echo " 

                    <form method='get' action='Courses.php'>
 
                    <div class='form-row'>
                        <div class='form-group col-md-6'>
                            <input type='text' class='form-control' name='search'  aria-label='Enter Course Code' placeholder='Enter Course Code'>
                        </div>
                        <div class='form-group col-md-4'> 
                            <select name='faculty' class='form-control' aria-label='Search by'><option selected value='' >Search by Faculty</option>";
                            $result = mysqli_query($con, "SELECT   DISTINCT(Faculty) as Faculty FROM `courses_table`");
                            if (mysqli_num_rows($result) == 0) {
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $fname = $row['Faculty'];

                                    echo "<option value='$fname'>Faculty $fname </option>";
                                }
                            }

                            echo "</select>
                        </div>


                        <div class='form-group col-md-2'>
                            <button class='btn btn-primary' type='submit' >Find</button> 
                        </div>  

                    </div> 
                    </form>";
                }

                ?>
                </div>
            </div>
  
 
                    <?php
                    error_reporting(0);
                    $student_id = $_SESSION['user_student_id'];
                    if (!empty($_GET["search"]) || !empty($_GET["faculty"])) {
                        $search = trim($_GET["search"]);
                        $faculty = $_GET["faculty"];

                        if ($faculty == "") {
                            echo "<div class='my-3 p-3 bg-body rounded shadow-sm'>  <div class='row' >    <div class='col-md-12'> ";
                            echo "<h4 class='border-bottom pb-2 mb-0'> Search Results for Course Code $search</h4>";
                            $result = mysqli_query($con, "SELECT `Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`,"
                                . " `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`  "
                                . " , users_table.Full_Name  FROM `courses_table` INNER JOIN users_table"
                                . " ON users_table.User_ID=courses_table.Lecturer_User_ID where Course_Code like '%{$search}%' and courses_table.Course_ID not in (select course_id from course_students_table where Student_ID=$student_id)");
                        } else {
                            echo "<div class='my-3 p-3 bg-body rounded shadow-sm'>  <div class='row' >    <div class='col-md-12'> ";
                            echo "<h3 class='border-bottom pb-2 mb-0'> Find Courses under faculty $faculty</h3>";
                            $result = mysqli_query($con, "SELECT `Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`,
                             `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members` 
                             , users_table.Full_Name  FROM `courses_table` INNER JOIN users_table
                             ON users_table.User_ID=courses_table.Lecturer_User_ID where Faculty='$faculty'  and courses_table.Course_ID not in (select course_id from course_students_table where Student_ID=$student_id)");
                        }

               echo "<div class='row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3'>";
                        if (mysqli_num_rows($result) == 0) {
                            echo "<h5>No results found for your Search <h5><hr>";
                        } else {

                            while ($row = mysqli_fetch_assoc($result)) {
                                $name = $row['Course_Name'];
                                $code = $row['Course_Code'];
                                $faculty = $row['Faculty'];
                                $lecturer = $row['Full_Name'];
                                $academic = $row['Academic_Year'];
                                $url = $row['URL'];
                                $id = $row['Course_ID'];
                                $v = $row['Verify_New_Members'];
                                $msg2 = "Join Course";
                                if ($v > 0) {
                                    $msg = "<i class='fa fa-exclamation-circle'></i> Lecturer verification required";
                                    $msg2 = "Send Joining Request";
                                }

                                 echo "<div class='col'> 
                                        <div class='card'>  
                                            <div class='card-header'>
                                                <ul class='nav nav-pills card-header-pills'>
                                                  <li class='nav-item'>
                                                    <h5 class='nav-link'> $name </h5>
                                                  </li>
                                                  <li class='nav-item'>
                                                    <p class='nav-link'>$msg</p>
                                                  </li> 
                                                </ul> 
                                            </div>
                                            <div class='card-body'>
                                              <h5 class='card-title'>$code</h5> 
                                              <p class='card-text'> $academic</p>
                                              <p class='card-text'>$faculty</p>
                                              <p class='card-text'>$lecturer</p>
                                              <a href='~\..\Script.php?JoinCourse=y&id=$id&std=$student_id&joining=$v' onclick=return confirm(\"Are you sure to join this course?\")' class='btn btn-success'>Open</a>
                                            </div>
                                        </div>
                                      </div>";
 
                            }
                        }

               echo "</div>";
                    }
                    ?>
</div>
</div>
</div>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h4 class="border-bottom pb-2 mb-0"> My Courses </h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3"> 
                    <?php 
                    $result = mysqli_query($con, "SELECT users_table.Full_Name, course_students_table.Status, courses_table.Course_ID, `Course_Name`, `Academic_Year`, `Faculty`, `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members` FROM `courses_table`
                        INNER JOIN users_table
                        ON users_table.User_ID=courses_table.Lecturer_User_ID

                        INNER JOIN course_students_table on course_students_table.Course_ID=courses_table.Course_ID

                        where course_students_table.Student_ID=$student_id");

                    if (mysqli_num_rows($result) == 0) {
                        echo "<h4><i class='fa fa-exclamation-circle'></i> You are not Enrolled in any Course</h4>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $name = $row['Course_Name'];
                            $code = $row['Course_Code'];
                            $faculty = $row['Faculty'];
                            $lecturer = $row['Full_Name'];
                            $academic = $row['Academic_Year'];
                            $url = $row['URL'];
                            $id = $row['Course_ID'];
                            $Status = $row['Status'];

                    ?>
          
             <?php 
                            if ($Status == "Joined") {
                                echo "<div class='col'> 
                                        <div class='card'>  
                                            <div class='card-header'>
                                                <ul class='nav nav-pills card-header-pills'>
                                                  <li class='nav-item'>
                                                    <h5 class='nav-link'> $name </h5>
                                                  </li>
                                                  <li class='nav-item'>
                                                    <p class='nav-link bg-success'><i class='fa fa-check-circle'></i> $Status</p>
                                                  </li> 
                                                </ul> 
                                            </div>
                                            <div class='card-body'>
                                              <h5 class='card-title'>$code</h5> 
                                              <p class='card-text'> $academic</p>
                                              <p class='card-text'>$faculty</p>
                                              <p class='card-text'>$lecturer</p>
                                              <a href='~\..\Course.php?url=$url' class='btn btn-primary'>Open</a>
                                            </div>
                                            </div>
                                      </div>";
                            } else { 

                               echo "<div class='col'> 
                                        <div class='card'>  
                                            <div class='card-header'>
                                                <ul class='nav nav-pills card-header-pills'>
                                                  <li class='nav-item'>
                                                    <h5 class='nav-link'> $name </h5>
                                                  </li>
                                                  <li class='nav-item'>
                                                    <p class='nav-link btn-danger'><i class='fa fa-check-circle'></i> $Status</p>
                                                  </li> 
                                                </ul> 
                                            </div>
                                            <div class='card-body'>
                                              <h5 class='card-title'>$code</h5> 
                                              <p class='card-text'> $academic</p>
                                              <p class='card-text'>$faculty</p>
                                              <p class='card-text'>$lecturer</p> 
                                            </div>
                                        </div>
                                      </div>";
 
                            }
                        }
                    }

                    ?>
        </div>
    </div>
</div>
  

</main>