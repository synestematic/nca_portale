/////////////////////////// Spinner display functions ///////////////////////////
var spinner = document.getElementById('spinner');
function showSpinner() {
  spinner.style.display = 'inline-block';
}
function hideSpinner() {
  spinner.style.display = 'none';
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

    var ab = stockidInserito.substr(0, 2);
    var num = stockidInserito.substr(2, 5);
    var length = stockidInserito.length;

    if ( stockidInserito.length !== 7 || !/^[a-zA-Z]*$/g.test(ab) || isNaN(num) ) {
      alert('Lo Stock ID dev\'essere composto da 2 lettere seguiti da 5 numeri.');
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

  var messaggio = "";

  if (targaInserita === "") {
    messaggio += "Inserisci una Targa.\n";
    alert(messaggio);
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
/////////////////////////// Validate Export submit ///////////////////////////
function validateExport() {
    alert('Nessun dato da esportare.');
    return false;
}
/////////////////////////// Traffic Warning ///////////////////////////
function trafficWarning() {
    $foo = confirm('Questa funzione genera un\'elevata quantità di traffico...\nUsare con discrezione, grazie!');
    return $foo;
}
