<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataAwalSeeder extends Seeder
{
    public function run()
    {
        // Memasukkan data ke tabel 'layanan'
        $dataLayanan = [
            ['nama_layanan' => 'Reguler Commerce', 'kelas_paket' => 'A', 'nominal_perjam' => 15000],
            ['nama_layanan' => 'Supreme Commerce', 'kelas_paket' => 'B', 'nominal_perjam' => 35000],
            ['nama_layanan' => 'Reguler Gaming', 'kelas_paket' => 'A', 'nominal_perjam' => 30000],
            ['nama_layanan' => 'Gaming Buddy', 'kelas_paket' => 'B', 'nominal_perjam' => 40000],
        ];
        $this->db->table('layanan')->insertBatch($dataLayanan);

        // Memasukkan data ke tabel 'ruangan'
        $nomorRuangan = ['A-01', 'A-02', 'A-03', 'A-04', 'A-05', 'B-01', 'B-02', 'B-03', 'B-04', 'B-05'];
        $dataRuangan = [];
        foreach ($nomorRuangan as $nomor) {
            $dataRuangan[] = ['nomor_ruangan' => $nomor];
        }
        $this->db->table('ruangan')->insertBatch($dataRuangan);

        // Memasukkan data ke tabel 'paket'
        $dataPaket = [
            ['id_ruangan' => 1, 'id_layanan' => 1, 'deskripsi' => 'Internet Stabil (20Mbps),HD Camera,Mic wireless kualitas terbaik,Perlengkapan pendukung(meja, kursi, rak,gantungan,dll)'],
            ['id_ruangan' => 2, 'id_layanan' => 1, 'deskripsi' => 'Internet Stabil (20Mbps),HD Camera,Mic wireless kualitas terbaik,Perlengkapan pendukung(meja, kursi, rak,gantungan,dll)'],
            ['id_ruangan' => 3, 'id_layanan' => 1, 'deskripsi' => 'Internet Stabil (20Mbps),HD Camera,Mic wireless kualitas terbaik,Perlengkapan pendukung(meja, kursi, rak,gantungan,dll)'],
            ['id_ruangan' => 4, 'id_layanan' => 3, 'deskripsi' => 'Internet Stabil (50Mbps),HD Camera,Microphone kualitas terbaik,1 set PC gaming(Entry Level),1 set meja dan kursi nyaman,Ruangan nyaman,tertutup dan terprivasi.'],
            ['id_ruangan' => 5, 'id_layanan' => 3, 'deskripsi' => 'Internet Stabil (50Mbps),HD Camera,Microphone kualitas terbaik,1 set PC gaming(Entry Level),1 set meja dan kursi nyaman,Ruangan nyaman,tertutup dan terprivasi.'],
            ['id_ruangan' => 6, 'id_layanan' => 2, 'deskripsi' => 'Internet Stabil (50Mbps),nHD Camera,Mic wireless kualitas terbaik,Perlengkapan pendukung(meja, kursi, rak,gantungan,dll),Ruangan nyaman dan terprivasi.'],
            ['id_ruangan' => 7, 'id_layanan' => 2, 'deskripsi' => 'Internet Stabil (50Mbps),nHD Camera,Mic wireless kualitas terbaik,Perlengkapan pendukung(meja, kursi, rak,gantungan,dll),Ruangan nyaman dan terprivasi.'],
            ['id_ruangan' => 8, 'id_layanan' => 4, 'deskripsi' => 'Internet Stabil (50Mbps),HD Camera Microphone kualitas terbaik,1 set PC gaming(High End),1 set meja dan kursi gaming,Ruangan nyaman,tertutup ,dan terprivasi.'],
            ['id_ruangan' => 9, 'id_layanan' => 4, 'deskripsi' => 'Internet Stabil (50Mbps),HD Camera Microphone kualitas terbaik,1 set PC gaming(High End),1 set meja dan kursi gaming,Ruangan nyaman,tertutup ,dan terprivasi.'],
            ['id_ruangan' => 10, 'id_layanan' => 4, 'deskripsi' => 'Internet Stabil (50Mbps),HD CameraMicrophone kualitas terbaik,1 set PC gaming(High End),1 set meja dan kursi gaming,Ruangan nyaman,tertutup ,dan terprivasi.'],
        ];
        $this->db->table('paket')->insertBatch($dataPaket);
    }
}
