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