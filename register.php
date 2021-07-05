<!--
//register.php
!-->

<?php

include('model/model.php'); // database connection

session_start(); // session_start

$message = '';

if(isset($_SESSION['user_id']))
{
    header('location:index.php'); // if session set
}

if(isset($_POST["register"]))
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);
    $check_query = "
    SELECT * FROM login
    WHERE username = :username
    ";
    $statement = $connect->prepare($check_query);
    $check_data = array(
        ':username'  => $username
    );
    if($statement->execute($check_data))
    {
        if($statement->rowCount() > 0)
        {
            $message .= '<p><label>Username already taken</label></p>';
        } // to check if any account created according to this name
        else
        {
            if(empty($username))
            {
                $message .= '<p><label>Username is required</label></p>';
            }
            elseif (!preg_match("/^[a-zA-Z-' ]*$/",$username))
            {
                $message = '<p><label>Only letters and white space are allowed as Username</label></p>';
            }

            elseif (empty($_POST["email"]))
            {
                $message = '<p><label>Email is required</label></p>';
            }
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $message = '<p><label>Please enter a correct email address</label></p>';
            }

            elseif(empty($password))
            {
                $message .= '<p><label>Password is required</label></p>';
            }
            elseif(strlen($_POST["password"]) <= 8)
            {
                $message .= '<p><label>Password must contain at least 8 characters!</label></p>';
            }
            elseif(!preg_match("#[0-9]+#",$password))
            {
                $message = '<p><label>Password must contain at least 1 number!</label></p>';
            }
            elseif(!preg_match("#[A-Z]+#",$password))
            {
                $message = '<p><label>Password must contain at least 1 capital letter!</label></p>';
            }
            elseif(!preg_match("#[a-z]+#",$password))
            {
                $message = '<p><label>Password must contain at least 1 lowercase letter!</label></p>';
            }
            else
            {
                if($password != $_POST['confirm_password'])
                {
                    $message .= '<p><label>Password not match</label></p>';
                }
            }
            if($message == '')
            {
                $data = array(
                    ':username'  => $username,
                    ':email'     => $email,
                    ':password'  => password_hash($password, PASSWORD_DEFAULT)
                );

                $query = "
                INSERT INTO login
                (username, password, email)
                VALUES (:username, :password, :email)
                ";
                $statement = $connect->prepare($query);
                if($statement->execute($data))
                {
                    $message = "<label class='text-success'>Registration Completed! Please login with your credentials</label>";
                }
            }
        }
    }
}

?>

<html>
<head>
    <title>TalkBuzz Sign Up</title>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="registerStyle.css">
    <script type="text/javascript" src="script.js"></script>

</head>
<body class="register-body">
    <div class="container">
        <br />

        <h3 align="center">TalkBuzz </a></h3><br />
        <br />
        <div class="panel panel-default">
            <div class="display-4">TalkBuzz Sign Up </div>
            <div class="panel-body">
                <!-- <form method="post"> -->
                    <form name="myform" method="post" action="" onsubmit="validateform()" >
                    <span class="text-danger"><?php echo $message; ?></span>
                    <div class="alert alert-light">
                        <label class="display-6">Enter Username</label>
                        <input type="text" name="username" id="username" class="form-control onkeyup="checkName()" onblur="checkName()">
                        <span id="nameErr"></span>
                    </div>
                     <div class="alert alert-light">
                        <label class="display-6">Enter Email</label>
                        <input type="text" id="email" name="email" class="form-control onkeyup="checkEmail()" onblur="checkEmail()">
                        <span id="emailErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <label class="display-6">Enter Password</label>
                        <input type="password" id="password" name="password" class="form-control" onkeyup="checkPass()" onblur="checkPass()" onkeyup="checkPassMatch()" onblur="checkPassMatch()">
                        <span id="passErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <label class="display-6">Confirm Password</label>

                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" onkeyup="checkCPass()" onblur="checkCPass()" onkeyup="checkPassMatch()" onblur="checkPassMatch()">
                        <span id="passCErr"></span>
                    </div>
                    <div class="alert alert-light">
                        <input type="submit" name="register" class="btn btn-secondary" value="Register" />
                        <a href="login.php" class="btn btn-primary">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
