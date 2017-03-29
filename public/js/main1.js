/////////////////////////// clear input fields on focus and change color ///////////////////////////
// function setButtonBehaviour(buttonId) {
//   var sblockButton = document.getElementById(buttonId);
//   sblockButton.addEventListener("click", function(){
//     sblockPrac(buttonId); // MUST use anon function in order to pass parameters to listener function !!!
//   });
//   // console.log(buttonId + ' is listening for sblock.');
// }
// function setButtonClassBehaviour(className) {
//   var inputs = document.getElementsByClassName(className);
//   for (n = 0; inputs[n]; n++) {
//       input = inputs[n];
//       setButtonBehaviour(input.id);
//   }
// }
/////////////////////////// clear input fields on focus and change color ///////////////////////////
function setInputBehaviour(id) {
  var input = document.getElementById(id);
  var defaultValue = input.value;
  var defaultTextAlign = input.style.textAlign;
  var defaultColor = input.style.color;

  input.onfocus = function clearValue() {
     if ( input.value === defaultValue) {
         input.value = '';
       input.style.textAlign = 'center';
       input.style.color = '#094e7a';
     }
  };
  input.onblur = function restoreValue() {
     if ( input.value === "") {
         input.value = defaultValue;
        // input.style.textAlign = defaultTextAlign;
        // input.style.color = defaultColor;
        input.style.textAlign = 'left';
        input.style.color = 'grey';
     }
  };
  console.log('#' + id + ' behaviour has been initialized.');
}

function setInputClassBehaviour(className) {
  // var inputs = document.getElementsByTagName('input');
  var inputs = document.getElementsByClassName(className);
  for (n = 0; inputs[n]; n++) {
      input = inputs[n];
      setInputBehaviour(input.id);
  }
}

/////////////////////////// Reset inputs to name value ///////////////////////////
function resetFormInputs(form) {
  var inputs = form.getElementsByTagName('input');
  for (n = 0; inputs[n]; n++) {
    input = inputs[n];
    console.log(input.name);
    input.value = input.name;
    input.style.textAlign = 'left';
    input.style.color = 'grey';
  }
}
/////////////////////////// Keep headerRow visible ///////////////////////////
// Thank this guy for this !!! >>> http://stackoverflow.com/posts/25902860/revisions
var tableDiv = document.getElementById("table_div");
    if (tableDiv !== null) {
        tableDiv.addEventListener("scroll",function(){
            var translate = "translate(0,"+(this.scrollTop - 4)+"px)";
            this.querySelector('#table_header_row').style.transform = translate;
            //  this.headerRow.style.transform = translate; // this does NOT work
        });
    }
/////////////////////////// Spinner display functions ///////////////////////////
var searchButton = document.getElementById("search_button");
var spinner = document.getElementById('spinner');

