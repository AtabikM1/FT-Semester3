<?php
session_start();
session_unset(); // Hapus semua variabel sesi
session_destroy(); // Hancurkan sesi
header("Location: /"); // Arahkan ke halaman login
exit;
?>