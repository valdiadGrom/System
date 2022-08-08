<!DOCTYPE phpl>
<phpl>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/nav.css" rel="stylesheet" />
</head>
<body>

<div class="sidenav">
    <a href="mainmenu.php">Главное меню</a>
    <button class="dropdown-btn">Таблицы 
        <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-container">
        <a href="tables.php">Таблица Cost</a>
        <a href="">Таблица ???</a>
        <a href="">Таблица ???</a>
  </div>
  <a href="exit.php">Выход</a>
</div>

<div class="main">
  <h2>Выпадающее меню боковой панели</h2>
  <p>Нажмите на кнопку выпадающего меню, чтобы открыть выпадающее меню внутри боковой навигации.</p>
  <p>Эта боковая панель имеет полную высоту (100%) и всегда отображается.</p>
  <p>Какой-то случайный текст..</p>
</div>

<script>
/* Цикл через все кнопки выпадающего списка для переключения между скрытием и отображением его выпадающего содержимого - это позволяет пользователю иметь несколько выпадающих списков без каких-либо конфликтов */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
</script>

</body>
</phpl>
