//POST REQUEST

$(document).ready(function () {
  $("#apiform").validate({
    // Set up validation rules and messages
    rules: {
      sku: "required",
      name: "required",
      price: "required",
      productType: "required",
      size: "required",
      weight: "required",
      height: "required",
      length: "required",
      width: "required",
    },
    messages: {
      sku: {
        required: "Please, enter valid SKU",
      },
      name: {
        required: "Please, enter product name",
      },
      price: {
        required: "Please, enter price",
      },
      productType: {
        required: "Please select one of the provided options",
      },
      size: {
        required: "Please, provide the size of the DVD",
      },
      weight: {
        required: "Please, provide the weight",
      },
      height: {
        required: "Please, provide the required dimension",
      },
      length: {
        required: "Please, provide the required dimension",
      },
      width: {
        required: "Please, provide the required dimension",
      },
    },
    // Set up error placement function
    errorPlacement: function (error, element) {
      // Add the error message after the form field
      error.insertAfter(element);
    },
  });

  // Add a submit event listener to the form
  $("#apiform").submit(function (event) {
    // $('.error').text('');
    // Prevent the form from being submitted
    event.preventDefault();

    // Check if the form is valid
    if ($("#apiform").valid()) {
      // The form is valid, so serialize the form data and submit it via AJAX
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
        dataType: "json",
        success: function (response) {
          console.log(response);
          if (response.success === true) {
            $("#success-message").text("Product added successfully");
            window.location = "ajaxCall.html";
          } else {
            for (var field in response.errors) {
              console.log(field);
              if (response.errors.hasOwnProperty(field)) {
                // display the error message next to the form field
                $("#" + field + "-error").text(response.errors[field]).addClass('error-message');
              }
            }
          }
        },
      });
    }
  });
});

//GET REQUEST

document.addEventListener("DOMContentLoaded", function () {
  if (window.location.pathname === "/ajaxCall.html") {
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
                    <tr class="content"> 
                    <th><input type="checkbox" class="checkbox" name="checkbox[]" value="${
                      user.id
                    }" data-id="${user.id}"></th>       
                    <td>  ${user.sku}</td>
                    <td>  ${user.name}</td>
                    <td>  ${user.price} $</td>
                    ${user.size ? `<td> Size: ${user.size} MB </td>` : ""} 
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
  }
});

const container = document.querySelector('.message');

container.addEventListener('click', event => {
  if (event.target.matches('.content')) {
    console.log('checkbox clicked');
    const checkbox = event.target.querySelector('.checkbox');
    checkbox.checked = !checkbox.checked;

    event.target.classList.toggle('selected');
  }
});

//DELETE

$(document).ready(function () {
  $("#deleteBtn").click(function (e) {
    
    let checkboxes = document.querySelectorAll("input[type=checkbox]:checked");

    let ids = [];

    for (let checkbox of checkboxes) {
      ids.push(checkbox.value);
    }
    // Create the query string using the IDs array
    // let queryString = '?' + ids.map((id) => `id=${id}`).join('&');
    let data = {
      id: ids,
    };
    $.ajax({
      url: "/api/post/delete.php",
      type: "DELETE",
      data: JSON.stringify(data),
      dataType: "json",
      contentType: "application/json",
      success: function (response) {
        console.log(response);
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
});


document.getElementById("addBtn").onclick = function () {
  location.href = "ajaxProd.html";
};

function selectChanged() {
  var sel = document.getElementById("productType");
  let dvd = document.getElementById("dvd");
  let book = document.getElementById("book");
  let furniture = document.getElementById("furniture");
  for (var i = 1; i < 4; i++) {
    dvd.style.display = sel.value == "dvd" ? "block" : "none";
    book.style.display = sel.value == "book" ? "block" : "none";
    furniture.style.display = sel.value == "furniture" ? "block" : "none";
  }
}

