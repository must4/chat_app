<?php 
require_once 'Database.php';
require_once 'Session.php';
     class Messages{
         private static $tablName='messages';
         private static $column=['sender_id','reciever_id','message','datetime'];
        //add message
        public static function addMessage(array $values){
               global $db;
               return $db->insertData(self::$tablName,self::$column,$values);
        }
       
        //delet message
        public static function Deletmessage($conditionColumn='',$conditionValues=''){
                  global $db;
                  return $db->deletData(self::$tablName,$conditionColumn,$conditionValues);
        }
        
        //delet all message
        public static function deletAllMessage($sender_id,$rciever_id){
            global $db;
            $tablNam=self::$tablName;
            $Query="DELETE FROM chat.$tablNam WHERE sender_id = :s AND reciever_id = :r";
            return $db->customeQuery($Query,[':s'=>$sender_id,':r'=>$rciever_id]);
        }
        
        //send message function
        public static function sendMessages(){
            if(isset($_POST['message_send'])){
                $sender_id=Session::get_session("id");
                $the_message='';
                 $the_message=filter_input(INPUT_POST,'the_messages',FILTER_SANITIZE_STRING);
                $receiver_id=filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
                $time=new DateTime();
                $values=[$sender_id,$receiver_id,$the_message,$time->format('Y-m-d H:i')];
                    self::addMessage($values);
            }
        }
        

     }
       
     //execet function sendMessages when user click button send
     Messages::sendMessages();
     

?>