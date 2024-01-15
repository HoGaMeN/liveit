<?= $this->extend('template/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Daftar Transaksi</h1>
    <h1 class="h5 mb-4 text-gray-800">Selamat Datang <?= user()->username; ?></h1>
    <h1 class="h5 mb-4 text-gray-800">Berikut Semua Transaksi Yang Telah Dibayar</h1>
    <div class="row">
        <div class="col-lg-8">
            <div class="responsive-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">NO</th>
                                <th scope="col">Email</th>
                                <th scope="col">Nama Layanan</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($transaksis as $transaksi) : ?>
                                <tr>
                                    <th scope="row"><?= $i++; ?></th>
                                    <td><?= $transaksi['email']; ?></td>
                                    <td><?= $transaksi['nama_layanan']; ?></td>
                                    <td><?= $transaksi['total']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>