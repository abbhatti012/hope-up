<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\NotificationStatus;
use App\Helpers\PaginationHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Added this import for the new logic
use App\Services\EmailService;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $searchFields = ['id', 'total_amount', 'currency', 'payment_status', 'payment_method'];

        $query = Transaction::with(['patient', 'doctor']);

        // Apply search filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('payment_status', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('total_amount', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('doctor', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('payment_status', $request->status);
        }

        // Apply source filter
        if ($request->has('source') && !empty($request->source)) {
            $query->where('source', $request->source);
        }

        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply amount range filter
        if ($request->has('amount_min') && is_numeric($request->amount_min)) {
            $query->where('total_amount', '>=', $request->amount_min);
        }
        if ($request->has('amount_max') && is_numeric($request->amount_max)) {
            $query->where('total_amount', '<=', $request->amount_max);
        }

        // Apply sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Get pagination setting or default to 50
        $perPage = $request->input('per_page', 50);
        $transactions = $query->with([
                'patient' => function($q) {
                    $q->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
                },
                'doctor' => function($q) {
                    $q->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
                }
            ])
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.transactions.listing', compact('transactions'));
    }

   
    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $transactions = $query->get();

        $fileName = 'transactions-' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 with Excel
            fputs($file, "\xEF\xBB\xBF");
            
            // Add CSV headers with Excel-friendly formatting hints
            fputcsv($file, [
                'Transaction ID',
                'Date (YYYY-MM-DD HH:MM)',
                'Patient',
                'Doctor',
                'Amount',
                'Doctor Amount',
                'Source',
                'Status',
                'Payment Method',
                'Reference'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                $patientName = 'N/A';
                $doctorName = 'N/A';
                
                if ($transaction->patient) {
                    $patientName = trim($transaction->patient->first_name . ' ' . $transaction->patient->last_name);
                }
                
                if ($transaction->doctor) {
                    $doctorName = trim($transaction->doctor->first_name . ' ' . $transaction->doctor->last_name);
                }
                
                // Format date in Excel-friendly format with explicit date type
                $transactionDate = $transaction->created_at ? 
                    '="' . $transaction->created_at->format('Y-m-d H:i') . '"' : 'N/A';
                
                // If no external transaction ID, use internal ID as reference
                $reference = $transaction->transaction_id ?? 'ORD-' . $transaction->id;
                
                fputcsv($file, [
                    'ORD-' . $transaction->id,
                    $transactionDate,
                    $patientName,
                    $doctorName,
                    ($transaction->currency ? $transaction->currency . ' ' : 'GHS ') . number_format($transaction->total_amount ?? 0, 2),
                    ($transaction->currency ? $transaction->currency . ' ' : 'GHS ') . (strtolower($transaction->source ?? '') === 'subscription' ? '0.00' : number_format($transaction->doctor_amount ?? 0, 2)),
                    $transaction->source ?? 'N/A',
                    $transaction->payment_status ? ucfirst($transaction->payment_status) : 'N/A',
                    $transaction->payment_method ?? 'N/A',
                    $reference
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Build the filtered query based on request parameters
     */
    public function generateReceipt(Transaction $transaction)
    {
        // Ensure the user has permission to view this receipt
        // You might want to add additional authorization checks here
        
        // Increase the maximum execution time
        set_time_limit(300); // 5 minutes
        
        $data = [
            'transaction' => $transaction->load(['patient', 'doctor']),
            'clinic' => [
                'name' => config('app.name', 'Hope Up'),
                'address' => '123 Medical Center Dr, City, Country',
                'phone' => '+1 (555) 123-4567',
                'email' => 'info@clinic.com',
                'logo' => null, // Don't include logo if GD extension is not available
            ]
        ];

        // Only try to include logo if GD extension is available
        if (extension_loaded('gd') && function_exists('gd_info')) {
            $logoPath = public_path('assets/images/logo-w.png');
            if (file_exists($logoPath)) {
                $data['clinic']['logo'] = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        try {
            $pdf = Pdf::loadView('admin.transactions.receipt', $data);
            $filename = 'receipt-' . $transaction->id . '-' . now()->format('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error generating receipt: ' . $e->getMessage());
            
            // Return a more user-friendly error message
            return back()->with('error', 'Failed to generate receipt. ' . 
                (extension_loaded('gd') ? '' : 'GD extension is required for image processing. ') . 
                'Please contact support.');
        }
    }

    /**
     * Update the status of a transaction
     *
     * @param  \App\Models\Transaction  $transaction
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Show transaction details in a modal
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\View\View
     */
    public function details(Transaction $transaction)
    {
        $transaction->load(['patient', 'doctor', 'appointment']);
        
        return view('admin.transactions.details', compact('transaction'));
    }

    public function toggleBlock(Transaction $transaction, Request $request)
    {
        $validStatuses = ['completed', 'cancelled', 'refunded'];
        $newStatus = $request->input('payment_status');
        
        if (!in_array($newStatus, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status provided.');
        }
        
        try {
            // Update the transaction status using raw query to avoid any casting issues
            DB::table('transactions')
                ->where('id', $transaction->id)
                ->update([
                    'payment_status' => (string)$newStatus,
                    'updated_at' => now()
                ]);

            // Add total_amount to superadmin's net_balance when status becomes completed
            if ($newStatus === 'completed' && $transaction->total_amount > 0) {
                $superadmin = User::where('role', 'superadmin')->first();
                if ($superadmin) {
                    $superadmin->net_balance = ($superadmin->net_balance ?? 0) + $transaction->total_amount;
                    $superadmin->save();
                }
            }

            // Handle refunds - deduct commission from superadmin and doctor_amount from doctor
            if ($newStatus === 'refunded') {
                $superadmin = User::where('role', 'superadmin')->first();
                if ($superadmin && $transaction->total_amount > 0) {
                    // Deduct commission amount (total_amount - doctor_amount) from superadmin
                    $commissionAmount = $transaction->total_amount - ($transaction->doctor_amount ?? 0);
                    $superadmin->net_balance = ($superadmin->net_balance ?? 0) - $commissionAmount;
                    $superadmin->save();
                }

                // Deduct doctor_amount from doctor's net_balance
                if ($transaction->doctor && $transaction->doctor_amount > 0) {
                    $doctor = $transaction->doctor;
                    $doctor->net_balance = ($doctor->net_balance ?? 0) - $transaction->doctor_amount;
                    $doctor->save();
                }

                // Add total_amount to patient's net_balance (refund to patient)
                if ($transaction->patient && $transaction->total_amount > 0) {
                    $patient = $transaction->patient;
                    $patient->net_balance = ($patient->net_balance ?? 0) + $transaction->total_amount;
                    $patient->save();
                }
            }
                
            // Send transaction status update email
            try {
                $emailService = app(EmailService::class);
                if ($newStatus === 'completed') {
                    $emailService->sendTransactionNotification($transaction, 'paid');
                } elseif ($newStatus === 'refunded') {
                    $emailService->sendTransactionNotification($transaction, 'refunded');
                } else {
                    $emailService->sendTransactionNotification($transaction, 'updated');
                }
            } catch (\Exception $e) {
                Log::error('Failed to send transaction status update email: ' . $e->getMessage());
            }
            return redirect()->back()->with('success', "Transaction has been marked as " . ucfirst($newStatus) . " successfully!");
            
        } catch (\Exception $e) {
            \Log::error('Error updating transaction status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update transaction status. Please try again.');
        }
    }
    
    /**
     * Mark a transaction as paid and upload payment proof
     */
    public function markPaid(Request $request, Transaction $transaction)
    {
        // For subscription transactions, no payment proof is required
        if (strtolower($transaction->source ?? '') === 'subscription') {
            $request->validate([
                'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } else {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        }

        try {
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('storage/payment_proofs');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $filename);
                $filePath = 'storage/payment_proofs/' . $filename;
                $transaction->payment_proof = $filePath;
            }
            
            $transaction->is_paid = true;
            $transaction->save();

            // Update doctor's net_balance only for non-subscription transactions
            if (strtolower($transaction->source ?? '') !== 'subscription' && $transaction->doctor && $transaction->doctor_amount > 0) {
                $doctor = $transaction->doctor;
                $doctor->net_balance = ($doctor->net_balance ?? 0) + $transaction->doctor_amount;
                $doctor->save();
            }

            // Deduct doctor_amount from superadmin's net_balance when admin sends payment to doctor
            // Only for non-subscription transactions
            if (strtolower($transaction->source ?? '') !== 'subscription' && $transaction->doctor_amount > 0) {
                $superadmin = User::where('role', 'superadmin')->first();
                if ($superadmin) {
                    $superadmin->net_balance = ($superadmin->net_balance ?? 0) - $transaction->doctor_amount;
                    $superadmin->save();
                }
            }

            // Send transaction paid email
            try {
                $emailService = app(EmailService::class);
                $emailService->sendTransactionNotification($transaction, 'paid');
            } catch (\Exception $e) {
                Log::error('Failed to send transaction paid email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Transaction marked as paid successfully.');
        } catch (\Exception $e) {
            Log::error('Error marking transaction as paid: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to mark transaction as paid. Please try again.');
        }
    }

    /**
     * Build the filtered query based on request parameters
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Transaction::with(['patient', 'doctor']);

        // Apply search filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('payment_status', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('total_amount', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('doctor', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('payment_status', $request->status);
        }

        // Apply source filter
        if ($request->has('source') && !empty($request->source)) {
            $query->where('source', $request->source);
        }

        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply amount range filter
        if ($request->has('amount_min') && is_numeric($request->amount_min)) {
            $query->where('total_amount', '>=', $request->amount_min);
        }
        if ($request->has('amount_max') && is_numeric($request->amount_max)) {
            $query->where('total_amount', '<=', $request->amount_max);
        }

        // Apply sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        $query->orderBy($sortField, $sortDirection);

        return $query;
    }
}
