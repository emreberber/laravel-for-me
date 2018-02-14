<?php

use Illuminate\Database\Seeder;
use App\Models\Kullanici;
use App\Models\KullaniciDetay;

class KullaniciTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Kullanici::truncate();
        KullaniciDetay::truncate();

        $kullanici_yonetici = Kullanici::create([
            'adsoyad'       => 'Emre Berber',
            'email'         => 'brbr.emre@gmail.com',
            'sifre'         => bcrypt('123456'),
            'aktif_mi'      => 1,
            'yonetici_mi'   => 1
        ]);

        $kullanici_yonetici -> detay()->create([
            'adres'         => 'Trabzon',
            'telefon'       => '223 23 23',
            'ceptelefonu'   => '555 55 55'
        ]);

        for($i=0; $i<50; $i++){
            $kullanici_musteri = Kullanici::create([
                'adsoyad'       => $faker->name,
                'email'         => $faker->unique()->safeEmail,
                'sifre'         => bcrypt('123456'),
                'aktif_mi'      => 1,
                'yonetici_mi'   => 0
            ]);

            $kullanici_musteri -> detay()->create([
                'adres'         => $faker->address,
                'telefon'       => $faker->e164phoneNumber,
                'ceptelefonu'   => $faker->e164phoneNumber
            ]);

        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }  
}
