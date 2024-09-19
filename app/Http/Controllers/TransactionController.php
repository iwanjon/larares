<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Helpers\MidtransConfig;
use App\Models\Campaign;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Resources\CampaignResource;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\CreateTransactionResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Midtrans\Notification;

class TransactionController extends Controller
{
    //

    public function CreateTransaction(int $campaign_id, CreateTransactionRequest $request): JsonResponse
    {
        $curent_user = $request->get("current_user");
        if (!$curent_user){
            return throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                        ]
                        ]
                    ],400));
                }
                
                // dd("momo");
        $data = $request->validated();

        $campaign = Campaign::query()->find($campaign_id);
        
        if (!$campaign){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid campaign"
                        ]
                        ]
                    ],400));
                };


        if ($campaign->goal_amount <= $campaign->current_amount ){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "campaign goal amount is reached"
                        ]
                        ]
                    ],400));
        };


        $transaction =new Transaction($data);
        $transaction->status = 0;
        $transaction->user_id = $curent_user->id;
        $transaction->campaign_id = $campaign_id;
        $transaction->setCode();
        $transaction->save();
        $code = $transaction->id.$transaction->code;
        MidtransConfig::SetMidtransConfig();
        // dd($transaction);
// midtrans
        $midtrans = array(
            'transaction_details' => array(
                'order_id' =>  $code,
                'gross_amount' => (int) $transaction->amount,
            ),
            'customer_details' => array(
                'first_name'    => $transaction->user->name,
                'email'         => $transaction->user->email
            ),
            'enabled_payments' => array('gopay','bank_transfer'),
            'vtweb' => array()
        );

        try {
            // Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // Redirect ke halaman midtrans
            // return ResponseFormatter::success($transaction,'Transaksi berhasil');
            return (new CreateTransactionResource($transaction))->response()->setStatusCode(201);
        }
        catch (\Exception $e) {
            // return ResponseFormatter::error($e->getMessage(),'Transaksi Gagal');

            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid Transaction",
                        $e->getMessage()
                        ]
                    ]
                ],400));
            };


        


        // $transaction->save();

        return (new CreateTransactionResource($transaction))->response()->setStatusCode(201);
    }


    // GetUserTransactions(writer http.ResponseWriter, request *http.Request)

    public function GetUserTransactions(int $user_id, Request $request):JsonResponse {
        $curent_user = $request->get("current_user");

        if (!$curent_user){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                        ]
                        ]
                    ],400));
                }
                
        if($curent_user->id != $user_id){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                        ]
                        ]
                    ],400));
                }
        // dd($curent_user->transactions);
        $transaction= $curent_user->transactions;

    return CreateTransactionResource::collection($transaction)->response();

    }
	// GetCampaignTransactions(writer http.ResponseWriter, request *http.Request)

    public function GetCampaignTransactions(int $campaign_id, Request $request):JsonResponse {
        $campaign = Campaign::query()->find($campaign_id);

        if (!$campaign){
            throw new HttpResponseException(response(
                [
                    "errors"=>[
                        "message"=>[
                            "campaign not found"
                        ]
                    ]
                ], 400
                )                        
                );
         }

         $transaction= $campaign->transactions;
         
        return CreateTransactionResource::collection($transaction)->response();

    }

    public function Notification(Request $request) :JsonResponse
    {
        // Set konfigurasi midtrans
        MidtransConfig::SetMidtransConfig();

        // dd("ccc");
        try {
            $notif = new Notification();
        }
        catch (\Exception $e) {
            exit($e->getMessage());
        }
        
        $notif = $notif->getResponse();
        // Buat instance midtrans notification
        $notification = new Notification();
        echo $notification->order_id;
        echo $notification->transaction_status;
        // dd($notification);
        // Assign ke variable untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        // Cari transaksi berdasarkan ID
        $transaction = Transaction::query()->findOrFail($order_id);

        // Handle notification status midtrans
        if ($status == 'capture') {
            if ($type == 'credit_card'){
                if($fraud == 'challenge'){
                    $transaction->status = 'PENDING';
                }
                else {
                    $transaction->status = 'SUCCESS';
                }
            }
        }
        else if ($status == 'settlement'){
            $transaction->status = 'SUCCESS';
        }
        else if($status == 'pending'){
            $transaction->status = 'PENDING';
        }
        else if ($status == 'deny') {
            $transaction->status = 'CANCELLED';
        }
        else if ($status == 'expire') {
            $transaction->status = 'CANCELLED';
        }
        else if ($status == 'cancel') {
            $transaction->status = 'CANCELLED';
        }

        // Simpan transaksi
        $transaction->save();

        // Kirimkan email
        if ($transaction)
        {
            if($status == 'capture' && $fraud == 'accept' )
            {
                //
                return response()->json([
                    'meta' => [
                        'code' => 400,
                        'message' => 'Midtrans Payment Fraud'
                    ]
                ]);
            }
            else if ($status == 'settlement')
            {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment Settled'
                    ]
                ]);
            }
            else if ($status == 'success')
            {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment Success'
                    ]
                ]);
            }
            else if($status == 'capture' && $fraud == 'challenge' )
            {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment Challenge'
                    ]
                ]);
            }
            else
            {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment not Settlement'
                    ]
                ]);
            }

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Notification Success'
                ]
            ]);
        }
    }
	// GetNotif(writer http.ResponseWriter, request *http.Request)
}
