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
                <li><a href="">預約名單</a></li>
                <li><a href="revise.php">預約資料修改</a></li>
                <li><a href="">使用者登入</a></li>
            </ul>
        </div>
    </nav>

</main>


    <h1>預約資料</h1>

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
        // 連接到資料庫
        $serverName = "SHERRYCHOU";
        $connectionOptions = array(
            "Database" => "health_system",
            "Uid" => "sa",
            "PWD" => "Sherry920710",
            "CharacterSet" => "UTF-8"
        );
        $conn = sqlsrv_connect($serverName, $connectionOptions);

        // 查詢預約資料並以表格形式顯示
        $sql = "SELECT Package_id, ReservationDate, ChineseName, EnglishName, IDNumber, Sexual, Birthdate, Address, ResidenceAddress, Phone, Email, Wedding  FROM Patient";
        $result = sqlsrv_query($conn, $sql);

        if ($result === false) {
            echo "查詢預約資料失敗: " . print_r(sqlsrv_errors(), true);
        } else {
            // 遍歷結果集，顯示每一筆預約資料
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['Package_id'] . "</td>";
                echo "<td>" . $row['ReservationDate'] . "</td>";
                echo "<td>" . $row['ChineseName'] . "</td>";
                echo "<td>" . $row['EnglishName'] . "</td>";
                echo "<td>" . $row['IDNumber'] . "</td>";
                echo "<td>" . $row['Sexual'] . "</td>";
                echo "<td>" . $row['Birthdate']->format('Y-m-d') . "</td>";
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
        ?>
    </table>
</body>
</html>
