var form = document.getElementById("nb_form");
var table = document.getElementById("tavol");
var tbody = document.getElementById("table_body");
var action = form.getAttribute("action"); // 2.php

function updateCalls(tavola, new_html) {
  showSpinner();
  var class_name = tbody.firstElementChild.className;
  var rows = tbody.getElementsByClassName(class_name);
  var next_id = Number(rows[0].firstElementChild.id) + 1 ;

  var form_data = new FormData(form);
  form_data.append("id", next_id);
  // var form_data = mergeFormdataWith(next_id);
  // for ([key, value] of form_data.entries()) {
      // confirm(key + '=' + value);
  // }

  var xhr2 = new XMLHttpRequest();
  xhr2.open('POST', action, true);
  // xhr2.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');   // do not set content-type with FormData
  xhr2.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr2.onreadystatechange = function () {
      if(xhr2.readyState == 4 && xhr2.status == 200) {
          var result = xhr2.responseText;
          tbody.innerHTML = xhr2.responseText + tbody.innerHTML
          hideSpinner();
          // var len = rows.length;
          // for(i=0; i < len; i++) {
            // alert(rows[i].firstElementChild.id);
            // tavola.appendChild(rows[0]);
            // tbody.insertBefore(rows[0], tbody.firstElementChild);
            // tavola.insertBefore(rows[0], tavola.firstElementChild);
            // $(rows[0]).hide().prependTo(tavola).fadeIn(100);
            // $(rows[0]).fadeIn(2000); JQUERY HAS PROBLEMS WITH THESE MANY RESULTS
          // }
      }
  };
  xhr2.send(form_data);
}

function reloadCalls() {
  tbody.innerHTML = '';
  showSpinner();
  var form_data = new FormData(form);
  // for ([key, value] of form_data.entries()) { confirm(key + value); }
  // confirm(form_data.entries());

  var xhr = new XMLHttpRequest();
  // xhr.open('GET' , '2.php?page=1' , true);
  xhr.open('POST', action, true);
  // xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');   // do not set content-type with FormData
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
          var result = xhr.responseText;
          tbody.innerHTML = result;
          foobar();
          hideSpinner();
      }
  };
  xhr.send(form_data);
}

var button = document.getElementById("bottone_cerca");
button.addEventListener("click", reloadCalls);

var button = document.getElementById("more");
button.addEventListener("click", updateCalls);

function foobar() {
    setInterval(updateCalls, 5000) ;
}
// window.onload = function () {
//     setInterval(updateCalls, 10000) ;
// }
