<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/sewa.css">
</head>

<body>
    <div class="form-container">
        <span class="title">Sewa</span>
        <form class="form" action="<?= base_url('user/simpanTransaksi') ?>" method="post">
            <div class="form-group">
                <label for="select">Pilih Paket Yang Akan Diambil :</label>
                <select name="id_layanan" class="form-select">
                    <?php foreach ($layanans as $layanan) : ?>
                        <option value="<?= $layanan['id_layanan']; ?>"><?= $layanan['nama_layanan']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="select">Pilih Ruangan Yang Akan Tersedia Berikut :</label>
                <select name="id_ruangan" class="form-select" id="ruanganSelect" aria-label="Ruangan select example">
                    <!-- Opsi ruangan akan dimuat di sini menggunakan JavaScript -->
                </select>
            </div>
            <div class="form-group">
                <label for="input">Tentukan Tanggal dan Jam Akan Melakukan Reservasi</label>
                <input type="datetime-local" name="checkin">
            </div>
            <div class="form-group">
                <label for="input">Lama Sewa</label>
                <input type="datetime-local" name="checkout">
            </div>
            <div class="form-group">
                <label for="textarea"><b>BIAYA</b></label>
                <textarea required="" cols="50" rows="10" id="textarea" name="textarea" readonly></textarea>
            </div>
            <button type="submit" class="form-submit-btn">Submit</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const layananSelect = document.querySelector('select[name="id_layanan"]');
            const ruanganSelect = document.querySelector('select[name="id_ruangan"]');
            const checkinInput = document.querySelector('input[name="checkin"]');
            const checkoutInput = document.querySelector('input[name="checkout"]');
            const biayaTextarea = document.querySelector('textarea[name="textarea"]');
            let nominalPerJam = 0;

            // Fungsi untuk menghitung selisih antara dua tanggal dalam jam
            function hitungDurasiSewa(checkin, checkout) {
                let durasi = (new Date(checkout) - new Date(checkin)) / 1000 / 60 / 60; // Hasil dalam jam
                return durasi;
            }

            layananSelect.addEventListener('change', function() {
                const layananId = this.value;
                ruanganSelect.innerHTML = ''; // Bersihkan pilihan ruangan

                // Request ke server untuk mendapatkan ruangan berdasarkan layanan terpilih
                fetch(`/user/sewa/getKetersediaanRuangan/${layananId}`)
                    .then(response => response.json())
                    .then(ruangans => {
                        ruangans.forEach(ruangan => {
                            const option = new Option(ruangan.nomor_ruangan, ruangan.id_ruangan);
                            ruanganSelect.add(option);
                        });
                    })
                    .catch(error => console.error('Error fetching ruangans:', error));

                // Request untuk mengambil nominal_perjam berdasarkan layanan yang dipilih
                fetch(`/user/sewa/getNominalPerJam/${layananId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Debugging response
                        nominalPerJam = data.nominal_perjam;
                        console.log(`Nominal per jam: ${nominalPerJam}`); // Debugging value
                    })
                    .catch(error => console.error('Error fetching nominal per jam:', error));
            });

            checkoutInput.addEventListener('change', function() {
                if (checkinInput.value && this.value && nominalPerJam) {
                    let totalJamSewa = hitungDurasiSewa(checkinInput.value, this.value);
                    let totalBiaya = parseFloat((totalJamSewa * nominalPerJam).toFixed(2)); // Ini akan menghasilkan Number
                    console.log(`Total biaya: ${totalBiaya}`); // Debugging calculation
                    biayaTextarea.value = `TOTAL JAM SEWA = ${totalJamSewa.toFixed(2)} JAM\n` +
                        `PERHITUNGAN: ${totalJamSewa.toFixed(2)} JAM X ${nominalPerJam}\n` +
                        `TOTAL = Rp.${totalBiaya}`;
                }
            });
        });
    </script>
</body>

</html>