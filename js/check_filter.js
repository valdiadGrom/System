function filter_onchange() {
  var Name_sub = document.getElementById("Name_sub").value;
  var StatusD = document.getElementById("StatusD").value;
  var Managers = document.getElementById("Managers").value;
  var Engineers = document.getElementById("Engineers").value;

  var plan1 = document.getElementById("plan1").value;
  var plan2 = document.getElementById("plan2").value;
  var plan3 = document.getElementById("plan3").value;
  var fact1 = document.getElementById("fact1").value;
  var fact2 = document.getElementById("fact2").value;
  var fact3 = document.getElementById("fact3").value;
  var credit = document.getElementById("credit").value;
  var full_expencess = document.getElementById("full_expencess").value;
  var margin = document.getElementById("margin").value;
  var prize = document.getElementById("prize").value;
  var dateStart = document.getElementById("dateStart").value;
  var moneyDate = document.getElementById("moneyDate").value;
  var endDate = document.getElementById("endDate").value;
  var debt = document.getElementById("debt").value;

  var table, tr, td, i, txtValue, number;
  let Flist = [
    Name_sub,
    plan1,
    plan2,
    plan3,
    fact1,
    fact2,
    fact3,
    credit,
    full_expencess,
    margin,
    StatusD,
    Managers,
    prize,
    dateStart,
    moneyDate,
    endDate,
    debt,
    Engineers,
  ];
  table = document.getElementById("FilterTable");
  tr = table.getElementsByTagName("tr");
  var b = 0;
  var number_list = [
    0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17,
  ];
  reset();
  let count = 0;
  Flist.forEach((check_null) => {
    if (check_null == "" || check_null == null) count++;
  });
  if (count == Flist.length) {
    return;
  }
  Flist.forEach((filter) => {
    number = number_list[b];
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[number];
      if (td && filter != "") {
        txtValue = td.textContent || td.innerText;
        if (filter == txtValue && tr[i].style.display == "") {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
    i = 0;
    b++;
  });
}
function reset() {
  table = document.getElementById("FilterTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td && tr[i].style.display == "none") {
      tr[i].style.display = "";
    }
  }
}
