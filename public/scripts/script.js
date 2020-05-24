function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()
  
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result)
        }
  
        reader.readAsDataURL(input.files[0])
    }
  }
  $(".modileMenu").on("click",function(e){    
    e.stopPropagation()
    console.log("toto")
    $("nav").addClass("flex")
    $( "#slide" ).slideDown( 500 );
})
var windowWidth = window.innerWidth;
if(windowWidth<=640){
    $("nav").on("click",function(){
        $( "#slide" ).slideUp( 500 );
    })
}
$(".delStagiaire").on("click",function(e){
    e.preventDefault()
    if(confirm("Etes vous sûre de vouloir supprimer ce stagiaire ?")){
        let url = $(this).attr("href")
        let data = $(this).data("id")
        $("#stagiaire_"+data).prepend('<td class="lds-hourglass"></td>')
        $.get(url,{        
              data: data        
          }).then(function(response){
             console.log('toto')
             $("#stagiaire_"+data).remove()
               
        })
    }
    else{
        alert("suppression annulée !")
    }
})
$(".delSession").on("click",function(e){
    e.preventDefault()
    if(confirm("Etes vous sûre de vouloir supprimer cette session ?")){
        let url = $(this).attr("href")
        let data = $(this).data("id")
        $("#session_"+data).prepend('<div class="lds-hourglass"></div>')
        $.get(url,{        
              data: data        
          }).then(function(response){
             console.log('toto')
             $("#session_"+data).remove()               
        })
    }
    else{
        alert("supression annulée");
    }
})