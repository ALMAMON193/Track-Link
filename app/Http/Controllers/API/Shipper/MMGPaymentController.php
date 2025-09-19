<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\JobPost;
use App\Models\User;
use App\Notifications\JobPaymentSuccessNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\MMGPaymentService;
use Illuminate\Support\Facades\Log;

class MMGPaymentController extends Controller
{
    use ApiResponse;
//    protected MMGPaymentService $paymentService;

    public function jobAccept(Request $request)
    {
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
            'user_id'     => 'required|exists:users,id',
        ]);

        $shipperId = auth()->id();

        // Ensure the logged-in user owns this job post
        $jobPost = JobPost::where('id', $request->job_post_id)
            ->where('user_id', $shipperId)
            ->first();

        if (!$jobPost) {
            return $this->sendError('You are not authorized to accept this job.', [], 403);
        }

        // Find application for this applicant
        $application = JobApplication::where('user_id', $request->user_id)
            ->where('job_post_id', $request->job_post_id)
            ->first();

        if (!$application) {
            return $this->sendError('Application not found.');
        }

        // Update applicant to accepted
        $application->update([
            'status'      => 'accepted',
            'assigned_at' => now(),
        ]);

        // Reject all other applicants for this job
        JobApplication::where('job_post_id', $request->job_post_id)
            ->where('id', '!=', $application->id)
            ->update(['status' => 'rejected']);

        return $this->sendResponse([], 'Applicant accepted successfully.');
    }

//    public function __construct(MMGPaymentService $paymentService)
//    {
//        $this->paymentService = $paymentService;
//    }
    // 1️⃣ Make Payment for a Job
