<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Transaction;

date_default_timezone_set('Asia/Jakarta');
class TransactionController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function storeadd(Request $request)
    {

        //assign input ke variable baru
        $id = $request->id;
        $jml_uang = $request->jml_uang;
        $note = $request->note;

        //ambil data user
        $user = User::find($id);
        $member = Member::find($id);

        //buat transaksi
        $transaction = new Transaction;
        $transaction->type = "add";
        $transaction->jml_uang = $jml_uang;
        $transaction->note = $note;
        $transaction->user_id = $user->id;
        $transaction->save();

        //update jumlah tabungan
        $jml_tabunganNew = $member->balance + $jml_uang;
        $account = new account;
        $account->user_id = $user->id;
		$account->debit = $jml_uang;
        $account->balance = $jml_tabunganNew;
        $account->save();

        //jumlah total
        $jml_tabunganNew = $member->balance+$jml_uang;
        $member->balance = $jml_tabunganNew;
        $member->save();


        //return response
        return
        response()->json([
            'massage' => 'berhasil menambahkan uang',
            // 'saldo' => $account,
            'data' => $transaction,
            'id' => $account,
        ]);
    }

    public function storeget(Request $request)
    {

        //assign input ke variable baru
        $id = $request->id;
        $user_id = $request->id;
        $jml_uang = $request->jml_uang;
        $note = $request->note;

        //ambil data user
        $user = User::find($id);
        $member = Member::find($id);

        if($member->balance == 0) {
           return response()->json([
                'massage' => 'saldo anda habis',
            ]);
        }
        // cek apakah jml_uang yang ingin di ambil melebihi jml_tabungan
        if($jml_uang > $member->balance) {
            return response()->json([
                'massage' => 'jumlah yang anda masukkan melebihi saldo anda',
            ]);
        }

        //buat transaksi
        $transaction = new Transaction;
        $transaction->type = "get";
        $transaction->jml_uang = $jml_uang;
        $transaction->note = $note;
        $transaction->user_id = $user->id;
        $transaction->save();

        //update jumlah tabungan
        $jml_tabunganNew = $member->balance-$jml_uang;
        $account = new account;
        $account->user_id = $user->id;
		$account->kredit = $jml_uang;
        $account->balance = $jml_tabunganNew;
        $account->save();

        //jumlah total
        $jml_tabunganNew = $member->balance-$jml_uang;
        $member->balance = $jml_tabunganNew;
        $member->save();


        //return response
        return
        response()->json([
            'massage' => 'berhasil mengambil uang',
            // 'saldo' => $account,
            'data' => $transaction,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show(Request $request)
    {
        //assign parameter ke variabel baru
        $id = $request->id;
        
        //ambil question sesuai parameter
        $transaction = Transaction::find($id);

        //return
        return response()->json([
            'id' => $transaction->id,
            'type' => $transaction->type,
            'note' => $transaction->note,
            'jml_uang' => $transaction->jml_uang,
            'created_at' => $transaction->created_at,
        ]);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //assign nilai baru 
        $id = $request->id;
        $type = $request->type;
        $jml_uang = $request->jml_uang;
        $note = $request->note;

        //ambil data question
        $transaction = Transaction::find($id);

        //assign nilai baru ke database
        $transaction->type = $type;
        $transaction->jml_uang = $jml_uang;
        $transaction->note = $note;
        $transaction->save();

        //return
        return response()->json([
            'massage' => 'Data berhasil diupdate',
            'data' => $transaction,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //assign parameter
        $id = $request->id;

        $transaction = Transaction::find($id);

        $transaction->delete();

        return response()->json([
            'massage' => 'Data berhasil dihapus',
            'data' => $transaction,
        ]);
    }
}
