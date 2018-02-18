@extends('yonetim.layouts.master') @section('title', 'Urun Yönetimi') @section('content')

<h1 class="page-header">Urun Yönetimi</h1>

<form method="post" action="{{ route('yonetim.urun.kaydet', @$entry->id) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="pull-right">
        <button type="submit" class="btn btn-primary">
            {{ @$entry->id > 0 ? "Güncelle" : "Kaydet" }}
        </button>
    </div>

    <h3 class="sub-header">
        Urun {{ @$entry->id > 0 ? "Güncelle" : "Kaydet" }}
    </h3>

    @include('layouts.partials.errors') @include('layouts.partials.alert')

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="urun_adi">Ürün Adı </label>
                <input type="text" class="form-control" id="urun_adi" name="urun_adi" placeholder="Ad Soyad" value="{{ old('urun_adi', $entry->urun_adi) }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="slug">Slug </label>
                <input type="hidden" name="original_slug" value="{{ old('slug', $entry->slug) }}">
                <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" value="{{ old('slug', $entry->slug) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="aciklama">Açıklama</label>
                <textarea class="form-control" name="aciklama" id="aciklama">{{ old('aciklama', $entry->aciklama) }}</textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="fiyati">Fiyati </label>
                <input type="text" class="form-control" id="fiyati" name="fiyati" placeholder="Fiyatı" value="{{ old('fiyati', $entry->fiyati) }}">
            </div>
        </div>
    </div>

    <div class="checkbox" class="form-control">
        <label>
            <input type="hidden" value="0" name="goster_slider">
            <input type="checkbox" value="1" name="goster_slider" {{ old('goster_slider', $entry->detay->goster_slider) ? "checked" : "" }}> Slider'da Göster
        </label>
    </div>
    <div class="checkbox" class="form-control">
        <label>
            <input type="hidden" value="0" name="goster_gunun_firsati">
            <input type="checkbox" value="1" name="goster_gunun_firsati" {{ old('goster_gunun_firsati', $entry->detay->goster_gunun_firsati) ? "checked" : "" }}> Günün Fırsatında Göster
        </label>
    </div>
    <div class="checkbox" class="form-control">
        <label>
            <input type="hidden" value="0" name="goster_one_cikan">
            <input type="checkbox" value="1" name="goster_one_cikan" {{ old('goster_one_cikan', $entry->detay->goster_one_cikan) ? "checked" : "" }}> Öne Çıkanlarda Göster
        </label>
    </div>
    <div class="checkbox" class="form-control">
        <label>
            <input type="hidden" value="0" name="goster_cok_satan">
            <input type="checkbox" value="1" name="goster_cok_satan" {{ old('goster_cok_satan', $entry->detay->goster_cok_satan) ? "checked" : "" }}> Çok Satanlarda Göster
        </label>
    </div>
    <div class="checkbox" class="form-control">
        <label>
            <input type="hidden" value="0" name="goster_indirimli">
            <input type="checkbox" value="1" name="goster_indirimli" {{ old('goster_indirimli', $entry->detay->goster_indirimli) ? "checked" : "" }}> İndirimli Olarak Göster
        </label>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="kategoriler">Kategoriler</label>
                <select name="kategoriler[]" id="kategoriler" class="form-control" multiple>
                    @foreach($kategoriler as $kategori)
                        <option value="{{ $kategori->id }}" {{ collect(old('kategoriler', $urun_kategorileri))->contains($kategori->id) ? 'selected' : '' }}>
                            {{ $kategori->kategori_adi }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group" for="urun_resmi">
            @if($entry->detay->urun_resmi != null)
                <img src="/uploads/urunler/{{ $entry->detay->urun_resmi }}" style="height:100px ; margin-right:20px" class="thumbnail pull-left">
            @endif
            <label for="urun_resmi">Ürün Resmi</label>
            <input type="file" name="urun_resmi" id="urun_resmi">
        </div>
    </div>
</form>
@endsection @section('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> 

@endsection 

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    $(function() {
        $('#kategoriler').select2({
            placeholder: 'Lütfen Kategori Seçiniz'
        });
    });
</script>
@endsection