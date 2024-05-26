<?php
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
    exit;
}

$patient_id = $_POST['patient_id'];
$chinese_name = $_POST['chinese_name'];
$english_name = $_POST['english_name'];
$id_number = $_POST['id_number'];
$sexual = $_POST['sexual'];
$birthdate = $_POST['birthdate'];
$dietary_habits = $_POST['dietary_habits'];
$appointment_status = $_POST['appointment_status'];

$sql = "UPDATE Patient SET 
        ChineseName = ?, 
        EnglishName = ?, 
        IDNumber = ?, 
        Sexual = ?, 
        Birthdate = ?, 
        dietary_habits = ?, 
        appointment_status = ?
        WHERE PatientID = ?";

$params = array($chinese_name, $english_name, $id_number, $sexual, $birthdate, $dietary_habits, $appointment_status, $patient_id);

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    echo json_encode(array("success" => false, "message" => "更新失敗: " . print_r(sqlsrv_errors(), true)));
    exit;
}

sqlsrv_close($conn);
echo json_encode(array("success" => true));
?>
