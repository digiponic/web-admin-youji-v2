<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class FavoriteController extends Controller
{
    public function data($idcustomer)
    {
        $path_ = DB::table('cms_settings')->where('name', 'lokasi_penyimpanan')->get();
        $path = $path_[0]->content;

        $data = DB::table('tb_favorite_item as f')
            ->join('tb_produk as pd', 'pd.id', '=', 'f.id_produk')
            ->join('tb_general as kt', 'pd.kategori', '=', 'kt.id')
            ->join('tb_general as jn', 'pd.jenis', '=', 'jn.id')
            ->join('tb_general as st', 'pd.satuan_jual', '=', 'st.id')
            ->select('pd.*', 'kt.keterangan as kategori', 'jn.keterangan as jenis', 'st.keterangan as satuan')
//            ->whereIn('pd.jenis',[8,22])
            ->where('f.id_customer', $idcustomer)
            ->whereNull('f.deleted_at')
            ->get();

        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            if ($data[$i]->gambar == null)
                $data[$i]->gambar = null;
            else
                $data[$i]->gambar = $path . "/" . $data[$i]->gambar;
        }

        return response()->json($data, 200);
    }

    public function tambah(Request $param)
    {
        $save = DB::table('tb_favorite_item')->insert([
            "id_customer" => $param['id_customer'],
            "id_produk"   => $param['id_produk'],
            "created_at"  => date("Y-m-d H:i:s")
        ]);
        if ($save) {
            return response()->json(['error' => false, 'msg' => 'Produk Disukai'], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }

    public function hapus(Request $param)
    {
        $hapus = DB::table('tb_favorite_item')->where('id_customer', $param['id_customer'])->where('id_produk', $param['id_produk'])->delete();
        if ($hapus) {
            return response()->json(['error' => false, 'msg' => 'Produk Batal Disukai'], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }
}
