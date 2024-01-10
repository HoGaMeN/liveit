<?= $this->extend('template/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Rencana Sewa</h1>
    <h1 class="h5 mb-4 text-gray-800">Selamat Datang <?= user()->username; ?></h1>

    <h1 class="h5 mb-4 text-gray-800">Ayo segera buat rencana sewa temapt live yang nyaman dan gampang bersama LIVEIT!!!</h1>
    <hr class="sidebar-divider">
    <a href="<?= base_url('/user/sewa'); ?>" class="btn btn-info">Sewa Sekarang!!!</a>
    <hr class="sidebar-divider">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">NO</th>
                <th scope="col">Layanan</th>
                <th scope="col">Ruangan</th>
                <th scope="col">Tanggal Booking</th>
                <th scope="col">Tanggal Checkout</th>
                <th scope="col">Total</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($transaksi as $trx) : ?>
                <tr>
                    <th scope="row"><?= $i++; ?></th>
                    <td><?= $trx['nama_layanan']; ?></td>
                    <td><?= $trx['nomor_ruangan']; ?></td>
                    <td><?= $trx['tanggal_booking']; ?></td>
                    <td><?= $trx['tanggal_checkout']; ?></td>
                    <td><?= $trx['total']; ?></td>
                    <td><?= $trx['status']; ?></td>
                    <td>
                        <a type="button" href="<?= base_url('user/detailTransaksi/' . $trx['id_transaksi']); ?>" class="btn btn-info">Detail</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>