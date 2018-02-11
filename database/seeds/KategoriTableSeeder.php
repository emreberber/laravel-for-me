<?php

use Illuminate\Database\Seeder;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0; ');
        DB::table('kategori')->truncate();
        $id = DB::table('kategori')->insertGetId(['kategori_adi'=>'Elektronik', 'slug'=>'elektronik']);
        DB::table('kategori')->insert(['kategori_adi'=>'Bilgisayar/Tablet', 'slug'=>'bilgisayar-tablet', 'ust_id'=>$id]);
        DB::table('kategori')->insert(['kategori_adi'=>'Telefon', 'slug'=>'telefon', 'ust_id'=>$id]);
        DB::table('kategori')->insert(['kategori_adi'=>'Tv ve Ses Sistemleri', 'slug'=>'tv-ses-sistemleri', 'ust_id'=>$id]);
        DB::table('kategori')->insert(['kategori_adi'=>'Kamera', 'slug'=>'kamera', 'ust_id'=>$id]);

        $id = DB::table('kategori')->insertGetId(['kategori_adi'=>'Kitap', 'slug'=>'kitap']);
        DB::table('kategori')->insert(['kategori_adi'=>'Edebiyat', 'slug'=>'edebiyat', 'ust_id'=>$id]);
        DB::table('kategori')->insert(['kategori_adi'=>'Çocuk', 'slug'=>'cocuk', 'ust_id'=>$id]);
        DB::table('kategori')->insert(['kategori_adi'=>'Bilgisayar', 'slug'=>'bilgisayar', 'ust_id'=>$id]);
        DB::table('kategori')->insert(['kategori_adi'=>'Sınavlara Hazırlık', 'slug'=>'sinavlara-hazirlik', 'ust_id'=>$id]);

        DB::table('kategori')->insert(['kategori_adi'=>'Dergi', 'slug'=>'dergi']);
        DB::table('kategori')->insert(['kategori_adi'=>'Mobilya', 'slug'=>'mobilya']);
        DB::table('kategori')->insert(['kategori_adi'=>'Aksesuar', 'slug'=>'aksesuar']);
        DB::table('kategori')->insert(['kategori_adi'=>'Bilgisayar', 'slug'=>'bilgisayar']);
        DB::table('kategori')->insert(['kategori_adi'=>'Spor', 'slug'=>'spor']);
        DB::table('kategori')->insert(['kategori_adi'=>'Beyaz Eşya', 'slug'=>'beyazesya']);
        DB::statement('SET FOREIGN_KEY_CHECKS=1; ');
    }
}
