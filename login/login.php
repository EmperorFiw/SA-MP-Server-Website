<?php
session_start();
$_SESSION['username'] = null;
require "../db.php";
$secret = "6LcQkPwpAAAAAHhyO2xZGmtF6pm4lS5Ic-mVnN8s";

if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
    $verifyResponse = file_get_contents('https://google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        echo json_encode(array("status" => "error", "msg" => "reCAPTCHA ไม่ถูกต้อง"));
        exit;
    }

    if (isset($_POST['user'], $_POST['password'])) {
        $user = trim($_POST['user']);
        $pass = trim($_POST['password']);

        if (empty($user)) {
            echo json_encode(array("status" => "error", "msg" => "โปรดกรอกชื่อผู้ใช้"));
            exit();
        } elseif (empty($pass)) {
            echo json_encode(array("status" => "error", "msg" => "โปรดกรอกรหัสผ่าน"));
            exit();
        } elseif (strlen($pass) < 4) {
            echo json_encode(array("status" => "error", "msg" => "รหัสผ่านต้องไม่น้อยกว่า 4 ตัว"));
            exit();
        } else {
            $hash = strtoupper(hash('whirlpool', $pass));

            $stmt = $pdo->prepare("SELECT * FROM players WHERE playerName = :username AND playerPassword = :password");
            $stmt->execute(['username' => $user, 'password' => $hash]);
            $userRow = $stmt->fetch();

            if ($userRow) {
                $_SESSION['username'] = $user;
                echo json_encode(array("status" => "success", "msg" => "เข้าสู่ระบบสำเร็จ"));
                exit();
            } else {
                echo json_encode(array("status" => "error", "msg" => "ชื่อตัวละครหรือรหัสผ่านไม่ถูกต้อง"));
                exit();
            }
        }
    }
} else {
    echo json_encode(array("status" => "error", "msg" => "ไม่มีข้อมูล USERNAME หรือ PASSWORD"));
    exit();
}
?>
