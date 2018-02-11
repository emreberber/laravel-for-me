<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;
class UrunController extends Controller
{
    public function index($slug_urunadi){
        $urun = Urun::where('slug', $slug_urunadi)->firstOrFail();
        $kategoriler = $urun->kategoriler()->distinct()->get();
        return view('urun', compact('urun', 'kategoriler'));
    }

    public function ara(){
        $aranan  = request()->input('aranan');
        $urunler = Urun::where('urun_adi', 'like', "%$aranan%") // Cift tırnaklar icinde yazmak gerekiyor.    
        ->orWhere('aciklama', 'like', "%$aranan%")
        ->paginate(8);  // bir sayfada 8 kayıt göster
        // ->simplePaginate(2)  önceki - sonraki şeklinde
        request()->flash(); // oturum icinde session saklar
        return view('arama', compact('urunler'));
    }
}
