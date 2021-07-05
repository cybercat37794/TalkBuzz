<?php
#logout.php

session_start(); # session start

session_destroy(); # session destroy

header('location:login.php');

?>
