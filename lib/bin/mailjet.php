<?php
    require '../../vendor/autoload.php';
    require 'keys.php';
    
    use \Mailjet\Resources;
    

    if(isset($_GET['message'])){        
        sendMail();
    }

    if(isset($_GET['fileuploadMailjet'])){
        fileuploadMailjet();
    }
    
    //Mail send function to send bulk message
    function sendMail(){
        
        global $apiKey; 
        global $apiSecret;

        $mailjet = new \Mailjet\Client($apiKey,$apiSecret);

        $json = [];
        $emails = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        for($i = 0; $i < count($emails); $i++){
                array_push($json,['Email' => $emails[$i]]);
        }

        $body = [
            'Messages' => [
                [
                    'FromEmail' => "akbarshaikh.dev@gmail.com",
                    'FromName' => "Akbar Shaikh",
                    'Recipients' => $json,
                    
                    'Subject' => $subject,
                    'Text-part' => $message,
                    'Html-part' => "<h3>$message</h3><br />Please Do not Reply to This Mail"
                ]

            ]
        ];

        $response = $mailjet->post(Mailjet\Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    
        return 'Error: ' . print_r($response->getStatus(), true);

    }

     //file upload function to upload csv contacts to MailJet contact list on server
     function fileuploadMailjet(){

        $filename=$_FILES["file"]["tmp_name"];

        $content = file_get_contents($filename);

        $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'));
        $response = $mj->post(Resources::$ContactslistCsvdata, ['body' => $content, 'id' => $ID_CONTACTLIST]);
        $response->success() && var_dump($response->getData());

    }