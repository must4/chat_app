<?php
     require_once "includes/header_chat.php";
     require_once 'classes/Session.php';

     //test which  page user geting 
     //if get login page
     if(isset($_GET['login'])){
          //test if user already login
          if(Session::session_IsExist('id')){
               Session::redirect("includes/home.php");
          }else{  
               $id=filter_input(INPUT_GET,'login',FILTER_SANITIZE_NUMBER_INT);
               if($id){
                    include "includes/login.php";
                    
               }
          }
     }
     //if get register page
     else if(isset($_GET['register'])){
          //test if user already register
          if(Session::session_IsExist('id')){
               Session::redirect("includes/home.php");
          }else{
               $id=filter_input(INPUT_GET,'register',FILTER_SANITIZE_NUMBER_INT);
               if($id){
                    include "includes/register.php";
               }
          }
     }
     //if get home page
     else if(isset($_GET['chat'])){
          if(!Session::session_IsExist('id')){
                Session::redirect('?login=1');

          }else{

               $id=filter_input(INPUT_GET,'chat',FILTER_SANITIZE_NUMBER_INT);
               if($id){
                    include "includes/chat.php";
               }
          }
     }
     //page geting not home or login or register
     else{
          if(Session::session_IsExist('id')){
               Session::redirect("includes/home.php");
           }else{ 
            include "includes/login.php";
           }
     }
     require_once "includes/footer.php";
?>
     

         
