<!--
//profile.php
!-->

<?php

include('model/model.php'); // database connection


session_start(); // session_start

if(!isset($_SESSION['user_id']))
{
    header('location:index.php'); // if session set
}

$query = "
SELECT * FROM login
WHERE user_id = '".$_SESSION['user_id']."'
"; # this will fetch all user details except currently logged in users

$statement = $connect->prepare($query);

$statement->execute(); # this will execute the query inside the statement

$result = $statement->fetchAll(); # this will fetch all data and store in the result variable

foreach($result as $row)
{
    $row['username'];
    $row['email'];
}

$message = '';



#################DELETE OPERATION######################
if (isset($_GET['delete']))
{
    $query = "
    DELETE FROM `login` WHERE `login`.`user_id` = '".$_SESSION['user_id']."'
    ";
    $statement = $connect->prepare($query);

    if($statement->execute())
    {
        echo "<p><label class='text-danger'>Account Deleted!</label></p>";
        header('location:account_deleted.php');

    }
}
#######################################################
#################UPDATE OPERATION######################
if (isset($_POST["update"]))
{
    if(empty($_POST["username"]))
    {
        $message .= '<p><label>Username is required</label></p>';
    }
    elseif (!preg_match("/^[a-zA-Z-' ]*$/",$_POST["username"]))
    {
        $message = '<p><label>Only letters and white space are allowed as Username</label></p>';
    }
    elseif (empty($_POST["email"]))
    {
        $message = '<p><label>Email is required</label></p>';
    }
    elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    {
        $message = '<p><label>Please enter a correct email address</label></p>';
    }

    elseif(empty($_POST["password"]))
    {
        $message .= '<p><label>Password is required</label></p>';
    }
    elseif(strlen($_POST["password"]) <= 8)
    {
        $message .= '<p><label>Password must contain at least 8 characters!</label></p>';
    }
    elseif(!preg_match("#[0-9]+#",$_POST["password"]))
    {
        $message = '<p><label>Password must contain at least 1 number!</label></p>';
    }
    elseif(!preg_match("#[A-Z]+#",$_POST["password"]))
    {
        $message = '<p><label>Password must contain at least 1 capital letter!</label></p>';
    }
    elseif(!preg_match("#[a-z]+#",$_POST["password"]))
    {
        $message = '<p><label>Password must contain at least 1 lowercase letter!</label></p>';
    }
    elseif($_POST["password"] != $_POST['confirm_password'])
    {
        $message .= '<p><label>Password not match</label></p>';
    }
    else
    {
        $username=$_POST['username'];
        $email=$_POST['email'];
        $password=password_hash($_POST['password'], PASSWORD_DEFAULT);


        $query = "
        UPDATE login
        SET username='$username',
        email='$email',
        password='$password'
        WHERE user_id = '".$_SESSION['user_id']."'
        ";
        $statement = $connect->prepare($query);
        if($statement->execute())
        {
            $message = "<label class='text-success'>Updated Successfully</label>";
        }
    }
}
#######################################################

// if(isset($_POST["register"]))
// {
//     $username = trim($_POST["username"]);
//     $password = trim($_POST["password"]);
//     $email = trim($_POST["email"]);
//     $check_query = "
//     SELECT * FROM login
//     WHERE username = :username
//     ";
//     $statement = $connect->prepare($check_query);
//     $check_data = array(
//         ':username'  => $username
//     );
//     if($statement->execute($check_data))
//     {
//         if($statement->rowCount() > 0)
//         {
//             $message .= '<p><label>Username already taken</label></p>';
//         } // to check if any account created according to this name
//         else
//         {
//             if(empty($username))
//             {
//                 $message .= '<p><label>Username is required</label></p>';
//             }
//             elseif (!preg_match("/^[a-zA-Z-' ]*$/",$username))
//             {
//                 $message = '<p><label>Only letters and white space are allowed as Username</label></p>';
//             }
//
//             elseif (empty($_POST["email"]))
//             {
//                 $message = '<p><label>Email is required</label></p>';
//             }
//             elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
//             {
//                 $message = '<p><label>Please enter a correct email address</label></p>';
//             }
//
//             elseif(empty($password))
//             {
//                 $message .= '<p><label>Password is required</label></p>';
//             }
//             elseif(strlen($_POST["password"]) <= 8)
//             {
//                 $message .= '<p><label>Password must contain at least 8 characters!</label></p>';
//             }
//             elseif(!preg_match("#[0-9]+#",$password))
//             {
//                 $message = '<p><label>Password must contain at least 1 number!</label></p>';
//             }
//             elseif(!preg_match("#[A-Z]+#",$password))
//             {
//                 $message = '<p><label>Password must contain at least 1 capital letter!</label></p>';
//             }
//             elseif(!preg_match("#[a-z]+#",$password))
//             {
//                 $message = '<p><label>Password must contain at least 1 lowercase letter!</label></p>';
//             }
//             else
//             {
//                 if($password != $_POST['confirm_password'])
//                 {
//                     $message .= '<p><label>Password not match</label></p>';
//                 }
//             }
//         }
//     }
// }



?>

<html>
<head>
    <title>TalkBuzz Profile js</title>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="registerStyle.css">
    <script type="text/javascript" src="script.js"></script>

</head>
<body class="register-body">
    <div class="container">
        <br />

        <h3 align="center">TalkBuzz js</a></h3><br />
        <br />
        <div class="panel panel-default">
            <div class="display-4">View/Edit Profile js</div>
            <div class="panel-body">
                <form name="myform" method="post" action="" onsubmit="validateform()" >
                    <span class="text-danger"><?php echo $message; ?></span>
                    <div class="alert alert-light">
                        <label class="display-6">Enter Username</label>
                        <input value="<?php echo $row['username']; ?>" type="text" name="username" id="username" class="form-control onkeyup="checkName()" onblur="checkName()">
                        <span id="nameErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <label class="display-6">Enter Email</label>
                        <input value="<?php echo $row['email']; ?>" type="text" id="email" name="email" class="form-control onkeyup="checkEmail()" onblur="checkEmail()">
                        <span id="emailErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <label class="display-6">Enter New Password</label>
                        <input type="password" id="password" name="password" class="form-control onkeyup="checkPass()" onblur="checkPass()">
                        <span id="passErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <label class="display-6">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control onkeyup="checkCPass()" onblur="checkCPass()">
                        <span id="passCErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <!-- <input type="hidden" name="user_id" value="<?php #echo $_GET['user_id'] ?>"> -->
                        <input type="submit" name="update" class="btn btn-secondary" value="Update" />

                        <script type="text/javascript">
                        function AlertIt()
                        {
                            var answer = confirm ("Are you sure want to delete this account.")
                            if (answer)
                            window.location="profile.php?delete=<?php echo $_SESSION['user_id']; ?>";
                        }
                        </script>

                        <a href="javascript:AlertIt();" class="btn btn-danger">Delete Account</a>
                        <a href="index.php" class="btn btn-primary">Back to Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
