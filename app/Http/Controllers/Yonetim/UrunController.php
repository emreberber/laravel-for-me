<?php

namespace App\Http\Controllers\Yonetim;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\UrunDetay;

class UrunController extends Controller
{
    public function index(){
        request()->flash();

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
        request()->flash();
        // id 0'dan buyuk değilse boş kullanıcı gönderiyoruz
        $entry = new Urun;

        $urun_kategorileri = [];

        if($id>0){
            $entry = Urun::find($id);
            $urun_kategorileri = $entry->kategoriler()->pluck('kategori_id')->all(); 
            // sadece bir sütundan veri cekme plunck
        }

        $kategoriler = Kategori::all();

        return view('yonetim.urun.form', compact('entry', 'kategoriler', 'urun_kategorileri'));
    }

    public function kaydet($id = 0){
        request()->flash();
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

       // dd(request('goster_indirimli')); programı burada durdurur ve ekrana çıktıyı verir.

       // urunde ait detayda hangi alanları çekeceğimiz 
       $data_detay = request()->only(
           'goster_slider', 'goster_gunun_firsati', 'goster_one_cikan', 'goster_cok_satan', 'goster_indirimli'
       );

       $kategoriler = request('kategoriler');
      
       if($id > 0){
           // güncelle
           $entry = Urun::where('id', $id)->firstOrFail();
           $entry->update($data);  

           // UrunDetayın güncellenmesi 
           $entry->detay()->update($data_detay);
           // sync ile kategori urun tablosunu update ediyoruz
           $entry->kategoriler()->sync($kategoriler);
       }
       else{
           // kaydet
           $entry = Urun::create($data);
           $entry->detay()->create($data_detay);
           // kategori_urun tablosuna tek tek urunun kategorilerini ekleyecektir
           $entry->kategoriler()->attach($kategoriler);
       }

       // resim yükleme 
       if(request()->hasFile('urun_resmi')){
           $this->validate(request(), [
               'urun_resmi' => 'image|mimes:jpg,png,jpeg,gif|max:2048'
               // max:2048 -> 2mb
           ]);

           $urun_resmi = request()->file('urun_resmi');
           $urun_resmi = request()->urun_resmi;
           // $urun_resmi->extennsion();
           // $urun_resmi->getClientOriginalName(); dosyanın orijinal adı
           // $urun_resmi->hashName(); orijinal adı yerine hashlenmiş adını verir

           $dosyaadi = $entry->id . '-' . time() . '.' . $urun_resmi->extension();

           // dosya seçtikten sonra geçici alana düzgün yüklendiyse
           if($urun_resmi->isValid()){
                $urun_resmi->move('uploads/urunler', $dosyaadi);

                // varsa güncelle yoksa oluştur
                UrunDetay::updateOrCreate(
                    ['urun_id' => $entry->id],  // buna göre ekle veya güncelle
                    ['urun_resmi' => $dosyaadi] // bunu ekle veya güncelle
                );
           }

       }

       return redirect()
           ->route('yonetim.urun.duzenle', $entry->id)
           ->with('mesaj', ($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
           ->with('mesaj_tur', 'success');
    }


    public function sil($id){

        $urun = Urun::find($id);
        $urun->kategoriler()->detach();  // many to many silme 
        // $urun->detay()->delete(); detayı silmiyoruz.Bunun nedeni diğer tablolarda softdelete yapısını kullanmamız
        //detayda zaten silinme tarihi bulunmuyor 

        $urun->delete();

        return redirect()
            ->route('yonetim.urun')
            ->with('mesaj', 'Ürün silindi')
            ->with('mesaj_tur', 'success');
    }
}
