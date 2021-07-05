<!--//index.php!-->
<!-- Video tutorial No. 2 -->
<!--
//index.php
!-->

<?php

include('model/model.php');

session_start();

if(!isset($_SESSION['user_id']))
{
    header("location:login.php");
}

?>

<html>
<head>
    <title>TalkBuzz</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">
    <link rel="stylesheet" href="indexStyle.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

</head>
<body>
    <div class="container">
        <br />

        <h2 class="Display-1" align="center" style="font-family: 'Pacifico', cursive; margin-bottom:5px;">TalkBuzz</a></h2><br />
        <p class="Display-6" align="center" style="font-family: 'Pacifico', cursive; font-size:17px;">A friendly chatting website for everyone</p>
        <br />
        <div class="row">
            <div class="col-md-8 col-sm-6">
                <h4 class="display-5" style="font-family: 'Pacifico', cursive;">Friend List</h4>
            </div>
            <div class=".col-md-2 col-sm-3">
                <input type="hidden" id="is_active_group_chat_window" value="no" />
                <button type="button" name="group_chat" id="group_chat" class="btn btn-info">Group Chat</button>
            </div>
            <div class=".col-">
                <p align="right" style="font-family: 'Pacifico', cursive;">Hi - <?php echo $_SESSION['username']; ?> - <a href="profile.php" class="btn btn-info">Profile</a> - <a href="logout.php" class="btn btn-info">Logout</a></p>
            </div>
        </div>
        <div class="table-responsive">

            <div id="user_details"></div>
            <div id="user_model_details"></div>
        </div>
    </div>
</body>
</html>

<style>

.chat_message_area
{
    position: relative;
    width: 100%;
    height: auto;
    background-color: #FFF;
    border: 1px solid #CCC;
    border-radius: 3px;
}

#group_chat_message
{
    width: 100%;
    height: auto;
    min-height: 80px;
    overflow: auto;
    padding:6px 24px 6px 12px;
}

.image_upload
{
    position: absolute;
    top:3px;
    right:3px;
}
.image_upload > form > input
{
    display: none;
}

.image_upload img
{
    width: 24px;
    cursor: pointer;
}

</style>

<div id="group_chat_dialog" title="Group Chat Window">
    <div id="group_chat_history" style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;">

    </div>
    <div class="form-group">
        <!--<textarea name="group_chat_message" id="group_chat_message" class="form-control"></textarea>!-->
        <div class="chat_message_area">
            <div id="group_chat_message" contenteditable class="form-control">

            </div>
            <div class="image_upload">
                <form id="uploadImage" method="post" action="upload.php">
                    <label for="uploadFile"><img src="upload/upload.png" /></label>
                    <input type="file" name="uploadFile" id="uploadFile" accept=".jpg, .png, .jpeg, .gif" />
                </form>
            </div>
        </div>
    </div>
    <div class="form-group" align="right">
        <button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info">Send</button>
    </div>
</div>


