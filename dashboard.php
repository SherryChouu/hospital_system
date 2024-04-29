<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>hospital</title>
</head>
<body>

<main>       
    <div class="navbar">
        <h1 class= "title"><a href="index.php">預約管理系統</a></h1>
        <nav>
            <ul class="flex-nav">
                <li><a href="">健檢預約名單</a></li>
                <li><a href="">其他</a></li>
            </ul>
        </nav>
    </div>
</main>

<!--篩選方式-->
<div class="choose-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    
        <!--用健檢日期篩選名單-->
        <label for="date" >選擇健檢日期：</label> 
        <input type="date" id="date" name="date">

        <!--用預約項目篩選名單-->
        <label for="package">選擇預約項目：</label>
        <select id="package" name="package">
            <option value="">全部</option>
            <option value="1">卓越C套餐</option>
            <option value="2">卓越M套餐</option>
            <option value="3">尊爵A套餐</option>
            <option value="4">尊爵B套餐</option>
            <option value="5">尊爵C套餐</option>
            <option value="6">尊爵D套餐</option>
        </select>

        <!--用身分證字號或姓名來篩選名單-->
        <label for="name">輸入姓名或身份證字號：</label>
        <input type="text" id="keyword" name="keyword">

        <input type="submit" name="submit" class="bt1" value="查詢">

    </form>
</div>

<table border="1"> 
    <tr>
        <th>預約日期</th>
        <th>預約項目</th>
        <th>中文姓名</th>
        <th>英文姓名</th>
        <th>身份證字號</th>
        <th>生理性別</th>
        <th>出生日期</th>
        <th>飲食習慣</th>
    </tr>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["date"])) {
        // 設定連線至資料庫的伺服器名稱和埠號
        $serverName = "SHERRYCHOU";

        // 設定連線選項，包括資料庫名稱、使用者名稱和密碼
        $connectionOptions = array(
            "Database" => "health_system", // 資料庫名稱
            "Uid" => "sa", // 使用者名稱
            "PWD" => "Sherry920710", // 密碼
            "CharacterSet" => "UTF-8"
        );

        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // 處理套餐名稱篩選
            $package = isset($_POST["package"]) ? $_POST["package"] : null;
            // 處理日期篩選
            $date = !empty($_POST["date"]) ? $_POST["date"] : null;
            // 處理姓名或身份證字號篩選
            $keyword = !empty($_POST["keyword"]) ? $_POST["keyword"] : null;


            // 查詢預約資料並以表格形式顯示
            $sql = "SELECT ChineseName, EnglishName, IDNumber, Sexual, Birthdate, dietary_habits, CASE 
                        WHEN Package_id = 1 THEN '卓越C套餐'
                        WHEN Package_id = 2 THEN '卓越M套餐'
                        WHEN Package_id = 3 THEN '尊爵A套餐'
                        WHEN Package_id = 4 THEN '尊爵B套餐'
                        WHEN Package_id = 5 THEN '尊爵C套餐'
                        WHEN Package_id = 6 THEN '尊爵D套餐'
                        ELSE '未選擇'
                    END AS Package_name, 
                    ReservationDate 
                    FROM Patient 
                    WHERE 1 = 1";

            // 添加套餐名稱篩選條件
            if (!empty($package)) {
                $sql .= " AND Package_id = ?";
            }

            // 添加日期篩選條件
            if (!empty($date)) {
                $sql .= " AND CONVERT(date, ReservationDate) = ?";
            }

            // 添加姓名或身份證字號篩選條件
            if (!empty($keyword)) {
                $sql .= " AND (ChineseName LIKE ? OR IDNumber = ?)";
            }

            // 構建參數陣列
            $params = array();
            if (!empty($package)) {
                $params[] = $package;
            }
            if (!empty($date)) {
                $params[] = $date;
            }
            if (!empty($keyword)) {
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
                $params[] = $keyword;
            }


            // 執行 SQL 查詢
            $result = sqlsrv_query($conn, $sql, $params);

            if ($result === false) {
                echo "查詢預約資料失敗: " . print_r(sqlsrv_errors(), true);
            } else {
                // 遍歷結果集，顯示每一筆預約資料
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . ($row['ReservationDate'] ? $row['ReservationDate']->format('Y-m-d') : '') . "</td>"; //先確認是否為空值
                    echo "<td>" . $row['Package_name'] . "</td>";              
                    echo "<td>" . $row['ChineseName'] . "</td>";
                    echo "<td>" . $row['EnglishName'] . "</td>";
                    echo "<td>" . $row['IDNumber'] . "</td>";
                    echo "<td>" . $row['Sexual'] . "</td>";
                    echo "<td>" . ($row['Birthdate'] ? $row['Birthdate']->format('Y-m-d') : '') . "</td>";
                    echo "<td>" . $row['dietary_habits'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
        }

        // 關閉資料庫連接
        sqlsrv_close($conn);
        }
    }
    ?>

</table>
</body>
</html>
