<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\tbl_karyawan;

class Karyawan extends Controller
{
  public function baca(){
    $data = DB::table('tbl_karyawan')->get();
    if(count($data) > 0){
      $res['status'] = "Berhasil";
      $res['message'] = "Data dapat ditampilkan";
      $res['value'] = $data;
      return response($res);
    }else{
      $res['status'] = "Gagal";
      $res['message'] = "Data tidak dapat ditampilkan atau kosong";
      return response($res);
    }
  }

  public function simpan(Request $request){
    $this->validate($request, [
      'file' => 'required|max:2048'
    ]);
    $file = $request->file('file');
    $nama_file = time()."_".$file->getClientOriginalName();
    $tujuan_upload = 'data_file';
    if($file->move($tujuan_upload,$nama_file)){
      $data = tbl_karyawan::create([
        'nama' => $request->nama,
        'jabatan' => $request->jabatan,
        'umur' => $request->umur,
        'alamat' => $request->alamat,
        'foto' => $nama_file
      ]);
      $res['status'] = "Berhasil";
      $res['message'] = "Data dapat ditambahkan";
      $res['values'] = $data;
      return response($res);
    }
  }

  public function ubah(Request $request){
    if(!empty($request->file)){
      $this->validate($request, [
        'file' => 'required|max:2048'
      ]);
      $file = $request->file('file');
      $nama_file = time()."_".$file->getClientOriginalName();
      $tujuan_upload = 'data_file';
      $file->move($tujuan_upload,$nama_file);
      $data = DB::table('tbl_karyawan')->where('id',$request->id)->get();
      foreach($data as $keterangan){
        @unlink(public_path('data_file/'.$keterangan->gambar));
        $ket = DB::table('tbl_karyawan')->where('id',$request->id)->update([
          'nama' => $request->nama,
          'jabatan' => $request->jabatan,
          'umur' => $request->umur,
          'alamat' => $request->alamat,
          'foto' => $nama_file
        ]);
        $res['status'] = "Berhasil";
        $res['message'] = "Gambar dan Data Telah Terupdate";
        $res['values'] = $ket;
        return response($res);
      }
    }else{
      $data = DB::table('tbl_karyawan')->where('id',$request->id)->get();
      foreach($data as $keterangan){
        $ket = DB::table('tbl_karyawan')->where('id',$request->id)->update([
          'nama' => $request->nama,
          'jabatan' => $request->jabatan,
          'umur' => $request->umur,
          'alamat' => $request->alamat,
        ]);
        $res['status'] = "Berhasil";
        $res['message'] = "Data Telah Terupdate";
        $res['values'] = $ket;
        return response($res);
      }
    }
  }

  public function hapus($id){
    $data = DB::table('tbl_karyawan')->where('id',$id)->get();
    foreach($data as $keterangan){
      if(file_exists(public_path('data_file/'.$keterangan->foto))){
        @unlink(public_path('data_file/'.$keterangan->foto));
        DB::table('tbl_karyawan')->where('id',$id)->delete();
        $res['status'] = "Berhasil";
        $res['message'] = "Data telah terhapus";
        return response($res);
      }else{
        $res['message'] = "Data Kosong";
        return response($res);
      }
    }
  }
}