<script>
$(document).ready(function(){

    fetch_user();

    setInterval(function(){
        update_last_activity();
        fetch_user();
        update_chat_history_data();
        fetch_group_chat_history();
    }, 5000);

    function fetch_user()
    {
        $.ajax({
            url:"controller/fetch_user.php",
            method:"POST",
            success:function(data){
                $('#user_details').html(data);
            }
        })
    }

    function update_last_activity()
    {
        $.ajax({
            url:"controller/update_last_activity.php",
            success:function()
            {

            }
        })
    }

    function make_chat_dialog_box(to_user_id, to_user_name)
    {
        var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_name+'">';
        modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
        modal_content += fetch_user_chat_history(to_user_id);
        modal_content += '</div>';
        modal_content += '<div class="form-group">';
        modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea>';
        modal_content += '</div><div class="form-group" align="right">';
        modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
        $('#user_model_details').html(modal_content);
    }

    $(document).on('click', '.start_chat', function(){
        var to_user_id = $(this).data('touserid');
        var to_user_name = $(this).data('tousername');
        make_chat_dialog_box(to_user_id, to_user_name);
        $("#user_dialog_"+to_user_id).dialog({
            autoOpen:false,
            width:400
        });
        $('#user_dialog_'+to_user_id).dialog('open');
        $('#chat_message_'+to_user_id).emojioneArea({
            pickerPosition:"top",
            toneStyle: "bullet"
        });
    });

    $(document).on('click', '.send_chat', function(){
        var to_user_id = $(this).attr('id');
        var chat_message = $('#chat_message_'+to_user_id).val();
        $.ajax({
            url:"controller/insert_chat.php",
            method:"POST",
            data:{to_user_id:to_user_id, chat_message:chat_message},
            success:function(data)
            {
                //$('#chat_message_'+to_user_id).val('');
                var element = $('#chat_message_'+to_user_id).emojioneArea();
                element[0].emojioneArea.setText('');
                $('#chat_history_'+to_user_id).html(data);
            }
        })
    });

    function fetch_user_chat_history(to_user_id)
    {
        $.ajax({
            url:"controller/fetch_user_chat_history.php",
            method:"POST",
            data:{to_user_id:to_user_id},
            success:function(data){
                $('#chat_history_'+to_user_id).html(data);
            }
        })
    }

    function update_chat_history_data()
    {
        $('.chat_history').each(function(){
            var to_user_id = $(this).data('touserid');
            fetch_user_chat_history(to_user_id);
        });
    }

    $(document).on('click', '.ui-button-icon', function(){
        $('.user_dialog').dialog('destroy').remove();
        $('#is_active_group_chat_window').val('no');
    });

    $(document).on('focus', '.chat_message', function(){
        var is_type = 'yes';
        $.ajax({
            url:"controller/update_is_type_status.php",
            method:"POST",
            data:{is_type:is_type},
            success:function()
            {

            }
        })
    });

    $(document).on('blur', '.chat_message', function(){
        var is_type = 'no';
        $.ajax({
            url:"controller/update_is_type_status.php",
            method:"POST",
            data:{is_type:is_type},
            success:function()
            {

            }
        })
    });

    $('#group_chat_dialog').dialog({
        autoOpen:false,
        width:400
    });

    $('#group_chat').click(function(){
        $('#group_chat_dialog').dialog('open');
        $('#is_active_group_chat_window').val('yes');
        fetch_group_chat_history();
    });

    $('#send_group_chat').click(function(){
        var chat_message = $('#group_chat_message').html();
        var action = 'insert_data';
        if(chat_message != '')
        {
            $.ajax({
                url:"controller/group_chat.php",
                method:"POST",
                data:{chat_message:chat_message, action:action},
                success:function(data){
                    $('#group_chat_message').html('');
                    $('#group_chat_history').html(data);
                }
            })
        }
    });

    function fetch_group_chat_history()
    {
        var group_chat_dialog_active = $('#is_active_group_chat_window').val();
        var action = "fetch_data";
        if(group_chat_dialog_active == 'yes')
        {
            $.ajax({
                url:"controller/group_chat.php",
                method:"POST",
                data:{action:action},
                success:function(data)
                {
                    $('#group_chat_history').html(data);
                }
            })
        }
    }

    $('#uploadFile').on('change', function(){
        $('#uploadImage').ajaxSubmit({
            target: "#group_chat_message",
            resetForm: true
        });
    });

    // video tutorial no. 11
    $(document).on('click', '.remove_chat', function(){
        var chat_message_id = $(this).attr('id');
        if(confirm("Are you sure you want to remove this chat?"))
        {
            $.ajax({
                url:"controller/remove_chat.php",
                method:"POST",
                data:{chat_message_id:chat_message_id},
                success:function(data)
                {
                    update_chat_history_data();
                }
            })
        }
    });
});
</script>
