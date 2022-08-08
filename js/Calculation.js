function addRow() {
  var tbody = document.getElementById("tbody");
  var tr = document.createElement("tr");
  tr.innerHTML =
    '<th><a href="#" onclick="deleteRow(this)">Удалить</a></th><tr><th><input class="inp" type="text" name="NameInp[]" form="form" placeholder="Наименование" required></th><th><input class="inp" type="text" name="EdIzm[]" form="form" placeholder="Ед.Изм" required></th><th><input class="inp" type="number" pattern="^0*[1-9]d*$" name="koll[]" form="form" placeholder="Количество" onchange="Calc()" required></th><th>---</th><th>---</th><th>---</th><th>---</th><th colspan="2">---</th><th>---</th><th>---</th><th>---</th><th><input class="inp" type="number" name="Zamen[]" form="form" value="0" placeholder="Замена" onchange="Calc()"></th><th><input class="inp" type="number" name="time[]" form="form" value="0" placeholder="Время" onchange="Calc()" required></th><th>---</th><th>---</th><th><input class="inp" type="number" name="GaniMed[]" form="form" value="0" onchange="Calc()"></th><th><input class="inp" type="number" name="ETM[]" form="form" value="0" onchange="Calc()"></th><th><input class="inp" type="number" name="Citil[]" form="form" value="0" onchange="Calc()"></th><th><input class="inp" type="number" name="bolid[]" form="form" value="0" onchange="Calc()"></th><th>---</th><th><input class="inp" type="url" name="urlSale[]" form="form" placeholder="https"></th></tr>';
  tbody.appendChild(tr);
  Calc();
}

function deleteRow(row) {
  let row_del = row.closest("tr");
  row_del.parentElement.removeChild(row_del);
  // var tbody = document.getElementById("tbody");
  // var length = tbody.rows.length;
  // if (length == 1) return;
  // length = length - 1;
  // tbody.rows[length].remove();
}

function getNumbersOfDays(start, end) {
  const date1 = new Date(start);
  const date2 = new Date(end);
  const oneDay = 1000 * 60 * 60 * 24;
  const diffInTime = date2.getTime() - date1.getTime();
  const diffInDays = Math.round(diffInTime / oneDay);

  return diffInDays;
}

