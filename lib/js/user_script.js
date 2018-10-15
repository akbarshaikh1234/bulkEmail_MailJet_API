$(document).ready(function(){
    
    $('#submit').on('click',function(event){
        event.preventDefault();

        let email = $("#email").val();

        $.ajax({
            type:'POST',
            url:"./lib/bin/call_to_database.php?subscribeInsert=true",
            data : {
                'email':email
            },
            success : function(res){
                alert(res);
            }
        });

    });

    function loginsuccess(val){
        if(val){
           window.location = './admin/index.php';
        }else{
            $('#admin-email').css("border","1px solid red");
            $('#admin-pass').css("border","1px solid red");
            $('#errorHelper').text("Username or Password not correct");
            $('#errorHelper').css('color','red');
        }
    }

    $('#login').on('click',function(){
        let username = $('#admin-email').val();
        let pass = $('#admin-pass').val();

        $.ajax({
            type:'POST',
            url:"./lib/bin/call_to_database.php?adminLogin=true",
            data : {
                'email':username,
                'pass':pass
            },
            success : function(res){
               loginsuccess(res);
            }
        });
    });
});