<?php
# video tutorial No. 8
# update_is_type_status.php

include('../model/model.php');

session_start();

$query = "
UPDATE login_details
SET is_type = '".$_POST["is_type"]."'
WHERE login_details_id = '".$_SESSION["login_details_id"]."'
";

$statement = $connect->prepare($query);

$statement->execute();

?>
