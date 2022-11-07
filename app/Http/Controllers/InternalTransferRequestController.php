<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Notifications\ApprovedTransferRequest;
use App\Notifications\DepositMoney;
use App\Notifications\RejectTransferRequest;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalTransferRequestController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('backend.internal_transfer_request.list');
    }

    public function get_table_data(Request $request) {
        $transactions = Transaction::select('transactions.*')
            ->with('user')
            ->with('currency')
            ->whereRaw("(type = 'Transfer' OR type = 'Exchange')")
            ->where('parent_id', null)
            ->orderBy("transactions.id", "desc");

        return Datatables::eloquent($transactions)
            ->filter(function ($query) use ($request) {
                $status = $request->has('status') ? $request->status : 1;
                $query->where('status', $status);
            }, true)
            ->editColumn('user.name', function ($transaction) {
                return '<b>' . $transaction->user->name . ' </b><br>' . $transaction->user->email;
            })
            ->editColumn('amount', function ($transaction) {
                return decimalPlace($transaction->amount, currency($transaction->currency->name));
            })
            ->editColumn('status', function ($transaction) {
                return transaction_status($transaction->status);
            })
            ->addColumn('action', function ($transaction) {
                $actions = '<form action="' . action('InternalTransferRequestController@destroy', $transaction['id']) . '" class="text-center" method="post">';
                $actions .= '<a href="' . action('InternalTransferRequestController@show', $transaction['id']) . '" data-title="' . _lang('Transfer Details') . '" class="btn btn-outline-primary btn-sm ajax-modal"><i class="icofont-eye-alt"></i> ' . _lang('Details') . '</a>&nbsp;';
                $actions .= $transaction->status != 2 ? '<a href="' . action('InternalTransferRequestController@approve', $transaction['id']) . '" class="btn btn-outline-success btn-sm"><i class="icofont-check-circled"></i> ' . _lang('Approve') . '</a>&nbsp;' : '';
                $actions .= $transaction->status != 0 ? '<a href="' . action('InternalTransferRequestController@reject', $transaction['id']) . '" class="btn btn-outline-warning btn-sm"><i class="icofont-close-circled"></i> ' . _lang('Reject') . '</a>&nbsp;' : '';
                $actions .= csrf_field();
                $actions .= '<input name="_method" type="hidden" value="DELETE">';
                $actions .= '<button class="btn btn-outline-danger btn-sm btn-remove" type="submit"><i class="icofont-trash"></i> ' . _lang('Delete') . '</button>';
                $actions .= '</form>';

                return $actions;
            })
            ->setRowId(function ($transaction) {
                return "row_" . $transaction->id;
            })
            ->rawColumns(['user.name', 'status', 'action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $transaction = Transaction::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.internal_transfer_request.modal.view', compact('transaction', 'id'));
        }
    }

    /**
     * Approve Wire Transfer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id) {
        DB::beginTransaction();

        $debit         = Transaction::find($id);
        $debit->status = 2;
        $debit->save();

        $credit         = $debit->child_transaction;
        $credit->status = 2;
        $credit->save();

        DB::commit();
        try {
            $debit->user->notify(new ApprovedTransferRequest($debit));
            $credit->user->notify(new DepositMoney($credit));
        } catch (\Exception$e) {}

        return redirect()->route('internal_transfer_requests.index')->with('success', _lang('Transfer Request Approved'));
    }

    /**
     * Reject Wire Transfer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id) {
        DB::beginTransaction();

        $debit         = Transaction::find($id);
        $debit->status = 0;
        $debit->save();

        $credit         = $debit->child_transaction;
        $credit->status = 0;
        $credit->save();

        DB::commit();
        try {
            $debit->user->notify(new RejectTransferRequest($debit));
        } catch (\Exception$e) {}

        return redirect()->route('internal_transfer_requests.index')->with('success', _lang('Transfer Request Rejected'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::beginTransaction();

        $debit = Transaction::find($id);

        $credit = $debit->child_transaction;
        $credit->delete();

        $debit->delete();

        DB::commit();

        return redirect()->route('internal_transfer_requests.index')->with('success', _lang('Deleted Successfully'));
    }

}