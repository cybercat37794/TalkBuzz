<?php
# model.php | not in video tutorial | using MVC architecture
# video tutorial No. 4
date_default_timezone_set('Asia/Dhaka');

require 'database_connection.php';

function fetch_user_last_activity($user_id, $connect)
{
    $query = "
    SELECT * FROM login_details
    WHERE user_id = '$user_id'
    ORDER BY last_activity DESC
    LIMIT 1
    "; # this will fetch last inserted record of a particular user_login_details from login_details table

    $statement = $connect->prepare($query); # prepare statement
    $statement->execute(); # execute statement
    $result = $statement->fetchAll(); # store result in result variable

    foreach ($result as $row)
    {
        return $row['last_activity']; # will return last activity
    }
} // this function will work with users last activity (online/offline)

// video tutorial No. 6 and 11
function fetch_user_chat_history($from_user_id, $to_user_id, $connect)
{
    $query = "
    SELECT * FROM chat_message
    WHERE (from_user_id = '".$from_user_id."'
    AND to_user_id = '".$to_user_id."')
    OR (from_user_id = '".$to_user_id."'
    AND to_user_id = '".$from_user_id."')
    ORDER BY timestamp ASC
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '<ul class="list-unstyled">';
    foreach($result as $row)
    {
        $user_name = '';
        $dynamic_background = '';
        $chat_message = '';
        if($row["from_user_id"] == $from_user_id)
        {
            if($row["status"] == '2')
            {
                $chat_message = '<em>This message has been removed</em>';
                $user_name = '<b class="text-success">You</b>';
            } // when message is deleted then this message will be shown
            else
            {
                $chat_message = $row['chat_message'];
                $user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['chat_message_id'].'">x</button>&nbsp;<b class="text-success" >You</b>';
            }

            $dynamic_background = 'background-color:#cfcfcf;';
        }
        else
        {
            if($row["status"] == '2')
            {
                $chat_message = '<em>This message has been removed</em>';
            }
            else
            {
                $chat_message = $row["chat_message"];
            }
            $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
            $dynamic_background = 'background-color:#ededed;';
        } // this will be displayed to the receiver side
        $output .= '
        <li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
        <p>'.$user_name.' - '.$chat_message.'
        <div align="right">
        - <small><em>'.$row['timestamp'].'</em></small>
        </div>
        </p>
        </li>
        ';
    }
    $output .= '</ul>';
    $query = "
    UPDATE chat_message
    SET status = '0'
    WHERE from_user_id = '".$to_user_id."'
    AND to_user_id = '".$from_user_id."'
    AND status = '1'
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $output;
}

function get_user_name($user_id, $connect)
{
    $query = "SELECT username FROM login WHERE user_id = '$user_id'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        return $row['username'];
    }
} // this method will return username based on value inside user_id variable

// video tutorial No. 7
function count_unseen_message($from_user_id, $to_user_id, $connect)
{
    $query = "
    SELECT * FROM chat_message
    WHERE from_user_id = '$from_user_id'
    AND to_user_id = '$to_user_id'
    AND status = '1'
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();
    $output = '';
    if($count > 0)
    {
        $output = '<span class="label label-success">'.$count.'</span>';
    }
    return $output;
} // this will show the number of unseen massages

// video tutorial No. 8
function fetch_is_type_status($user_id, $connect)
{
    $query = "
    SELECT is_type FROM login_details
    WHERE user_id = '".$user_id."'
    ORDER BY last_activity DESC
    LIMIT 1
    ";  // this will select is_type from login_details
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '';
    foreach($result as $row)
    {
        if($row["is_type"] == 'yes')
        {
            $output = ' - <small><em><span class="text-muted">Typing...</span><span class="spinner-grow spinner-grow-sm" role="status"></span><span class="spinner-grow spinner-grow-sm" role="status"></span><span class="spinner-grow spinner-grow-sm" role="status"></span></em></small>';
        }
    }
    return $output;
}

// video tutorial No. 10
function fetch_group_chat_history($connect)
{
    $query = "
    SELECT * FROM chat_message
    WHERE to_user_id = '0'
    ORDER BY timestamp ASC
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '<ul class="list-unstyled">';
    foreach($result as $row)
    {
        $user_name = '';
        $chat_message = '';
        $dynamic_background = '';

        if($row['from_user_id'] == $_SESSION['user_id'])
        {
            if($row["status"] == '2')
            {
                $chat_message = '<em>This message has been removed</em>';
                $user_name = '<b class="text-success" >You</b>';
            } // this will remove chat from group chat
            else
            {
                $chat_message = $row['chat_message'];
                $user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['chat_message_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
            }
            $dynamic_background = 'background-color:#cfcfcf;';
        }
        else
        {
            if($row["status"] == '2')
            {
                $chat_message = '<em>This message has been removed</em>';
            }
            else
            {
                $chat_message = $row['chat_message'];
            } // this will displayed on the receiver side
            $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
            $dynamic_background = 'background-color:#ededed;';
        }
        $output .= '
        <li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
        <p>'.$user_name.' - '.$chat_message.'
        <div align="right">
        - <small><em>'.$row['timestamp'].'</em></small>
        </div>
        </p>

        </li>
        ';
    }
    $output .= '</ul>';
    return $output;
}


?>
