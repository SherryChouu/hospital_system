<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <title>hospital</title>
</head>
<body>

<main>       
    <div class="nav">
        <h1 class= "title"><a href="dashboard.php">預約管理系統</a></h1>
    </div>
</main>

<!--篩選方式-->
<div class="choose-container">
    <form class="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    
        <!--用健檢日期篩選名單-->
        <label for="date" >依健檢日期查詢</label> 
        <input type="date" id="date" name="date">

        <!--用預約項目篩選名單-->
        <label for="package">依預約項目查詢</label>
        <select type="package" id="package" name="package">
            <option value="">全部</option>
            <option value="1">卓越C套餐</option>
            <option value="2">卓越M套餐</option>
            <option value="3">尊爵A套餐</option>
            <option value="4">尊爵B套餐</option>
            <option value="5">尊爵C套餐</option>
            <option value="6">尊爵D套餐</option>
        </select>

        <!--用狀態篩選名單-->
        <label for="status" >依狀態查詢</label> 
        <select type="status" id="status" name="status">
        <option value="">全部</option>
        <option value="已確認">已確認</option>
        <option value="已取消">已取消</option>
        <option value="待確認">待確認</option>
        </select>

        <!--用身分證字號或姓名來篩選名單-->
        <label for="name"></label>
        <input type="text" id="keyword" name="keyword" placeholder="輸入姓名或身份證字號">
        <input type="submit" name="submit" class="bt1" value="查詢">

    </form>
</div>


<!--顯示項目-->
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
            <th>狀態</th>
            <th>編輯</th>
        </tr>

        <?php
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
                // 處理狀態篩選
                $status = !empty($_POST["status"]) ? $_POST["status"] : null;
                // 處理姓名或身份證字號篩選
                $keyword = !empty($_POST["keyword"]) ? $_POST["keyword"] : null;


                // 查詢預約資料並以表格形式顯示
                $sql = "SELECT ChineseName, EnglishName, IDNumber, Sexual, Birthdate, dietary_habits, appointment_status, 
                        CASE 
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
                // 添加狀態篩選條件
                if (!empty($status)) {
                    $sql .= " AND appointment_status = ?";
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
                if (!empty($status)) {
                    $params[] = $status;
                }
                if (!empty($keyword)) {
                    $params[] = "%$keyword%";
                    $params[] = $keyword;
                }


                // 執行 SQL 查詢
                $result = sqlsrv_query($conn, $sql, $params);

                if ($result === false) {
                    echo "查詢預約資料失敗: " . print_r(sqlsrv_errors(), true);
                } else {
                if (sqlsrv_has_rows($result)) {
                echo "查詢成功";

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
                        echo "<td>" . $row['appointment_status'] . "</td>";
                        echo "<td>";
                        echo "<button class='edit-button' onclick=\"openEditModal('{$row['ChineseName']}', '{$row['EnglishName']}', '{$row['IDNumber']}', '{$row['Sexual']}', '{$row['Birthdate']->format('Y-m-d')}', '{$row['dietary_habits']}', '{$row['appointment_status']}', '{$row['Package_name']}')\">編輯</button>";
                        echo "</td>";
                        echo "</tr>";

                    }
                }else{
                    echo "此篩選條件尚未有預約資料！";
                    }
                }

                // 關閉資料庫連接
                sqlsrv_close($conn);
            }
        ?>
    </table>

<!-- 模態對話框 -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">編輯資料</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form2" id="editForm" action="update.php" method="post">
                        <input type="hidden" id="patient_id" name="patient_id">
                        <div class="edit-group">
                            <label for="chinese_name">中文姓名</label>
                            <input type="text1" class="form-control" id="chinese_name" name="chinese_name">
                        </div>
                        <div class="edit-group">
                            <label for="english_name">英文姓名</label>
                            <input type="text1" class="form-control" id="english_name" name="english_name">
                        </div>
                        <div class="edit-group">
                            <label for="id_number">身份證字號</label>
                            <input type="text1" class="form-control" id="id_number" name="id_number">
                        </div>
                        <div class="edit-group">
                            <label for="sexual">生理性別</label>
                            <input type="text1" class="form-control" id="sexual" name="sexual">
                        </div>
                        <div class="edit-group">
                            <label for="birthdate">出生日期</label>
                            <input type="date1" class="form-control" id="birthdate" name="birthdate">
                        </div>
                        <div class="edit-group">
                            <label for="dietary_habits">飲食習慣</label>
                            <input type="text1" class="form-control" id="dietary_habits" name="dietary_habits">
                        </div>
                        <div class="edit-group">
                            <label for="appointment_status">狀態</label>
                            <input type="text1" class="form-control" id="appointment_status" name="appointment_status">
                        </div>
                        <div class="edit-group">
                            <label for="package_name">預約項目</label>
                            <input type="text1" class="form-control" id="package_name" name="package_name">
                        </div>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 引入Bootstrap的JavaScript依賴 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script> <!--Popper.js 用於管理彈出框（如工具提示和彈出菜單）-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
          function openEditModal(ChineseName, EnglishName, IDNumber, Sexual, Birthdate, dietary_habits, appointment_status, Package_name, ReservationDate) {
            // 填充模態對話框中的表單字段
            document.getElementById('chinese_name').value = ChineseName;
            document.getElementById('english_name').value = EnglishName;
            document.getElementById('id_number').value = IDNumber;
            document.getElementById('sexual').value = Sexual;
            document.getElementById('birthdate').value = Birthdate;
            document.getElementById('dietary_habits').value = dietary_habits;
            document.getElementById('appointment_status').value =appointment_status;
            document.getElementById('package_name').value = Package_name;
            //document.getElementById('reservation_date').value = ReservationDate;
            
            // 顯示模態對話框
            $('#editModal').modal('show');
    }
    
    </script>
</body>
</html>