function showSpinner() {
  spinner.style.display = 'inline-block';
  searchButton.disabled = true;
  searchButton.value = 'Attendi...';
}
function hideSpinner() {
  spinner.style.display = 'none';
  searchButton.disabled = false;
  searchButton.value = 'Cerca';
}
/////////////////////////// Reset StockID input ///////////////////////////
var inputStockId = document.getElementById('jsstockid');
if (inputStockId !== null) {
// THIS IF IS HERE IN CASE LOGGED USER IS AN AGENCY
  var defaultStockIdValue = inputStockId.value;

  inputStockId.onfocus = function() {
    // if ( inputStockId.value === defaultStockIdValue )
    if ( inputStockId.value === "Stock ID" ) {
      inputStockId.setAttribute("value", "");
    }
  };
  inputStockId.onblur = function() {
    if ( inputStockId.value === "" ) {
      // inputStockId.setAttribute("value", defaultStockIdValue);
      inputStockId.setAttribute("value", "Stock ID");
    }
  };
}
/////////////////////////// Reset Targa input ///////////////////////////
var inputTarga = document.getElementById('jstarga');
if (inputTarga !== null) {
  var defaultTargaValue = inputTarga.value;

  inputTarga.onfocus = function() {
    if ( inputTarga.value == "Targa" ) {
      inputTarga.setAttribute("value", "");
    }
  };
  inputTarga.onblur = function() {
    if ( inputTarga.value == "" ) {
      inputTarga.setAttribute("value", "Targa");
    }
  };
}
/////////////////////////// Validate Branch file submit ///////////////////////////
function validateBR() {
  var targaInserita = document.forms["manda_doc"]["targa"].value;
  var targaInserita = targaInserita.toUpperCase();
  var stockidInserito = document.forms["manda_doc"]["stockid"].value;
  var stockidInserito = stockidInserito.toUpperCase();

  var urlParams = new URLSearchParams(window.location.search);
  var dataInserita = atob(urlParams.get('d'));
  var y = dataInserita.substr(0,4);
  var m = dataInserita.substr(5,2);
  var d = dataInserita.substr(8,2);

  var messaggio = "Dati mancanti:\n\n";
  if (stockidInserito === "") {
    messaggio += "   Stock ID\n";
  }
  if (targaInserita === "") {
    messaggio += "     Targa\n";
  }
  if (messaggio !== "Dati mancanti:\n\n") {
    alert(messaggio);
    return false;
  } else {
    var messaggio = "Attenzione:\n\n";
    if ( validateStockId(stockidInserito) === false ) {
        messaggio += "- Lo Stock ID dev\'essere composto da 2 lettere seguiti da 5 numeri.\n";
    }
    if (validateTarga(targaInserita) === false) {
        messaggio += "- La Targa inserita non è valida.\n"
    }

    if (messaggio !== "Attenzione:\n\n") {
        alert(messaggio);
        return false;
    } else {
      var bar = "Confermi i seguenti dati?\n\n Stock ID = "+stockidInserito+"\n Targa = "+targaInserita+"\n Data = "+d+" / "+m+" / "+y;
      var foo = confirm(bar);
      if (foo == false) {
       return false;
      } else {
       return true;
      }
    }
  }
}
/////////////////////////// Validate Agency file submit ///////////////////////////
function validateAG() {
  var targaInserita = document.forms["manda_doc"]["targa"].value;
  var targaInserita = targaInserita.toUpperCase();

  var urlParams = new URLSearchParams(window.location.search);
  var dataInserita = atob(urlParams.get('d'));
  var y = dataInserita.substr(0,4);
  var m = dataInserita.substr(5,2);
  var d = dataInserita.substr(8,2);

  if (targaInserita === "") {
    alert("Inserisci una Targa.\n");
    return false;
  } else if (validateTarga(targaInserita) === false) {
    alert("La Targa inserita non è valida.");
    return false;
  } else {
    var bar = "Confermi i seguenti dati?\n\n Targa = "+targaInserita+"\n Data = "+d+" / "+m+" / "+y;
    var foo = confirm(bar);
    if (foo == false) {
     return false;
    } else {
     return true;
    }
  }
}

/////////////////////////// Validate StockId ///////////////////////////
function validateStockId(stockId) {
  var regex = /^[a-zA-Z]{2}[0-9]{5}$/g;
  var result = regex.test(stockId);
  return result;
}
/////////////////////////// Validate Targa ///////////////////////////
function validateTarga(targa) {
    // targa is probably best validated without a regex
  var regex = /^[a-zA-Z]{2}[0-9]{3}[a-zA-Z0-9]{2}[0-9]?$/g;
  var result = regex.test(targa);
  return result;
}
/////////////////////////// Validate FormData for Targa & StockID ///////////////////////////
function validateFormData(formData) {
    var correctTarga = false;
    var correctStockId = false;
    var errorMessage = '';
    for ([key, value] of formData.entries()) {
      //   confirm(key + '=' + value);
      if (key === 'Targa') {
          correctTarga = validateTarga(value);
      }
      if (key === 'StockID') {
          correctStockId = validateStockId(value);
      }
    }
    if (correctTarga === false) {
        errorMessage += '\t- La Targa inserita non è corretta.\n';
    }
    if (correctStockId === false) {
        errorMessage += '\t- Lo StockID inserito non è corretto.\n';
    }
    if (errorMessage !== '') {
        hideSpinner();
        alert(errorMessage);
        return false;
    } else {
        return true;
    }
}
/////////////////////////// Validate Data di rilasciato ///////////////////////////
function verifyDate(date) {
  var errorMessage = '';
  if (isNaN(date)) {
    errorMessage += '- La data inserita dev\'essere composta da 8 numeri.\n';
  } else {
    if (date.length < 8) {
      errorMessage += '- La data inserita è troppo corta.\n';
    }
    if (date.length > 8) {
      errorMessage += '- La data inserita è troppo lunga.\n';
    }
  }
  if (errorMessage !== '') {
    hideSpinner();
    alert(errorMessage);
    return false;
  } else {
    return true;
  }
}

/////////////////////////// Validate Export submit ///////////////////////////
function validateExport() {
    alert('Nessun dato da esportare.');
    return false;
}


setInputClassBehaviour('search_input_class');
setInputClassBehaviour('block_input_class');
