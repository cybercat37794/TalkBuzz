<?php
# update_last_activity.php
# video tutorial No. 4

# include('../model/database_connection.php');
require '../model/model.php'; # model required

session_start();

$query = "
UPDATE login_details
SET last_activity = now()
WHERE login_details_id = '".$_SESSION["login_details_id"]."'
"; # this will update login_details_id in the database with login_details_id is session

$statement = $connect->prepare($query); # this will prepare the statement for execution

$statement->execute(); # this will execute the statement

?>
