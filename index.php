<?php
// Главная страница
session_start();
$auth = $_SESSION['auth'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA Salon</title>
</head>

<body>
    <h1>SPA Salon</h1>

    <? if (!$auth) { ?>
        <h3>Личный кабинет</h3>
        <form action="login.php" method="post">
            <input name="login" type="text" placeholder="Логин">
            <input name="password" type="password" placeholder="Пароль">
            <input name="submit" type="submit" value="Войти">
        </form>
    <? }


    if (isset($_GET['do']) && $_GET['do'] == 'logout') {
        unset($_SESSION['auth']);
        unset($_SESSION['username']);
        unset($_SESSION['age']);
        unset($_SESSION['until-birthday']);
        session_regenerate_id();
        header('Location: index.php');
        exit;
    }

    if ($auth) { ?>
        <h3>Вы авторизованы</h3>
        <p><a href="login.php">Вернуться в личный кабинет</a></p>
        <a href="?do=logout">Выйти</a>
    <? } ?>


    <!-- для всех пользователей: актуальные услуги, акции, фото салона -->
    <ul>
        <li>Массаж горячими камнями</li>
        <li>Молочная ванна</li>
        <li>Парафинотерапия</li>
        <li>Маски для тела</li>
        <li>Воздействие паром</li>
        <li>Маникюр и педикюр</li>
        <li>Обертывание</li>
        <li>Грязевая ванна</li>
    </ul>

    <!-- для авторизованных -->
    <? if ($auth) {
        $thisTime = new DateTime(date('d.m.Y H:i:s'));   // Текущии дата и время
        $timeUp = new DateTime($_SESSION['timeUp']);     // Дата и время истечения акции
        $interval = $thisTime->diff($timeUp);            // Разница
        if ($timeUp > $thisTime) { ?>
            <p>Для вас действует персональная скидка в размере 10%</p>
            <? echo $interval->format('До истечения персональной скидки осталось: %H ч. %I мин. %S сек.');
        }
    } ?>

    <!-- для всех -->
    <h3>Галерея</h3>
    <img title="" src="spa-photo/photo1.jpeg" alt="" width="200" draggable="false">
    <img title="" src="spa-photo/photo2.jpg" alt="" width="200" draggable="false">
    <img title="" src="spa-photo/photo3.jpg" alt="" width="200" draggable="false">
    <img title="" src="spa-photo/photo4.jpg" alt="" width="200" draggable="false">
    <img title="" src="spa-photo/photo5.jpg" alt="" width="200" draggable="false">
    <img title="" src="spa-photo/photo6.jpg" alt="" width="200" draggable="false">



    <?
    // Сессии
    // echo '<br>';
    // echo '<br>';
    // echo '<br>';
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    ?>

</body>

</html>