<?php     
      require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/User.php";
      require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/messages.php";
      require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/Database.php";
      if(isset($_POST['search'])){
        exit;
      }


       //delet all messages the value null
	 Messages::Deletmessage('message',"");

 
      
?>
<!doctype html>
<html lang="en">
    <?php require_once "header_home.php" ?>
   <body>
       <?php require_once "navbar_home.php" ?>
       
       <div class="container">
               <?php 
                $alluser=User::get_all_users();
               foreach($alluser as $user):
               ?>

                <div class="card" id="user_<?php echo $user->id ?>">
                    
                            <div class="header">
                                <div class="main">
                                    <div class="image">
                                        <img src="/chat/<?php echo$user->img ?>">
                                        <div class="hover">
                                        </div>
                                    </div>
                                    <h3 class="name"><?php echo$user->username ?></h3>
                                    <?php 
                                    if($user->login==1){?>
                                        <div class="online">online</div>
                                    <?php
                                    }else{?>
                                        <div class="offline">offline</div>

                                    <?php }?>
                                </div>
                            </div>

                            <div class="content">
                                <div class="left">
                                    <div class="about-container">
                                        <h3 class="title">About</h3>
                                        <p class="text"><?php echo$user->about ?></p>
                                    </div>
                                    <div class="buttons-wrap chat_new" >
                                        <div class="follow-wrap">
                                            <div class="follow" >chat new</div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                </div>
        <?php endforeach;
             ?>
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="/chat/js/home.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

  </body>
</html>