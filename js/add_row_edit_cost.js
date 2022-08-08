function add_row() {
  var tbody = document.getElementById("tbody");
  let number = tbody.rows[0].cells[0];
  let date = tbody.rows[0].cells[1];
  let name = tbody.rows[0].cells[2];
  let summ = tbody.rows[0].cells[3];
  let new_number = number.cloneNode();
  let new_date = date.cloneNode(true);
  let new_name = name.cloneNode(true);
  let new_summ = summ.cloneNode(true);

  let last = tbody.rows.length;
  let last_number = tbody.rows[last - 1].cells[0].textContent;
  new_number.textContent = Number(last_number) + 1;

  new_date.children[0].value =
    new_name.children[0].value =
    new_summ.children[0].value =
      "";

  let tr = document.createElement("tr");
  tr.appendChild(new_number);
  tr.appendChild(new_date);
  tr.appendChild(new_name);
  tr.appendChild(new_summ);

  let new_type = document.createElement("th");
  new_type.id = "new_type";
  tr.appendChild(new_type);

  let new_subtype = document.createElement("th");
  new_subtype.id = "new_subtype";
  tr.appendChild(new_subtype);

  let new_full_name = document.createElement("th");
  new_full_name.id = "new_full_name";
  tr.appendChild(new_full_name);

  let new_org = document.createElement("th");
  new_org.id = "new_org";
  tr.appendChild(new_org);

  tbody.appendChild(tr);

  $("#selectType")
    .clone()
    .removeAttr("id")
    .removeAttr("class")
    .appendTo("#new_type")
    .SumoSelect({
      search: true,
    });
  $("#selectSubType")
    .clone()
    .removeAttr("id")
    .removeAttr("class")
    .appendTo("#new_subtype")
    .SumoSelect({
      search: true,
    });
  $("#selectName")
    .clone()
    .removeAttr("id")
    .removeAttr("class")
    .appendTo("#new_full_name")
    .SumoSelect({
      search: true,
    });
  $("#selectOrg")
    .clone()
    .removeAttr("id")
    .removeAttr("class")
    .appendTo("#new_org")
    .SumoSelect({
      search: true,
    });
  new_type.id = new_subtype.id = new_full_name.id = new_org.id = "";

  //   let new_row = row1.cloneNode(true);
  //   tbody.appendChild(new_row);
}

function check_summ() {
  let full_summ = Number(document.getElementById("full_summ").textContent);

  let all_sums = document.querySelectorAll(".summ_check");
  let summ = 0;
  all_sums.forEach((element) => {
    summ += Number(element.value);
  });
  if (summ > full_summ) {
    new Toast({
      title: "Ошибка",
      text: "Сумма превышает допустимую",
      theme: "warning",
      autohide: true,
      interval: 10000,
    });
    all_sums.forEach((element) => {
      //   element.invalid = true;
      element.style.background = "red";
    });
  } else {
    all_sums.forEach((element) => {
      element.style.background = "";
    });
  }
}

const form = document.getElementById("EditCost");
let all_sums = document.querySelectorAll(".summ_check");
form.onsubmit = function (evt) {
  let count = 0;
  all_sums.forEach((element) => {
    if (element.style.background == "red") {
      count++;
    }
    if (count > 0) {
      evt.preventDefault();
      new Toast({
        title: "Ошибка",
        text: "Заполните поля верно",
        theme: "danger",
        autohide: true,
        interval: 10000,
      });
    }
  });
};
