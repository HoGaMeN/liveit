<?= $this->extend('template/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Daftar Ruangan</h1>
    <h1 class="h5 mb-4 text-gray-800">Selamat Datang <?= user()->username; ?></h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nomor Ruangan</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ruangans as $ruangan) : ?>
                            <tr>
                                <td><?= $ruangan['nomor_ruangan']; ?></td>
                                <td><?= $ruangan['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>