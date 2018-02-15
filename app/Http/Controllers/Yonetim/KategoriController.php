<?php

namespace App\Http\Controllers\Yonetim;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
    public function index(){

        if(request()->filled('aranan') || request()->filled('ust_id')){
            // arama yapıldıktan sonra arama yapılan kelime tekrar inputta gözükmesi için
            // gelen değeri sessionda tutması için flash() kullanıyoruz
            // view içinde de old() metoduyla çekebiliyoruz.
            request()->flash();
            $aranan = request('aranan');
            $ust_id = request('ust_id');
            // view'de $entry->ust_kategori->kategori-> id şeklinde çekmek yerine with() ile ust_kategori
            // bilgisini de çekip gönderiyoruz.Böylece her seferinde modeldeki sorgu çalışmamış olur ve
            // daha performanslı olur.
            $list   = Kategori::with('ust_kategori')
                ->where('kategori_adi', 'like', "%$aranan%")
                ->where('ust_id', $ust_id)
                ->orderByDesc('id')
                ->paginate(2)
                ->appends(['aranan'=>$aranan, 'ust_id'=>$ust_id]);
        }
        else{
            request()->flush();  // flash() ın tersi : istekten gelen değerleri sıfırlar
            $list = Kategori::with('ust_kategori')->orderByDesc('id')->paginate(8);
        }
   
        $anakategoriler = Kategori::where('ust_id', null)->get();

        return view('yonetim.kategori.index', compact('list', 'anakategoriler'));
    }


    // id göndermezse default 0 alacak bu yeni kayıt deme
    // id gelirse de bunun duzenleme oldugunu anlayacagız
    public function form($id = 0){
        // id 0'dan buyuk değilse boş kullanıcı gönderiyoruz
        $entry = new Kategori;
        if($id>0){
            $entry = Kategori::find($id);
        }

        $kategoriler = Kategori::all();

        return view('yonetim.kategori.form', compact('entry', 'kategoriler'));
    }

    public function kaydet($id = 0){
        
        // slug degerini boş bırakırsa biz buradan dolduralım dedik

        $data = request()->only('kategori_adi', 'slug', 'ust_id');
        if(!request()->filled('slug')){
            $data['slug'] = str_slug(request('kategori_adi'));
            request()->merge(['slug'=>$data['slug']]);
        }

        $this->validate(request(), [
            'kategori_adi' => 'required',
            'slug'         => (request('original_slug') != request('slug') ? 'unique:kategori,slug' : '')
        ]);
       
    
  
        if($id > 0){
            // güncelle
            $entry = Kategori::where('id', $id)->firstOrFail();
            $entry->update($data);

            
        }
        else{
            // kaydet
            $entry = Kategori::create($data);
        }

        return redirect()
            ->route('yonetim.kategori.duzenle', $entry->id)
            ->with('mesaj', ($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
            ->with('mesaj_tur', 'success');
    }


    public function sil($id){

        $kategori = Kategori::find($id);
        // bu kategoriye ait ürünleri silmek için detach() kullanılır eklemek icin attach()
        $kategori->urunler()->detach();

       // DB'de kullanıcıyı silmez.Silinme tarihi alanını doldurur.Tekrar aynı mail ile giris yapamazsın
       // Kullanici::destroy($id); bu da farklı bir gösterim
        $kategori =  Kategori::where('id', $id)->delete();
        // $kategori->delete() de kullanılbilir

        return redirect()
            ->route('yonetim.kategori')
            ->with('mesaj', 'Kategori silindi')
            ->with('mesaj_tur', 'success');
    }
}
