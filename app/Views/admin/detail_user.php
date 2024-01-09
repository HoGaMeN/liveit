<?= $this->extend('template/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Detail Member</h1>
    <h1 class="h5 mb-4 text-gray-800"></h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/img/IMG1.png" class="img-fluid rounded-start">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <h4><?= $user->username; ?></h4>
                                </li>
                                <li class="list-group-item">
                                    <?= $user->nama_lengkap; ?>
                                </li>
                                <li class="list-group-item">
                                    <?= $user->email; ?>
                                </li>
                                <li class="list-group-item">
                                    <?= $user->nomor_hp; ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge badge-success">
                                        <?= $user->name; ?>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <a href="<?= base_url('admin'); ?>">&laquo; Kembali Ke Daftar Member</a>
                                </li>
                            </ul>
                            <p class="card-text"><small class="text-muted">Bergabung pada <?= $user->created_at; ?></small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>