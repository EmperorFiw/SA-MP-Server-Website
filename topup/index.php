<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        @import "https://fonts.googleapis.com/css?family=Kanit";

        * {
            font-family: "Kanit", sans-serif;
        }

    </style>
    <title>เติมเงินอัตโนมัติ</title>
</head>
<body>
    <nav>
        <div class="container">
            <div class="logo ml-4">
                <img src="../assets/img/logo.png" class="w-14 rounded-full" alt="Logo">
            </div>
            <div class="h-menu">
                <ul><a href="../index.html">หน้าหลัก</a></ul>
                <ul><a href="../page/index.php">สถานะตัวละคร</a></ul>
                <ul><a href="../page/logout.php">ออกจากระบบ</a></ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="con-g bg-zinc-800 grid justify-center">
            <div class="box relative">
                <img src="../assets/img/wallet.png" alt="">
                <div class="b-t flex justify-center items-center flex-col absolute">
                    <h1>ราคาขั้นต่ำ 10 บาท</h1>
                    <form id="term" action="term.php" method="POST" class="flex flex-col items-center p-8">
                        <label for="">ลิงก์เติมเงิน</label>
                        <input type="text" placeholder="Link" class="text-black mt-3 mb-10 p-2 rounded-lg" name="url" required>
                        <button type="submit" class="p-5 flex justify-center items-center">เติมเงิน</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer class="flex justify-center bg-zinc-800 bottom-0">
        <p class="text-white">สงวนลิขสิทธิ์ © 2567 By <a href="https://www.facebook.com/Fiw.Thewaphithak">EMPEROR</a></p>
    </footer>

    <script>
    $(document).ready(function() {
        $("#term").submit(function (e) {
            e.preventDefault();

            let formUrl = $(this).attr("action");
            let reqMethod = $(this).attr("method");
            let formData = $(this).serialize();
            let username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";

            // แสดงตัวยืนยันก่อน
            Swal.fire({
                title: 'คุณต้องการที่จะดำเนินการต่อหรือไม่?',
                text: 'ดำเนินการต่อในชื่อ ' + username,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ถ้าผู้ใช้กดตกลง
                    $.ajax({
                        url: formUrl,
                        type: reqMethod,
                        data: formData,
                        success: function(data) {
                            // Check if data is already an object (parsed JSON)
                            if (typeof data === 'object') {
                                if (data.status == "success") {
                                    console.log("success", data);
                                    Swal.fire({
                                        title: "สำเร็จ!",
                                        text: data.msg,
                                        icon: data.status,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    console.log("error", data);
                                    Swal.fire("ผิดพลาด!", data.msg, data.status).then(() => {
                                        location.reload();
                                    });
                                }
                            } else {
                                console.error("เกิดข้อผิดพลาด: ข้อมูลที่ได้รับไม่ใช่ JSON ที่ถูกต้อง");
                                Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถดำเนินการได้ในขณะนี้", "error").then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("เกิดข้อผิดพลาดในการส่งคำขอ: " + error);
                            Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถดำเนินการได้ในขณะนี้", "error").then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    });
    </script>

</body>
</html>