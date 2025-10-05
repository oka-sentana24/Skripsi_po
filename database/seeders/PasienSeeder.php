<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pasiens = [
            [
                'nama' => 'Ni Kadek Dian Lestari',
                'alamat' => 'Jl. By Pass Ngurah Rai No. 99, Sanur, Denpasar',
                'tanggal_lahir' => '1996-08-12',
                'no_hp' => '081239876543',
                'email' => 'kadek.dian@gmail.com',
            ],
            [
                'nama' => 'Ida Ayu Bintang Maharani',
                'alamat' => 'Jl. Danau Tamblingan No. 47, Sanur',
                'tanggal_lahir' => '2001-04-05',
                'no_hp' => '081338765432',
                'email' => 'idayu.bintang@gmail.com',
            ],
            [
                'nama' => 'Ni Luh Putu Anggreni',
                'alamat' => 'Desa Penglipuran, Bangli',
                'tanggal_lahir' => '1989-09-21',
                'no_hp' => '085987654321',
                'email' => 'luh.anggreni@gmail.com',
            ],
            [
                'nama' => 'Ni Wayan Puspita Sari',
                'alamat' => 'Jl. Raya Kerobokan No. 100, Badung',
                'tanggal_lahir' => '1993-02-03',
                'no_hp' => '087876543210',
                'email' => 'wayan.puspita@gmail.com',
            ],
            [
                'nama' => 'Anak Agung Istri Ratih',
                'alamat' => 'Jl. Monkey Forest, Ubud, Gianyar',
                'tanggal_lahir' => '1995-11-17',
                'no_hp' => '089654321098',
                'email' => 'gungistri.ratih@gmail.com',
            ],
            [
                'nama' => 'Ni Komang Yuniartini',
                'alamat' => 'Jl. Uluwatu II No. 88, Jimbaran, Badung',
                'tanggal_lahir' => '1999-06-30',
                'no_hp' => '081865432109',
                'email' => 'komang.yuni@gmail.com',
            ],
            [
                'nama' => 'Ni Made Widyawati',
                'alamat' => 'Jl. Teuku Umar Barat No. 12, Denpasar',
                'tanggal_lahir' => '1987-01-14',
                'no_hp' => '081754321098',
                'email' => 'made.widya@gmail.com',
            ],
            [
                'nama' => 'Cokorda Istri Dewi',
                'alamat' => 'Jl. Tirta Empul, Tampaksiring, Gianyar',
                'tanggal_lahir' => '2000-07-28',
                'no_hp' => '082143210987',
                'email' => 'cokistri.dewi@gmail.com',
            ],
            [
                'nama' => 'Ni Ketut Candra Kirana',
                'alamat' => 'Jl. Gajah Mada No. 1, Negara, Jembrana',
                'tanggal_lahir' => '1997-10-09',
                'no_hp' => '081543210987',
                'email' => 'ketut.candra@gmail.com',
            ],
            [
                'nama' => 'Desak Putu Wulandari',
                'alamat' => 'Jl. Raya Goa Lawah, Klungkung',
                'tanggal_lahir' => '1994-03-11',
                'no_hp' => '081932109876',
                'email' => 'desak.wulan@gmail.com',
            ],
            [
                'nama' => 'Gusti Ayu Paramita',
                'alamat' => 'Jl. Pahlawan No. 15, Klungkung',
                'tanggal_lahir' => '1998-04-22',
                'no_hp' => '081234500011',
                'email' => 'gustiayu.paramita@gmail.com',
            ],
            [
                'nama' => 'Sang Ayu Ketut Mawar',
                'alamat' => 'Desa Tegalalang, Gianyar',
                'tanggal_lahir' => '2002-08-19',
                'no_hp' => '081234500022',
                'email' => 'sangayu.mawar@gmail.com',
            ],
            [
                'nama' => 'Luh Gede Indrayani',
                'alamat' => 'Jl. Raya Lovina, Buleleng',
                'tanggal_lahir' => '1991-12-01',
                'no_hp' => '081234500033',
                'email' => 'luhgede.indra@gmail.com',
            ],
            [
                'nama' => 'Ni Nyoman Trisna Dewi',
                'alamat' => 'Jl. WR Supratman No. 210, Denpasar',
                'tanggal_lahir' => '1986-06-07',
                'no_hp' => '081234500044',
                'email' => 'nyoman.trisna@gmail.com',
            ],
            [
                'nama' => 'Made Dwi Cahyani',
                'alamat' => 'Jl. Sunset Road, Kuta, Badung',
                'tanggal_lahir' => '1995-05-25',
                'no_hp' => '081234500055',
                'email' => 'made.dwi@gmail.com',
            ],
            [
                'nama' => 'A.A. Sagung Putri',
                'alamat' => 'Jl. Raya Batubulan No. 45, Gianyar',
                'tanggal_lahir' => '1999-01-30',
                'no_hp' => '081234500066',
                'email' => 'sagung.putri@gmail.com',
            ],
            [
                'nama' => 'Ni Putu Yunita Sari',
                'alamat' => 'Jl. Ahmad Yani Utara No. 150, Denpasar',
                'tanggal_lahir' => '2000-09-11',
                'no_hp' => '081234500077',
                'email' => 'putu.yunita@gmail.com',
            ],
            [
                'nama' => 'Kadek Ayu Permata',
                'alamat' => 'Jl. Raya Candidasa, Karangasem',
                'tanggal_lahir' => '1997-07-07',
                'no_hp' => '081234500088',
                'email' => 'kadekayu.permata@gmail.com',
            ],
            [
                'nama' => 'Wayan Sri Rahayu',
                'alamat' => 'Desa Jatiluwih, Penebel, Tabanan',
                'tanggal_lahir' => '1992-02-14',
                'no_hp' => '081234500099',
                'email' => 'wayan.sri@gmail.com',
            ],
            [
                'nama' => 'Jro Kadek Suartini',
                'alamat' => 'Jl. Raya Kintamani, Bangli',
                'tanggal_lahir' => '1990-10-28',
                'no_hp' => '081234500110',
                'email' => 'jrokadek.suartini@gmail.com',
            ],
        ];

        // Looping untuk memasukkan data ke database
        foreach ($pasiens as $pasien) {
            // Menggunakan updateOrCreate agar tidak terjadi duplikasi email
            // dan bisa dijalankan berulang kali
            Pasien::updateOrCreate(['email' => $pasien['email']], $pasien);
        }
    }
}
