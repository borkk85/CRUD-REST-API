


//POST REQUEST

// $(document).ready(function(){
//   $('#saveBtn').click(function(e){
//       e.preventDefault();

//       //serialize form data
//       var url = $('form').serialize();

//       //function to turn url to an object
//       function getUrlVars(url) {
//           var hash;
//           var myJson = {};
//           var hashes = url.slice(url.indexOf('?') + 1).split('&');
//           for (var i = 0; i < hashes.length; i++) {
//               hash = hashes[i].split('=');
//               myJson[hash[0]] = hash[1];
//           }
//           return JSON.stringify(myJson);
//       }

//       //pass serialized data to function
//       var test = getUrlVars(url);
      
//       //post with ajax
//       $.ajax({
//           type:"POST",
//           url: "/api/post/create.php",
//           data: test,
//           ContentType:"application/json",

//           success:function(){
//               alert('successfully posted');
//           },
//           error:function(){
//               alert('Could not be posted');
//           }

//       });
//   });
// });

// $(document).ready(function() {
//   $("#apiform").validate({
//     messages: {
//       sku: {
//         required: "Please, enter valid SKU"
//       },
//       name: {
//         required: "Please, enter product name",
//       },
//       price: {
//         required: "Please, enter price"
//       },
//       size: {
//         required: "Please, provide the size of the DVD"
//       },
//       weight: {
//         required: "Please, provide the weight"
//       },
//       height: {
//         required: "Please, provide the required dimension"
//       },
//       length: {
//         required: "Please, provide the required dimension"
//       },
//       width: {
//         required: "Please, provide the required dimension"
//       }
//     }
//   });
  
//     $("#saveBtn").click(function() {
//       if(!$("#apiform").validate()) {
//         return false;
//       } else {
//         $("#apiform").submit()

        
//       }
//     })
//     //serialize form data
   
//     var url = $("form").serialize();

//     //function to turn url to an object
//     function getUrlVars(url) {
//       var hash;
//       var myJson = {};
//       var hashes = url.slice(url.indexOf("?") + 1).split("&");
//       for (var i = 0; i < hashes.length; i++) {
//         hash = hashes[i].split("=");
//         myJson[hash[0]] = hash[1];
//       }
//       return JSON.stringify(myJson);
//     }

//     //pass serialized data to function
//     var test = getUrlVars(url);
   

//     //post with ajax
    
//     $.ajax({
//       type: "POST",
//       url: "/api/post/create.php",
//       data: test,
//       ContentType: "application/json",
//       success: function() {
//         alert("successfully posted");
//       },
//       error: function() {

//         alert("SKU already exists");
//       }
//     });
    
//   });
    



$(document).ready(function () {
  // $("#apiform").validate({
  //   rules: {
  //     sku: {
  //         required: {
  //           depends:function(){
  //             $(this).val($.trim($(this).val()));
  //             return true;
  //           }
  //         },
  //         minlength: 9,
  //     },
  //     name: {
  //         required: true,
  //         maxlength: 20,
  //     },
  //     price: {
  //         required: true,
  //         maxlength: 5,
  //     },
  //     productType: {
  //       required: true,
  //     },
  //     size: {
  //         required: true,
  //         maxlength: 4,
  //     },
  //     weight: {
  //         required: true,
  //         maxlength: 4,
  //     },
  //     height: {
  //         required: true,
  //         maxlength: 3,
  //     },
  //     width: {
  //         required: true,
  //         maxlength: 3,
  //     },
  //     length: {
  //         required: true,
  //         maxlength: 3,
  //     }
  //     },
  //     messages: {
  //       sku: {
  //         required: "Please, enter valid SKU",
  //         minlength: "Please enter at least 9 characters"
  //       },
  //       name: {
  //         required: "Please, enter valid name",
  //         maxlength: "Please enter "
  //       },
  //       price: {
  //         required: "Please, enter price",
  //         maxlength: ""
  //       },
  //       productType: {
  //         required: "Please select an option from the list",
  //       },
  //       size: {
  //         required: "Please, provide the size of the DVD",
  //         maxlength: ""
  //       },
  //       weight: {
  //         required: "Please, provide the weight",
  //         maxlength: ""
  //       },
  //       height: {
  //         required: "Please, provide the required dimension",
  //         maxlength: ""
  //       },
  //       width: {
  //         required: "Please, provide the required dimension",
  //         maxlength: ""
  //       },
  //       length: {
  //         required: "Please, provide the required dimension",
  //         maxlength: ""
  //       }
  //     }
  //   });

      $("#saveBtn").click(function (e) {
      e.preventDefault();
      
      if (!$("#apiform").valid()) {
        return false 
      } else {
          
              //serialize form data
    var url = $("form").serialize();

    //function to turn url to an object
    function getUrlVars(url) {
      var hash;
      var myJson = {};
      var hashes = url.slice(url.indexOf("?") + 1).split("&");
      for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split("=");
        myJson[hash[0]] = hash[1];
      }
      return JSON.stringify(myJson);
    }
         
    //pass serialized data to function
    var test = getUrlVars(url);

    //post with ajax
    $.ajax({
      type: "POST",
      url: "/api/post/create.php",
      data: test,
      contentType: "application/json; charset=UTF-8",
      success: function (data) {
        console.log(data);
        alert("successfully posted");
      },
      error: function (data) {
        console.log(data)
        alert('unsuccessfully posted');
      },
    });

      };
  
  });

});

