<?php
require 'users.php';

// функция getUsersList() возвращает массив всех пользователей и хэшей их паролей;

function getUsersList($users) {
    return $users;
}
// print_r(getUsersList($users));


// функция existsUser($login) проверяет, существует ли пользователь с указанным логином;

function existsUser($login, $users) {
    return in_array($login, array_column(getUsersList($users),'login'));
}
// in_array - проверяет, есть ли в масcиве значение.
// array_column - возвращает массив из значений одного столбца входного массива.
// echo existsUser('alex', $users);


// функция возвращает информацию о пользователе с указанным логином иначе 0 (пусто)
function getUser($login, $users) {
    $users = getUsersList($users);
    foreach ($users as $user) {
        if ($login == $user['login']) {
            return $user;
        }
    }
}
// print_r(getUser('alex', $users));


// функция checkPassword($login, $password) возвращает true когда существует пользователь с указанным логином и введенный им пароль прошел проверку, иначе — false;

function checkPassword($login, $password, $users) {
    if (existsUser($login, $users) === true) {
        if (password_verify($password, getUser($login, $users)['password'])) {   // если логин пользователя найден проверяем хэш пароля пользователя
            return true;
        }
    }
    return false;
}
// print_r(checkPassword('alex', 45612, $users));


// функция getCurrentUser() которая возвращает либо имя вошедшего на сайт пользователя, либо null.

function getCurrentUser($users) {
    if (isset($_SESSION['username'])) {   // Проверяем, есть ли элемент с индексом 'username' в массиве сессии
        if (existsUser($_SESSION['username'], $users)) {   // Проверяем существует ли пользователь с заданным логином
            return $_SESSION['username'];
        }
    }
}


// функция получения возраста
function getAge($day, $month, $year) {
    if ($month > date('m') || $month == date('m') && $day > date('d')) {
        $years = (date('Y') - $year - 1);
    } else {
        $years = (date('Y') - $year);
    }
    return $years;
}

// функция ограничения возраста - минимальная дата рождения (-100 лет от настоящего времени)
function hundredYearsAgo($today) {
    $arrayDateToday = explode("-", $today);
    $day = $arrayDateToday[2];
    $month = $arrayDateToday[1];
    $year = $arrayDateToday[0];
    $hundredYears = $year - 100;
    $hundredYearsAgo = "$hundredYears-$month-$day";
    return $hundredYearsAgo;
}

// функция получения "год", "года", "лет" в зависимости от числа
function getYearsStr($years) {
    // $years = $_SESSION['age'];
    $lastDigit = substr($years, -1);
    if ($lastDigit == 1 && $years != 11) {
        $yearsStr = 'год';
    } elseif ($lastDigit >= 2 && $lastDigit <= 4 && $years != 12 && $years != 13 && $years != 14) {
        $yearsStr = 'года';
    } else {
        $yearsStr = 'лет';
    }
    return $yearsStr;
}

// функция получения сколько осталось до дня рождения
function untilBirthday($day, $month, $year) {

    if ($month > date('m') || $month == date('m') && $day > date('d')) {
        $years = date('Y') - $year - 1;
    } else {
        $years = date('Y') - $year;
    }

    $nextYear = $year + $years + 1;

    if ($month == date('m') && $day < date('d')) {
        $months = 11;
        $days = $days = cal_days_in_month(CAL_GREGORIAN, 2, $nextYear) - date('d') + $day;
    } elseif ($month < date('m') && $day < date('d')) {
        $months = 12 - date('m') + $month - 1;
        $days = date('t') - date('d') + $day;
    } elseif ($month < date('m') && $day >= date('d')) {
        $months = 12 - date('m') + $month;
        $days = $day - date('d');
    } elseif ($month >= date('m') && $day >= date('d')) {
        $months = $month - date('m');
        $days = $day - date('d');
    } elseif ($month >= date('m') && $day < date('d')) {
        $months = $month - date('m') - 1;
        $days = date('t') - date('d') + $day;
    }

    if ($months == 1) {
        $monthStr = 'месяц';
    } elseif ($months >= 2 && $months <= 4) {
        $monthStr = 'месяца';
    } else {
        $monthStr = 'месяцев';
    }

    $lastDigitDay = substr($days, -1);
    if ($lastDigitDay == 1 && $days != 11) {
        $dayStr = 'день';
    } elseif ($days >= 2 && $days <= 4 || $days >= 22 && $days <= 24) {
        $dayStr = 'дня';
    } else {
        $dayStr = 'дней';
    }

    if ($days == 0 && $months == 0) {
        return "Сегодня у вас День Рождения! Поздравляем!<br>Для вас сегодня действует персональная скидка 5% на все услуги салона.";
    } elseif ($days == 0) {
        return "До дня рождения осталось: ровно $months $monthStr";
    } elseif ($months == 0) {
        return "До дня рождения осталось: $days $dayStr";
    } else {
        return "До дня рождения осталось: $months $monthStr, $days $dayStr";
    }
}