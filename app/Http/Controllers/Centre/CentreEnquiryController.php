<?php

namespace App\Http\Controllers\Centre;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\Course;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentreEnquiryController extends Controller
{
    protected function getAuthCentre(): Centre
    {
        $user = Auth::user();
        $centre = $user->centre;

        if (!$centre && $user->isSuperAdmin()) {
            $centre = Centre::where('status', 'active')->first();
        }

        if (!$centre) {
            abort(403, 'No franchise study centre is linked to your user account.');
        }

        return $centre;
    }

    /**
     * Display a listing of enquiries for this franchise centre.
     */
    public function index(Request $request)
    {
        $centre = $this->getAuthCentre();

        $query = Enquiry::where('centre_id', $centre->id)->with('course');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('mobile', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('message', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $stats = [
            'total'     => Enquiry::where('centre_id', $centre->id)->count(),
            'new'       => Enquiry::where('centre_id', $centre->id)->where('status', 'new')->count(),
            'contacted' => Enquiry::where('centre_id', $centre->id)->where('status', 'contacted')->count(),
            'converted' => Enquiry::where('centre_id', $centre->id)->where('status', 'converted')->count(),
        ];

        $enquiries = $query->latest('id')->paginate(15)->withQueryString();
        $courses = Course::where('status', true)->orderBy('course_name')->get();

        return view('centre.enquiries.index', compact('centre', 'enquiries', 'courses', 'stats'));
    }

    /**
     * Update the status of an enquiry.
     */
    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $centre = $this->getAuthCentre();

        if ($enquiry->centre_id !== $centre->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,converted,closed',
        ]);

        $enquiry->update(['status' => $validated['status']]);

        return back()->with('success', 'Enquiry status updated to ' . ucfirst($validated['status']) . ' successfully.');
    }

    /**
     * Remove an enquiry.
     */
    public function destroy(Enquiry $enquiry)
    {
        $centre = $this->getAuthCentre();

        if ($enquiry->centre_id !== $centre->id) {
            abort(403, 'Unauthorized action.');
        }

        $enquiry->delete();

        return back()->with('success', 'Enquiry removed successfully.');
    }
}
