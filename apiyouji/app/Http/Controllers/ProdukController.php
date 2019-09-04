<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use CRUDBooster;
use Storage;
use DB;

class ProdukController extends Controller
{
    public function data()
    {
        $path_ = DB::table('cms_settings')->where('name', 'lokasi_penyimpanan')->get();
        $path = $path_[0]->content;

        $data = DB::table('tb_produk as pd')
            ->join('tb_general as kt', 'pd.kategori', '=', 'kt.id')
            ->join('tb_general as jn', 'pd.jenis', '=', 'jn.id')
            ->join('tb_general as st', 'pd.satuan_jual', '=', 'st.id')
            ->select('pd.*', 'kt.keterangan as kategori', 'jn.keterangan as jenis', 'st.keterangan as satuan')
            ->whereIn('pd.jenis', [8, 22])
            ->whereNull('pd.deleted_at')
            ->get();

        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            if ($data[$i]->gambar == null)
                $data[$i]->gambar = null;
            else
                $data[$i]->gambar = $path . "/" . $data[$i]->gambar;
        }

        return $data;
    }

    public function filter(Request $request)
    {
        $path_ = DB::table('cms_settings')->where('name', 'lokasi_penyimpanan')->get();
        $path = $path_[0]->content;

        $data = DB::table('tb_produk as pd')
            ->join('tb_general as kt', 'pd.kategori', '=', 'kt.id')
            ->join('tb_general as jn', 'pd.jenis', '=', 'jn.id')
            ->join('tb_general as st', 'pd.satuan_jual', '=', 'st.id')
            ->select('pd.*', 'kt.keterangan as kategori', 'jn.keterangan as jenis', 'st.keterangan as satuan')
            ->where('pd.keterangan', 'like', '%' . $request['nama_produk'] . '%')
            ->orWhere('kt.keterangan', 'like', '%' . $request['nama_produk'] . '%')
            ->whereNull('pd.deleted_at')
            ->get();


        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            if ($data[$i]->gambar == null)
                $data[$i]->gambar = null;
            else
                $data[$i]->gambar = $path . "/" . $data[$i]->gambar;
        }

        return $data;
    }

    public function detail($id_produk, $id_customer)
    {
        $path_ = DB::table('cms_settings')->where('name', 'lokasi_penyimpanan')->get();
        $path = $path_[0]->content;

        $data = DB::table('tb_produk as pd')
            ->join('tb_general as kt', 'pd.kategori', '=', 'kt.id')
            ->join('tb_general as jn', 'pd.jenis', '=', 'jn.id')
            ->join('tb_general as st', 'pd.satuan_jual', '=', 'st.id')
            ->select('pd.*', 'kt.keterangan as kategori', 'jn.keterangan as jenis', 'st.keterangan as satuan')
            ->where('pd.id', $id_produk)
            ->whereNull('pd.deleted_at')
            ->first();

        if ($data) {
            $data->favorite = false;
            if ($data->gambar == null)
                $data->gambar = null;
            else
                $data->gambar = $path . "/" . $data->gambar;

            $cekFavorit = DB::table('tb_favorite_item as f')
                ->where('id_produk', $id_produk)
                ->where('id_customer', $id_customer)
                ->whereNull('f.deleted_at')
                ->first();

            if ($cekFavorit) {
                $data->favorite = true;
            }
        }

        return response()->json($data, 200);
    }
}
