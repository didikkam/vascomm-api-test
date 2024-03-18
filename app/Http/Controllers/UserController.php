<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $take = $request->input('take', 10);
        $skip = $request->input('skip', 0);
        $search = $request->input('search', '');
        $query = User::query();
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $users = $query->take($take)->skip($skip)->get();
        return getSuccessMessage($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|email|unique:users',
            'phone'     => 'required|numeric|unique:users',
            'password'  => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return getValidatedMessage($validator);
        }

        try {
            $data = User::create([
                'name'     => $request->name,
                'email'     => $request->email,
                'password'     => Hash::make($request->password),
                'phone'     => $request->phone,
            ]);
            return getSuccessMessage($data);
        } catch (\Throwable $th) {
            return getThrowMessage($th);
        }
    }

    public function show($id)
    {
        try {
            $data = User::where("id", $id)->first();
            if (!$data) {
                throw new Exception("Data tidak ditemukan", Response::HTTP_NOT_FOUND);
            }
            return getSuccessMessage($data);
        } catch (\Throwable $th) {
            return getThrowMessage($th);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'   => 'required|string|max:255|email',
            'phone'     => 'required|numeric',
            'password'  => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return getValidatedMessage($validator);
        }

        try {
            $data = User::where("id", $id)->first();
            if (!$data) {
                throw new Exception("Data tidak ditemukan", Response::HTTP_NOT_FOUND);
            }

            $dataUser = [
                'name'     => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
            ];
            if ($request->password) {
                $dataUser['password'] = Hash::make($request->password);
            }
            $data->update($dataUser);
            return getSuccessMessage($data);
        } catch (\Throwable $th) {
            return getThrowMessage($th);
        }
    }

    public function destroy($id)
    {
        try {
            $data = User::where("id", $id)->first();
            if (!$data) {
                throw new Exception("Data tidak ditemukan", Response::HTTP_NOT_FOUND);
            }
            $data->delete();
            return getSuccessMessage("");
        } catch (\Throwable $th) {
            return getThrowMessage($th);
        }
    }
}
