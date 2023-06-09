<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wakaf;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WakafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Wakaf::get();
        if ($request->ajax()) {

            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" id="' . $data->uuid . '" class="edit btn btn-mini btn-primary shadow-sm">Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" id="' . $data->uuid . '" class="delete btn btn-mini btn-danger shadow-sm">Delete</button>';
                    return $button;
                })
                ->addColumn('detail', function ($data) {
                    return '<button type="button" id="' . $data->uuid . '" class="detail-wakaf badge badge-pill badge-warning">Lihat Detail</button>';
                })
                // ->editColumn('thumbnail', function ($data) {
                //     $url = url('storage/wakaf/' . $data->thumbnail . '');
                //     $thumbnail = '<img id="myImg" src="' . $url . '" style="width:200;height:200px">';
                //     return $thumbnail;
                // })
                ->editColumn('target', function ($data) {
                    $formattedTarget = "Rp " . number_format($data->target, 0, ',', '.');
                    return $formattedTarget;
                })
                ->rawColumns(['action', 'detail'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.wakaf.wakaf', compact(['data']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'nullable',
                'target' => 'required',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }

            if ($request->file('thumbnail')) {
                $fileName = time() . '.' . $request->thumbnail->extension();
                $request->thumbnail->storeAs('public/wakaf', $fileName);
            }

            $targetAmount = preg_replace("/[^0-9]/", "", str_replace(',', '', $request->input('target')));
            Wakaf::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'target' => $targetAmount,
                'thumbnail' => $fileName,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()
            ->json([
                'success' => 'Data berhasil ditambahkan.',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        try {
            $user = Wakaf::query()->where('uuid', $uuid)->first();
            return $user;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'nullable',
                'target' => 'required',
                'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }

            $targetAmount = preg_replace("/[^0-9]/", "", str_replace(',', '', $request->input('target')));

            $wakaf = Wakaf::findOrFail($uuid);
            // dd($wakaf->thumbnail);
            if ($request->file('thumbnail')) {
                $fileName = time() . '.' . $request->thumbnail->extension();
                Storage::delete('public/wakaf/' . $wakaf->thumbnail);
                $request->thumbnail->storeAs('public/wakaf', $fileName);
                $wakaf->thumbnail = $fileName;
            }

            $wakaf->title = $request->input('title');
            $wakaf->description = $request->input('description');
            $wakaf->target = $targetAmount;
            $wakaf->update();
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()
            ->json([
                'success' => 'Data berhasil diupdate.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            $user = Wakaf::query()->where('uuid', $uuid)->first();
            $user->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
