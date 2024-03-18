<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $take = $request->input('take', 10);
        $skip = $request->input('skip', 0);
        $search = $request->input('search', '');
        $query = Product::query();
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('price', 'like', '%' . $search . '%');
        }
        $users = $query->take($take)->skip($skip)->get();
        return getSuccessMessage($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'price'     => 'required|numeric',
            'status'     => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return getValidatedMessage($validator);
        }

        try {
            $data = Product::create([
                'name'     => $request->name,
                'price'     => $request->price,
                'status'     => $request->status,
            ]);
            return getSuccessMessage($data);
        } catch (\Throwable $th) {
            return getThrowMessage($th);
        }
    }

    public function show($id)
    {
        try {
            $data = Product::where("id", $id)->first();
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
            'name'      => 'required|string|max:255',
            'price'     => 'required|numeric',
            'status'     => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return getValidatedMessage($validator);
        }

        try {
            $data = Product::where("id", $id)->first();
            if (!$data) {
                throw new Exception("Data tidak ditemukan", Response::HTTP_NOT_FOUND);
            }

            $dataUser = [
                'name'     => $request->name,
                'price'     => $request->price,
                'status'     => $request->status,
            ];
            $data->update($dataUser);
            return getSuccessMessage($data);
        } catch (\Throwable $th) {
            return getThrowMessage($th);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Product::where("id", $id)->first();
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