function Calc() {
  var tbody = document.getElementById("tbody");
  var summOB = 0;
  var summWork = 0;
  var summAllI = 0;
  var zakAll = 0;
  var work100All = 0;
  var timeAllMin = 0;
  var timeAllHour = 0;
  var i = 0;
  var d;
  for (let row of tbody.rows) {
    let ganimed = tbody.rows[i].cells[16].children[0].value;
    let etm = tbody.rows[i].cells[17].children[0].value;
    let citilink = tbody.rows[i].cells[18].children[0].value;
    let bolid = tbody.rows[i].cells[19].children[0].value;
    let koll = tbody.rows[i].cells[3].children[0].value;
    let time = tbody.rows[i].cells[13].children[0].value;
    let percentPrice = document.getElementById("percentPrice").value;
    let timeNumber = document.getElementById("timeNumber").value;
    let timePercent = document.getElementById("PercentWork").value;

    var arrayP = [ganimed, etm, citilink, bolid];
    let min;
    min = Math.min.apply(
      null,
      arrayP.filter((number) => number > 0)
    );
    let max = Math.max.apply(
      null,
      arrayP.filter((number) => number > 0)
    );
    if (min == Infinity) {
      min = 0;
    }
    if (max == Infinity) {
      max = 0;
    }
    let summ;
    if (koll == "" || koll == null || koll == undefined || koll == 0) {
      koll = 1;
    }
    if (tbody.rows[i].cells[12].children[0].value > 0) {
      min = Number(tbody.rows[i].cells[12].children[0].value);
    }
    summ = min + min * (percentPrice / 100);
    let summAll = koll * min + koll * min * (percentPrice / 100);

    tbody.rows[i].cells[4].textContent = summ.toFixed(2);
    tbody.rows[i].cells[5].textContent = summAll.toFixed(2);

    let priceWork = timeNumber * time + timeNumber * time * (timePercent / 100);
    tbody.rows[i].cells[6].textContent = priceWork.toFixed(2);

    let stoimWork = Number(priceWork.toFixed(2)) * koll;
    tbody.rows[i].cells[7].textContent = stoimWork.toFixed(2);

    tbody.rows[i].cells[8].textContent = min;
    let stoimZak = min * koll;
    tbody.rows[i].cells[9].textContent = stoimZak;

    let Price100 = timeNumber * time;
    tbody.rows[i].cells[10].textContent = Price100.toFixed(2);
    let stoim100work = timeNumber * time * koll;
    tbody.rows[i].cells[11].textContent = stoim100work.toFixed(2);

    tbody.rows[i].cells[15].textContent = min;
    tbody.rows[i].cells[20].textContent = max;
    let timeAll = time * koll;
    tbody.rows[i].cells[14].textContent = timeAll;
    i++;
    summOB += summAll;
    summWork += stoimWork;
    zakAll += stoimZak;
    work100All += stoim100work;
    timeAllMin += timeAll;
  }
  var summAllI = summOB + summWork;

  var logistic = document.getElementById("logistic");
  var komandir = document.getElementById("komandir");

  if (logistic.disabled == false && logistic.value > 0) {
    summAllI += Number(logistic.value);
  }

  var checkpusk = document.getElementById("checkpusk");
  var pusk = document.getElementById("pusk");
  var percent10 = document.getElementById("percent10");
  if (checkpusk.checked == true) {
    percent10.disabled = false;
    pusk.disabled = false;
    pusk.readOnly = true;
    var puskV = summOB * (percent10.value / 100);
    pusk.value = puskV.toFixed(2);
  } else {
    pusk.value = "";
    pusk.disabled = true;
    percent10.disabled = true;
  }

  if (pusk.disabled == false && pusk.value > 0) {
    summAllI += Number(pusk.value);
  }
  var ItogoOb = document.getElementById("ItogoOB");
  var ItogoWork = document.getElementById("ItogoWork");
  var ItogoAll = document.getElementById("ItogoAll");
  ItogoOb.textContent = summOB.toFixed(2);
  ItogoWork.textContent = summWork.toFixed(2);

  let summItogoStoim = zakAll + work100All;
  document.getElementById("zakAll").value = zakAll;
  document.getElementById("work100All").value = work100All.toFixed(2);
  document.getElementById("itogoStoim").textContent = summItogoStoim.toFixed(2);
  document.getElementById("minTime").textContent = timeAllMin.toFixed(0);
  timeAllHour = timeAllMin / 60;
  document.getElementById("hourTime").textContent = timeAllHour.toFixed(2);
  var day1 = timeAllHour / 8;
  document.getElementById("day1").textContent = day1.toFixed(2);

  var kolWorkers = document.getElementById("kolWorkers").value;

  var dayAny = (day1 / kolWorkers).toFixed(2);
  document.getElementById("dayAny").textContent = dayAny;
  document.getElementById(
    "dayN"
  ).textContent = `Дней на ${kolWorkers} человек(a)`;

  var DateStart = document.getElementById("dateN").value;
  var DateEnd = document.getElementById("dateF").value;
  if (dayAny > 0) {
    var DateNewEnd = new Date(DateStart);
    let day = Math.round(dayAny);
    DateNewEnd.setDate(DateNewEnd.getDate() + day);
    var dateScr = DateNewEnd.toLocaleString("ru-RU", {
      year: "numeric",
      month: "numeric",
      day: "numeric",
    });
    dateDst = dateScr.split(".").reverse().join("-");
    document.getElementById("dateF").value = dateDst;
    document.getElementById("test").textContent = dateDst;
    var DateEnd = document.getElementById("dateF").value;
  }

  var DiffDays = getNumbersOfDays(DateStart, DateEnd);
  document.getElementById("kolDays").textContent = DiffDays;

  var riskPer = document.getElementById("riskPer").value;
  var itogoDaysRisk = DiffDays + DiffDays * (riskPer / 100);
  document.getElementById("itogoDaysRisk").textContent = itogoDaysRisk;

  var vakWeek = document.getElementById("vakWeek").value;
  var kolvalWeek = (itogoDaysRisk / 7) * vakWeek;
  document.getElementById("kolVak").textContent = kolvalWeek.toFixed(0);
  var workdays = DiffDays - kolvalWeek;
  document.getElementById("workDays").textContent = workdays.toFixed(0);

  var wayKM = document.getElementById("wayKM").value;
  var wayKM2 = wayKM * 2;
  document.getElementById("wayKM2").textContent = wayKM2;

  var rashod100 = document.getElementById("rashod100").value;
  var rashodNa100 = rashod100 / 100;
  document.getElementById("rashodNa100").textContent = rashodNa100;

  var benzin = document.getElementById("benzin").value;
  var proezd4 = benzin * rashodNa100;
  document.getElementById("proezd4").textContent = proezd4.toFixed(2);

  var kolTrans = Math.ceil(kolWorkers / 4);
  document.getElementById("kolTrans").textContent = kolTrans;

  var itogoProezd = kolTrans * wayKM2 * proezd4;
  document.getElementById("itogoProezd").textContent = itogoProezd.toFixed(2);

  document.getElementById("kolhouse").textContent = kolTrans;

  var monthsAll = itogoDaysRisk / (365 / 12);
  document.getElementById("kolMonth").textContent = monthsAll.toFixed(1);

  var houseMonth = document.getElementById("houseMonth").value;
  var totalMonth = Math.ceil(monthsAll) * houseMonth * kolTrans;
  document.getElementById("totalMonth").textContent = totalMonth;

  var houseDay = document.getElementById("houseDay").value;
  var totalDay = houseDay * kolTrans * itogoDaysRisk;
  document.getElementById("totalDay").textContent = totalDay;

  var profit;
  var placementDay;
  if (totalMonth < totalDay) {
    placementDay = totalMonth / kolWorkers / itogoDaysRisk;
    profit = "Помесячно";
  } else {
    placementDay = totalDay / kolWorkers / itogoDaysRisk;
    profit = "Посуточно";
  }
  document.getElementById("placementDay").textContent = placementDay.toFixed(2);
  document.getElementById("profit").textContent = profit;

  var totalPlacement = placementDay * kolWorkers * itogoDaysRisk;
  document.getElementById("totalPlacement").textContent =
    totalPlacement.toFixed(2);

  var dayPeop = document.getElementById("dayPeop").value;
  var totalDayPeop = dayPeop * kolWorkers * itogoDaysRisk;
  document.getElementById("totalDayPeop").textContent = totalDayPeop.toFixed(2);

  var totalKomand = itogoProezd + totalPlacement + totalDayPeop;
  document.getElementById("totalKomand").textContent = totalKomand.toFixed(2);

  if (komandir.disabled == false) {
    komandir.value = totalKomand.toFixed(2);
    summAllI += totalKomand;
  } else {
    komandir.value = "";
  }
  ItogoAll.value = summAllI.toFixed(2);
}

function savePost() {
  var score = 0;
  var inputs = document.getElementsByTagName("input");
  for (let input of inputs) {
    if (!input.checkValidity()) {
      score++;
    }
    //Добавить неактивность кнопки
  }
  if (score > 0) {
    new Toast({
      title: "Ошибка",
      text: "Пиши нормально",
      theme: "warning",
      autohide: true,
      interval: 1000,
    });
  }
}

$("#checklog").on("click", function () {
  if ($("#checklog").is(":checked")) {
    $("#logistic").prop("disabled", false);
  } else {
    $("#logistic").prop("disabled", true);
  }
});

$("#checkKomand").on("click", function () {
  if ($("#checkKomand").is(":checked")) {
    $("#komandir").prop("disabled", false);
  } else {
    $("#komandir").prop("disabled", true);
  }
});
