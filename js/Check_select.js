function Select() {
  // var plan1 = document.getElementById("plan1");
  // var plan2 = document.getElementById("plan2");
  // var plan3 = document.getElementById("plan3");
  // var fact1 = document.getElementById("fact1");
  // var fact2 = document.getElementById("fact2");
  // var fact3 = document.getElementById("fact3");
  // var credit = document.getElementById("credit");
  // var full_expencess = document.getElementById("full_expencess");
  // var margin = document.getElementById("margin");

  // var option = document.createElement("option");
  // option.value = option.innerHTML = "Не выбрано";
  // plan1.appendChild(option);
  // plan1.classList.add("SumoSelect");

  var plan1 = $("select.SumoSelect")[1].sumo;
  var plan2 = $("select.SumoSelect")[2].sumo;
  var plan3 = $("select.SumoSelect")[3].sumo;
  var fact1 = $("select.SumoSelect")[4].sumo;
  var fact2 = $("select.SumoSelect")[5].sumo;
  var fact3 = $("select.SumoSelect")[6].sumo;
  var credit = $("select.SumoSelect")[7].sumo;
  var full_expencess = $("select.SumoSelect")[8].sumo;
  var margin = $("select.SumoSelect")[9].sumo;
  var prize = $("select.SumoSelect")[12].sumo;
  var dateStart = $("select.SumoSelect")[13].sumo;
  var moneyDate = $("select.SumoSelect")[14].sumo;
  var endDate = $("select.SumoSelect")[15].sumo;
  var debt = $("select.SumoSelect")[16].sumo;

  var arr_select = [
    plan1,
    plan2,
    plan3,
    fact1,
    fact2,
    fact3,
    credit,
    full_expencess,
    margin,
    prize,
    dateStart,
    moneyDate,
    endDate,
    debt,
  ];

  var numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 12, 13, 14, 15, 16];
  var id = 0;

  var tbody = document.getElementById("FilterTable");

  arr_select.forEach((selects) => {
    // selects.add("", "Не выбрано");
    // let index = arr_select.indexOf(selects);
    // index++;
    let Array_filter = [];
    for (let i = 0; i < tbody.rows.length; i++) {
      let cell_value = tbody.rows[i].cells[numbers[id]].textContent;
      if (Check_duplicate(Array_filter, cell_value) == false) {
        Array_filter.push(cell_value);
      }
    }
    Array_filter.forEach((element) => {
      selects.add(element);
    });
    id++;
    Array_filter.length = 0;
  });
  id = 0;
  // arr_select.forEach((select) => {
  //   select.selectItem(0);
  //   // plan1.add();
  // });
}

function Check_duplicate(array, item) {
  let check = false;
  array.forEach((element) => {
    if (element == item) check = true;
  });
  return check;
}
