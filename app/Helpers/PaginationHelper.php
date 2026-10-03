<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PaginationHelper
{
    public static function applyDynamicFilters(Builder $query, Request $request, array $searchFields = [])
    {
        // Get the search term from the request
        $search = $request->input('search', '');

        // Get the entries per page value from the request (default to 5)
        $perPage = $request->input('per_page', 50);

        // Apply search filter if search term is provided
        if ($search && !empty($searchFields)) {
            $query = $query->where(function($query) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $query->orWhere($field, 'like', '%' . $search . '%');
                }
            });
        }

        foreach ($request->except(['page', 'search', 'per_page']) as $key => $value) {
            if ($value) {
                $query = $query->where($key, $value);
            }
        }

        // Apply pagination to the query
        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
}
