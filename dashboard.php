<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>hospital</title>
</head>
<body>

<main>       
    <div class="navbar">
        <h1 class= "title"><a href="index.php">後台管理系統</a></h1>
        <nav>
            <ul class="flex-nav">
                <li><a href="">健檢預約名單</a></li>
                <li><a href="revise.php">預約資料修改</a></li>
            </ul>
        </nav>
    </div>
</main>

<h1>預約資料</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="date">選擇日期:</label>
    <input type="date" id="date" name="date">
    <input type="submit" value="查詢">
</form>

<table border="1">
    <tr>
        <th>預約日期</th>
        <th>預約項目</th>
        <th>中文姓名</th>
        <th>英文姓名</th>
        <th>身份證字號</th>
        <th>生理性別</th>
        <th>出生日期</th>
        <th>通訊地址</th>
        <th>戶籍地址</th>
        <th>連絡電話</th>
        <th>電子郵件</th>
        <th>婚姻狀態</th>
    </tr>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["date"])) {
        // 連接到資料庫
        $serverName = "DESKTOP-947P2F9";
        $connectionOptions = array(
            "Database" => "health_system", // 資料庫名稱
            "Uid" => "sa", // 使用者名稱
            "PWD" => "1106Evelyn", // 密碼
            "CharacterSet" => "UTF-8"
        );
        $conn = sqlsrv_connect($serverName, $connectionOptions);

        // 查詢預約資料並以表格形式顯示
        $sql = "SELECT  ChineseName, EnglishName, IDNumber, Sexual, Birthdate, Address, ResidenceAddress, Phone, Email, Wedding ,Package_id, ReservationDate 
                FROM Patient 
                WHERE CONVERT(date, ReservationDate) = ?";

        $params = array($_POST["date"]);
        $result = sqlsrv_query($conn, $sql, $params);

        if ($result === false) {
            echo "查詢預約資料失敗: " . print_r(sqlsrv_errors(), true);
        } else {
            // 遍歷結果集，顯示每一筆預約資料
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . ($row['ReservationDate'] ? $row['ReservationDate']->format('Y-m-d') : '') . "</td>"; //先確認是否為空值
                echo "<td>" . $row['Package_id'] . "</td>";              
                echo "<td>" . $row['ChineseName'] . "</td>";
                echo "<td>" . $row['EnglishName'] . "</td>";
                echo "<td>" . $row['IDNumber'] . "</td>";
                echo "<td>" . $row['Sexual'] . "</td>";
                echo "<td>" . ($row['Birthdate'] ? $row['Birthdate']->format('Y-m-d') : '') . "</td>";
                echo "<td>" . $row['Address'] . "</td>";
                echo "<td>" . $row['ResidenceAddress'] . "</td>";
                echo "<td>" . $row['Phone'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['Wedding'] . "</td>";
                echo "</tr>";
            }
        }

        // 關閉資料庫連接
        sqlsrv_close($conn);
    }
    ?>
</table>
</body>
</html>
