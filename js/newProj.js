function NumberDocument() {
  var select = document.getElementById("selectNazn");
  var Contract = document.getElementById("Contract");
  var Manager = document.getElementById("selectMan");
  var id = document.getElementById("idSub");
  Contract.value = `${id.value}\\${select.value}\\${Manager.value}`;
}
