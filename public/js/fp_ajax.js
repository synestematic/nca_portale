var tBody = document.getElementById("tbody");
var textAreas = document.getElementsByTagName('textarea');
var titleDiv = document.getElementById("title");
var titleName = titleDiv.firstElementChild.innerHTML;

var searchForm = document.getElementById("search_form");
var action = searchForm.getAttribute("action");
var searchButton = document.getElementById("search_button");
searchButton.addEventListener("click", getResults);

function gatherData() {
  var wheres = new FormData(searchForm); //this needs to be inside the function so that each time it's called it reads the value of searchForm items
  // wheres.append("id", next_id);
  var whereString = 'where={';
  for ([name, value] of wheres.entries()) {
      whereString += ('"' + name + '":"' + value + '",');
  }
  var whereString = whereString.replace(/.$/,"}");

  var dataArray = [];
  for(i=0; i < textAreas.length; i++) {
    var nameValue = textAreas[i].name + '=' + textAreas[i].value;
    dataArray.push(nameValue);
  }
  dataArray.push(whereString);
  return dataArray.join('&');
}

function getResults() {
  showSpinner();
  var xhr = new XMLHttpRequest();
  xhr.open('POST', action, true);
  // alert(action);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');   // do not set content-type with FormData
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
          var response = xhr.responseText;
          tbody.innerHTML = xhr.responseText;
          titleDiv.firstElementChild.innerHTML = titleName + ': ' + tbody.getElementsByTagName('tr').length;
          hideSpinner();
      }
  };
  var data = gatherData();
  xhr.send(data);
  // xhr.send("first_name=Bob&last_name=Smith");
}
