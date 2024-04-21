<?php

// 確保收到 POST 請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取表單提交的數據
    $patientID = $_POST["patient_id"];
    $newAddress = $_POST["new_address"];

    // 準備更新資料的 SQL 語句
    $sql = "UPDATE Patient SET Address = ? WHERE PatientID = ?";

    // 使用 PDO 預備語句，防止 SQL 注入攻擊
    $stmt = $conn->prepare($sql);

    // 綁定參數
    $stmt->bindParam(1, $newAddress);
    $stmt->bindParam(2, $patientID);

    // 執行 SQL 語句
    if ($stmt->execute()) {
        echo "資料更新成功";
    } else {
        echo "資料更新失敗";
    }
}
?>

<!-- HTML 表單 -->
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約資料修改</title>
</head>
<body>
    <h2>預約資料修改</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="patient_id">病人ID：</label>
        <input type="text" id="patient_id" name="patient_id"><br><br>
        <label for="new_address">新地址：</label>
        <input type="text" id="new_address" name="new_address"><br><br>
        <input type="submit" value="提交">
    </form>
</body>
</html>
