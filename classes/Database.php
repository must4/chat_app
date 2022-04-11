<?php   
   class Database{
      //connection to database
      private $PdoConniction;
     
      public function __construct(){

         try{
            $user='root';
            $password='';
            $host='mysql:host=localhost;dbmane=chat';
            $option=[
               PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
            ];

            $this->PdoConniction=new PDO($host,$user,$password,$option);
   
         }catch(PDOException $e){
            echo $e->getMessage();
         }
      }
      //fonction select data in table

      public function SelectData($table,$col='',$val=''){

            $quiry="SELECT * FROM chat.$table";

            if($col !="" && $val != ""){

               $quiry .=" where $col =:val";
               //prepare sql and pind parameter
               $stm=$this->PdoConniction->prepare($quiry);
               $stm->bindParam(':val',$val);
               $stm->execute();
               
            }else{
               $stm=$this->PdoConniction->prepare($quiry);
               $stm->execute();
            }
            return $stm->fetchAll();
      }
      //function insert data to table 
   public function insertData($table,$columns,$values){
      //implod:array to string
      $columnsToStr=implode(',',$columns);
      $query="INSERT INTO chat.$table ($columnsToStr) VALUES(?";
      for($i=0;$i<count($values)-1;$i++){
          $query .= ",?";
      }
      $query .= ")";
      
      $st=$this->PdoConniction->prepare($query);
      foreach($values as $key=>$val){
           $st->bindValue(++$key,$val);
      }
         if($st->execute()){
            //lastinsertid return last id insirted for useed in anther place like class session
            return $this->PdoConniction->lastInsertId();
         }
         return false;
   }
   ///function updat data of table
   public function updatData($table,$columns,$values,$conditionColumn='',$conditionValues=''){
        $query="UPDATE chat.$table set $columns[0]=?";
        for($i=0;$i<count($columns);$i++){
           if($i!=0){

              $query.=",$columns[$i]=? ";
           }
        }
        if($conditionColumn!=''&&$conditionValues!=''&&is_string($conditionValues)==true){
           $query.=" WHERE $conditionColumn='$conditionValues'";

        }else if($conditionColumn!=''&&$conditionValues!=''){

          $query.=" WHERE $conditionColumn=$conditionValues";
        }
        $stt=$this->PdoConniction->prepare($query);
        foreach($values as $key=>$vul){
           $stt->bindValue(++$key,$vul);
         } 
        return $stt->execute();

   }
  //function delet data of table
   public function deletData($table,$conditionColumn,$conditionValues){
         $query="DELETE FROM chat.$table WHERE $conditionColumn=:val";

        $st=$this->PdoConniction->prepare($query);
        $st->bindParam(':val',$conditionValues);
        $st->execute();
   }

   //custome Query
   public function customeQuery($query,$values=[]){
      
      if(isset($values)&&$values !==''){
          $stmt=$this->PdoConniction->prepare($query);
           $stmt->execute($values);
           return $stmt->fetchAll();
      }
      else{
             $stmt= $this->PdoConniction->query($query);
             return $stmt->fetchAll();
      }
   } 

   }//end class database

   //declare once and use him miltip time in anther file 
   $db=new Database();
?>