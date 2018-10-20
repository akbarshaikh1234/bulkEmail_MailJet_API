$(document).ready(function () {     
    /**
     * 
     * hide all content at the start
     */
    $('.content-area').hide();


   /**
    * change or show content using sidebar
    **/
    $('#links li a').click(function (event){
        event.preventDefault();        
        var content = $(this).attr('href');
        $('#upload').hide();
        $(content).show();        
        $(content).siblings('.content-area').hide();
    });

    /**
     * Making an Ajax Call to Mailjet.php file to get The Contact List
     * 
     */
    let tableRow;
    $.ajax({
        
        "type":"GET",
        "url":"../lib/bin/mailjet.php?contactList",
        "content-type":"json",
        success:function(res){
            let jsonObj = JSON.parse(res);              
            for(let i = 0; i < jsonObj.Data.length; i++){
                $('<option>').val(jsonObj.Data[i].ID).text(jsonObj.Data[i].Name).appendTo('#contactListId');
                $('<option>').val(jsonObj.Data[i].ID).text(jsonObj.Data[i].Name).appendTo('#listIds');

                tableRow += "<tr><td><div class='checkBoxRest'><input type='checkbox' class='check_list' name='check_list[]' value="+jsonObj.Data[i].ID+"></div></td><td>"+jsonObj.Data[i].ID+"</td><td>"+jsonObj.Data[i].Name+"</td></tr>";
                            
                $("#contactListTable > tbody").html(tableRow);
            }
         
        }
      });



    //slide down nav
    $("#slideNav").on('click',function(){
        $("#sub-list-link").slideToggle("fast");
    });

    /**
     * selection of emails check all button
     *  select all check boxes on simple click
     */

     //check all for mailjet server table
    $("#mailjetTable #checkAll").change(function(){ 
        $("#mailjetTable .check_list").prop('checked', $(this).prop("checked")); 
    });

    $('#mailjetTable .check_list').change(function(){ 
        if(false == $(this).prop("checked")){
            $("#mailjetTable #checkAll").prop('checked', false); 
        }

        if ($('#mailjetTable .check_list:checked').length == $('#mailjetTable .check_list').length ){
            $("#mailjetTable #checkAll").prop('checked', true);
        }
    });

    //check all from local server table
    $("#localTable #checkAll").change(function(){ 
        $("#localTable .check_list").prop('checked', $(this).prop("checked")); 
    });

    $('#localTable .check_list').change(function(){ 
        if(false == $(this).prop("checked")){
            $("#localTable #checkAll").prop('checked', false); 
        }

        if ($('#localTable .check_list:checked').length == $('#localTable .check_list').length ){
            $("#localTable #checkAll").prop('checked', true);
        }
    });

    //check all for contact list
    $("#contactListTable #checkAll").change(function(){ 
        $("#contactListTable .check_list").prop('checked', $(this).prop("checked")); 
    });

    $('#contactListTable .check_list').change(function(){ 
        if(false == $(this).prop("checked")){
            $("#contactListTable #checkAll").prop('checked', false);         }

        if ($('#contactListTable .check_list:checked').length == $('#contactListTable .check_list').length ){
            $("#contactListTable #checkAll").prop('checked', true);
        }
    });


    /**
     * Making an Ajax call to mailjet.php to send Mails to selected Emails
     * 
     */
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
            url:"../lib/bin/mailjet.php?message",
            data : {
                'email':mailArr,
                'subject':subject,
                'message':message
            },
            success : function(res){
            console.table(res);
            }
        });

        $(".close").trigger('click');
    });

    $("#listIds").on('change',function(){
        var tableRow2;
        let id = $('#listIds').val();
            if(id !=""){
                $.ajax({
                    type:'POST',
                    url:"../lib/bin/mailjet.php?mailjetListEmails",
                    data : {
                        'listId':id
                    },
                    success : function(res){
                        let jsonObj = JSON.parse(res);              
                        for(let i = 0; i < jsonObj.Data.length; i++){
                            tableRow2 += "<tr><td><div class='checkBoxRest'><input type='checkbox' class='check_list' name='check_list[]' value="+jsonObj.Data[i].Email+"></div></td><td>"+jsonObj.Data[i].ID+"</td><td>"+jsonObj.Data[i].Email+"</td></tr>";
                            
                            $("#mailjetTable > tbody").html(tableRow2);
                        }
                    }
                });
            }
    });

    $("#addName").on('click',function(){
        let listName = $('#listName').val();

        $.ajax({
            type:'POST',
            url:"../lib/bin/mailjet.php?addContactList",
            data : {
                'listName':listName
            },
            success : function(res){
                let jsonObj = JSON.parse(res);
            }
        });
            
    });


    // Line graph generation using Chart.js
    $('#fetch').on('click',function(){
        let val = $('#dateControl').val();
        let val2 = $('#dateControl2').val();
   
       $.ajax({
             "type":"POST",
             "url":"../lib/bin/mailjet.php?stats",
             "content-type":"json",
             data:{
               'dateFrom':val,
               'dateTo':val2
             },
             success:function(res){
               lineChart(res);
             }
           });
      });
   
    
});


