var inputStockId = document.getElementById('jsstockid');
var defaultStockIdValue = inputStockId.value;

inputStockId.onfocus = function() {
  // if ( inputStockId.value === defaultStockIdValue ) {
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
//////////////////////////////////////////////////////////
var inputTarga = document.getElementById('jstarga');
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
//////////////////////////////////////////////////////////
function validateBR() {
  var targaInserita = document.forms["manda_doc"]["targa"].value;
  var targaInserita = targaInserita.toUpperCase();
  var stockidInserito = document.forms["manda_doc"]["stockid"].value;
  var stockidInserito = stockidInserito.toUpperCase();

  var giornoInserito = document.forms["manda_doc"]["giorno"].value;
  var meseInserito = document.forms["manda_doc"]["mese"].value;
  var annoInserito = document.forms["manda_doc"]["anno"].value;

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
      alert('Lo Stock ID deve essere composto da 2 lettere seguiti da 5 numeri.');
      return false;
    } else {
      var bar = "Confermi i seguenti dati?\n\n Stock ID = "+stockidInserito+"\n Targa = "+targaInserita+"\n Data = "+giornoInserito+"/"+meseInserito+"/"+annoInserito;
      var foo = confirm(bar);
      if (foo == false) {
       return false;
      } else {
       return true;
      }
    }
  }
}
//////////////////////////////////////////////////////////
function validateAG() {
  var targaInserita = document.forms["manda_doc"]["targa"].value;
  var targaInserita = targaInserita.toUpperCase();

  var giornoInserito = document.forms["manda_doc"]["giorno"].value;
  var meseInserito = document.forms["manda_doc"]["mese"].value;
  var annoInserito = document.forms["manda_doc"]["anno"].value;

  var messaggio = "";

  if (targaInserita === "") {
    messaggio += "Inserisci la Targa.\n";
    alert(messaggio);
    return false;
  } else {
    var bar = "Confermi i seguenti dati?\n\n Targa = "+targaInserita+"\n Data = "+giornoInserito+"/"+meseInserito+"/"+annoInserito;
    var foo = confirm(bar);
    if (foo == false) {
     return false;
    } else {
     return true;
    }
  }
}
//////////////////////////////////////////////////////////
function validateExport() {
    alert('Nessun dato da esportare.');
    return false;
}
