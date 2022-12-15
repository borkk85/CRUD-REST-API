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
  $("#saveBtn").click(function (e) {
    e.preventDefault();

    if (!$("#apiform").valid()) {
      return false;
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
        contentType: "application/json",
        success: function (data) {
          console.log(data);
          alert("successfully posted");
        },
        error: function (data) {
          console.log(data);
          alert("unsuccessfully posted");
        },
      });
    }
  });
});

//GET REQUEST

document.addEventListener("DOMContentLoaded", function () {
  window.onload = function () {
    async function getUsers() {
      let url = "../api/post/read.php";
      try {
        let res = await fetch(url);
        return await res.json();
      } catch (error) {
        console.log(error);
      }
    }
    async function renderUser() {
      let users = await getUsers();
      let html = ``;

      users.forEach((user) => {
        let htmlSegment = `
                <table class="box">
                    <tr> 
                    <th><input type="checkbox" class="checkbox" name="checkbox[]" value="${
                      user.id
                    }" data-id="${user.id}"></th>       
                    <td>  ${user.sku}</td>
                    <td>  ${user.name}</td>
                    <td>  ${user.price}</td>
                    ${user.size ? `<td> Size: ${user.size} $ </td>` : ""} 
                    ${user.weight ? `<td> Weight: ${user.weight}  Kg</td>` : ""}
                    ${user.height ? `<td>  Height: ${user.height} CM</td>` : ""}
                    ${user.length ? `<td>  Length: ${user.length} CM</td>` : ""}
                    ${user.width ? `<td>  Width: ${user.width} CM</td>` : ""}
                    </tr>
                </table>`;

        html += htmlSegment;
      });

      let container = document.querySelector(".message");
      container.innerHTML = html;
    }
    renderUser();
  };
});

//DELETE

$(document).ready(function() {
  $("#deleteBtn").click(function(e) {
      // Get the checkboxes that are checked
      let checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

      // Create an array to hold the IDs of the records to be deleted
      let ids = [];

      // Loop through the checkboxes and get the ID value from the data attribute
      for (let checkbox of checkboxes) {
        ids.push(checkbox.value);
      }
      
      // Create the query string using the IDs array
      let queryString = '?' + ids.map((id) => `id=${id}`).join('&');

      // Submit the delete_form form to the server using the query string
      $.ajax({
        url: '/api/post/delete.php' + queryString,
        type: 'DELETE',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log(response);
        },
        error: function(error) {
          console.log(error);
        }
      });
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
