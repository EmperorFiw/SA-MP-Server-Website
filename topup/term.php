<?php
session_start();

require "../db.php";
include("../config.php");
include("api.php");

header('Content-Type: application/json');

if (isset($_POST['url'])) {
    $url = $_POST['url'];

    if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false || strpos($url, '?v=') === false) {
        echo json_encode(array("status" => "error", "msg" => "รูปแบบ URL ไม่ถูกต้อง"));
        exit;
    }

    $tc = new topup();

    $playerName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

    $vc = (object) $tc->giftcode($url, $phoneNumber); 

    if ($vc && isset($vc->status['code'])) 
    {
        if ($vc->status['code'] != 'SUCCESS') 
        {
            echo json_encode(array('status' => "error", 'msg' => 'ไม่พบซองนี้ในระบบหรือใช้งานไปแล้ว ' . $vc->status['code']));
        } 
        else 
        {
            $amounts = isset($vc->data['voucher']['amount_baht']) ? $vc->data['voucher']['amount_baht'] : 0;
            $amount = str_replace(",", "", trim($amounts));
            try {
                $stmt = $pdo->prepare("UPDATE players SET $field = $field + :amounts WHERE playerName = :playerName");
                $stmt->execute(['amounts' => $amount, 'playerName' => $playerName]);
                
                echo json_encode(array('status' => "success", "msg" => "เติมเงินสำเร็จ ตัวละครชื่อ $playerName จำนวน $amounts บาท"));
            } catch (PDOException $e) {
                error_log('Database update error: ' . $e->getMessage());
                echo json_encode(array('status' => "error", "msg" => "เกิดข้อผิดพลาดในการเติมเงิน"));
            }
        }
    } 
    else 
    {
        echo json_encode(array('status' => "error", "msg" => "ผิดพลาด URL ไม่ถูกต้อง"));
    }
}
else 
{
    echo json_encode(array("status" => "error", "msg" => "ไม่พบข้อมูล"));
}

?>
