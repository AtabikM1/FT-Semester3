<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Ambil ID Loker dari parameter URL
$idLoker = $_GET['id'] ?? null;
if (!$idLoker) {
    die("ID Loker tidak ditemukan.");
}

// Query untuk mendapatkan detail loker
$sql = "SELECT l.idLoker, l.judul, l.deskripsi, l.tipe_loker, l.lokasi, l.gaji, l.tanggal_post, l.tanggal_deadline, 
               p.nama AS nama_perusahaan, p.foto
        FROM loker l
        INNER JOIN perusahaan p ON l.Username_perusahaan = p.User_username
        WHERE l.idLoker = ?";
$params = [$idLoker];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || !($loker = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    die("Loker tidak ditemukan.");
}

// Proses approve atau reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $status = ($_POST['action'] === 'approve') ? 2 : 3; // 2 = Approve, 3 = Reject

    $updateQuery = "UPDATE loker SET status_approval = ? WHERE idLoker = ?";
    $updateStmt = sqlsrv_query($conn, $updateQuery, [$status, $idLoker]);

    if ($updateStmt) {
        $message = ($_POST['action'] === 'approve') ? "Lowongan berhasil disetujui." : "Lowongan berhasil ditolak.";
        header("http://project.test/dashboard/admin/managejob.php");
        exit;
    } else {
        die("Terjadi kesalahan saat memperbarui status loker.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Loker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Detail Loker</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo htmlspecialchars($loker['judul']); ?></h2>
            <p class="text-gray-600 mb-2"><strong>Perusahaan:</strong>
                <?php echo htmlspecialchars($loker['nama_perusahaan']); ?></p>
            <p class="text-gray-600 mb-2"><strong>Deskripsi:</strong>
                <?php echo nl2br(htmlspecialchars($loker['deskripsi'])); ?></p>
            <p class="text-gray-600 mb-2"><strong>Tipe Loker:</strong>
                <?php echo htmlspecialchars($loker['tipe_loker']); ?></p>
            <p class="text-gray-600 mb-2"><strong>Lokasi:</strong> <?php echo htmlspecialchars($loker['lokasi']); ?></p>
            <p class="text-gray-600 mb-2"><strong>Gaji:</strong> <?php echo htmlspecialchars($loker['gaji']); ?></p>
            <p class="text-gray-600 mb-2"><strong>Deadline:</strong>
                <?php echo htmlspecialchars($loker['tanggal_deadline']->format('Y-m-d')); ?></p>

            <div class="mt-6">
                <form method="POST" class="inline-block">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Approve
                    </button>
                </form>
                <form method="POST" class="inline-block ml-2">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>