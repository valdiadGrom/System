function pip() {
  var Sdata = document.getElementById("selectData").value;
  var Snaim = document.getElementById("selectnaim").value;
  var Ssumm = document.getElementById("selectsumm").value;
  var Stype = document.getElementById("selectType").value;
  var Ssubtype = document.getElementById("selectSubtype").value;
  var Sname = document.getElementById("selectName").value;
  var Sorg = document.getElementById("selectOrg").value;
  var hide_date = document.getElementById("hide_date").value;
  var table, tr, td, i, txtValue;
  table = document.getElementById("Ftable");
  tr = table.getElementsByTagName("tr");
  if (Sdata == hide_date) Sdata = "";
  let Flist = [Sdata, Snaim, Ssumm, Stype, Ssubtype, Sname, Sorg];
  var b = 1;
  reset(0);
  let count = 0;

  Flist.forEach((check_null) => {
    if (check_null == "" || check_null == null) count++;
  });
  if (count == Flist.length) {
    return;
  }

  Flist.forEach((filter) => {
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[b];
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
function reset(change) {
  table = document.getElementById("Ftable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td && tr[i].style.display == "none") {
      tr[i].style.display = "";
    }
  }
  if (change == 1) {
    var hide_date = document.getElementById("hide_date").value;
    document.getElementById("selectData").value = hide_date;
    $("select.selectNew")[0].sumo.selectItem(0);
    $("select.selectNew")[1].sumo.selectItem(0);
    $("select.selectNew")[2].sumo.selectItem(0);
    $("select.selectNew")[3].sumo.selectItem(0);
    $("select.selectNew")[4].sumo.selectItem(0);
    $("select.selectNew")[5].sumo.selectItem(0);
  }
}
