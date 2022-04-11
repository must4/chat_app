
document.getElementById('signout').addEventListener("click",function(){
  let sout=new XMLHttpRequest();
    let formatdata=new FormData();
    formatdata.append('signout',1);
    sout.open("POST","/chat/classes/Session.php");
    sout.send(formatdata);
    window.open("http://localhost/chat/?login=1","_self");
})

function eventLisnTocard(){
  var users=document.querySelectorAll(".card"); 
  for(var i=0;i<users.length;i++){
          users[i].addEventListener("click",function(){
            let sendid=new XMLHttpRequest();
            let formatdata=new FormData();
            let iduser=this.getAttribute("id").split("_")[1]; 
           formatdata.append('id',iduser);
          sendid.open("POST","/chat/includes/chat.php");
          sendid.send(formatdata); 
          url="http://localhost/chat/?chat=1&contId="+iduser;
          window.open(url,"_self");
           
      });
  }
} 


let loadContinerHome=setInterval(function(){
  eventLisnTocard();
  setTimeout(function(){ 
    $(".container").load(location.href+"  .container");
  }, 900);
},1000);
window.onload=loadContinerHome;




let eleSearch=document.querySelector('.search');
eleSearch.addEventListener('keyup',function(){
     let search=new XMLHttpRequest();
     let formatdata=new FormData();
     formatdata.append('search',1);
     formatdata.append('username',eleSearch.value)
     search.onreadystatechange=function(){
      if(this.readyState===4 && this.status===200){
        let usercard=document.querySelector('.container');
        usercard.innerHTML= this.responseText; 
         clearInterval(loadContinerHome);

      }
  }
     search.open("POST","/chat/classes/User.php"); 
     search.send(formatdata);
     
})



