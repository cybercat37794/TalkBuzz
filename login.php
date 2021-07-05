<!-- login.php -->
<?php

include ('model/database_connection.php'); # this including database form database_connection.php file

session_start(); # session will start according to the user

$message = ''; # massage variable is now empty globally

if (isset($_SESSION['user_id']))
{
    header('location:index.php'); # if user already login into the system, then no need to login again. User can directly go to the feed page which is index.php
}


if (isset($_POST['login'])) # isset method will check if the form is posted or not
{
    $query = "
    SELECT * FROM login
    WHERE username = :username
    "; # query for selecting username from login table in the TalkBuzz database

    $statement = $connect->prepare($query); # this will store the query commands in the statement variable
    $statement->execute(
        array(
            ':username' => $_POST['username'], // this username is the top username
        ) // array can be modified later for multiple value
    );

    $count = $statement->rowCount(); # rowCount method will count rows in the table then return the number of rows in the $count variable
    if($count > 0)
    {
        $result = $statement->fetchAll();
        foreach ($result as $row)
        {
            if(password_verify($_POST['password'], $row['password'])) #password_verify method will verify password in the database
            {
                $_SESSION['user_id']  = $row['user_id'];  # this will store in login_details table
                $_SESSION['username'] = $row['username']; # another session variable storing username to show the welcome message

                $sub_query = "
                INSERT INTO login_details (user_id)
                VALUES ('".$row['user_id']."')
                "; # this query will insert the user_id into the login_details table
                # this will help user to update online/offline status

                $statement = $connect->prepare($sub_query); # this will store the sub_query commands in the statement variable
                $statement->execute();
                $_SESSION['login_details_id'] = $connect->lastInsertId(); # will return id of the last inserted record in login_details_id table (will sort the last inserted id to the top of the table)

                header('location:index.php'); # this will redirect to index.php which is feedpage for the users
            }

            elseif($_POST['username']=="")
            {
                $message .= '<label>Password required</label>';
            }
            else
            {
                $message = '<label>Wrong Password</label>';
            }
        }
    }
    elseif(empty($username))
    {
        $message .= '<label>Username required </label>';
    }
    else
    {
        $message = '<label>Wrong Username</label>';
    }
}
?>


<html>
<head>
    <title>TalkBuzz Login</title>
       <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="loginStyle.css">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>
    <div class="container">
        <br />
        <h3 align="center">TalkBuzz</a></h3>
        <br />
        <br />
        <div class="panel panel-default">
            <div class="display-4">TalkBuzz Login</div>
            <div class="panel-body">
                <form name="myform" method="post" action="" onsubmit="validateform()" >
                    <p class="text-danger"><?php echo $message; ?></p>
                    <div class="form-control">
                        <label>Enter Username</label>
                        <input type="text" name="username" id="username" class="form-control" onkeyup="checkName()" onblur="checkName()">
                        <span id="nameErr"></span>
                    </div>
                    <div class="form-control">
                        <label>Enter Password</label>
                        <!-- <input type="password" name="password" class="form-control" /> -->
                        <input type="password" id="password" name="password" class="form-control" onkeyup="checkPass()" onblur="checkPass()" onsubmit="checkEmpty()">
                        <span id="passErr"></span>
                    </div>
                    <div class="">
                        <input type="submit" name="login" class="btn btn-primary" value="Login" />
                        <a href="register.php" class="btn btn-secondary">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<footer class="text-center text-white fixed-bottom" style="background-color: #2b2b2b;">
    <!-- Grid container -->
    <div class="container p-3">
        Created by
        <a target="_blank" href="https://github.com/cybercat37794">Imran Ahmed, </a>
        <a target="_blank" href="https://github.com/AbdusSobhan">Abs Sobhan,</a> and
        <a target="_blank" href="https://github.com/HossainNur">Nur Hossain</a>
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: #000000;">
        © 2021 Copyright:
        <a class="text-white" href="https://github.com/cybercat37794/TalkBuzz#talkbuzz">All Rights Reserved</a>
    </div>
    <!-- Copyright -->
</footer>
</html>
