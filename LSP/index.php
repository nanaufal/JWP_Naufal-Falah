<?php
require_once 'functions.php';

$tugas = ambil_data();
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$data_edit = $edit_id !== null ? $tugas[$edit_id] : ['nama'=>'', 'deskripsi'=>'', 'deadline'=>''];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi To Do List</title>
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            border: none;
        }
        .list-group-item {
            border-radius: 8px !important;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        .list-group-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="text-primary fw-bold"><i class="fas fa-tasks me-2"></i>Aplikasi To Do List</h1>
                <p class="text-muted">Kelola tugas harian Anda dengan mudah</p>
            </div>

            <?php if (isset($_GET['alert'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_GET['alert']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="action.php" method="POST">
                        <input type="hidden" name="aksi" value="<?= $edit_id !== null ? 'edit' : 'tambah' ?>">
                        <?php if ($edit_id !== null): ?>
                            <input type="hidden" name="id" value="<?= $edit_id ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Tugas</label>
                            <input type="text" name="nama" id="nama" class="form-control form-control-lg" required
                                   value="<?= htmlspecialchars($data_edit['nama']) ?>" placeholder="Masukkan nama tugas">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="2"
                                      placeholder="Tambahkan deskripsi (opsional)"><?= htmlspecialchars($data_edit['deskripsi']) ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="deadline" class="form-label fw-bold">Deadline</label>
                            <input type="date" name="deadline" id="deadline" class="form-control" required
                                   value="<?= htmlspecialchars($data_edit['deadline']) ?>">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <?php if ($edit_id !== null): ?>
                                <a href="index.php" class="btn btn-secondary me-md-2"><i class="fas fa-times me-1"></i> Batal</a>
                            <?php endif; ?>
                            <button class="btn btn-<?= $edit_id !== null ? 'warning' : 'primary' ?> flex-grow-1" type="submit">
                                <i class="fas fa-<?= $edit_id !== null ? 'save' : 'plus' ?> me-1"></i>
                                <?= $edit_id !== null ? 'Perbarui Tugas' : 'Tambah Tugas' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <h4 class="mb-3 fw-bold text-primary">Daftar Tugas</h4>
            
            <?php if (empty($tugas)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Belum ada tugas yang ditambahkan.
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($tugas as $index => $item): 
                        $deadline = new DateTime($item['deadline']);
                        $today = new DateTime();
                        $interval = $today->diff($deadline);
                        $isLate = $today > $deadline;
                    ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center <?= $isLate ? 'border-start border-danger border-3' : '' ?>">
                            <div class="d-flex flex-column">
                                <span class="fw-bold"><?= htmlspecialchars($item['nama']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars($item['deskripsi']) ?></small>
                                <small class="<?= $isLate ? 'text-danger' : 'text-success' ?>">
                                    <i class="far fa-clock me-1"></i>
                                    Deadline: <?= $item['deadline'] ?>
                                    <?= $isLate ? '(Terlambat)' : '('.$interval->days.' hari lagi)' ?>
                                </small>
                            </div>
                            <div class="btn-group">
                                <a href="index.php?edit=<?= $index ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="action.php?hapus=<?= $index ?>" class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3.2 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<!-- SweetAlert2 untuk konfirmasi lebih menarik -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi penghapusan dengan SweetAlert2
    document.querySelectorAll('[onclick="return confirm(\'Yakin ingin menghapus tugas ini?\')"]').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = e.target.closest('a').href;
                }
            });
        };
    });
</script>
</body>
</html>