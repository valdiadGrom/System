function rating() {
  var table = document.getElementById("list");

  var length = table.rows[0].cells.length;
  var total = length - 2;
  var rank = length - 1;

  var filterFloat = function (value) {
    if (/^(\-|\+)?([0-9]+(\.[0-9]+)?|Infinity)$/.test(value))
      return Number(value);
    return NaN;
  };

  var rank_units = [];
  for (let i = 0; i < table.rows.length; i++) {
    let num = table.rows[i].cells[total].textContent;
    num = num.replace(/\s/g, "");
    num = num.replace(/,/g, ".");
    rank_units.push(filterFloat(num));
  }
  var copy_arr = Array.from(new Set(rank_units));

  for (let j = 0; j < copy_arr.length; j++) {
    if (copy_arr[j] == 0) {
      copy_arr.splice(j, 1);
    }
  }
  copy_arr.sort(function (a, b) {
    return b - a;
  });
  let val = 0;
  for (let i = 0; i < table.rows.length; i++) {
    copy_arr.forEach((element) => {
      if (rank_units[i] === element && val == 0) {
        val = copy_arr.indexOf(element);
        table.rows[i].cells[rank].textContent = ++val;
      }
    });
    let color;
    switch (val) {
      case 1:
        color = "#4dff4d";
        break;
      case 2:
        color = "#19ff19";
        break;
      case 3:
        color = "#00e600";
        break;
      case 4:
        color = "#00b300";
        break;
      case 5:
        color = "#e6e600";
        break;
      case 6:
        color = "#ffd800";
        break;
      case 7:
        color = "#ff8800";
        break;
      case 8:
        color = "#ff6800";
        break;
      case 9:
        color = "#ff4f00";
        break;
      case 10:
        color = "#ff2400";
        break;
      case 11:
        color = "#141613";
        break;
      default:
        break;
    }
    table.rows[i].cells[rank].style.backgroundColor = color;
    val = 0;
  }
}
