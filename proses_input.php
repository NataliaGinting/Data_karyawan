<?php
session_start();
include 'koneksi.php';

/* =========================
CEK LOGIN
========================= */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

/* =========================
PROSES INPUT
========================= */
if (isset($_POST['submit'])) {

    // =========================
    // AMBIL DATA
    // =========================
    $nama              = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $alamat            = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $tempat_lahir      = mysqli_real_escape_string($conn, $_POST['tempat_lahir'] ?? '');
    $tanggal_lahir     = $_POST['tanggal_lahir'] ?? '';
    $agama             = $_POST['agama'] ?? '';
    $pendidikan        = $_POST['pendidikan'] ?? '';
    $no_telepon        = $_POST['no_telepon'] ?? '';
    $nik               = $_POST['nik'] ?? '';

    // FIX PENTING
    $status_pernikahan = $_POST['status_pernikahan'] ?? '';

    $unit_kerja        = $_POST['unit_kerja'] ?? '';
    $jabatan           = $_POST['jabatan'] ?? '';
    $divisi            = $_POST['divisi'] ?? '';
    $tgl_masuk         = $_POST['tgl_masuk'] ?? '';
    $no_bpjs           = $_POST['no_bpjs'] ?? '';
    $no_rekening       = $_POST['no_rekening'] ?? '';

    $jenis_kelamin     = $_POST['jenis_kelamin'] ?? '';
    $status_karyawan   = $_POST['status_karyawan'] ?? 'Aktif';

    $status_verifikasi = 'pending';
    $created_by        = $_SESSION['username'];

    // =========================
    // HITUNG USIA
    // =========================
    $usia = '';
    if (!empty($tanggal_lahir)) {
        $lahir = new DateTime($tanggal_lahir);
        $today = new DateTime();
        $usia = $today->diff($lahir)->y;
    }

    // =========================
    // KODE PEKERJA
    // =========================
    $kode_pekerja = "KRY-" . rand(1000, 9999);

    // =========================
    // FOLDER UPLOAD
    // =========================
    if (!is_dir('uploads')) {
        mkdir('uploads');
    }

    // =========================
    // UPLOAD KTP
    // =========================
    $foto_ktp = '';
    if (!empty($_FILES['foto_ktp']['name'])) {
        $foto_ktp = time() . '_' . basename($_FILES['foto_ktp']['name']);
        move_uploaded_file($_FILES['foto_ktp']['tmp_name'], 'uploads/' . $foto_ktp);
    }

    // =========================
    // UPLOAD KK
    // =========================
    $foto_kk = '';
    if (!empty($_FILES['foto_kk']['name'])) {
        $foto_kk = time() . '_' . basename($_FILES['foto_kk']['name']);
        move_uploaded_file($_FILES['foto_kk']['tmp_name'], 'uploads/' . $foto_kk);
    }

    // =========================
    // INSERT KARYAWAN (FIXED TOTAL)
    // =========================
    $sql = "
    INSERT INTO karyawan (
        nama,
        alamat,
        tempat_lahir,
        tanggal_lahir,
        usia,
        agama,
        pendidikan,
        no_telepon,
        nik,
        status_pernikahan,
        unit_kerja,
        jabatan,
        created_by,
        divisi,
        tgl_masuk,
        no_bpjs,
        no_rekening,
        foto_ktp,
        foto_kk,
        status_karyawan,
        status_verifikasi,
        kode_pekerja,
        jenis_kelamin
    ) VALUES (
        '$nama',
        '$alamat',
        '$tempat_lahir',
        '$tanggal_lahir',
        '$usia',
        '$agama',
        '$pendidikan',
        '$no_telepon',
        '$nik',
        '$status_pernikahan',
        '$unit_kerja',
        '$jabatan',
        '$created_by',
        '$divisi',
        '$tgl_masuk',
        '$no_bpjs',
        '$no_rekening',
        '$foto_ktp',
        '$foto_kk',
        '$status_karyawan',
        '$status_verifikasi',
        '$kode_pekerja',
        '$jenis_kelamin'
    )";

    $query = mysqli_query($conn, $sql);

    // =========================
    // HASIL INSERT
    // =========================
    if ($query) {

        $id_karyawan = mysqli_insert_id($conn);

        // =========================
        // SIMPAN KELUARGA (AMAN)
        // =========================
        if (!empty($_POST['nama_keluarga'])) {

            foreach ($_POST['nama_keluarga'] as $i => $nk) {

                $hub = $_POST['hubungan'][$i] ?? '';
                $nikk = $_POST['nik_keluarga'][$i] ?? '';

                $nk = mysqli_real_escape_string($conn, $nk);
                $hub = mysqli_real_escape_string($conn, $hub);
                $nikk = mysqli_real_escape_string($conn, $nikk);

                if ($nk != '' || $hub != '' || $nikk != '') {

                    mysqli_query($conn, "
                        INSERT INTO keluarga_karyawan (
                            id_karyawan,
                            nama_keluarga,
                            hubungan,
                            nik_keluarga
                        ) VALUES (
                            '$id_karyawan',
                            '$nk',
                            '$hub',
                            '$nikk'
                        )
                    ");
                }
            }
        }

        echo "<script>
            alert('Data karyawan berhasil disimpan');
            window.location='lihat_data.php';
        </script>";

    } else {

        echo "<h3>Gagal Menyimpan Data</h3>";
        echo mysqli_error($conn);
    }

} else {

    header("Location: inputkaryawan.php");
}
?>