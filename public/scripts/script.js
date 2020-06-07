// $( function() {
    // $( ".datePicker" ).datepicker();
// } );


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
    $( "#slide" ).slideDown( 500 );
    $( "nav" ).slideDown( 500 );
    // $("nav").addClass("flex")
})
var windowWidth = window.innerWidth;
if(windowWidth<=640){
    $("nav").on("click",function(){
        $( "#slide" ).slideUp( 500 );
        $( "nav" ).slideUp( 500 );
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
             $("#session_"+data).remove()               
        })
    }
    else{
        alert("supression annulée");
    }
})
// $(".delProgramme").on("click",function(e){
//     e.preventDefault()
//     if(confirm("Etes vous sûre de vouloir supprimer ce module ?")){
//         let url = $(this).attr("href")
//         let data = $(this).data("id")
//         let session = $(this).data("session")
//         $("#programme_"+data).prepend('<div class="lds-hourglass"></div>')
//         $.get(url,{        
//               data: data,
//               session: session        
//           }).then(function(response){
//              $("#programme_"+data).remove()               
//         })
//     }
//     else{
//         alert("supression annulée");
//     }
// })
$(".delModule").on("click",function(e){
    e.preventDefault()
    if(confirm("Etes vous sûre de vouloir supprimer ce module ?")){
        let url = $(this).attr("href")
        let data = $(this).data("id")
        
        $("#module_"+data).prepend('<div class="lds-hourglass"></div>')
        $.get(url,{        
              data: data      
          }).then(function(response){    
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
$(".errors").slideDown(300).delay(5000).slideUp(300);
$(".success").slideDown(300).delay(5000).slideUp(300);


var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65,13],
n = 0;
$(document).keydown(function (e) {
    if (e.keyCode === k[n++]) {
        if (n === k.length) {
            $(".konami").slideDown(500).delay(1000).slideUp(500)
            n = 0;
            return false;
        }
    }
    else {
        n = 0;
    }
});

// const searchClient = algoliasearch(
//     'latency',
//     '94a2f57396b45e4153113af04823808a'
//   );
  
//   const search = instantsearch({
//     indexName: 'movies',
//     searchClient,
//   });
  
//   search.addWidgets([
//     {
//       init(opts) {
//         const helper = opts.helper;
//         const input = document.querySelector('#stagiaire_ville');
//         console.log(opts)
//         input.addEventListener('input', ({currentTarget}) => {
//           helper.setQuery(currentTarget.value) // update the parameters
//                 .search(); // launch the query
//         });
//       }
//     },
//     {
//       render(options) {
//         const results = options.results;
//         console.log(results)
//         // read the hits from the results and transform them into HTML.
//         document.querySelector('#hits').innerHTML = results.hits
//           .map(
//             hit => `<p>${instantsearch.highlight({ attribute: 'nom', hit })}</p>`
//           )
//           .join('');
//       },
//     }
//   ]);
  
//   search.start();

//   const data_url =
//   "https://raw.githubusercontent.com/algolia/datasets/master/movies/actors.json";

//   function indexData(data_url) {
//     return axios
//       .get(data_url, {})
//       .then(response => {
//         return dataToAlgoliaObject(response.data);
//       })
//       .then(function(response) {
//         return;
//       })
//       .catch(function(error) {
//         console.warn(error);
//       });
//   }

//   function dataToAlgoliaObject(data_points) {
//     var algoliaObjects = [];
//     for (var i = 0; i < data_points.length; i++) {
//       var data_point = data_points[i];
//       var algoliaObject = {
//         objectID: data_point.objectID,
//         name: data_point.name,
//         rating: data_point.rating,
//         image_path: data_point.image_path,
//         alternative_name: data_point.alternative_name
//       };
//       algoliaObjects.push(algoliaObject);
//     }
  
//     return algoliaObjects;
//   }

//   const algoliaClient = algoliasearch(
//     process.env.ALGOLIA_APP_ID,
//     process.env.ALGOLIA_ADMIN_API_KEY
//   );
//   const algoliaIndex = algoliaClient.initIndex("commune");
  
//   function sendDataToAlgolia(algoliaObjects) {
//     return new Promise((resolve, reject) => {
//       algoliaIndex.addObjects(algoliaObjects, (err, content) => {
//         if (err) reject(err);
//         resolve();
//       });
//     });
//   }
