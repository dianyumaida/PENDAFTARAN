<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load library PHPMailer (Pastikan folder vendor/autoload sudah ada)
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $emailTujuan    = $_POST['email_tujuan'];
    $fileUploaded   = $_FILES['file_upload'];

    // Ambil metadata dari file temporary yang diupload user
    $namaAsliFile   = $fileUploaded['name'];
    $pathSementara  = $fileUploaded['tmp_name']; // Ini lokasi file di RAM/folder temp server
    $ukuranFile     = $fileUploaded['size'];
    $errorStatus    = $fileUploaded['error'];

    // 1. Validasi jika ada error upload
    if ($errorStatus !== UPLOAD_ERR_OK) {
        die("<div style='color:red; text-align:center; margin-top:50px;'>❌ Gagal mengunggah file dari komputer Anda!</div>");
    }

    // 2. Batasi ukuran file (Saran: Maksimal 20MB demi aturan SMTP Mail biasa)
    // 20 MB = 20971520 Bytes
    if ($ukuranFile > 20971520) {
        die("<div style='color:red; text-align:center; margin-top:50px;'>❌ Ukuran file terlalu besar! Maksimal 20 MB.</div>");
    }

    // 3. Proses Pengiriman via PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi Server SMTP Pengirim
        $mail->isSMTP();
        $mail->Host       = '://gmail.com';               // Ganti dengan SMTP penyedia email Anda
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emailanda@gmail.com';          // EMAIL ANDA (sebagai pengirim)
        $mail->Password   = 'xxxx xxxx xxxx xxxx';          // APP PASSWORD GMAIL ANDA (16 digit)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Pengaturan Penerima & Pengirim
        $mail->setFrom('emailanda@gmail.com', 'Sistem Pengirim File');
        $mail->addAddress($emailTujuan);

        // ATTACHMENT LANGSUNG DARI PATH SEMENTARA SERVER
        // File ditempel langsung dari RAM/Temp tanpa perlu disimpan permanen di server/GitHub
        $mail->addAttachment($pathSementara, $namaAsliFile);

        // Isi Email
        $mail->isHTML(true);
        $mail->Subject = 'Lampiran File Baru: ' . $namaAsliFile;
        $mail->Body    = 'Halo, berikut terlampir file yang dikirimkan langsung dari form website.';

        // Kirim email
        $mail->send();

        // 4. Pemberitahuan Sukses Terkirim
        echo "
        <div style='max-width: 400px; margin: 80px auto; padding: 20px; text-align: center; border: 2px solid #28a745; border-radius: 8px; font-family: Arial; background-color: #e2f0d9;'>
            <h2 style='color: #28a745; margin-bottom: 10px;'>✅ File Sukses Terkirim!</h2>
            <p style='color: #555;'>File <strong>{$namaAsliFile}</strong> telah berhasil diteruskan ke email <strong>{$emailTujuan}</strong> tanpa disimpan di server.</p>
            <br>
            <a href='index.html' style='display:inline-block; padding:10px 20px; background:#28a745; color:white; text-decoration:none; border-radius:4px;'>Kirim File Lain</a>
        </div>";

    } catch (Exception $e) {
        // Pemberitahuan Gagal Terkirim
        echo "
        <div style='max-width: 400px; margin: 80px auto; padding: 20px; text-align: center; border: 2px solid #dc3545; border-radius: 8px; font-family: Arial; background-color: #f8d7da;'>
            <h2 style='color: #dc3545; margin-bottom: 10px;'>❌ File Gagal Terkirim</h2>
            <p style='color: #555;'>Terjadi kesalahan sistem saat mengirim email.</p>
            <p style='font-size:12px; color:#777;'>Error: {$mail->ErrorInfo}</p>
            <br>
            <a href='index.html' style='display:inline-block; padding:10px 20px; background:#dc3545; color:white; text-decoration:none; border-radius:4px;'>Coba Lagi</a>
        </div>";
    }
} else {
    header("Location: index.html");
}
?>
