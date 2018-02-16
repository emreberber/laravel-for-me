<?php

namespace App\Http\Controllers\Yonetim;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Urun;

class UrunController extends Controller
{
    public function index(){

        if(request()->filled('aranan')){
            // arama yapıldıktan sonra arama yapılan kelime tekrar inputta gözükmesi için
            // gelen değeri sessionda tutması için flash() kullanıyoruz
            // view içinde de old() metoduyla çekebiliyoruz.
            request()->flash();
            $aranan = request('aranan');
            $list   = Urun::where('urun_adi', 'like', "%$aranan%")
                ->orWhere('aciklama', 'like', "%$aranan%")
                ->orderByDesc('id')
                ->paginate(8);
        }
        else{
            $list = Urun::orderByDesc('id')->paginate(8);
        }
   
        return view('yonetim.urun.index', compact('list'));
    }


    // id göndermezse default 0 alacak bu yeni kayıt deme
    // id gelirse de bunun duzenleme oldugunu anlayacagız
    public function form($id = 0){
        // id 0'dan buyuk değilse boş kullanıcı gönderiyoruz
        $entry = new Urun;
        if($id>0){
            $entry = Urun::find($id);
        }

        return view('yonetim.urun.form', compact('entry'));
    }

    public function kaydet($id = 0){
       // slug degerini boş bırakırsa biz buradan dolduralım dedik

       $data = request()->only('urun_adi', 'slug', 'fiyati', 'aciklama');
       if(!request()->filled('slug')){
           $data['slug'] = str_slug(request('urun_adi'));
           request()->merge(['slug'=>$data['slug']]);
       }

       $this->validate(request(), [
           'urun_adi' => 'required',
           'fiyati'     => 'required',
           'slug'     => (request('original_slug') != request('slug') ? 'unique:urun,slug' : '')
       ]);
      
       if($id > 0){
           // güncelle
           $entry = Urun::where('id', $id)->firstOrFail();
           $entry->update($data);  
       }
       else{
           // kaydet
           $entry = Urun::create($data);
       }

       return redirect()
           ->route('yonetim.urun.duzenle', $entry->id)
           ->with('mesaj', ($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
           ->with('mesaj_tur', 'success');
    }


    public function sil($id){

        // DB'de kullanıcıyı silmez.Silinme tarihi alanını doldurur.Tekrar aynı mail ile giris yapamazsın

       // Kullanici::destroy($id); bu da farklı bir gösterim
        $kullanici =  Kullanici::where('id', $id)->delete();

        return redirect()
            ->route('yonetim.kullanici')
            ->with('mesaj', 'Kullanıcı silindi')
            ->with('mesaj_tur', 'success');
    }
}
