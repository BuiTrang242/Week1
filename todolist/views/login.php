<style>
    form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #f2f2f2;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 2px #ccc;
    }

    form label {
        display: block;
        margin-bottom: 10px;
    }

    form input[type="text"],
    form input[type="password"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    form input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        opacity: 0.8;
    }
</style>

<form action="" method="post">
    <label for="">Tên đăng nhập</label>
    <input type="text" name="username" required>
    <label for="">Password</label>
    <input type="password" name="password" required>
    <input type="submit" value="Đăng nhập" />
    <a href="/register.php">Đăng ký</a>
    <p id="message"></p>
</form>
<?php
$isOk = false;
$message = '';
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $account_service = new Database('data/accout.json');
    $data_account = $account_service->getAll();
    for ($i = 0; $i < count($data_account); $i++) {
        if ($data_account[$i]['user_name'] == $username && $data_account[$i]['password'] == $password) {
            setcookie('account', $username, time() + (86400 * 30), "/");
            $isOk = true;
            break;
        }
    }
    if ($isOk) {
        header('Location: index.php');
    } else {
        $message = 'Sai tài khoản';
    }
}


?>
<script>
    document.getElementById('message').innerHTML = '<?php echo $message ?>';
    document.getElementById('message').style.color = '<?php echo $isOk ? 'green' : 'red' ?>';
</script>
