<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $guarded = [ 
        'id' 
      ]; 

    use HasFactory;

    public function anchorRelation($id)
    {
      $inquiry = new Inquiry;
      $fuga[]=$inquiry::where('id',$id)->first()->inquiry_id;//該当記事のIDを配列に格納
      $hoge = $inquiry::where('id',$id)->first()->anchor;//該当記事のanchor
      $fuga[]=$hoge;
   
      for ($i =1; $i <= 100; $i++){
          if($hoge !=null){
              $hoge = $inquiry::where('inquiry_id',$hoge)->first()->anchor;
              $fuga[]=$hoge;
          }
      }        

      $fuga = array_diff($fuga,array(null));
      $fuga = array_reverse($fuga);
      $fuga = implode('>', $fuga);

      return $fuga;
    }    
}
