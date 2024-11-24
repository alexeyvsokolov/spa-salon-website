<?php
require 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA Salon</title>
</head>

<body>

    <?   
    $login = $_POST['login'] ?? null;
    $password = $_POST['password'] ?? null;
    
    session_id($login);
    session_start();

    if ($login !== null || $password !== null) {
        // Если логин и пароль из users.php совпадают с логином и паролем из формы
        if (checkPassword($login, $password, $users) === true) {
            $_SESSION['auth'] = true;
            $_SESSION['username'] = $login;

            // Время входа на сайт при авторизации
            if (empty($_SESSION['thisTime'])) {
                $_SESSION['thisTime'] = date('d.m.Y H:i:s');
                $time = time() + 86400;
                $_SESSION['timeUp'] = date('d.m.Y H:i:s', $time);
            }
        } elseif (checkPassword($login, $password, $users) === false) {
            header('Location: index.php');
            exit;
        }
    }

    $auth = $_SESSION['auth'] ?? null;

    if (isset($_GET['do']) && $_GET['do'] == 'logout') {
        unset($_SESSION['auth']);
        unset($_SESSION['username']);
        unset($_SESSION['age']);
        unset($_SESSION['until-birthday']);
        session_regenerate_id();
        header('Location: index.php');
        exit;
    }

    // если авторизованы и сессия дня рождения
    if ($auth && isset($_SESSION['birthday-date'])) { ?>

        <h1>SPA Salon</h1>
        <h2>Личный кабинет</h2>
        <h3>Вы авторизованы</h3>
        <p><a href="index.php">Вернуться на главную</a></p>
        <a href="?do=logout">Выйти</a>
        <p>Контент для авторизованного пользователя</p>

        <?

        $arrayDate = explode(".", $_SESSION['birthday-date']);
        $day = $arrayDate[0];
        $month = $arrayDate[1];
        $year = $arrayDate[2];

        $age = getAge($day, $month, $year);
        $ageGetYearsStr = getYearsStr($age);
        $_SESSION['age'] = "$age $ageGetYearsStr";
        $_SESSION['birthday-date'] = "$day.$month.$year";
        $_SESSION['until-birthday'] = untilBirthday($day, $month, $year);
        // $_SESSION['time'] = date('d.m.Y H:i:s');   // комментарий ниже
        ?>


        <p>Имя: <? echo $_SESSION['username'] ?></p>
        <p>Дата рождения: <? echo $_SESSION['birthday-date'] ?></p>
        <p>Возраст: <? echo $_SESSION['age'] ?></p>
        <p><? echo $_SESSION['until-birthday'] ?></p>
        

    <? } elseif ($auth && !isset($_SESSION['birthday-date'])) { ?>

        <h1>SPA Salon</h1>
        <h2>Личный кабинет</h2>
        <h3>Вы авторизованы</h3>
        <p><a href="index.php">Вернуться на главную</a></p>
        <a href="?do=logout">Выйти</a>
        <p>Контент для авторизованного пользователя</p>

        <?
        $_SESSION['birthday-date'] = $_SESSION['birthday-date'] ?? null;
        $_SESSION['age'] = $_SESSION['age'] ?? null;
        $_SESSION['until-birthday'] = $_SESSION['until-birthday'] ?? null;
        ?>

        <p>Имя: <? echo $_SESSION['username'] ?></p>
        <? if ($_SESSION['birthday-date']) { ?>
            <p>Дата рождения: <? echo $_SESSION['birthday-date'] ?></p>
            <p>Возраст: <? echo $_SESSION['age'] ?></p>
            <p><? echo $_SESSION['until-birthday'] ?></p>
        <? } ?>

        <!-- Дата рождения -->
        <?
        $today = date("Y-m-d");
        $birthdayDate = $_POST['date'] ?? $today;
        $arrayDate = explode("-", $birthdayDate);
        $day = $arrayDate[2];
        $month = $arrayDate[1];
        $year = $arrayDate[0];

        if (!$_SESSION['birthday-date']) { ?>

            <div class="centr">
                <form method="post">
                    <p class="text">Введите дату рождения</p>
                    <input type="date" name="date" value="<? echo "$year-$month-$day" ?>" min="<? echo hundredYearsAgo($today) ?>" max="<? echo $today ?>" />
                    <button type="submit" name="submitAge">Ввод</button>
                </form>
            </div>

        <? }

        if (isset($_POST['submitAge'])) {
            $_SESSION['birthday-date'] = "$day.$month.$year";
            $age = getAge($day, $month, $year);
            $ageGetYearsStr = getYearsStr($age);
            $_SESSION['age'] = "$age $ageGetYearsStr";
            $_SESSION['until-birthday'] = untilBirthday($day, $month, $year);
            // $_SESSION['time'] = date('d.m.Y H:i:s');
            header('Location: login.php');
        }
    } else {
        header('Location: index.php');
        exit;
    } ?>

</body>



<?
// Сессии
echo '<br>';
echo '<br>';
echo '<br>';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';


// $_SESSION['time'] = date('d.m.Y H:i:s');   // дата и время присваиваются только при вводе даты рождения
// нужно для проверки изменения времени при вводе даты рождения
// $_SESSION['time'], $_SESSION['age'] и $_SESSION['until-birthday'] находятся в одном участке кода
// т.е. с течением времени будет показана актуальная информация о возрасте и сколько дней до дня рождения.