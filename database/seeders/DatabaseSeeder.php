<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Bulan;
use App\Models\Tahun;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Kategori;
use App\Models\Pemakaian;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Kategori::create([
            'kategori'  => 'umum'
        ]);
        Kategori::create([
            'kategori'  => 'perumahan'
        ]);

        User::create([
            'name'      => 'admin',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('1234'),
            'role_id'   => 1
        ]);
        User::create([
            'name'      => 'petugas',
            'email'     => 'petugas@gmail.com',
            'password'  => bcrypt('1234'),
            'role_id'   => 2
        ]);
        User::create([
            'no_pelanggan'  => 'PAM0001',
            'name'          => 'Dwi Purnomo',
            'email'         => 'purnomodwi174@gmail.com',
            'no_hp'         => '081229248179',
            'tgl_pasang'    => '2023-10-16',
            'password'      => bcrypt('1234'),
            'role_id'       => 3,
            'kategori_id'   => 1
        ]);
        User::create([
            'no_pelanggan'  => 'PAM0002',
            'name'          => 'Mujiyono',
            'email'         => 'mujiyono@gmail.com',
            'no_hp'         => '081229248179',
            'tgl_pasang'    => '2023-10-16',
            'password'      => bcrypt('1234'),
            'role_id'       => 3,
            'kategori_id'   => 2
        ]);

        Role::create([
            'role'  => 'admin'
        ]);
        Role::create([
            'role'  => 'petugas'
        ]);
        Role::create([
            'role'  => 'pelanggan'
        ]);

        Bulan::create([
            'bulan' => 'Januari'
        ]);
        Bulan::create([
            'bulan' => 'Februari'
        ]);
        Bulan::create([
            'bulan' => 'Maret'
        ]);
        Bulan::create([
            'bulan' => 'April'
        ]);
        Bulan::create([
            'bulan' => 'Mei'
        ]);
        Bulan::create([
            'bulan' => 'Juni'
        ]);
        Bulan::create([
            'bulan' => 'Juli'
        ]);
        Bulan::create([
            'bulan' => 'Agustus'
        ]);
        Bulan::create([
            'bulan' => 'September'
        ]);
        Bulan::create([
            'bulan' => 'Oktober'
        ]);
        Bulan::create([
            'bulan' => 'November'
        ]);
        Bulan::create([
            'bulan' => 'Desember'
        ]);


        Tahun::create([
            'tahun' => '2022'
        ]);
        Tahun::create([
            'tahun' => '2023'
        ]);

        Periode::create([
            'periode'   => 'September 2023',
            'bulan_id'  => 9,
            'tahun_id'  => 2,
            'status'    => 'Aktif'
        ]);
        Periode::create([
            'periode'   => 'Oktober 2023',
            'bulan_id'  => 10,
            'tahun_id'  => 2,
            'status'    => 'Aktif'
        ]);
        Periode::create([
            'periode'   => 'November 2023',
            'bulan_id'  => 11,
            'tahun_id'  => 2,
            'status'    => 'Aktif'
        ]);

        Tarif::create([
            'm3_umum'        => '1500',
            'm3_perumahan'   => '2000',
            'beban'          => '5000',
            'denda'          => '5000'
        ]);
    }
}
