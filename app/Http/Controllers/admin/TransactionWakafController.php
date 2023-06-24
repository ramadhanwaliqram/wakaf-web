<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionWakaf;
use App\Models\User;
use App\Models\Wakaf;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TransactionWakafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $selectedWakaf;
    public function index(Request $request)
    {
        $dataWakaf = Wakaf::whereStatus('active')->get();
        $data = TransactionWakaf::with(['wakaf', 'references'])->latest()->get();

        if ($request->ajax()) {

            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" id="' . $data->uuid . '" class="edit btn btn-mini btn-primary shadow-sm">Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" id="' . $data->uuid . '" class="delete btn btn-mini btn-danger shadow-sm">Delete</button>';
                    return $button;
                })
                ->addColumn('wakaf', function ($data) {
                    return $data->wakaf->title;
                })
                ->editColumn('reference', function ($data) {
                    return $data->references->name;
                })
                ->editColumn('amount', function ($data) {
                    $formattedTarget = "Rp " . number_format($data->amount, 0, ',', '.');
                    return $formattedTarget;
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 'success') {
                        return '<span class="badge badge-pill badge-success">Selesai</span>';
                    }
                    if ($data->status == 'rejected') {
                        return '<span class="badge badge-pill badge-danger">Ditolak</span>';
                    }
                    if ($data->status == 'cancel') {
                        return '<span class="badge badge-pill badge-danger">Dibatalkan</span>';
                    }
                    if ($data->status == 'pending') {
                        return '<span class="badge badge-pill badge-warning">Menunggu</span>';
                    }
                })
                ->rawColumns(['action', 'wakaf', 'status'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.transaction.transaction', compact(['dataWakaf']));
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
                'wakaf' => 'required',
                'signature' => 'required',
                'amount' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $amount = preg_replace("/[^0-9]/", "", str_replace(',', '', $request->input('amount')));

            TransactionWakaf::create([
                'wakaf_uuid' => $request->input('wakaf'),
                'signature' => $request->input('signature'),
                'amount' => $amount,
                'reference' => auth()->user()->uuid,
                'status' => $request->input('status'),
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
            $transaction = TransactionWakaf::query()->where('uuid', $uuid)->first();
            return $transaction;
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
                'wakaf' => 'required',
                'signature' => 'required',
                'amount' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $amount = preg_replace("/[^0-9]/", "", str_replace(',', '', $request->input('amount')));

            TransactionWakaf::findOrFail($uuid)->update([
                'wakaf_uuid' => $request->input('wakaf'),
                'signature' => $request->input('signature'),
                'amount' => $amount,
                'reference' => auth()->user()->uuid,
                'status' => $request->input('status'),
            ]);
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
            $transaction = TransactionWakaf::query()->where('uuid', $uuid)->first();
            $transaction->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
