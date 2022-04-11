//select all users in contact
let contact=document.querySelectorAll(".contacts li");
contact_lenght=contact.length
for(let i=0;i<contact_lenght;i++){
    //add event listn in every contact
    contact[i].addEventListener("click",function(){
        let get_id_contact=contact[i].getAttribute("id").split("_")[1];
        //add parametr id  user in  url when click it  
        url="?chat=1&contId="+get_id_contact;
          window.open(url,"_self");
    });
}



//set scroll in the end every time load
let scrollt;
function scrolldown(){
    divMessage=document.getElementById("msgcont");
    divMessage.scrollTop = divMessage.scrollHeight;
    scrollt=divMessage.scrollTop;
}


//add event listn in button send message------------------------

let button_send=document.querySelector(".send_btn");
button_send.addEventListener("click",function(){
    let input_message =document.querySelector(".type_msg");
    let get_id_url=location.href.split('contId=')[1];
    let get_id_user_activ=document.querySelector("li.active").getAttribute("id").split("_")[1];
    
    if(get_id_url==get_id_user_activ){
        let formatdata=new FormData();
        formatdata.append('id',get_id_user_activ);
        formatdata.append('the_messages',input_message.value);
        formatdata.append('message_send',1);
        var test =new XMLHttpRequest();
        /*test.onreadystatechange=function(){
            if(this.readyState===4 && this.status===200){
                //console.log(this.responseText);
            }
        }*/
        test.open("POST","classes/messages.php");
        
           
           if(input_message.value.trim()!==""){
               test.send(formatdata);
            }
            input_message.value="";//clear input  when send message
        }
        setTimeout(function(){
            $(".nmess").load(location.href+" .nmess");
            $(".allmsg").load(location.href+" .allmsg");
            scrolldown();
          }, 100);
          setTimeout(function(){
            scrolldown();
          }, 200);
        
    });
    //reload card evry 200 ms

    window.onload=setInterval(function(){
        $(".nmess").load(location.href+" .nmess");
        $(".allmsg").load(location.href+" .allmsg");
        divMessage=document.getElementById("msgcont");
        if(scrollt==divMessage.scrollTop){//scroll down when just i don't go to up 
            scrolldown();
        }
      }, 200);
      window.onload=scrolldown;


 
   
