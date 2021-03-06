<?php

namespace App\Http\Controllers;

use Auth;
use App\Wallet;
use App\Payment;
use Illuminate\Http\Request;
use Instamojo\Instamojo;

class WalltetController extends Controller
{
    private $payment;
    private $wallet;
    private $api;
    private $apiPaymentResponse;

    /**
     * Show wallet page
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('agent.home.wallet.index');
    }

    /**
     * Add money to wallet
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->check($request);
        $this->addPayment($request);
        $this->initApi();
        return $this->redirectToPaymentGateway();
    }

    /**
     * Check payment
     *
     * @param Response $response
     * @param string $txn_id
     * @return Illuminate\Http\Response
     */
    public function callback(Request $request, $txn_id)
    {
        $this->payment = Payment::where('txn_id', $txn_id)->firstOrFail();
        if (!$this->isPaymentComplete($request->payment_request_id)) {
            return redirect()->route('agent.home.wallet.index')->with([
                'ok' => false,
                'msg' => 'Payment Failed! Please try again.',
            ]);
        }

        $this->completePayment();
        $this->addToWallet();
        return redirect()->route('agent.home.wallet.index')->with([
            'ok' => true,
            'msg' => 'Payment successful!',
        ]);
    }

    /**
     * Check form data
     *
     * @param Request $request
     * @reutn void
     */
    private function check(Request $request)
    {
        $rules = [
            'amount' => 'required',
        ];
        $request->validate($rules);
    }

    /**
     * Create a new payment and set status to pending
     *
     * @return Illuminate\Http\Response
     */
    private function addPayment(Request $request)
    {
        $this->payment = Payment::create([
            'amount' => $request->amount,
            'status' => 'pending',
            'user_id' => Auth::user()->id,
        ]);
        $this->payment->txn_id = Hash('sha256', $this->payment->id);
        $this->payment->request = $this->getPaymentData();
        $this->payment->save();
    }

    /**
     * Get payment data
     *
     * @return array
     */
    private function getPaymentData()
    {
        return [
            'purpose' => 'Wallet Recharge - INR ' . $this->payment->amount,
            'amount' => $this->payment->amount,
            'buyer_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'send_email' => true,
            'phone' => Auth::user()->phone,
            'redirect_url' => route('wallet.callback', [$this->payment->txn_id])
        ];
    }

    /**
     * Create a new payment and set status to pending
     *
     * @return Illuminate\Http\Response
     */
    private function redirectToPaymentGateway()
    {
        try {
            $response =  $this->api->paymentRequestCreate($this->getPaymentData());
            return redirect($response['longurl']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['ok' => false, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Add money to wallet once transaction is complete
     *
     * @return void
     */
    private function addToWallet()
    {
        $this->wallet = Wallet::create([
            'payment_id' => $this->payment->id,
            'user_id' => Auth::user()->id,
            'amount' => $this->payment->amount,
            'slug' => 'wallet-recharge',
        ]);
    }

    /**
     * Mark payment as complete and save server response
     *
     * @return void
     */
    private function completePayment()
    {
        $this->payment->response = $this->apiPaymentResponse;
        $this->payment->status = 'success';
        $this->payment->save();
    }

    /**
     * Initialize API
     *
     * @return this
     */
    private function initApi()
    {
        if (env('INSTAMOJO_TEST_MODE')) {
            $this->api = new Instamojo(
                env('INSTAMOJO_TEST_API_KEY'),
                env('INSTAMOJO_TEST_AUTH_TOKEN'),
                env('INSTAMOJO_TEST_URL')
            );
            return $this;
        }

        // init default live payment gateway
        $this->api = new Instamojo(
            env('INSTAMOJO_LIVE_API_KEY'),
            env('INSTAMOJO_LIVE_AUTH_TOKEN')
        );

        return $this;
    }

    /**
     * Check if payment was successful
     *
     * @param string $payment_request_id - API Payment request id
     * @return boolean
     */
    private function isPaymentComplete($payment_request_id)
    {
        $this->initApi();
        try {
            $respnose = $this->api->paymentRequestStatus($payment_request_id);
            if ('Completed' == $respnose['status'] && $respnose['amount'] == $this->payment->amount) {
                $this->apiPaymentResponse = $respnose;
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