function lineChart(res){

    //to remove previous frame of the canvas if the graph is updated with new data
    $('#canvas').remove(); // remove <canvas> element
    $('.graph-container').append('<canvas id="canvas"><canvas>');

    let month = ['Jan','Fab','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    let jsonObj = JSON.parse(res);

    let labelsXaxis = [], clickDataset = [], blockedDataset = [], openDataset =[], queDataset = [], sentDataset = [], unsubDataset = [];

    for(let i = 0; i < jsonObj.Count; i++){
        let dateMonth = new Date(jsonObj.Data[i].Timeslice).getMonth();
        let dateDay = new Date(jsonObj.Data[i].Timeslice).getDate();

        labelsXaxis.push(month[dateMonth]+" "+dateDay);
        clickDataset.push(jsonObj.Data[i].MessageClickedCount);
        blockedDataset.push(jsonObj.Data[i].MessageBlockedCount);
        openDataset.push(jsonObj.Data[i].MessageOpenedCount);
        queDataset.push(jsonObj.Data[i].MessageQueuedCount);
        sentDataset.push(jsonObj.Data[i].MessageSentCount);
        unsubDataset.push(jsonObj.Data[i].MessageUnsubscribedCount);
    }

    let chartData = {
        labels:labelsXaxis,
        datasets:[
        {
            label: "Clicked",
            fill:false,                  
            backgroundColor: "rgba(69, 178, 176,1)",
            borderColor: "rgba(69, 178, 176,.7)",
            lineTension: 0,
            radius: 5,
            data: clickDataset
        },
        {
            label: "Blocked",
            backgroundColor: "rgba(135, 40, 40,1)",
            borderColor: "rgba(135, 40, 40,.7)",
            fill: false,
            lineTension: 0,
            radius: 5,
            data: blockedDataset
        },
        {
            label: "Opened",
            backgroundColor: "rgba(107, 206, 118,1)",
            borderColor: "rgba(107, 206, 118,0.7)",
            fill: false,
            lineTension: 0,
            radius: 5,
            data: openDataset
        },
        {
            label: "Queued",
            backgroundColor: "rgba(226, 169, 63,1)",
            borderColor: "rgba(226, 169, 63,.7)",
            fill: false,
            lineTension: 0,
            radius: 5,
            data: queDataset
        },
        {
            label: "Sent",
            backgroundColor: "rgba(61, 169, 219,1)",
            borderColor: "rgba(61, 169, 219,.7)",
            fill: false,
            lineTension:0,
            radius: 5,
            data: sentDataset
        },
        {
            label: "Unsubscribed",
            backgroundColor: "rgba(44, 6, 51,1)",
            borderColor: "rgba(44, 6, 51,.7)",
            fill: false,
            lineTension: 0,
            radius: 5,
            data:unsubDataset
        }
        ]
    };

    let option = {
        responsive: true
    };

    let canvas = $("#canvas");

    let chart = new Chart(canvas,{
        type:"line",
        data : chartData,
        options: option
    });
}