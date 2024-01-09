<link rel="stylesheet" href="/css/sewa.css">
<div class="form-container">
    <span class="title">Sewa</span>
    <form class="form" action="<?= base_url('sewa/submit') ?>" method="post">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required="">
        </div>
        <div class="form-group">
            <label for="nomor_hp">Nomor HP</label>
            <input type="text" id="nomor_hp" name="nomor_hp" required="">
        </div>
        <div class="form-group">
            <select name="id_layanan" class="form-select" multiple aria-label="multiple select example">
                <?php foreach ($layanans as $layanan) : ?>
                    <option value="<?= $layanan['id_layanan']; ?>"><?= $layanan['nama_layanan']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <select name="id_ruangan" class="form-select" multiple aria-label="multiple select example">
                <?php foreach ($ruangans as $ruangan) : ?>
                    <option value="<?= $ruangan['id_ruangan']; ?>"><?= $ruangan['nomor_ruangan']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="datetime-local" name="checkin">
        </div>
        <div class="form-group">
            <input type="datetime-local" name="checkout">
        </div>
        <div class="form-group">
            <label for="textarea">How Can We Help You?</label>
            <textarea required="" cols="50" rows="10" id="textarea" name="textarea"></textarea>
        </div>
        <button type="submit" class="form-submit-btn">Submit</button>
    </form>
</div>