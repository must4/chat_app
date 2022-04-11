<?php 
      require_once 'Database.php';
    class Session{
                 
        public static function  start_session(){
                  if(! session_id()){
                      Session_start();
                  }
        }
        public static function set_session($key,$value){
                     self::start_session();
                      $_SESSION["$key"]=$value;
        }
        public static function get_session($key){
                   self::start_session();
                  return  $_SESSION["$key"];
        }
        public static function destroy_session(){
                    self::start_session();
                    session_destroy();
        }
        public static function unset_session($key){
            self::start_session();
            session_unset();
        }
        public  static function redirect($location){
            header("Location:$location");
            exit;
        }
        public static function session_IsExist($key){
            self::start_session();
            return isset($_SESSION[$key]);
        }
        public static function logout(){
            if(isset($_POST['signout'])){
                global $db;
                $time=new DateTime();
                $time_logout=$time->format('Y-m-d H:i');
                $db->updatData('users',['login'],[0],'id',self::get_session('id'));
                $db->updatData('users',['time_left'],[$time_logout],'id',self::get_session('id'));
               self::destroy_session();}
               
        }
    }
      Session::logout();
?>