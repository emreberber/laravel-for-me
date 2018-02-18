@extends('yonetim.layouts.master') @section('title', 'Urun Yönetimi') @section('content')

<h1 class="page-header">Urun Yönetimi</h1>

<h3>Urun Listesi</h3>
<h1 class="sub-header">
    <div class="well">
        <div class="btn-group pull-right">
            <a href="{{ route('yonetim.urun.yeni') }}" class="btn btn-md btn-info">Yeni</a>
        </div>

        <form method="post" action="{{ route('yonetim.urun') }}" class="form-inline">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="search">Ara</label>
                <input type="text" class="form-control form-control-sm" name="aranan" id="aranan" placeholder="Arayan Bulur..." value="{{ old('aranan') }}">
                <button type="submit" class="btn btn-primary">Ara </button>
                <a href="{{ route('yonetim.urun') }}" class="btn btn-primary">Temizle</a>
            </div>
        </form>

    </div>
</h1>

@include('layouts.partials.alert')

<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Resim</th>
                <th>Slug</th>
                <th>Ürün Adı</th>
                <th>Fiyatı</th>
                <th>Kayıt Tarihi</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($list)==0)
                <tr>
                    <td colspan="7" class="text-center">Kayıt Bulunamadı</td>
                </tr>
            @endif
            @foreach ($list as $entry)
            <tr>
                <td>{{ $entry->id }}</td>
                <td>
                <img src="{{$entry->detay->urun_resmi != null ? asset('/uploads/urunler/'.$entry->detay->urun_resmi) : 'http://via.placeholder.com/400x400?text=UrunResmi' }}" class="img-responsive" style="width:120px">
                </td>
                <td>{{ $entry->slug }}</td>
                <td>{{ $entry->urun_adi }}</td>
                <td>{{ $entry->fiyati }}</td>
                <td>{{ $entry->olusturulma_tarihi }}</td>
                <td style="width: 100px">
                    <a href="{{ route('yonetim.urun.duzenle', $entry->id) }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Düzenle">
                        <span class="fa fa-pencil"></span>
                    </a>
                    <a href="{{ route('yonetim.urun.sil', $entry->id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Sil" onclick="return confirm('Are you sure?')">
                        <span class="fa fa-trash"></span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $list->appends('aranan', old('aranan'))->links() }}
</div>

@endsection