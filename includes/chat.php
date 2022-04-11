
<?php  
     require_once $_SERVER['DOCUMENT_ROOT'].'/chat/includes/header_chat.php';
     require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/User.php";
     require_once $_SERVER['DOCUMENT_ROOT']."/chat/classes/messages.php";

	

	$users_id=User::get_contact_users_id();//get all id for users contact to carrent user
	$last_mess=user::get_last_conv();//get last user contract with carrent user
	$current_user=Session::get_session('id');//get id the carrent users
	$user_active=1;
	global $db;//acces to golobal variable database
	$name_user_chat="select user to chat with him";
	$image_user_chat="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg";
	if(isset($_GET['contId'])){
          //set new id for get last messages when user click in anther contact
		$last_mess= User::get_chat_click(filter_input(INPUT_GET,'contId',FILTER_SANITIZE_NUMBER_INT));
	}
	Messages::sendMessages();//execut when user send messages and relaod messages body
	


	if(isset($_POST['id'])){//send null messages to the user click in home page for get him in chat page
		setcookie("id_click_card",$_POST['id'], time() + (86400 * 30), "/");
	   $sender_id=filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
	   $receiver_id=Session::get_session('id');
	   $time=new DateTime();
		$values=[$sender_id,$receiver_id,'',$time->format('Y-m-d H:i')];
	   Messages::addMessage($values);
   }
   
?>

<a class="logo" href="http://localhost/chat/?home=1"><div class="a">C</div>hat<div class="a">A</div>pp</a>

