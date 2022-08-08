<?php
switch ($_SESSION['role']) {
    case 'admin':
?>
        <div class="sidenav">
            <a href="mainmenu.php">Главное меню</a>
            <button class="dropdown-btn" name="btn-menu">Таблицы ▷
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-container">
                <a href="../tableCost.php">Cost</a>
                <a href="../tablePlan.php">Fact</a>
                <a href="../tableCheck.php">Check</a>
                <a href="../tableCalculation.php">Calculation</a>
                <a href="../Name-Orig.php">Таблица Name</a>
                <a href="../SubType-Orig.php">Таблица SubType</a>
                <a href="../Type-Orig.php">Таблица Type</a>
                <a href="../Org-Orig.php">Таблица Org</a>
                <a href="../newProject.php">Новый проект</a>
                <a href="../listContract.php">Список проектов</a>
            </div>
            <button class="dropdown-btn" name="btn-users">Работа с пользователями ▷
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-container">
                <a href="../managers/managers_list.php">Список менеджеров</a>
                <a href="../userlist.php">Список пользователей</a>
                <a href="../addusers.php">Добавление пользователей</a>
                <a href="">Что нибудь еще</a>
            </div>
            <a href="exit.php">Выход</a>
        </div>
    <?php
        break;
    case 'manager':
    ?>
        <div class="sidenav">
            <a href="mainmenu.php">Главное меню</a>
            <button class="dropdown-btn" name="btn-menu">Таблицы ▷
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-container">

                <a href="newProject.php">Новый проект</a>
                <a href="tableCheck.php">Check</a>
            </div>

            <a href="exit.php">Выход</a>
        </div>
    <?php
        break;
    case 'engineer':
    ?>
        <div class="sidenav">
            <a href="mainmenu.php">Главное меню</a>
            <button class="dropdown-btn" name="btn-menu">Таблицы ▷
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-container">
                <a href="tableCheck.php">Check</a>
            </div>

            <a href="exit.php">Выход</a>
        </div>
    <?php
        break;
    case 'buyer':
    ?>
        <div class="sidenav">
            <a href="mainmenu.php">Главное меню</a>
            <button class="dropdown-btn" name="btn-menu">Таблицы ▷
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-container">
                <a href="tableCheck.php">Check</a>
            </div>

            <a href="exit.php">Выход</a>
        </div>
    <?php
        break;
    case 'document_specialist':
    ?>
        <div class="sidenav">
            <a href="mainmenu.php">Главное меню</a>
            <button class="dropdown-btn" name="btn-menu">Таблицы ▷
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-container">
                <a href="tableCheck.php">Check</a>
                <a href="listContract.php">Список проектов</a>

            </div>

            <a href="exit.php">Выход</a>
        </div>
<?php
        break;
    default:
        tologin();
}
?>