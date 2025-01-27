<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TransactionWakaf;
use App\Models\Wakaf;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

class WakafController extends Controller
{
    public function index(Request $request)
    {
        $only = $request->only(['status', 'per_page', 'page', 'keyword']);

        $query = Wakaf::with('transaction')->where('status', $only['status']);

        if (isset($only['keyword'])) {
            $query->where('title', 'LIKE', '%' . $only['keyword'] . '%');
        }

        $perPage = $only['per_page'] ?? 10;
        $page = $only['page'] ?? 1;

        $wakaf = $query->paginate($perPage, ['*'], 'page', $page);
        foreach ($wakaf as $key => $item) {
            $item->makeHidden('transaction');
            $item['total_amount'] = $item->transaction->where('status', 'success')->sum('amount');
        }

        $response = array_merge([
            "success" => true,
            "message" => "Successfully get data wakaf!"
        ], $wakaf->toArray());

        return response()->json($response);
    }

    public function transaction(Request $request)
    {
        $user = auth()->user();

        //check user not have pending deposit
        $pendingTransaction = $user->transaction()->where('user_uuid', $user->uuid)->orWhere('reference', $user->uuid)->where('status', 'pending')->first();

        if ($pendingTransaction) {
            return response()->json([
                'success' => false,
                'message' => "You have pending transaction",
            ], 403);
        }


        try {
            $amount = preg_replace("/[^0-9]/", "", str_replace(',', '', $request->amount));
            if ($user->role == 'user') {
                $transaction = TransactionWakaf::create([
                    'wakaf_uuid' => $request->wakaf_uuid,
                    'user_uuid' => auth()->user()->uuid,
                    'signature' => $request->signature,
                    'amount' => $amount,
                    'status' => $request->status,
                ]);
            } else {
                $transaction = TransactionWakaf::create([
                    'wakaf_uuid' => $request->wakaf_uuid,
                    'signature' => $request->signature,
                    'reference' => auth()->user()->uuid,
                    'amount' => $amount,
                    'status' => $request->status,
                ]);
            }

            $response = array_merge([
                "success" => true,
                "message" => "Successfully add transaction wakaf!"
            ], $transaction->toArray());

            return response()->json($response, 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function history(Request $request)
    {
        $only = $request->only(
            ['status', 'per_page', 'page']
        );

        $user = auth()->user();

        $query = TransactionWakaf::with('wakaf', 'references')->where('user_uuid', $user->uuid)->orWhere('reference', $user->uuid)->latest();

        if (isset($only['status'])) {
            $query->where('status', $only['status']);
        }

        $perPage = $only['per_page'] ?? 10;
        $page = $only['page'] ?? 1;

        $transaction = $query->paginate($perPage, ['*'], 'page', $page);

        $response = array_merge([
            "success" => true,
            "message" => "Successfully get data transaction!"
        ], $transaction->toArray());

        return response()->json($response);
    }
}
