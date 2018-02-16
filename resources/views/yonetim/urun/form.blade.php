@extends('yonetim.layouts.master') @section('title', 'Urun Yönetimi') @section('content')

<h1 class="page-header">Urun Yönetimi</h1>

<form method="post" action="{{ route('yonetim.urun.kaydet', @$entry->id) }}">
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
                <textarea  class="form-control" name="aciklama" id="aciklama">{{ old('aciklama', $entry->aciklama) }}</textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="fiyati">Fiyati </label>
                <input type="text" class="form-control" id="fiyati" name="fiyati" placeholder="Fiyatı" value="{{ old('fiyati', $entry->fiyati) }}">
            </div>
        </div>
    </div>
</form>


@endsection