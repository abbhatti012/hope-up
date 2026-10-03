<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\PatientDetail;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\NotificationStatus;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display subscription statistics
     */
    public function index()
    {
        $stats = $this->subscriptionService->getSubscriptionStats();
        $planDetails = $this->subscriptionService->getPlanDetails();
        
        $patients = PatientDetail::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.subscriptions.index', compact('stats', 'planDetails', 'patients'));
    }

    /**
     * Show subscription details for a patient
     */
    public function show($id)
    {
        $patientDetail = PatientDetail::with('user')->findOrFail($id);
        
        return view('admin.subscriptions.show', compact('patientDetail'));
    }

    /**
     * Upgrade patient subscription
     */
    public function upgrade(Request $request, $id)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $patientDetail = PatientDetail::with('user')->findOrFail($id);
        
        try {
            $this->subscriptionService->upgradeSubscription(
                $patientDetail->user,
                $request->plan,
                $request->amount,
                $request->payment_method,
                $request->transaction_id
            );

            return redirect()->back()->with([
                'notification' => 'Subscription upgraded successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Failed to upgrade subscription: ' . $e->getMessage(),
                'status' => NotificationStatus::ERROR->value
            ]);
        }
    }

    /**
     * Cancel patient subscription
     */
    public function cancel($id)
    {
        $patientDetail = PatientDetail::with('user')->findOrFail($id);
        
        try {
            $this->subscriptionService->cancelSubscription($patientDetail->user);

            return redirect()->back()->with([
                'notification' => 'Subscription cancelled successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Failed to cancel subscription: ' . $e->getMessage(),
                'status' => NotificationStatus::ERROR->value
            ]);
        }
    }

    /**
     * Reset monthly appointment counts
     */
    public function resetMonthlyCounts()
    {
        try {
            $count = $this->subscriptionService->resetMonthlyAppointmentCounts();
            
            return redirect()->back()->with([
                'notification' => "Monthly appointment counts reset for {$count} patients.",
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Failed to reset monthly counts: ' . $e->getMessage(),
                'status' => NotificationStatus::ERROR->value
            ]);
        }
    }

    /**
     * Check expired subscriptions
     */
    public function checkExpired()
    {
        try {
            $count = $this->subscriptionService->checkExpiredSubscriptions();
            
            return redirect()->back()->with([
                'notification' => "{$count} expired subscriptions updated.",
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Failed to check expired subscriptions: ' . $e->getMessage(),
                'status' => NotificationStatus::ERROR->value
            ]);
        }
    }
}
