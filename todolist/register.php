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
    <label for="">Nhập tên đăng nhập</label>
    <input type="text" name="username" required>
    <label for="">Nhập Password</label>
    <input type="password" name="password" required>
    <label for="">Nhập lại Password</label>
    <input type="password" name="re_password" required>
    <input type="submit" value="Đăng ký" />
    <p id="message"></p>
</form>
<?php
require './Database.php';
$isOk = false;
$message = '';
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['re_password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];
    if ($password != $re_password) {
        $isOk = false;
        $message = 'Mật khẩu không trùng khớp';

    } else {
        $account_service = new Database('data/accout.json');
        $data_account = $account_service->getAll();
        $isExist = false;
        for ($i = 0; $i < count($data_account); $i++) {
            if ($data_account[$i]['user_name'] == $username) {
                $isExist = true;
                break;
            }
        }
        if ($isExist) {
            $isOk = false;
            $message = 'Tài khoản đã tạo';
        } else {
            $isOk = true;
            $account_service->insert(
                [
                    'user_name' => $username,
                    'password' => $password,
                    'id' => count($data_account)+1,
                ]
            );
        }
    }

}

if ($isOk) {
    header('Location: index.php');
}
?>


<script>
    document.getElementById('message').innerHTML = '<?php echo $message ?>';
    document.getElementById('message').style.color = '<?php echo $isOk ? 'green' : 'red' ?>';
</script>