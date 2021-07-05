<?php
# fetch_user.php
# video tutorial No. 3
# last update backup
include('../model/model.php'); # model required

session_start();

$query = "
SELECT * FROM login
WHERE user_id != '".$_SESSION['user_id']."'
"; # this will fetch all user details except currently logged in users

$statement = $connect->prepare($query);

$statement->execute(); # this will execute the query inside the statement

$result = $statement->fetchAll(); # this will fetch all data and store in the result variable

$output = '
<table class="table table-dark table-hover">
 <tr>
  <th width="70%">Username</td>
  <th width="20%">Status</td>
  <th width="10%">Action</td>
 </tr>
';

foreach($result as $row)
{
 $status = '';
 $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second'); # strtotime is string to time function, in every 10 seconds, time will be stored in current_timestamp
 $current_timestamp = date('Y-m-d H:i:s', $current_timestamp); # this will conver unix time to date and time formate
 $user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
 if($user_last_activity > $current_timestamp)
 {
  $status = '<span class="badge bg-success">Online</span>'; # if the user is online
 }
 else
 {
  $status = '<span class="badge bg-secondary">Offline</span>'; # if the user is offline
 }
 $output .= '
 <tr>
  <td>'.$row['username'].' '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).' '.fetch_is_type_status($row['user_id'], $connect).'</td>
  <td>'.$status.'</td>
  <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">Start Chat</button></td>
 </tr>
 ';
} # this loop will show all the output from the result

$output .= '</table>';

echo $output;

?>
