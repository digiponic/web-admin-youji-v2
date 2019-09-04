<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function all()
    {
        // $data = Customer::all();
        $data = Customer::All();
        return $data;
    }

    public function show($id)
    {
        $data = Customer::find($id);
        return $data;
    }

    public function isExist($email)
    {
        $msg = array(
            'status'  => true,
            'message' => 'Email belum terdaftar'
        );
        $data = Customer::where('email', $email);
        if ($data) {
            $msg = array(
                'status'  => true,
                'message' => 'Email sudah terdaftar'
            );
        }
        return $msg;
    }

    public function store(Request $request)
    {
        $check = $this->isExist($request->json()->get('email'));
        if ($check) {
            $msg = array(
                'status'  => false,
                'message' => 'Email sudah terdaftar'
            );
        } else {
            $data = Customer::FirstOrCreate([
                "name"     => $request->json()->get('name'),
                "email"    => $request->json()->get('email'),
                "phone"    => $request->json()->get('phone'),
                "address"  => $request->json()->get('address'),
                "fullname" => $request->json()->get('fullname')
            ]);
            $status = ($data) ? true : false;
            $msg = array(
                'status'  => $status,
                'message' => ($status) ? 'Success' : 'Failed'
            );
        }
        return $msg;
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

    public function update(Request $request, Response $response, $id)
    {
        $data = Customer::FindOrFail($id)->update([
            "code"         => $request->json()->get('code'),
            "name"         => $request->json()->get('name'),
            "desc"         => $request->json()->get('desc'),
            "email"        => $request->json()->get('email'),
            "phone"        => $request->json()->get('phone'),
            "created_user" => null,
            "updated_user" => null,
            "deleted_user" => null,
        ]);
        $status = ($data) ? true : false;
        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }

    public function delete($id)
    {
        $data = Customer::FindOrFail($id)->delete();
        $status = ($data) ? true : false;
        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }

    public function login(Request $request)
    {
        $customer = Customer::
        where('email', $request->json()->get('username'))
            ->orWhere('phone', $request->json()->get('username'))
            ->first();
        if (password_verify($request->json()->get('password'), $customer['password'])) {
            return response()->json(['error' => false, 'msg' => 'Login berhasil', 'data' => $customer], 200);
        } else {
            return response()->json(['error' => true, 'msg' => 'Username / Password salah', 'data' => 0], 401);
        }

    }
}
