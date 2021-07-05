<?php
# remove_chat.php
# video tutorial No. 11

include('../model/model.php');

if(isset($_POST["chat_message_id"]))
{
    $query = "
    UPDATE chat_message
    SET status = '2'
    WHERE chat_message_id = '".$_POST["chat_message_id"]."'
    "; // this will change the chat_message status to '2', so that particular chat message will not be shown

    $statement = $connect->prepare($query);

    $statement->execute();
}

?>
