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
        DB::table('kategori')->truncate();
        DB::table('kategori')->insert(['kategori_adi'=>'Elektronik', 'slug'=>'elektronik']);
        DB::table('kategori')->insert(['kategori_adi'=>'Kitap', 'slug'=>'kitap']);
        DB::table('kategori')->insert(['kategori_adi'=>'Dergi', 'slug'=>'dergi']);
        DB::table('kategori')->insert(['kategori_adi'=>'Mobilya', 'slug'=>'mobilya']);
    }
}
