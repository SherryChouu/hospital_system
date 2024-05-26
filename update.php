<?php
//設定 HTTP 回應頭的內容類型是 JSON 格式(表示接下來的回應將是 JSON)
header('Content-Type: application/json');

$serverName = "SHERRYCHOU";
$connectionOptions = array(
    "Database" => "health_system",
    "Uid" => "sa",
    "PWD" => "Sherry920710",
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    echo json_encode(array("success" => false, "message" => "資料庫連接失敗"));
    exit;//檢查資料庫連接是否成功(失敗的話會回傳JSON格是錯誤的訊息)
}

//使用 POST 方法提交，表單資料會被傳遞到伺服器端並存儲在 $_POST 陣列中
//從 $_POST 陣列中獲取提交的表單數據並賦值給變數(對應資料庫中的欄位)
$patient_id = $_POST['patient_id'];
$chinese_name = $_POST['chinese_name'];
$english_name = $_POST['english_name'];
$id_number = $_POST['id_number'];
$sexual = $_POST['sexual'];
$birthdate = $_POST['birthdate'];
$dietary_habits = $_POST['dietary_habits'];
$appointment_status = $_POST['appointment_status'];

//SQL 的更新
//?是佔位符，會被實際的變數替換
$sql = "UPDATE Patient SET 
        ChineseName = ?, 
        EnglishName = ?, 
        IDNumber = ?, 
        Sexual = ?, 
        Birthdate = ?, 
        dietary_habits = ?, 
        appointment_status = ?
        WHERE PatientID = ?";

//定義一個陣列 $params，包含 SQL 語句中所有的參數(這些參數會按順序替換 SQL 語句中的問號)
$params = array($chinese_name, $english_name, $id_number, $sexual, $birthdate, $dietary_habits, $appointment_status, $patient_id);

//執行 SQL(sqlsrv_query 函數、傳入資料庫連接 $conn、SQL 語句 $sql 、參數 $params
$stmt = sqlsrv_query($conn, $sql, $params);

//檢查 SQL 語句是否執行成功。失敗會回傳 JSON 格式的錯誤消息並退出程式
if ($stmt === false) {
    echo json_encode(array("success" => false, "message" => "更新失敗: " . print_r(sqlsrv_errors(), true)));
    exit;
}

sqlsrv_close($conn);

//回傳 JSON 格式的成功消息，表示資料更新操作已成功完成
echo json_encode(array("success" => true));

?>
