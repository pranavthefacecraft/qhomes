<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ListingController extends Controller
{
    /**
     * Display property listings dashboard
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Property::query();
        
        // Filter by user role
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        }
        
        // Filter by status if specified
        $statusFilter = $request->get('status', 'all');
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        // Filter by featured status
        $featuredFilter = $request->get('featured', 'all');
        if ($featuredFilter === 'featured') {
            $query->where('is_featured', true);
        } elseif ($featuredFilter === 'regular') {
            $query->where('is_featured', false);
        }
        
        // Search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }
        
        // Order by newest first
        $query->orderBy('created_at', 'desc');
        
        $properties = $query->paginate(20);
        
        // Get statistics
        $stats = $this->getListingStats($user);
        
        return view('listings.index', compact('properties', 'stats', 'statusFilter', 'featuredFilter', 'search'));
    }
    
    /**
     * Get listing statistics
     */
    private function getListingStats($user)
    {
        $query = Property::query();
        
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        }
        
        return [
            'total' => $query->count(),
            'active' => $query->whereIn('status', ['for_sale', 'for_rent'])->count(),
            'sold_rented' => $query->whereIn('status', ['sold', 'rented'])->count(),
            'draft' => $query->where('status', 'draft')->count(),
            'featured' => $query->where('is_featured', true)->count(),
            'inactive' => $query->where('is_active', false)->count(),
        ];
    }
    
    /**
     * Toggle featured status
     */
    public function toggleFeatured(Property $property)
    {
        $user = Auth::user();
        
        // Check if user can modify this property
        if ($user->role === 'agent' && $property->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You can only modify your own properties.');
        }
        
        $property->update(['is_featured' => !$property->is_featured]);
        
        $status = $property->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()->with('success', "Property has been {$status} successfully.");
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive(Property $property)
    {
        $user = Auth::user();
        
        // Check if user can modify this property
        if ($user->role === 'agent' && $property->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You can only modify your own properties.');
        }
        
        $property->update(['is_active' => !$property->is_active]);
        
        $status = $property->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Property has been {$status} successfully.");
    }
    
    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'properties' => 'required|array|min:1',
            'properties.*' => 'exists:properties,id'
        ]);
        
        $user = Auth::user();
        $query = Property::whereIn('id', $request->properties);
        
        // Filter by user if agent
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        }
        
        $properties = $query->get();
        
        if ($properties->isEmpty()) {
            return redirect()->back()->with('error', 'No properties found or you do not have permission to modify these properties.');
        }
        
        switch ($request->action) {
            case 'activate':
                $properties->each(function($property) {
                    $property->update(['is_active' => true]);
                });
                $message = 'Properties activated successfully.';
                break;
            case 'deactivate':
                $properties->each(function($property) {
                    $property->update(['is_active' => false]);
                });
                $message = 'Properties deactivated successfully.';
                break;
            case 'feature':
                $properties->each(function($property) {
                    $property->update(['is_featured' => true]);
                });
                $message = 'Properties featured successfully.';
                break;
            case 'unfeature':
                $properties->each(function($property) {
                    $property->update(['is_featured' => false]);
                });
                $message = 'Properties unfeatured successfully.';
                break;
            case 'delete':
                $properties->each(function($property) {
                    $property->delete();
                });
                $message = 'Properties deleted successfully.';
                break;
        }
        
        return redirect()->back()->with('success', $message);
    }
}
