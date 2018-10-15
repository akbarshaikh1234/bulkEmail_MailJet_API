<?php

    require('Database.php');    
    
    //call to admin login function
    if(isset($_GET['adminLogin'])){
        adminLogin();
    }

    //call to user subscribing to newsletter function
    if(isset($_GET['subscribeInsert'])){
        subscribeInsert();
    }

    //call to logout function
    if(isset($_GET['logout'])){
        logout();
    }
    //call to csv file upload by admin
    if(isset($_GET['fileupload'])){
        fileupload();
    }

    //function defination for admin login 
    function adminLogin(){
        $bool;
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $db = new Database();
        

        $result = $db->getRow('SELECT username,pass FROM admin WHERE username=?',[$email]);

        if(password_verify($pass,$result['pass'])){
            session_start();
            $_SESSION['user'] = $result['username'];
            $bool = TRUE;
            echo $bool;
        }else{
            $bool = FALSE;
            echo $bool;
        }

        $db->disconnect();
    }

    //user subscribing to newsletter
    function subscribeInsert(){
        $db = new Database();
        $email = $_POST['email'];
        $result = $db->getRow('SELECT * from subs where email = ?',[$email]);

        if($result <= 0 && $email != NULL){
            $insertStat = $db->insertRow('INSERT INTO subs (email) VALUES(?)',[$email]);

            if($insertStat){
                echo "Thank you for Subscribing to our news letter";
            }
        }else{
            echo "You have already subscribed to our newsLetter";
        }
        $db->disconnect();
    }


    // logging out the admin
    function logout(){

        session_start();
        session_destroy();

        header('location:../../index.php');
    }

    function fileupload(){
        $db = new Database();
        $filename=$_FILES["file"]["tmp_name"];

        if($_FILES["file"]["size"] > 0){
            $file = fopen($filename, "r");
            
            while(!feof($file)){
              $data = fgetcsv($file);
              $email = $data[0];
    
              $result = $db->getRow('SELECT * FROM subs WHERE email = ?',["$email"]);
    
              if($result <= 0 && $email !=NULL){
                $db->insertRow('INSERT INTO subs (email)VALUES(?)',["$email"]);
              }
                    
            }              
            fclose($file);              
        }

        $db->disconnect();

        header('location:../../admin/index.php');
    }