<?php 
                  require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/Session.php";
                  require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/Database.php";
             ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container_header ">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand text-primary " href="http://localhost/chat/?home=1">chatApp</a>
      <ul class="navbar-nav">
        <li class= >
          <a class="nav-link  " aria-current="page" href="http://localhost/chat/?home=1">Home</a>
        </li>
        <li class= >
          <a class="nav-link" href="http://localhost/chat/?chat=1">chat <sub style="color:red">
             <?php 
                $messagesCount=0;
                global $db;
                $idUser=Session::get_session('id');
                $Query="SELECT id FROM chat.users where id not like $idUser";
                $idUsers=$db->customeQuery($Query);
                foreach($idUsers as $idus){
                    $Query="SELECT * from chat.messages where (sender_id = $idus->id and reciever_id = $idUser) or  (reciever_id = $idUser and sender_id = $idus->id ) order by id DESC";
                    $lastmessage=$db->customeQuery($Query);
                        if(isset($lastmessage) ){ 
                          if($lastmessage!=null){
                              if($lastmessage[0]->reciever_id==$idUser){
                                $messagesCount++;
                              }
                          }
                        }
                    }
                echo "+".$messagesCount?>
          </sub></a>
        </li>
        <form class="d-flex"> 
        <input class="form-control me-2 search" type="search" placeholder="Search" aria-label="Search">
        </form>
        <li  > 
          <a class="nav-link" id="signout" >
            sign out(<div class='username'>
            <?php 
                   if(! session_id()){
                     Session_start();
                    }
                   echo $_SESSION["username"];
          ?>
            </div>)</a>
        </li>
      </ul>
      
    </div>
  </div>
</nav>