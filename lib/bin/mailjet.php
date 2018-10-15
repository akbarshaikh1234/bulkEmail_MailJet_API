<?php
    require '../../vendor/autoload.php';

    use \Mailjet\Resources;

    if(isset($_GET['message'])){        
        sendMail();
    }

    function sendMail(){
        $apiKey ='f87e1a5f0fa2732480bc17bc415136e6';
        $apiSecret ='3797f2781ddcaae1615ccd4d3358a6c3';

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