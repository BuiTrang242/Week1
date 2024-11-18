<?php
$cookie_name = "account";
$cookie_value = "Bui Trang";
// 86400 = 1 day
?>
<html>
    
    <body>
        
        <?php
    if (!isset($_COOKIE[$cookie_name])) {
        // setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        echo "Bạn cần đăng nhập trang web này.";
        echo "<a href='index.php'>Đăng nhập</a>";
    } else {
        echo "Cookie '" . $cookie_name . "' đã được tạo!<br>";
        echo "Giá trị là: " . $_COOKIE[$cookie_name];
    }
    ?>

    <p><strong>Chú ý:</strong> Có thể bạn phải tại lại trang web này
        để tạo cookie và đọc cookie.</p>