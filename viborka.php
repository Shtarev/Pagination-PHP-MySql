<?php
/*
|--------------------------------------------------------------------------
| Постраничная навигация (Example)
|--------------------------------------------------------------------------
|
| $limit = 7; // сколько выводить пунктов навигации
| $order = 'ASC'; // в каком порядка выводить ASC || DESC
|
*/
$mem0 = memory_get_usage();  
$tim0 = microtime(true);

if(@!$mysqli = mysqli_connect('localhost', 'root', 'root', 'test')) {
    die('<font color="#FF0000">Не удалось подключиться к базе<br>Ошибка '.mysqli_connect_errno().'</font><br>');
}

$limit = 7;
$order = 'ASC';
$von = 0;
$arr = array();

for($count = $mysqli->query("SELECT COUNT(*) FROM tests")->fetch_array(MYSQLI_NUM)[0]; $count > $von; $von += $limit) {
    $arr[] = $mysqli->query("SELECT id, title FROM `tests` ORDER BY id $order LIMIT $von, $limit")->fetch_all(MYSQLI_ASSOC);
}

if(($start = 0) >= ($end = count($arr))) {
    echo 'В базе нет записей';
}
else {
    if(isset($_GET['viborka'])) {
        $viborka = (int)$_GET['viborka'];
        
        if($viborka > $end) {
            $viborka = --$end;
        }
        elseif($viborka < $start) {
            $viborka = $start;
        }
        else {
            $viborka = $viborka;
        }
    }
    else {
        $viborka = $start;
    }

    echo 'Вывод данных:<br>';
    foreach($arr[$viborka] as $key => $value) {
        echo '<a href="?artikel='.$value['id'].'">'.$value['title'].'</a><br>';
    }
    echo '<hr>Вывод пунктов:<br>';
    foreach($arr as $key => $value) {
        if($key != $viborka) {
            echo '<a href="?viborka='.$key.'">'.$key.') '.count($value).'-записей</a><br>';
        }
        else {
            echo '<b><a href="?viborka='.$key.'">'.$key.') '.count($value).'-записей</a></b><br>';
        }
    }
}

echo "<hr>Количество памяти выделенной PHP: ".$mem0." байт<br>На обработку скриптов потрачено байт: ".(memory_get_usage()-$mem0)."<br>На обработку скриптов потрачено секунд: ".(microtime(true) - $tim0)."<hr>";  