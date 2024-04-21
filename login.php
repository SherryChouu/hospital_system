<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>登入</title>
</head>
<body>
    
<div class="login-container">
    <h2>管理者登入</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">帳號：</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">密碼：</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="登入">
    </form>
</div>



<?php
session_start();


// 檢查是否有 POST 請求提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取用戶輸入的用戶名和密碼
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 在此處進行用戶名和密碼的驗證，可以查詢資料庫中的用戶表格或其他存儲位置

    // 假設驗證成功，將用戶信息保存到會話中
    $_SESSION['username'] = $username;

    // 跳轉到後台管理系統的首頁或其他需要登入的頁面
    header('Location: dashboard.php');
    exit;
}
?>


</body>
</html>