<div class="container-fluid h-100">
		<div class="row justify-content-center h-100">
		<!-- card contacts---------------------------------------------->
			<div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
		    <!--header card contact-->
			   <div class="card-header">
					<div class="input-group">
						<input type="text" placeholder="Search..." name="" class="form-control search">
						<div class="input-group-prepend">
							<span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
						</div>
					</div>
				</div>
            <!--body card contact-->
				<div class="card-body contacts_body">
					<ui class="contacts">
					   <?php ///get and show users contact///////////////
					  $copy_users_id=$users_id;//for used to get first item 
					 for($i=0;$i<count($users_id);$i++):// foreach($users_contact as $key=>$user_contact):\
							    $first_id=array_shift($copy_users_id);//get first item every time in array users_id
								$users_contact=$db->customeQuery("SELECT * FROM chat.USERS WHERE id=$first_id")[0] ;
					            $user_contact_click=filter_input(INPUT_GET,'contId',FILTER_SANITIZE_NUMBER_INT);
					 ?>

						<li id='chat_<?php echo $users_contact->id?>' class=<?php 
						                                                   if(isset($_GET['contId'])){
																		            	if($users_contact->id==$user_contact_click){
																				                echo 'active'; 
																				                $name_user_chat=$users_contact->username;
																								$image_user_chat=$users_contact->img;
																								$user_active=$users_contact->id;

																	                     }else{
																		              		     echo "";
																	                          }
																		       
																			}else{
																				       if($i==0){
                                                                                            echo 'active'; 
																							$name_user_chat=$users_contact->username;
																							$image_user_chat=$users_contact->img;
																							$user_active=$users_contact->id;
																							Session::redirect('?chat=1&contId='.$users_contact->id);
																				        }else{
																					         echo "";
																						}
																		   }
																		  ?>>
							<div class="d-flex bd-highlight">
								<div class="img_cont">
									<img src=<?php echo $users_contact->img;?> class="rounded-circle user_img">
								</div>
								<div class="user_info">
									<span><?php echo $users_contact->username; ?> </span>
									<p>
									   <?php	if($users_contact->login==1){
											      echo"online";
										       }else{
												   $new_time=date("Y-m-d H:i");
												   $time_left=$users_contact->time_left;
												   $tim1=new DateTime($new_time);
												   $tim2=new DateTime($time_left);
                                                   $time_left_day=$tim1->diff($tim2)->format("%d");
                                                   $time_left_hours=$tim1->diff($tim2)->format("%H");
                                                   $time_left_minut=$tim1->diff($tim2)->format("%d");
												   if($time_left_day!=0&&$time_left_hours!=0){

													   echo($tim1->diff($tim2)->format("lest login : %d day %H houre %I min"));
												   }else if($time_left_hours!=0){
													echo($tim1->diff($tim2)->format("lest login : %H houre %I min"));
												   }else{
													echo($tim1->diff($tim2)->format("lest login : %I min"));
												   }
										      } ?>
									 </p>
								</div>
							</div>
						</li>

					<?php endfor ;?>
					</ui>
				</div>
			<!--footer cart contaact-->
		    	<div class="card-footer"></div>

			</div></div>
          
 
       <!-- card messages----------------------------------------------->
		<div class="col-md-8 col-xl-6 chat">
		    <div class="card cardmess">
		    	<!-- card header messages-->
					<div class="card-header msg_head mhed">
						
						<div class="d-flex bd-highlight">
							
							<div class="img_cont">
								<img src=<?php echo $image_user_chat ?> class="rounded-circle user_img"> 
							</div>
							
							<div class="user_info">
								<span><?php echo $name_user_chat?></span>
								<p class='nmess'><?php 
								//get all number all messages between current user and activ user
								    $number_messages=$db->customeQuery("SELECT COUNT(id) as numbermessag FROM chat.messages  WHERE (sender_id=$current_user AND reciever_id=$user_active) OR (sender_id=$user_active  AND reciever_id=$current_user )")[0];
									$num_messs= $number_messages->numbermessag;
                                     
									if($_COOKIE["id_click_card"]==$user_active){
                                        echo $num_messs-1;
									}else{
                                          echo $num_messs;
									}
								?>  Messages</p>
							</div>
							
						</div>
						
						
					</div>
               <!-- card  boody messages-->
			    <div class="allmsg" id="msgcont">
			      <?php 
				       
				       foreach($last_mess as $mess): //-----start foreach for get messages
					     if($mess->sender_id==$current_user||$mess->reciever_id==$current_user){   //test if get messages are with current user?>
					
					<div class="card-body msg_card_body">
						<div class="d-flex  <?php echo $mess->sender_id==$current_user? 'justify-content-end mb-4': 'justify-content-start' ?>">
							<?php  
							if($mess->message==null){
                                 
							}
							else if($mess->sender_id!==$current_user ){
								//image first or message
							?>  
						            <div class="img_cont_msg">
								    <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg">
							       </div>
								      <div class="<?php echo $mess->sender_id==$current_user? 'msg_cotainer_send': 'msg_cotainer' ?>">
							           <?php echo $mess->message  //show messages?>
							        	<span class="msg_time"><?php $dat=new DateTime();
									                                if($dat->format("Y-m-d")==explode(" ",$mess->datetime)[0]){
																     	echo "today  ". explode(" ",$mess->datetime)[1];
															      	}else{
																    	echo  $mess->datetime;
															    	}
										
										                        ?></span>
						             	</div>
							<?php
							}
							else{?>
								<div class="<?php echo $mess->sender_id==$current_user? 'msg_cotainer_send': 'msg_cotainer' ?>">
								   <?php echo $mess->message  //show messages?>
								    <span class="msg_time"><?php $dat=new DateTime();
									                            if($dat->format("Y-m-d")==explode(" ",$mess->datetime)[0]){
																	echo "today  ". explode(" ",$mess->datetime)[1];
																}else{
																	echo  $mess->datetime;
																}
								                         	?></span>
								  </div>
								  <div class="img_cont_msg">
								    <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg">
							       </div>
								
					      <?php
							}/////end if---------
							?>
						</div>
					</div>
                    <?php }//end if
				        endforeach;//----------- end foreach ------------------------------------------------>
						?>
				</div>
			   <!-- card footer messages-->
					<div class="card-footer" id="test">
						<div class="input-group">

							<div class="input-group-append">
								<span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
							</div>

							<textarea id="message" name="" class="form-control type_msg" placeholder="Type your message..."></textarea>
							
							<div class="input-group-append">
								<span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
							</div>

						</div>

					</div>
			</div>
		</div>
			
		</div>
	</div>

<?php
   require_once $_SERVER['DOCUMENT_ROOT'].'/chat/includes/footer.php';
?>