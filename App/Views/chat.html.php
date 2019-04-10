<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Application chat - Messages</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/chat.css">
    <script src="js/jquery-3.2.1.min.js"></script>


</head>
<body>
<?php if (isset($_SESSION['user'])): ?>
    <div class="row">
        <div class="col-lg-2"> <?php if (isset($_SESSION['user'])) echo $_SESSION['user']->getUsername() ?> </div>
        <div class="col-lg-8"></div>
        <div class="col-lg-2">  <a href="logout" > logout </a> </div>
    </div>
<?php endif ?>

<div class="container" style="height:80%;">
    <div class="row" style="height: 80%">
        <div id="chat" class="col-lg-10"></div>
        <div class="col-log-2">
            <ul id="users">

            </ul>
        </div>
    </div>
    <div class="row">
        <form class="form-horizontal" id="send_message" action='send_message' method="POST">
            <div class="control-group">
                <div class="controls">
                    <input type="text" id="msg" name="msg" placeholder="" class="input-xlarge">
                    <input type="hidden" value="" name="id">
                    <input type="hidden" value="" name="lastMsgId">
                    <button class="btn btn-success">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var inConversation = false;
    var partnerId = 0;
    var partnerUsername = '';
    function getUTCDate(){
        return (Math.floor(Date.now() / 1000));
    }
    $(document).ready(function(){
        setInterval(getUsersList, 5000);
        setInterval(function(){
            if (inConversation){
                listConversation(partnerId, partnerUsername, getUTCDate());
            }
        }, 500);
        $(document.body).on('click', '.user', function(){
            inConversation = true;
            partnerId = $(this).data('partnerId');
            partnerUsername = $(this).data('partnerUsername');
            listConversation(partnerId, partnerUsername, '')
            return false;
        })

        $('#send_message').on('submit', function(){
            sendMessage($(this).serialize());
            return false;
        })
    })

    function getUsersList(){
        $usersDiv = $('#users');
        $.ajax({
            url: 'users',
            success: function(result){
                if (result.length > 0){
                    result.forEach(function(item){
                        $aElmnt = $('a.user[data-partner-id='+ item.id + ']');
                        if ($aElmnt.length <= 0) {
                            $usersDiv.append('<li><a class="user" href="#" data-partner-id="'+ item.id + '" data-partner-username="'+ item.username + '">'+ item.username +'</a></li>');
                        } else {
                            $aElmnt.toggleClass('active', item.active );
                        }
                    })
                }


            }
        })
    }
    function listConversation(id, username, utcDate){
        $chatDiv = $('#chat');
        $('input[name=id]').val(id);
        lastId = $('.msg').last().data('id');
        if (lastId == undefined) {
            lastId = 0;
        }
        $.ajax({
            url: 'conversation_with',
            type:"POST",
            data:{
                id: id,
            },
            success: function(result){
                if (result.length > 0){
                    result.forEach(function(item){
                        textAlign = item.its_me ? 'left' : 'right';
                        if ($('.msg[data-id='  + item.id + ']').length <= 0 ){
                            $chatDiv.append('<div class="msg" data-id="' + item.id +'" style="text-align:' + textAlign +  ';">' + item.msg +'</div>');

                        }
                    })
                } else {
                    if ($('#start-conv').length < 1 ) {
                        $chatDiv.empty();
                        $chatDiv.append('<div id="start-conv"> This is the very begining of you chat </div>');
                    }
                }


            }
        })
        return false;
    }
    function sendMessage(data){
        $.ajax({
            url: 'send_message',
            type: "POST",
            data: data,
            success: function(result){
                $('#msg').val('');

            }
        })
        return false;
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
</body>
</html>