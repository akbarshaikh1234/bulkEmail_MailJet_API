<?php

    session_start();

    require '../../vendor/autoload.php';
    require 'keys.php';
    
    use \Mailjet\Resources;
    
    if(isset($_SESSION['user'])){
        global $apiKey; 
        global $apiSecret;

        if(isset($_GET['message'])){        
            sendMail($apiKey,$apiSecret);
        }
    
        if(isset($_GET['fileuploadMailjet'])){
            fileuploadMailjet($apiKey,$apiSecret);
        }

        if(isset($_GET['contactList'])){
            $url="https://api.mailjet.com/v3/REST/contactslist";
            getCurlRequest($apiKey,$apiSecret,$url);
        }

        if(isset($_GET['mailjetListEmails'])){
            $id = intval($_POST['listId']);
            $url="https://api.mailjet.com/v3/REST/contact?contactslist=".$id;
            getCurlRequest($apiKey,$apiSecret,$url);
        }

        if(isset($_GET['addContactList'])){
            $name=$_POST['listName'];
            
            //$data->IsDeleted = false;
            //$data->Name=$name;

            $myJson = json_encode(array('IsDeleted'=>false,'Name'=>$name),JSON_FORCE_OBJECT);
            $url="https://api.mailjet.com/v3/REST/contactslist";

            postCurlRequest($apiKey,$apiSecret,$url,$myJson);
        }
    }else{
        echo "you have not logged in OR you are not registred User";
    }
    
    
    //Mail send function to send bulk message
    function sendMail($apiKey,$apiSecret){

        //global $apiKey; 
        //global $apiSecret;

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
     function fileuploadMailjet( $apiKey,$apiSecret){
         
        $ID_CONTACTLIST = intval($_POST['contactid'],10);
        var_dump($ID_CONTACTLIST);
        if(isset($_FILES["mailjetFile"]['tmp_name'])){
            $filename=$_FILES["mailjetFile"]["tmp_name"];

            $content = file_get_contents($filename);

            $mailjet = new \Mailjet\Client($apiKey,$apiSecret);
            
            $response = $mailjet->post(Resources::$ContactslistCsvdata, ['body' => $content, 'id' => $ID_CONTACTLIST]);

            $ID_DATA = $response->getData();
            $ID_DATA = $ID_DATA['ID'];
            $body = [
                'ContactsListID' => "$ID_CONTACTLIST",
                'DataID' => "$ID_DATA",
                'Method' => "addforce"
            ];
            $response = $mailjet->post(Resources::$Csvimport, ['body' => $body]);
            $response->success() && var_dump($response->getData());
            
        }else{
            echo "Please Select the File to Upload";
        }
        

    }

    function getCurlRequest($apiKey,$apiSecret,$url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            'Authorization: Basic '. base64_encode("$apiKey:$apiSecret"),
            "cache-control: no-cache"
        ), 
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
    }

    function postCurlRequest($apiKey,$apiSecret,$url,$data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            'Authorization: Basic '. base64_encode("$apiKey:$apiSecret"),
            "cache-control: no-cache"
        ), 
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
    }