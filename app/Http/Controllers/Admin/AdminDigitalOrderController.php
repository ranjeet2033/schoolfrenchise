<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalOrder;
use App\Models\DigitalProduct;
use App\Models\DigitalProductAccess;
use Illuminate\Http\Request;

class AdminDigitalOrderController extends Controller
{
    /**
     * Orders & Payments List.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        $query = DigitalOrder::with(['items.product'])->latest('id');

        if (!empty($status)) {
            $query->where('payment_status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('customer_gmail', 'like', "%{$search}%")
                  ->orWhere('razorpay_payment_id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $totalRevenue = DigitalOrder::where('payment_status', 'paid')->sum('total_amount');
        $totalPaidOrders = DigitalOrder::where('payment_status', 'paid')->count();
        $pendingInvites = DigitalProductAccess::where('youtube_invite_status', 'pending_invite')->count();

        return view('admin.store.orders.index', compact('orders', 'status', 'search', 'totalRevenue', 'totalPaidOrders', 'pendingInvites'));
    }

    /**
     * Show single order details.
     */
    public function show(DigitalOrder $order)
    {
        $order->load(['items.product', 'accesses.product']);
        return view('admin.store.orders.show', compact('order'));
    }

    /**
     * YouTube Private Video Invitations Management Hub.
     */
    public function youtubeInvites(Request $request)
    {
        $videoId = $request->query('video_id');
        $status = $request->query('status', 'pending_invite');

        $query = DigitalProductAccess::with(['product', 'order'])
            ->whereHas('product', function ($q) {
                $q->where('type', 'video');
            });

        if (!empty($videoId)) {
            $query->where('digital_product_id', $videoId);
        }

        if ($status !== 'all') {
            $query->where('youtube_invite_status', $status);
        }

        $accesses = $query->latest('id')->paginate(30)->withQueryString();

        // Get comma-separated list of pending Gmails for one-click copy to YouTube Studio
        $pendingGmailsQuery = DigitalProductAccess::where('youtube_invite_status', 'pending_invite')
            ->whereNotNull('customer_gmail');

        if (!empty($videoId)) {
            $pendingGmailsQuery->where('digital_product_id', $videoId);
        }

        $pendingGmails = $pendingGmailsQuery->pluck('customer_gmail')->unique()->take(50)->implode(', ');

        $videoProducts = DigitalProduct::videos()->active()->get();
        $pendingCount = DigitalProductAccess::where('youtube_invite_status', 'pending_invite')->count();
        $invitedCount = DigitalProductAccess::where('youtube_invite_status', 'invited')->count();

        return view('admin.store.youtube_invites', compact('accesses', 'videoProducts', 'videoId', 'status', 'pendingGmails', 'pendingCount', 'invitedCount'));
    }

    /**
     * Mark an access record as invited on YouTube.
     */
    public function markInvited(Request $request, DigitalProductAccess $access)
    {
        $access->update([
            'youtube_invite_status' => 'invited',
            'youtube_invited_at'    => now(),
            'admin_notes'           => $request->input('notes', 'YouTube Private invite sent manually by Admin.'),
        ]);

        return back()->with('success', "YouTube access marked as Invited for {$access->customer_gmail}!");
    }

    /**
     * Batch mark all pending for a specific video as invited.
     */
    public function batchMarkInvited(Request $request)
    {
        $videoId = $request->input('digital_product_id');

        $query = DigitalProductAccess::where('youtube_invite_status', 'pending_invite');
        if (!empty($videoId)) {
            $query->where('digital_product_id', $videoId);
        }

        $count = $query->update([
            'youtube_invite_status' => 'invited',
            'youtube_invited_at'    => now(),
            'admin_notes'           => 'Batch marked as invited by Admin.',
        ]);

        return back()->with('success', "Successfully marked {$count} invitations as Invited!");
    }

    /**
     * Revoke access.
     */
    public function revokeAccess(Request $request, DigitalProductAccess $access)
    {
        $access->update([
            'youtube_invite_status' => 'revoked',
            'status'                => 'revoked',
            'admin_notes'           => $request->input('notes', 'Access revoked by Admin.'),
        ]);

        if ($access->product && $access->product->type === 'video') {
            $access->product->decrement('current_invites');
        }

        return back()->with('success', "Access revoked for {$access->customer_email}.");
    }
}
