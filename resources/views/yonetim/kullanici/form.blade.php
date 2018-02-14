@extends('yonetim.layouts.master') @section('title', 'Kullanıcı Yönetimi') @section('content')

<h1 class="page-header">Kullanıcı Yönetimi</h1>

<form method="post" action="{{ route('yonetim.kullanici.kaydet', @$entry->id) }}">
    {{ csrf_field() }}
    <div class="pull-right">
        <button type="submit" class="btn btn-primary">
            {{ @$entry->id > 0 ? "Güncelle" : "Kaydet" }}
        </button>
    </div>

    <h3 class="sub-header">
        Kullanıcı {{ @$entry->id > 0 ? "Güncelle" : "Kaydet" }}
    </h3>

    @include('layouts.partials.errors') @include('layouts.partials.alert')

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="adsoyad">Ad Soyad </label>
                <!-- value kısmında old ile veri çekiyoruz bunun sebebi ise örneğin ad soyad alanı zorunlu oldugu halde boş bırakıp
                adres alanını doldurduğumuz zaman hata olusur hata durumunda old parametresi ile bir önceki veriyi cekiyoruz
                eger bir önceki değer yok ise de ikinci parametrede belirtileni alır.  -->
                <input type="text" class="form-control" id="adsoyad" name="adsoyad" placeholder="Ad Soyad" value="{{ old('adsoyad', $entry->adsoyad) }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email </label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', $entry->email) }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sifre">Sifre</label>
                <input type="password" class="form-control" name="sifre" id="sifre" placeholder="Şifre">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="address">Adres</label>
                <input type="text" class="form-control" name="adres" id="adres" placeholder="Adres" value="{{ old('adres', $entry->detay->adres) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="address">Telefon</label>
                <input type="text" class="form-control" id="telefon" name="telefon" placeholder="Telefon" value="{{ old('telefon', $entry->detay->telefon) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="address">Cep Telefonu</label>
                <input type="text" class="form-control" id="ceptelefonu" name="ceptelefonu" placeholder="Cep Telefonu" value="{{ old('ceptelefonu', $entry->detay->ceptelefonu) }}">
            </div>
        </div>
    </div>
    <!--
    <div class="form-group">
        <label for="exampleInputFile">File input</label>
        <input type="file" id="exampleInputFile">
        <p class="help-block">Example block-level help text here.</p>
    </div>
    -->
    <div class="checkbox">
        <label>
            <!-- Gizli inputları checkboxın old fonksiyonu düzgün çalışsın diye ekledik
            aktif_mi secilmediği zaman 0 olarak gidece. H4CKED BY HTML -->
            <input type="hidden" value="0" name="aktif_mi">
            <input type="checkbox" value="1" name="aktif_mi" {{ old('aktif_mi', $entry->aktif_mi) ? "checked" : "" }}> Aktif mi
        </label>
    </div>
    <div class="checkbox">
        <label>
                <input type="hidden" value="0" name="yonetici_mi">
                <input type="checkbox" value="1" name="yonetici_mi" {{ old('yonetici_mi', $entry->yonetici_mi) ? "checked" : "" }}> Yönetici mi
            </label>
    </div>

</form>


@endsection