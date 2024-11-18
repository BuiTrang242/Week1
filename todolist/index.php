<?php
require './Database.php';
require './classes/Todolist.php';
require './classes/Task.php';
function dd($content)
{
    echo '<pre>';
    print_r($content);
    echo '</pre>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo list</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <?php
    $login_status = isset($_COOKIE['account']) ? true : false;
    if (!$login_status) {

        require './views/login.php';
    } else {
        require './views/todolist.php';
    }
    ?>
</body>

</html>