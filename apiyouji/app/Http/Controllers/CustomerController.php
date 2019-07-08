<?php

namespace App\Http\Controllers;

use App\CustomerAddress;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\DB;
use Validator;

class CustomerController extends Controller
{
    function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'nama'    => 'required|min:4',
            'email'   => 'required|email|unique:tb_customer,email',
            'telepon' => 'required|digits_between:10,12',
        ], [
            'required'       => ':attribute harus diisi.',
            'unique'         => ':attribute harus unique.',
            'digits_between' => ':attribute harus 10 atau 12 digits',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'msg' => $validator->errors()], 401);
        }

        return true;
    }

    public function all()
    {
        $data = Customer::All();
        return response()->json(['error' => false, 'msg' => 'Daftar Pelanggan', 'data' => $data], 200);
    }

    public function show($email)
    {
        $data = DB::table('tb_customer')->where('email', $email)->first();
        if ($data) {
            return response()->json(['error' => false, 'msg' => 'Detail Pelanggan', 'data' => $data], 200);
        }
        return response()->json(['error' => false, 'msg' => 'Pelanggan Tidak Ditemukan', 'data' => null], 200);
    }

    public function store(Request $request)
    {

        $save = Customer::FirstOrCreate([
            "name"  => $request->json()->get('name'),
            "email" => $request->json()->get('email'),
            "phone" => $request->json()->get('phone'),
        ]);

        if ($save) {

            CustomerAddress::FirstOrCreate([
                "id_customer"    => $save->id,
                "keterangan"     => $request->json()->get('keterangan'),
                "kode_provinsi"  => $request->json()->get('kode_provinsi'),
                "kode_kota"      => $request->json()->get('kode_kota'),
                "kode_kecamatan" => $request->json()->get('kode_kecamatan'),
                "kodepos"        => $request->json()->get('kodepos'),
                "rt"             => $request->json()->get('rt'),
                "rw"             => $request->json()->get('rw'),
                "alamat"         => $request->json()->get('alamat'),
                "uatam"          => 1,
            ]);

            return response()->json(['error' => false, 'msg' => 'Data berhasil ditambahkan', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }

    public function batch(Request $request)
    {
        $data = $request->json()->all();
        $exec = Customer::insert($data);

        $status = ($exec) ? true : false;
        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }

    public function update(Request $request, $customerId)
    {
        $update = Customer::where('email', $customerId)->update([
            "name"  => $request->json()->get('name'),
            "email" => $request->json()->get('email'),
            "phone" => $request->json()->get('phone'),
        ]);

        if ($update) {
            return response()->json(['error' => false, 'msg' => 'Data berhasil diubah', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }

    public function delete($customerEmail)
    {
        $customer = Customer::where('email', $customerEmail)->first();
        $delete = Customer::where('email', $customerEmail)->delete();

        if ($delete) {
            // delete all the addresses
            CustomerAddress::where('id_customer', $customer['id_customer'])->delete();
            return response()->json(['error' => false, 'msg' => 'Data berhasil dihapus', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }
}
