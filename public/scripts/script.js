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
    $( "#slide" ).slideDown( 500 );
    // $("nav").addClass("flex")
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
$(".delProgramme").on("click",function(e){
    e.preventDefault()
    if(confirm("Etes vous sûre de vouloir supprimer ce module ?")){
        let url = $(this).attr("href")
        console.log(url)
        let data = $(this).data("id")
        let session = $(this).data("session")
        $("#programme_"+data).prepend('<div class="lds-hourglass"></div>')
        $.get(url,{        
              data: data,
              session: session        
          }).then(function(response){
             console.log('toto')
             $("#programme_"+data).remove()               
        })
    }
    else{
        alert("supression annulée");
    }
})
$(".delModule").on("click",function(e){
    e.preventDefault()
    if(confirm("Etes vous sûre de vouloir supprimer ce module ?")){
        let url = $(this).attr("href")
        let data = $(this).data("id")
        
        $("#module_"+data).prepend('<div class="lds-hourglass"></div>')
        $.get(url,{        
              data: data      
          }).then(function(response){
             console.log('toto')
            
             $("#module_"+data).remove()               
        })
    }
    else{
        alert("supression annulée");
    }
})
// $("input[type='checkbox']").on("click",function(){
//     data = $(this).val()
//     session = $(this).data('session')
//     stagiaire = $(this).data('stagiaire')
//     text = $("#"+stagiaire).html()
//     $("#"+stagiaire).prepend('<div class="lds-hourglass"></div>')
//     $.get('/session/addStagiaireSess',{        
//         id: session ,    
//         data: data,
//     }).then(function(response){
//         console.log(response)
//         if(response == 'true'){
           
//             $("#inscrit").append("<tr>"+text+"</tr>")
//         }
//         if(response === 'false') {
//             $("#nonInscrit").append("<tr>"+text+"</tr>")
//         }                    
//         $("#"+stagiaire).remove()
//   })
// })
$(".button").on("click",function(){
    $(this).prepend('<div class="lds-hourglass"></div>')
})
$(".errors").slideDown(300).delay(5000).slideUp(300)
$(".success").slideDown(300).delay(5000).slideUp(300)


var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
n = 0
$(document).keydown(function (e) {
    if (e.keyCode === k[n++]) {
        if (n === k.length) {
            $(".konami").slideDown(500).delay(1000).slideUp(500)
            n = 0
            return false;
        }
    }
    else {
        n = 0;
    }
})