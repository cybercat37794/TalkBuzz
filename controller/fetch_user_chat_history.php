<?php
# video tutorial No. 7
# fetch_user_chat_history.php

include('../model/model.php');

session_start(); // session start

echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect); // this will fetch chat history data and send to the ajex request

?>