//GET REQUEST

document.addEventListener("DOMContentLoaded", function () {
  window.onload = function () {
    async function getUsers() {
        let url = '../api/post/read.php';
        try {
            let res = await fetch(url);
            return await res.json(); 
        } catch(error) {
                console.log(error);
            }
    }
    async function renderUser() {
        let users = await getUsers(); 
        let html = ``;

        users.forEach(user => {
            let htmlSegment = `
                <table class="box">
                    <tr> 
                    <th> <input type='checkbox' class='checkbox' name='checkbox' data-id=${user.id}> </th>
                    <td style="display: none;">  ${user.id}</td>            
                    <td>  ${user.sku}</td>
                    <td>  ${user.name}</td>
                    <td>  ${user.price}</td>
                    ${user.size ? `<td> Size: ${user.size} $ </td>` : ""} 
                    ${user.weight ? `<td> Weight: ${user.weight}  Kg</td>` : "" }
                    ${user.height ? `<td>  Height: ${user.height} CM</td>` : ""}
                    ${user.length ? `<td>  Length: ${user.length} CM</td>` : ""}
                    ${user.width ? `<td>  Width: ${user.width} CM</td>` : ""}
                    </tr>
                </table>`;

                html += htmlSegment;
        });

        let container = document.querySelector('.message');
        container.innerHTML = html;
    }
    renderUser();
  };
});


//DELETE

$(document).ready(function () {
  $("#deleteBtn").click(function (e) {
    e.preventDefault();


    var val = [];
    
    $(':checkbox:checked').each(function() {
      val.push($(this).attr('data-id'));
    });
     
    if (val.length === 0) {
      alert("Please select at least one checkbox");
    } else {

    $.ajax({
      type: "DELETE",
      url: "/api/post/delete.php",
      data:{'val':val},
      ContentType:"application/json; charset=UTF-8",

      success:function(data){
          alert('successfully posted');
      },
      error:function(data){
          alert('Could not be posted');
      }
    
    });
   };
  });
});


// document.addEventListener('DOMContentLoaded',function(){
//   document.getElementById('deleteBtn').onclick=function(){
       
//     let checkBox = document.querySelectorAll('[type="checkbox"]');
//     [].forEach.call(checkBox, function(cb) {
//       cb.addEventListener("click", function() {
//         console.log(this);
//       });
//     });
//   };
// });

  // console.log(e.target.parentElement.dataset.id);



// document.addEventListener("DOMContentLoaded", function () {
// document.getElementById('deleteBtn').onclick=function(){
//   let url = '/api/post/delete.php'
//   let deleteBtn = e.target.id == 'checkbox'

//   console.log(e.target.parentElement.dataset.id);

//   fetch(`${url}/${id}`, {
//     method: 'DELETE', 
//     headers: {
//       'Content-Type' : 'application/json'
//     },
//   })
//      .then(res => console.log(res))

// }     
// })

//     let url = '../api/post/delete.php';
       
//     fetch(url) 
//     .then(res=>res.json())
//     .then(data=>renderPosts(data))


//     let checkBox = e.target.id == 'checkbox';

//     console.log(e.target)
//     if(checkBox) {
//       fetch(`${url}/${id}`, {
//         method: 'DELETE',
//       })
//       .then(res=> res.json())
//       .then(() => location.reload())
//     }

// async function getUsers() {
//         let url = '../api/post/read.php';
//         try {
//             let res = await fetch(url);
//             return await res.json(); 
//         } catch(error) {
//                 console.log(error);
//             }
//     }

// let btnDel = document.getElementById('#deleteBtn');

// let deleteData = async () => {
//     let response = await fetch ('../api/post/delete.php', {
//       method: 'DELETE', 
//       headers: {'Content-Type': 'application/json' },
//       body: JSON.stringify(id)
//     }) 
//     try {
//         let res = await fetch(url);
//         return await res.json(); 
//     } catch(error) {
//             console.log(error);
//         }
// }

// btnDel.addEventListener('click', deleteData);





  

