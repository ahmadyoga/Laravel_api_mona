<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Member;
use Carbon\Carbon;

date_default_timezone_set('Asia/Jakarta');

class UserController extends Controller
{
    public function info(Request $req)
    {
        //input ke variable
        $id = $req->user_id;

        //cari user dengan id yang diinginkan
        $user = User::find($id);

        return response()->json($user);
    }


    public function transaction(Request $req)
    {
        //cari transaction sesuai dengan id
        $user_id = $req->user_id;

        //
        $transaction = Transaction::where ('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        //respons
        return response()->json($transaction);
    }


    public function search(Request $req)
    {
        //cari transaction sesuai dengan id
        $user_id = $req->user_id;
        $keyword = $req->keyword;
        //
        $transaction = Transaction::where ('user_id', $user_id)->where ('note', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->get();
        //response
        if (is_null($transaction)) 
            { 
            return response()->json([ 'message' => 'Resource not found!' ], 404); 
            } 
        return response()->json($transaction, 200);
    }
    
    public function type(Request $req)
    {
        //cari transaction sesuai dengan id
        $user_id = $req->user_id;
        $keyword = $req->keyword;
        //
        $transaction = Transaction::where('user_id', $user_id)->where ('type', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->get();
        //response
        if (is_null($transaction)) 
            { 
            return response()->json([ 'message' => 'Resource not found!' ], 404); 
            } 
        return response()->json($transaction);
    }


    public function date(Request $req)
    {
        //cari transaction sesuai dengan id
        $user_id = $req->user_id;
        //
        $transaction = Transaction::where ('user_id', $user_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->get();
        //response
        if (is_null($transaction)) 
            { 
            return response()->json([ 'message' => 'Resource not found!' ], 404); 
            } 
            return
            response()->json($transaction, 200);
    }
    public function dashboard(Request $req)
    {
        //assign input
        $user_id = $req->user_id;

        //pengambilan data
        $transaction = Transaction::where ('user_id', $user_id)->get();
        $balance = Member::where ('id', $user_id)->get('balance');

        //return
        return response()->json(['balance' => $balance,'transaksi' => $transaction]);
    }
    
}