//    public function payJob(Request $request, $jobId)
//    {
//        try {
//            $job = JobPost::findOrFail($jobId);
//
//            $request->validate([
//                'customer_msisdn' => 'required|string|regex:/^[0-9+\-\s]+$/',
//                'amount' => 'nullable|numeric|min:1', // Optional override
//            ]);
//
//            // Use provided amount or job budget
//            $paymentAmount = $request->amount ?? $job->budget_amount;
//
//            if (!$paymentAmount || $paymentAmount <= 0) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Invalid payment amount'
//                ], 400);
//            }
//
//            $payment = Payment::create([
//                'job_post_id' => $job->id,
//                'amount'      => $paymentAmount,
//                'currency'    => $job->currency ?? 'GYD',
//                'status'      => 'pending',
//                'customer_msisdn' => $request->customer_msisdn,
//            ]);
//
//            Log::info('Payment initiated', [
//                'payment_id' => $payment->id,
//                'job_id' => $job->id,
//                'amount' => $paymentAmount,
//                'customer_msisdn' => $request->customer_msisdn
//            ]);
//
////            $response = $this->paymentService->merchantPayment([
////                'amount'          => $paymentAmount,
////                'currency'        => $job->currency ?? 'GYD',
////                'customer_msisdn' => $request->customer_msisdn,
////                'description'     => "Payment for job {$job->job_id}"
////            ]);
//
//            $payment->update([
//                'transaction_id' => $response['executionId'] ?? $response['transactionId'] ?? null,
//                'status'         => 'success',
//                'response'       => $response,
//            ]);
//
//            Log::info('Payment successful', [
//                'payment_id' => $payment->id,
//                'transaction_id' => $payment->transaction_id
//            ]);
//
//            return response()->json([
//                'success' => true,
//                'payment' => $payment,
//                'transaction_details' => $response
//            ]);
//
//        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Job not found'
//            ], 404);
//
//        } catch (\Illuminate\Validation\ValidationException $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Validation failed',
//                'errors' => $e->errors()
//            ], 422);
//
//        } catch (\Exception $e) {
//            Log::error('Payment failed', [
//                'job_id' => $jobId,
//                'error' => $e->getMessage(),
//                'trace' => $e->getTraceAsString()
//            ]);
//
//            // Update payment status if created
//            if (isset($payment)) {
//                $payment->update([
//                    'status'   => 'failed',
//                    'response' => ['error' => $e->getMessage()],
//                ]);
//            }
//
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }
//        public function payJob(Request $request, $jobId)
//        {
//            try {
//                $job = JobPost::findOrFail($jobId);
//
//                $paymentAmount = $job->budget_amount;
//                if (!$paymentAmount || $paymentAmount <= 0) {
//                    return response()->json(['success' => false, 'message' => 'Invalid payment amount'], 400);
//                }
//
//                // Create payment
//                $payment = Payment::create([
//                    'job_post_id'     => $job->id,
//                    'amount'          => $paymentAmount,
//                    'currency'        => $job->currency ?? 'GYD',
//                    'status'          => 'pending',
//                    'customer_msisdn' => $request->customer_msisdn,
//                ]);
//
//                // Simulate payment success
//                $transactionId = 'TEST-' . now()->timestamp;
//                $payment->update([
//                    'transaction_id' => $transactionId,
//                    'status'         => 'success',
//                    'response'       => [
//                        'transactionId' => $transactionId,
//                        'status'        => 'success',
//                        'message'       => 'Simulated payment successful',
//                    ],
//                ]);
//
//                // Assign job automatically without specifying user
//                $jobApplication = JobApplication::updateOrCreate(
//                    ['job_post_id' => $job->id],
//                    ['status' => 'accepted', 'assigned_at' => now(), 'rejection_reason' => null]
//                );
//                /**
//                 * Send notification to tracker
//                 */
//                $jobApplication = JobApplication::where('job_post_id', $job->id)
//                    ->where('status', 'accepted')
//                    ->first();
//
//                if ($jobApplication) {
//                    $tracker = $jobApplication->user;
//                    $tracker?->notify(new JobPaymentSuccessNotification($job, $payment));
//                }
//                return $this->sendResponse(compact('payment', 'jobApplication'), __('Payment successful'));
//
//            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//                return response()->json(['success' => false, 'message' => 'Job not found'], 404);
//
//            } catch (\Exception $e) {
//                if (isset($payment)) {
//                    $payment->update(['status' => 'failed', 'response' => ['error' => $e->getMessage()]]);
//                }
//                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
//            }
//        }
//    // 2️⃣ Balance Check
//    public function balanceCheck()
//    {
//        try {
//            $response = $this->paymentService->balanceCheck();
//            Log::info('Balance check successful', ['response' => $response]);
//            return response()->json([
//                'success' => true,
//                'balance' => $response
//            ]);
//        } catch (\Exception $e) {
//            Log::error('Balance check failed', [
//                'error' => $e->getMessage()
//            ]);
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }
//
//    // 3️⃣ Transaction History
//    public function transactionHistory(Request $request)
//    {
//        try {
//            $request->validate([
//                'from_date' => 'required|date|date_format:Y-m-d',
//                'to_date'   => 'required|date|date_format:Y-m-d|after_or_equal:from_date',
//                'offset'    => 'nullable|integer|min:0',
//                'limit'     => 'nullable|integer|min:1|max:100'
//            ]);
//
//            $response = $this->paymentService->transactionHistory(
//                $request->from_date,
//                $request->to_date,
//                $request->offset ?? 0,
//                $request->limit ?? 50
//            );
//
//            return response()->json([
//                'success' => true,
//                'transactions' => $response
//            ]);
//
//        } catch (\Illuminate\Validation\ValidationException $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Validation failed',
//                'errors' => $e->errors()
//            ], 422);
//
//        } catch (\Exception $e) {
//            Log::error('Transaction history failed', [
//                'error' => $e->getMessage()
//            ]);
//
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }
//
//    // 4️⃣ Transaction Lookup
//    public function transactionLookup($transactionId)
//    {
//        try {
//            if (empty($transactionId)) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Transaction ID is required'
//                ], 400);
//            }
//
//            $response = $this->paymentService->transactionLookup($transactionId);
//
//            return response()->json([
//                'success' => true,
//                'transaction' => $response
//            ]);
//
//        } catch (\Exception $e) {
//            Log::error('Transaction lookup failed', [
//                'transaction_id' => $transactionId,
//                'error' => $e->getMessage()
//            ]);
//
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }
//
//    // 5️⃣ Transaction Reversal
//    public function transactionReversal(Request $request, $transactionId)
//    {
//        try {
//            $request->validate([
//                'reason' => 'required|string|min:5|max:255'
//            ]);
//
//            if (empty($transactionId)) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Transaction ID is required'
//                ], 400);
//            }
//
//            Log::info('Transaction reversal initiated', [
//                'transaction_id' => $transactionId,
//                'reason' => $request->reason
//            ]);
//
//            $response = $this->paymentService->transactionReversal($transactionId, $request->reason);
//
//            return response()->json([
//                'success' => true,
//                'reversal' => $response
//            ]);
//
//        } catch (\Illuminate\Validation\ValidationException $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Validation failed',
//                'errors' => $e->errors()
//            ], 422);
//
//        } catch (\Exception $e) {
//            Log::error('Transaction reversal failed', [
//                'transaction_id' => $transactionId,
//                'error' => $e->getMessage()
//            ]);
//
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }
//
//    // 6️⃣ Payment Status Check (Additional helpful method)
//    public function checkPaymentStatus($paymentId)
//    {
//        try {
//            $payment = Payment::findOrFail($paymentId);
//
//            // If payment has transaction_id, check with MMG
//            if ($payment->transaction_id) {
//                try {
//                    $mmgResponse = $this->paymentService->transactionLookup($payment->transaction_id);
//
//                    // Update local payment status based on MMG response
//                    if (isset($mmgResponse['status'])) {
//                        $payment->update([
//                            'status' => $this->mapMmgStatus($mmgResponse['status']),
//                            'response' => array_merge($payment->response ?? [], ['mmg_status_check' => $mmgResponse])
//                        ]);
//                    }
//                } catch (\Exception $e) {
//                    Log::warning('MMG status check failed', [
//                        'payment_id' => $paymentId,
//                        'error' => $e->getMessage()
//                    ]);
//                }
//            }
//
//            return response()->json([
//                'success' => true,
//                'payment' => $payment->fresh()
//            ]);
//
//        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Payment not found'
//            ], 404);
//
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }
//
//    // Helper method to map MMG status to local status
//    private function mapMmgStatus(string $mmgStatus): string
//    {
//        return match(strtolower($mmgStatus)) {
//            'successful', 'completed', 'success' => 'success',
//            'failed', 'error', 'declined' => 'failed',
//            'pending', 'processing', 'initiated' => 'pending',
//            'reversed', 'refunded' => 'reversed',
//            default => 'unknown'
//        };
//    }
}
