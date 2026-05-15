<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemoPaymentRequest;
use App\Http\Requests\PaymentVerifyRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Determine if Razorpay is configured with real keys.
     */
    private function razorpayConfigured(): bool
    {
        $key = config('services.razorpay.key');
        return $key && !str_contains($key, 'REPLACE');
    }

    /**
     * Show payment checkout page.
     */
    public function checkout(Booking $booking)
    {
        // Ensure only the booking owner can pay
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // If already paid, redirect to success
        if ($booking->payment_status === 'paid') {
            return redirect()->route('payment.success', $booking);
        }

        $booking->load('pandit.user', 'service');

        // ─── Razorpay Mode ─────────────────────────────
        if ($this->razorpayConfigured()) {
            try {
                $api = new \Razorpay\Api\Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );

                $orderData = [
                    'receipt'         => 'rcptid_' . $booking->id,
                    'amount'          => (int)($booking->total_amount * 100), // paise
                    'currency'        => 'INR',
                    'payment_capture' => 1,
                ];

                $razorpayOrder = $api->order->create($orderData);

                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'transaction_id' => $razorpayOrder['id'],
                        'amount' => $booking->total_amount,
                        'status' => 'pending',
                        'payment_method' => 'razorpay',
                    ]
                );

                return view('payment.checkout', [
                    'booking' => $booking,
                    'razorpayOrder' => $razorpayOrder,
                    'paymentMode' => 'razorpay',
                ]);

            } catch (\Exception $e) {
                Log::error("Razorpay Order Error: " . $e->getMessage());
                // Fall through to demo mode
            }
        }

        // ─── Demo / Direct Payment Mode ────────────────
        // Works without any external SDK or API keys
        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'transaction_id' => 'DEMO_' . strtoupper(Str::random(12)),
                'amount' => $booking->total_amount,
                'status' => 'pending',
                'payment_method' => 'demo',
            ]
        );

        return view('payment.checkout', [
            'booking' => $booking,
            'razorpayOrder' => null,
            'paymentMode' => 'demo',
        ]);
    }

    /**
     * Verify Razorpay payment callback.
     */
    public function verify(PaymentVerifyRequest $request)
    {
        $validated = $request->validated();
        $success = true;
        $error = "Payment Failed";

        if (!empty($validated['razorpay_payment_id'])) {
            try {
                $api = new \Razorpay\Api\Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );

                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id' => $validated['razorpay_order_id'],
                    'razorpay_payment_id' => $validated['razorpay_payment_id'],
                    'razorpay_signature' => $validated['razorpay_signature'],
                ]);
            } catch (\Exception $e) {
                $success = false;
                $error = 'Payment verification failed: ' . $e->getMessage();
            }
        } else {
            $success = false;
            $error = "Payment ID not provided";
        }

        if ($success) {
            $payment = Payment::where('transaction_id', $validated['razorpay_order_id'])->first();
            if ($payment) {
                $payment->update([
                    'transaction_id' => $validated['razorpay_payment_id'],
                    'status' => 'successful',
                ]);
                $payment->booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                ]);
                return redirect()->route('payment.success', $payment->booking);
            }
        }

        return redirect()->route('pandit.search')->withErrors(['payment' => $error]);
    }

    /**
     * Process demo/direct payment (no external gateway needed).
     */
    public function processDemoPayment(DemoPaymentRequest $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        $payment = Payment::where('booking_id', $booking->id)->first();

        if ($payment) {
            $payment->update([
                'status' => 'successful',
                'payment_method' => $validated['payment_method'],
                'transaction_id' => strtoupper($validated['payment_method']) . '_' . strtoupper(Str::random(12)),
            ]);
        } else {
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => strtoupper($validated['payment_method']) . '_' . strtoupper(Str::random(12)),
                'amount' => $booking->total_amount,
                'status' => 'successful',
                'payment_method' => $validated['payment_method'],
            ]);
        }

        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        return redirect()->route('payment.success', $booking);
    }

    /**
     * Show payment success page.
     */
    public function success(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('pandit.user', 'service');

        return view('payment.success', compact('booking'));
    }
}
