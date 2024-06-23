<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
    <link href="../src/output.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        @import "https://fonts.googleapis.com/css?family=Kanit";

        * {
            font-family: "Kanit", sans-serif;
        }
    </style>
    <title>Login</title>
</head>
<body>
    <main>
    <div class="container flex justify-center items-center h-auto bg-zinc-800">
        <div class="box mt-10 mb-10">
            <form action="login.php" method="POST" id="login">
                <h1>เข้าสู่ระบบ</h1>
                <label for="">USERNAME</label>
                <input type="text" placeholder="ชื่อผู้ใช้" name="user">
                <label for="">PASSWORD</label>
                <input type="password" placeholder="รหัสผ่าน" name="password">
                <div class="g-recaptcha" data-sitekey="6LcQkPwpAAAAAGJ5ZBKvBLtVxXsQQ-hUPSgfx0DZ"></div>
                <button type="submit" id="loginbtn">เข้าสู่ระบบ</button>
                
            </form>
        </div>
    </div>
    </main>

    <script>
        $(document).ready(function() {
            $("#login").submit(function (e) {
            e.preventDefault();

            let formUrl = $(this).attr("action");
            let reqMethod = $(this).attr("method");
            let formData = $(this).serialize();


            $.ajax({
                url: formUrl,
                type: reqMethod,
                data: formData,
                success: function(data) {
                let res = JSON.parse(data);
                if (res.status == "success") {
                    console.log("success", res);
                    setTimeout(() => {
                    window.location.href = '/page/index.php';
                    }, 2000); 
                    Swal.fire({
                        title: "สำเร็จ!",
                        text: res.msg,
                        icon: res.status,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/page/index.php';
                        }
                    });
                }
                else
                {
                    console.log("error", res)
                    Swal.fire("ผิดพลาด!", res.msg, res.status).then((result) => {
                        if (result.isConfirmed) {
                        location.reload();
                        }
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000); 
                }
                }  
            })
        })
    })

    </script>
</body>
</html>