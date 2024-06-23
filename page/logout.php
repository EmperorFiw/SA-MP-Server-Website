<?php
include '../db.php';
session_start();
session_destroy();
echo '<style> @import "https://fonts.googleapis.com/css?family=Kanit"; * {  font-family: "Kanit", sans-serif;  }</style>';
echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@9"></script>';
echo '</body><script>Swal.fire( "สำเร็จ!","ออกจากระบบสำเร็จ...","success").then(function() {window.location = "../";});</script>';

?>