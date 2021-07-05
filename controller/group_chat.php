<?php
# video tutorial No. 10
# group_chat.php

include('../model/model.php');

session_start();

if($_POST["action"] == "insert_data")
{
    $data = array(
        ':from_user_id'  => $_SESSION["user_id"],
        ':chat_message'  => $_POST['chat_message'],
        ':status'   => '1'
    );

    $query = "
    INSERT INTO chat_message
    (from_user_id, chat_message, status)
    VALUES (:from_user_id, :chat_message, :status)
    "; // insert group chat data into chat_message table

    $statement = $connect->prepare($query);

    if($statement->execute($data))
    {
        echo fetch_group_chat_history($connect); // this will return all group chat history
    }

}

if($_POST["action"] == "fetch_data")
{
    echo fetch_group_chat_history($connect); // this will return latest group chat message, which is called from index
}

?>
