$(document).ready(function () {
    //hide all content at the start
    $('.content-area').hide();

    //select all check boxes on simple click
    $("#checkAll").change(function(){ 
        $(".check_list").prop('checked', $(this).prop("checked")); 
    });

    $('.check_list').change(function(){ 
        if(false == $(this).prop("checked")){
            $("#checkAll").prop('checked', false); 
        }

        if ($('.check_list:checked').length == $('.check_list').length ){
            $("#checkAll").prop('checked', true);
        }
    });

   //change content using sidebar
    $('#links li a').click(function (event){
        event.preventDefault();

        var content = $(this).attr('href');
        $(content).show();        
        $(content).siblings('.content-area').hide();
    });

    //mail send 
    $("#sendMail").on('click',function(){
        let mailArr = [];
        let subject = $('#subject').val();
        let message = $('#message').val();
       $("input[name='check_list[]']").each(function(){
            if(this.checked){
                mailArr.push(this.value);
            }            
       });

       $.ajax({
        type:'POST',
        url:"../lib/bin/mailjet.php?message=true",
        data : {
            'email':mailArr,
            'subject':subject,
            'message':message
        },
        success : function(res){
           console.table(res);
        }
    });
    });
    
});