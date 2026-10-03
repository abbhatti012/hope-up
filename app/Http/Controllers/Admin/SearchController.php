<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Transaction;
use App\Models\DoctorReview;
use App\Models\Content;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Perform global search across multiple entities
     */
    public function globalSearch(Request $request)
    {
        $query = trim($request->input('q', ''));
        $type = $request->input('type', 'all'); // all, users, appointments, transactions, reviews, content
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search query must be at least 2 characters long',
                'data' => []
            ]);
        }

        $results = [];

        try {
            switch ($type) {
                case 'users':
                    $results = $this->searchUsers($query);
                    break;
                case 'appointments':
                    $results = $this->searchAppointments($query);
                    break;
                case 'transactions':
                    $results = $this->searchTransactions($query);
                    break;
                case 'reviews':
                    $results = $this->searchReviews($query);
                    break;
                case 'content':
                    $results = $this->searchContent($query);
                    break;
                case 'specialities':
                    $results = $this->searchSpecialities($query);
                    break;
                default:
                    // Search all entities with individual error handling
                    $results = [];
                    
                    try {
                        $results['users'] = $this->searchUsers($query);
                    } catch (\Exception $e) {
                        Log::error('User search error: ' . $e->getMessage());
                        $results['users'] = [];
                    }
                    
                    try {
                        $results['appointments'] = $this->searchAppointments($query);
                    } catch (\Exception $e) {
                        Log::error('Appointment search error: ' . $e->getMessage());
                        $results['appointments'] = [];
                    }
                    
                    try {
                        $results['transactions'] = $this->searchTransactions($query);
                    } catch (\Exception $e) {
                        Log::error('Transaction search error: ' . $e->getMessage());
                        $results['transactions'] = [];
                    }
                    
                    try {
                        $results['reviews'] = $this->searchReviews($query);
                    } catch (\Exception $e) {
                        Log::error('Review search error: ' . $e->getMessage());
                        $results['reviews'] = [];
                    }
                    
                    try {
                        $results['content'] = $this->searchContent($query);
                    } catch (\Exception $e) {
                        Log::error('Content search error: ' . $e->getMessage());
                        $results['content'] = [];
                    }
                    
                    try {
                        $results['specialities'] = $this->searchSpecialities($query);
                    } catch (\Exception $e) {
                        Log::error('Speciality search error: ' . $e->getMessage());
                        $results['specialities'] = [];
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'query' => $query,
                'total_results' => $this->countTotalResults($results)
            ]);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage(), [
                'query' => $query,
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
                'data' => []
            ], 500);
        }
    }

    /**
     * Search users (doctors, patients, admins)
     */
    private function searchUsers($query)
    {
        return User::select('id', 'first_name', 'last_name', 'email', 'role', 'profile_photo', 'created_at')
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'title' => $user->first_name . ' ' . $user->last_name,
                    'subtitle' => $user->email,
                    'type' => 'user',
                    'role' => $user->role,
                    'avatar' => $user->profile_photo,
                    'url' => $this->getUserUrl($user),
                    'created_at' => $user->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Search appointments
     */
    private function searchAppointments($query)
    {
        return Appointment::with(['patient:id,first_name,last_name', 'doctor:id,first_name,last_name'])
            ->where(function($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                  ->orWhere('appointment_date', 'like', "%{$query}%")
                  ->orWhere('appointment_time', 'like', "%{$query}%")
                  ->orWhere('status', 'like', "%{$query}%")
                  ->orWhereHas('patient', function($q) use ($query) {
                      $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  })
                  ->orWhereHas('doctor', function($q) use ($query) {
                      $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => 'Appointment #' . $appointment->id,
                    'subtitle' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name . ' → ' . 
                                 $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name,
                    'type' => 'appointment',
                    'status' => $appointment->status,
                    'date' => $appointment->appointment_date,
                    'time' => $appointment->appointment_time,
                    'url' => route('appointments.edit', $appointment->id),
                    'created_at' => $appointment->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Search transactions
     */
    private function searchTransactions($query)
    {
        return Transaction::with(['patient:id,first_name,last_name', 'doctor:id,first_name,last_name'])
            ->where(function($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                  ->orWhere('id', 'like', "%{$query}%")
                  ->orWhere('payment_status', 'like', "%{$query}%")
                  ->orWhere('payment_method', 'like', "%{$query}%")
                  ->orWhere('source', 'like', "%{$query}%")
                  ->orWhere('total_amount', 'like', "%{$query}%")
                  ->orWhereHas('patient', function($q) use ($query) {
                      $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  })
                  ->orWhereHas('doctor', function($q) use ($query) {
                      $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get()
            ->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'title' => 'Transaction #' . $transaction->id,
                    'subtitle' => $transaction->patient->first_name . ' ' . $transaction->patient->last_name . ' → ' . 
                                 $transaction->doctor->first_name . ' ' . $transaction->doctor->last_name,
                    'type' => 'transaction',
                    'status' => $transaction->payment_status,
                    'amount' => $transaction->total_amount,
                    'method' => $transaction->payment_method,
                    'source' => $transaction->source,
                    'url' => route('transactions.details', $transaction->id),
                    'created_at' => $transaction->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Search reviews
     */
    private function searchReviews($query)
    {
        return DoctorReview::with(['doctor:id,first_name,last_name', 'reviewer:id,first_name,last_name'])
            ->where(function($q) use ($query) {
                $q->where('comments', 'like', "%{$query}%")
                  ->orWhere('rating_type', 'like', "%{$query}%")
                  ->orWhereHas('doctor', function($q) use ($query) {
                      $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  })
                  ->orWhereHas('reviewer', function($q) use ($query) {
                      $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'title' => 'Review by ' . $review->reviewer->first_name . ' ' . $review->reviewer->last_name,
                    'subtitle' => 'For Dr. ' . $review->doctor->first_name . ' ' . $review->doctor->last_name,
                    'type' => 'review',
                    'rating' => $review->rating,
                    'rating_type' => $review->rating_type,
                    'is_approved' => $review->is_approved,
                    'url' => route('reviews.show', $review->id),
                    'created_at' => $review->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Search content
     */
    private function searchContent($query)
    {
        return Content::where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function($content) {
                return [
                    'id' => $content->id,
                    'title' => $content->title,
                    'subtitle' => $content->description ? substr($content->description, 0, 100) . '...' : 'No description',
                    'type' => 'content',
                    'status' => 'Active',
                    'url' => route('content.edit', $content->id),
                    'created_at' => $content->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Search specialities
     */
    private function searchSpecialities($query)
    {
        return Speciality::where('title', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function($speciality) {
                return [
                    'id' => $speciality->id,
                    'title' => $speciality->title,
                    'subtitle' => 'Speciality',
                    'type' => 'speciality',
                    'status' => $speciality->is_active ? 'Active' : 'Inactive',
                    'url' => route('specialities.edit', $speciality->id),
                    'created_at' => $speciality->created_at->format('M d, Y')
                ];
            });
    }

    /**
     * Get user URL based on role
     */
    private function getUserUrl($user)
    {
        switch ($user->role) {
            case 'specialist':
                return route('doctor-detail', $user->id);
            case 'user':
                return route('patients.show', $user->id);
            case 'admin':
            case 'superadmin':
                return route('admin.users.edit', $user->id);
            default:
                return '#';
        }
    }

    /**
     * Count total results from all categories
     */
    private function countTotalResults($results)
    {
        if (is_array($results) && !empty($results) && !isset($results[0])) {
            // Categorized results
            $total = 0;
            foreach ($results as $category => $items) {
                if (is_array($items)) {
                    $total += count($items);
                }
            }
            return $total;
        } else {
            // Single category results
            return is_array($results) ? count($results) : 0;
        }
    }

    /**
     * Get search suggestions for autocomplete
     */
    public function getSuggestions(Request $request)
    {
        $query = trim($request->input('q', ''));
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = [];

        try {
            // User suggestions
            $users = User::select('first_name', 'last_name', 'role')
                ->where(function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($users as $user) {
                $suggestions[] = [
                    'text' => $user->first_name . ' ' . $user->last_name,
                    'category' => ucfirst($user->role),
                    'type' => 'user'
                ];
            }

            // Appointment suggestions
            $appointments = Appointment::with(['patient:id,first_name,last_name'])
                ->whereHas('patient', function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->limit(3)
                ->get();

            foreach ($appointments as $appointment) {
                $suggestions[] = [
                    'text' => 'Appointment #' . $appointment->id . ' - ' . $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                    'category' => 'Appointment',
                    'type' => 'appointment'
                ];
            }

            // Transaction suggestions
            $transactions = Transaction::with(['patient:id,first_name,last_name'])
                ->whereHas('patient', function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->limit(3)
                ->get();

            foreach ($transactions as $transaction) {
                $suggestions[] = [
                    'text' => 'Transaction #' . $transaction->id . ' - ' . $transaction->patient->first_name . ' ' . $transaction->patient->last_name,
                    'category' => 'Transaction',
                    'type' => 'transaction'
                ];
            }

            return response()->json(['suggestions' => $suggestions]);

        } catch (\Exception $e) {
            Log::error('Suggestions error: ' . $e->getMessage());
            return response()->json(['suggestions' => []]);
        }
    }
} 