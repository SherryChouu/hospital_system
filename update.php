<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 檢查是否收到 POST 請求
    // 建立與數據庫的連接
    $serverName = "SHERRYCHOU";
    $connectionOptions = array(
        "Database" => "health_system",
        "Uid" => "sa",
        "PWD" => "Sherry920710",
        "CharacterSet" => "UTF-8"
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        // 檢查數據庫連接是否成功
        die(print_r(sqlsrv_errors(), true));
    }

    // 檢查是否獲取了所有必要的 POST 參數
    if (!isset($_POST['patient_id'], $_POST['chinese_name'], $_POST['english_name'], $_POST['id_number'], $_POST['sexual'], $_POST['birthdate'], $_POST['dietary_habits'], $_POST['appointment_status'], $_POST['package_name'])) {
        die("缺少必要的 POST 參數");
    }

    // 獲取所有 POST 參數
    $patient_id = $_POST['patient_id'];
    $chinese_name = $_POST['chinese_name'];
    $english_name = $_POST['english_name'];
    $id_number = $_POST['id_number'];
    $sexual = $_POST['sexual'];
    $birthdate = $_POST['birthdate'];
    $dietary_habits = $_POST['dietary_habits'];
    $appointment_status = $_POST['appointment_status'];
    $package_name = $_POST['package_name'];

    // 在進行 SQL 更新之前，輸出這些值，以確認它們是否正確
    var_dump($patient_id, $chinese_name, $english_name, $id_number, $sexual, $birthdate, $dietary_habits, $appointment_status, $package_name);

    // 預約項目與 ID 的映射
    $package_id_map = array(
        '卓越C套餐' => 1,
        '卓越M套餐' => 2,
        '尊爵A套餐' => 3,
        '尊爵B套餐' => 4,
        '尊爵C套餐' => 5,
        '尊爵D套餐' => 6
    );

    // 檢查套餐名稱是否在映射表中
    if (!isset($package_id_map[$package_name])) {
        die("未知的套餐名稱: " . $package_name);
    }

    // 獲取套餐 ID
    $package_id = $package_id_map[$package_name];

    // 更新資料庫中的資料
    $sql = "UPDATE dbo.Patient SET 
            ChineseName = ?, 
            EnglishName = ?, 
            IDNumber = ?, 
            Sexual = ?, 
            Birthdate = ?, 
            dietary_habits = ?, 
            appointment_status = ?, 
            Package_id = ?
            WHERE PatientID = ?";

    // 準備 SQL 語句的參數
    $params = array($chinese_name, $english_name, $id_number, $sexual, $birthdate, $dietary_habits, $appointment_status, $package_id, $patient_id);

    // 執行 SQL 查詢
    $stmt = sqlsrv_query($conn, $sql, $params);

    // 檢查是否有錯誤
    if ($stmt === false) {
        echo "更新失敗: " . print_r(sqlsrv_errors(), true);
    } else {
        echo "更新成功";
    }

    // 關閉數據庫連接
    sqlsrv_close($conn);
}
?>
