<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kullanici extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'kullanici';
    protected $fillable = ['adsoyad', 'email', 'sifre', 'aktivasyon_anahtari', 'aktif_mi', 'yonetici_mi'];
    // sorgularda cekilmesini istemiyoruz
    protected $hidden = ['sifre', 'aktivasyon_anahtari'];

    const CREATED_AT = 'olusturulma_tarihi';
    const UPDATED_AT = 'guncelleme_tarihi';
    const DELETED_AT = 'silinme_tarihi';

    // password'ü sifre olarak değiştirdğimiz için oturum acarken hata aldık
    // bu yüzden de getAuthPassword() metodunu overrride ettik.
    // db'de password kolonu yerine sifre kolonuna bak demiş olduk.
    public function getAuthPassword(){
        return $this->sifre;
    }

    // Her kullanıcının bir detayı var
    public function detay(){
        return $this->hasOne('App\Models\KullaniciDetay')->withDefault();
    }
}
