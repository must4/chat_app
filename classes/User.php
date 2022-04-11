<?php 
       require_once 'Database.php';
       require_once 'Session.php';
     class User{
         private static $tablName='users';
         private static $column=['username','email','password','about','img','time_left','login'];
        //adduser
        public static function addUser(array $values){
               global $db;
               return $db->insertData(self::$tablName,self::$column,$values);
        }
       //update user
        public static function UpdateUser(array $values,$conditionColumn,$conditionValues){
                    global $db;
                    return $db->updatData(self::$tablName,self::$column,$values,$conditionColumn,$conditionValues);
        }
        //delet user
        public static function DeletUser($conditionColumn='',$conditionValues=''){
                  global $db;
                  return $db->deletData(self::$tablName,$conditionColumn,$conditionValues);
        }
        //register user
       public static function register(){
           if(isset($_POST['register'])){
               global $db;
               //take input
               $username=filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
               $email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
               $password=filter_input(INPUT_POST,'password');
               $confirm_password=filter_input(INPUT_POST,'confirm_password');
               $about=filter_input(INPUT_POST,'about');
               //uplode file
               $name_file=$_FILES['upload']['name'];
               $image_uploded_path='images/'.$name_file;
               move_uploaded_file($_FILES['upload']["tmp_name"],$image_uploded_path);
               $email_test=$db->SelectData('users','email',$email);
               //test correct input and add user
               if($email_test){
                Session::set_session('register_errer','email is exeist set anther email');
                 } 
               else if($_FILES['upload']['errer']==4){
                    Session::set_session('register_errer','no image uplode');
                 }
               else if(strlen($username)<5 || strlen($username)>20){
                   Session::set_session('register_errer','username most be between 5 and 20');
                }else if(strlen($about)>100||strlen($about)<10){
                    Session::set_session('register_errer','text whene talking about youself most be between 10 and 100');
                 }else if(strlen($password)<8 || strlen($password)>20){
                    Session::set_session('register_errer','password most be between 8 and 20');
                }else if($password!==$confirm_password){
                    Session::set_session('register_errer','password not equil');
                }else{
                      // $hashid_password=password_hash($password,PASSWORD_DEFAULT);
                       $img_default='images/img_default.jpg';
                       $user_array=[$username,$email,$password,$about,$image_uploded_path,'',1];
                       $user_id=self::addUser($user_array);
                       if(isset($user_id)){
                           Session::set_session('id',$user_id);
                           Session::set_session('username',$username);
                           Session::set_session('email',$email);
                           Session::redirect('?home=1');
                        }else{
                                Session::set_session('register_errer','register not match ');
                                
                            }
                    }
           }
           //end if
       }
       //end register
       //login function 
       public static function login(){
           if(isset($_POST['login'])){
                 $email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
                 $password=filter_input(INPUT_POST,'password');
                 
                 global $db;
                 
                 //select data in table users
                 
                 //test if select email or password is not null $select_email&&$select_password
                 if(!empty( $db->SelectData('users','email',$email)[0])){
                        $select_email =$db->SelectData('users','email',$email)[0];
                        //test if email and password is exist in database
                           if($select_email->email==$email && $select_email->password==$password){
                               Session::set_session('id',$select_email->id);
                               Session::set_session('username',$select_email->username);
                               Session::set_session('email',$select_email->email);
                               $db->updatData('users',['login'],[1],'id',$select_email->id);
                              Session::redirect('?home=1');
                            }else{
                                Session::set_session('login_errer','email or password is not correct');
                                Session::redirect('?login=1');
                            }
                    }else{  
                             Session::set_session('login_errer','email or password is not correct');
                    } 
           }
           //end if
       }
       //end login
       //get all users contact befor
       public static function get_contact_users_id(){
           global $db;
           $current_user=Session::get_session('id');
           //get all messages the current user whether it's like sender or reciever
           $messages=$db->customeQuery("SELECT * FROM chat.messages WHERE sender_id=$current_user OR reciever_id=$current_user");//$db->SelectData("messages",'sender_id',Session::get_session('id'))
           
           $filter_id=array();//array for remove reppet id the user 
           
           foreach($messages as $value){
               if($value->reciever_id==$current_user){
                array_push($filter_id, $value->sender_id);
               }else{
                   array_push($filter_id, $value->reciever_id);
               }
            }
 
            $filter_id=array_reverse($filter_id);//for remove first deplecated values
            $filter_id=array_unique($filter_id);//remove deplecated
            return $filter_id;
            
           /* $ids='(';//for add ids to query dirctly
            $count_filter_id=count($filter_id);

            for($i=0;$i<$count_filter_id;$i++){
                if($i !== $count_filter_id-1){ 
                    $ids.=array_shift($filter_id).',';
                }else{
                    $ids.=array_shift($filter_id).')';
                }
            }
            
            $query="SELECT * FROM chat.USERS WHERE id IN $ids";
          $users= $db->customeQuery($query);
          return $users;*/
       }

       //get  last user converstion and last messages
        public static function get_last_conv(){
            $id_user=Session::get_session('id');
            //select all chat current user
            $Query="SELECT * FROM chat.messages WHERE sender_id=$id_user || reciever_id=$id_user ORDER BY id DESC  LIMIT 1";
            global $db;
            //get last converstion
            $last_con=$db->customeQuery($Query); 
            if($last_con[0]){
                if($id_user==$last_con[0]->sender_id){//if current user is sand last message 
                    //get all messages the reciever last message
                    $reci_id=$last_con[0]->reciever_id;
                    $query2="SELECT * FROM chat.messages WHERE sender_id=$reci_id OR reciever_id=$reci_id";
                    return $db->customeQuery($query2);
                    
                    // $query1="SELECT * FROM chat.users WHERE id=$last_con->reciever_id";//get reciever 
                      //$db->customeQuery($query1)[0],
    
                }else{//if current user is reciever last message
                    //get all messages the sender  last message
                    $send_id=$last_con[0]->sender_id;
                    $query2="SELECT * FROM chat.messages WHERE sender_id=$send_id OR reciever_id=$send_id";
                    return $db->customeQuery($query2);
                    
                    // $db->customeQuery($query1)[0],
                    //$query1="SELECT * FROM chat.users WHERE id=$last_con->sender_id";
                }
            }
            
        }

        //get messages when click in contact user
        public static function get_chat_click($id_user_click){
             global $db;
             $user_login=Session::get_session("id");
             $Query="SELECT * FROM chat.messages WHERE (sender_id=$id_user_click AND reciever_id=$user_login) OR 
             (reciever_id=$id_user_click AND sender_id=$user_login) ORDER BY id";
              return $db->customeQuery($Query);
              
        }

        public static function get_all_users(){
            global $db;
            $user_curunt=Session::get_session('id');
            $query="SELECT * FROM chat.users WHERE id!=$user_curunt";
            return $db->customeQuery($query);
        }
        public static function get_user_search(){
             global $db;
           if(isset($_POST['search'])){
                $id_currnt_user=Session::get_session('id');
               $username=$_POST['username'];
              $query="SELECT * FROM chat.users WHERE id not like $id_currnt_user and (username like '% $username%' OR username like '$username%')";
               $alluser= $db->customeQuery($query);
          
                  foreach($alluser as $user):?> 
                  <div class="card" >
            
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
                                        <p class="text">Lorem Ipsum is simply text of the printing and types industry.</p>
                                    </div>
                                    <div class="buttons-wrap chat_new" id="user_<?php echo $user->id ?>">
                                        <div class="follow-wrap">
                                            <div class="follow" >chat new</div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                  </div>
           <?php endforeach;  }  
        }
    
    }

    //end class user


    User::get_user_search();
?>