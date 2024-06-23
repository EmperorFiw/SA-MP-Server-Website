<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login/index.php');
    exit();
}

require '../db.php';

// Get the username from the session
$username = trim($_SESSION['username']);

// Prepare the SQL statement
$stmt = $pdo->prepare("SELECT playerName, playerSkin, playerHungry, playerThirsty, playerHealth FROM players WHERE playerName = :username");

// Execute the statement with the username parameter
$stmt->execute(['username' => $username]);

// Fetch the result
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

if ($userRow) {
    // Set the player's skin and other attributes in the session
    $_SESSION['pSkin'] = $userRow['playerSkin'];
    $_SESSION['pHungry'] = $userRow['playerHungry'];
    $_SESSION['pThirsty'] = $userRow['playerThirsty'];
    $_SESSION['pHealth'] = $userRow['playerHealth'];
} else {
    // Default values if no user is found
    $_SESSION['pSkin'] = 'cat';
    $_SESSION['pHungry'] = 0;
    $_SESSION['pThirsty'] = 0;
    $_SESSION['pHealth'] = 0;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/main.js"></script>
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        @import "https://fonts.googleapis.com/css?family=Kanit";

        * {
            font-family: "Kanit", sans-serif;
        }

    </style>
    <title>Page</title>
</head>
<body>
    <nav>
        <div class="container">
            <div class="logo ml-4">
                <img src="../assets/img/logo.png" class="w-14 rounded-full" alt="Logo">
            </div>
            <div class="h-menu">
                <ul><a href="../index.html">หน้าหลัก</a></ul>
                <ul><a href="../topup/index.php">เติมเงิน</a></ul>
                <ul><a href="logout.php">ออกจากระบบ</a></ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="con-g bg-zinc-800 grid justify-center">
            <div class="box">
                <h1 class="text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                <img src="/page/assets/skins/<?php echo htmlspecialchars($_SESSION['pSkin']); ?>.png" alt="Player Skin">
                <div class="progress-bar" id="hungry">
                    <div class="progress-bar-fill" style="width: <?php echo htmlspecialchars($_SESSION['pHungry']); ?>%;">
                        Hungry: <?php echo htmlspecialchars($_SESSION['pHungry']); ?>%
                    </div>
                </div>
                <div class="progress-bar" id="thirsty">
                    <div class="progress-bar-fill" style="width: <?php echo htmlspecialchars($_SESSION['pThirsty']); ?>%;">
                        Thirsty: <?php echo htmlspecialchars($_SESSION['pThirsty']); ?>%
                    </div>
                </div>
                <div class="progress-bar" id="health">
                    <div class="progress-bar-fill" style="width: <?php echo htmlspecialchars($_SESSION['pHealth']); ?>%;">
                        Health: <?php echo htmlspecialchars($_SESSION['pHealth']); ?>%
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="flex justify-center bg-zinc-800 bottom-1">
        <p class="text-white">สงวนลิขสิทธิ์ © 2567 By <a href="https://www.facebook.com/Fiw.Thewaphithak">EMPEROR</a></p>
    </footer>
</body>
</html>
