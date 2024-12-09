<?php
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada
// Assuming the user ID is passed as a parameter
$username = $_GET['id'];

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM [user] WHERE username = '$username'";
$result = sqlsrv_query($conn, $query);

// Check if the query was successful
if ($result === false) {
    echo "Error querying database: " . print_r(sqlsrv_errors(), true);
} else {
    // Fetch the user data
    $user = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    echo $user['username'];

    // Logic for deleting the user
    if ($user['Role_idRole'] === 2) { //pelamar

        // studi
        $sqlstudi = "DELETE FROM [studi] WHERE Profile_User_username = '$username'";
        $restudi = sqlsrv_query($conn, $sqlstudi);
        //sertif
        $sqlsertif = "DELETE FROM [sertifikat] WHERE Profile_User_username = '$username'";
        $resertif = sqlsrv_query($conn, $sqlsertif);
        //pengalaman
        $sqlpeng = "DELETE FROM [pengalaman] WHERE Profile_User_username = '$username'";
        $repeng = sqlsrv_query($conn, $sqlpeng);
        //melamar
        $sqlpel = "DELETE FROM [melamar] WHERE User_pelamar = '$username'";
        $repel = sqlsrv_query($conn, $sqlpel);
        //pelamar profile
        $sql = "DELETE FROM [pelamar] WHERE User_username = '$username'";
        $res = sqlsrv_query($conn, $sql);
        //user
        $query = "DELETE FROM [user] WHERE username = '$username'";
        $result = sqlsrv_query($conn, $query);

        // Check if the deletion was successful
        if ($result === false or $res === false or $repeng === false or $repel === false or $resertif === false or $restudi === false) {
            echo "Error deleting user: " . print_r(sqlsrv_errors(), true);
            echo "<br><br><br>ERORRRR";
        } else {
            echo "User deleted successfully.";
            header("Location: manageuser.php");
        }
    } else if ($user['Role_idRole'] === 3) { //perusahaan
        //artikel
        $sqlartikel = "DELETE FROM [artikel] WHERE User_username = '$username'";
        $seart = sqlsrv_query($conn, $sqlartikel);
        //loker
        $sqlloker = "DELETE FROM [loker] WHERE Username_perusahaan = '$username'";
        $seloker = sqlsrv_query($conn, $sqlloker);
        //perusahaan profile
        $sql = "DELETE FROM [perusahaan] WHERE User_username = '$username'";
        $res = sqlsrv_query($conn, $sql);
        //user
        $query = "DELETE FROM [user] WHERE username = '$username'";
        $result = sqlsrv_query($conn, $query);

        // Check if the deletion was successful
        if ($result === false or $seart === false or $seloker === false or $res === false) {
            echo "Error deleting user: " . print_r(sqlsrv_errors(), true);
            echo "<br><br><br>ERORRRR";
        } else {
            echo "User deleted successfully.";
            header("Location: manageuser.php");
        }
    }

    // Check if the deletion was successful
    if ($result === false or ($sql) === false) {
        echo "Error deleting user: " . print_r(sqlsrv_errors(), true);
        echo "<br><br><br>ERORRRR";
    } else {
        echo "User deleted successfully.";
        header("Location: manageuser.php");
    }
}

// Close the database connection
sqlsrv_close($conn);
