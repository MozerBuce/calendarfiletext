<?php
$file = 'table_data.txt';
if (isset($_POST['markedDates'])) {
    $markedDates = $_POST['markedDates'];
    file_put_contents($file, $markedDates);
}