<?php

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Account Deleted</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    </head>
    <body>
        <div class="display-2" style="text-align: center; margin-top:20px;">
            <p class="text-danger">Your Account Has Been Deleted</p>
            <?php
            session_start(); # session start

            session_destroy(); # session destroy

            header('location:login.php');
            ?>
        </div>
    </body>
</html>
