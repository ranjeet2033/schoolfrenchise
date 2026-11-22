<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\DigitalProduct;
use App\Models\DigitalProductAccess;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalStoreController extends Controller
{
    /**
     * Store Catalog: Browse e-books, PDFs, and private video series.
     */
    public function index(Request $request)
    {
        $type = $request->query('type'); // 'pdf', 'video', or null
        $category = $request->query('category');
        $search = trim((string) $request->query('q', ''));

        $query = DigitalProduct::active()->orderBy('sort_order')->orderByDesc('id');

        if ($type === 'pdf') {
            $query->pdfs();
        } elseif ($type === 'video') {
            $query->videos();
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = DigitalProduct::active()->distinct()->pluck('category')->filter()->values();
        $totalPdfs = DigitalProduct::active()->pdfs()->count();
        $totalVideos = DigitalProduct::active()->videos()->count();

        return view('pages.store.index', compact('products', 'type', 'category', 'search', 'categories', 'totalPdfs', 'totalVideos'));
    }

    /**
     * Product details view.
     */
    public function show($type, $slug)
    {
        $product = DigitalProduct::where('slug', $slug)
            ->where('type', $type)
            ->active()
            ->firstOrFail();

        $relatedProducts = DigitalProduct::active()
            ->where('type', $product->type)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $razorpayKey = PaymentGatewayService::getPublicKey();

        return view('pages.store.show', compact('product', 'relatedProducts', 'razorpayKey'));
    }

    /**
     * Initiate Razorpay checkout order.
     */
    public function initiateCheckout(Request $request)
    {
        $validated = $request->validate([
            'product_id'      => 'required|exists:digital_products,id',
            'customer_name'   => 'required|string|max:191',
            'customer_email'  => 'required|email|max:191',
            'customer_mobile' => 'required|string|size:10',
            'customer_gmail'  => 'nullable|email|max:191',
        ]);

        $product = DigitalProduct::active()->findOrFail($validated['product_id']);

        // For video products, Gmail ID is strictly required for YouTube Private invitation
        if ($product->type === 'video') {
            if (empty($validated['customer_gmail'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid Gmail ID linked to your YouTube account to receive private video access.',
                ], 422);
            }

            // Check if product reached YouTube 50 invites quota
            if ($product->current_invites >= $product->max_invites) {
                return response()->json([
                    'success' => false,
                    'message' => 'This video course has reached maximum invitation capacity (50 seats). Please contact support for upcoming batches.',
                ], 422);
            }
        }

        $amount = $product->effective_price;
        $orderNumber = DigitalOrder::generateOrderNumber();
        $razorpayOrderId = 'order_' . date('Ymd') . '_' . strtolower(Str::random(14));

        $publicKey = PaymentGatewayService::getPublicKey();
        $secret = PaymentGatewayService::getKeySecret();

        // Create order via Razorpay API if live credentials exist
        if (str_starts_with($publicKey, 'rzp_') && !empty($secret) && $secret !== 'TECH_ERA_SECURE_SALT_KEY_2026') {
            try {
                $response = Http::withBasicAuth($publicKey, $secret)
                    ->timeout(10)
                    ->post('https://api.razorpay.com/v1/orders', [
                        'amount'   => (int) round($amount * 100),
                        'currency' => 'INR',
                        'receipt'  => $orderNumber,
                        'notes'    => [
                            'product_id'   => (string) $product->id,
                            'product_type' => $product->type,
                            'customer_name'=> $validated['customer_name'],
                        ],
                    ]);

                if ($response->successful() && !empty($response->json('id'))) {
                    $razorpayOrderId = $response->json('id');
                }
            } catch (\Throwable $e) {
                Log::warning('Razorpay Order API creation warning: ' . $e->getMessage());
            }
        }

        $order = DigitalOrder::create([
            'order_number'       => $orderNumber,
            'user_id'            => auth()->id(),
            'customer_name'      => $validated['customer_name'],
            'customer_email'     => strtolower(trim($validated['customer_email'])),
            'customer_mobile'    => trim($validated['customer_mobile']),
            'customer_gmail'     => !empty($validated['customer_gmail']) ? strtolower(trim($validated['customer_gmail'])) : null,
            'subtotal'           => $product->price,
            'discount'           => max(0, $product->price - $amount),
            'total_amount'       => $amount,
            'currency'           => 'INR',
            'payment_gateway'    => 'razorpay',
            'razorpay_order_id'  => $razorpayOrderId,
            'payment_status'     => 'pending',
        ]);

        DigitalOrderItem::create([
            'digital_order_id'   => $order->id,
            'digital_product_id' => $product->id,
            'product_title'      => $product->title,
            'product_type'       => $product->type,
            'price'              => $amount,
        ]);

        return response()->json([
            'success'            => true,
            'order_id'           => $order->id,
            'order_number'       => $order->order_number,
            'razorpay_order_id'  => $razorpayOrderId,
            'amount_paise'       => (int) round($amount * 100),
            'amount'             => $amount,
            'currency'           => 'INR',
            'key_id'             => $publicKey,
            'product_title'      => $product->title,
            'customer_name'      => $order->customer_name,
            'customer_email'     => $order->customer_email,
            'customer_mobile'    => $order->customer_mobile,
            'is_sandbox'         => PaymentGatewayService::isSandbox(),
        ]);
    }

    /**
     * Verify payment signature and grant immediate product access.
     */
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id'            => 'required|exists:digital_orders,id',
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'nullable|string',
        ]);

        $order = DigitalOrder::with('items.product')->findOrFail($validated['order_id']);

        // Check signature if not in test sandbox
        if (!PaymentGatewayService::isSandbox()) {
            $isValid = PaymentGatewayService::verifySignature(
                $validated['razorpay_order_id'],
                $validated['razorpay_payment_id'],
                $validated['razorpay_signature'] ?? ''
            );

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed. Please contact support.',
                ], 400);
            }
        }

        // Mark order as paid
        $order->update([
            'payment_status'      => 'paid',
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'razorpay_signature'  => $validated['razorpay_signature'] ?? 'sandbox_verified',
            'paid_at'             => now(),
        ]);

        // Grant access records for each purchased item
        foreach ($order->items as $item) {
            $product = $item->product;

            $accessToken = DigitalProductAccess::generateAccessToken();
            $inviteStatus = ($product && $product->type === 'video') ? 'pending_invite' : 'not_applicable';

            DigitalProductAccess::create([
                'digital_order_id'      => $order->id,
                'digital_product_id'    => $item->digital_product_id,
                'customer_email'        => $order->customer_email,
                'customer_gmail'        => $order->customer_gmail,
                'access_token'          => $accessToken,
                'youtube_invite_status' => $inviteStatus,
                'status'                => 'active',
            ]);

            // Increment invites counter if video product
            if ($product && $product->type === 'video') {
                $product->increment('current_invites');
            }
        }

        return response()->json([
            'success'      => true,
            'message'      => 'Payment verified successfully!',
            'redirect_url' => route('store.order.confirmation', $order->order_number),
        ]);
    }

    /**
     * Order confirmation and instant download receipt.
     */
    public function confirmation($orderNumber)
    {
        $order = DigitalOrder::with(['items.product', 'accesses.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('pages.store.confirmation', compact('order'));
    }

    /**
     * Secure PDF download streaming (Does not expose direct URL).
     */
    public function downloadPdf($accessToken)
    {
        $access = DigitalProductAccess::with('product')
            ->where('access_token', $accessToken)
            ->where('status', 'active')
            ->firstOrFail();

        $product = $access->product;

        if (!$product || $product->type !== 'pdf' || empty($product->pdf_file_path)) {
            abort(404, 'The requested PDF file is not available.');
        }

        // Check if file exists in private storage or public storage
        $filePath = $product->pdf_file_path;
        $fullPath = storage_path('app/private/' . ltrim($filePath, '/'));

        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/public/' . ltrim($filePath, '/'));
        }

        if (!file_exists($fullPath)) {
            abort(404, 'PDF file not found on server. Please contact support.');
        }

        // Increment download counter
        $access->increment('pdf_download_count');
        $access->update(['last_downloaded_at' => now()]);

        $fileName = Str::slug($product->title) . '.pdf';

        return response()->download($fullPath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Customer Purchase History & PDF Re-download Portal.
     */
    public function myPurchases(Request $request)
    {
        $query = trim((string) $request->input('email', ''));
        $orders = collect();

        if (!empty($query)) {
            $orders = DigitalOrder::with(['items.product', 'accesses.product'])
                ->where('customer_email', $query)
                ->orWhere('customer_mobile', $query)
                ->orWhere('customer_gmail', $query)
                ->where('payment_status', 'paid')
                ->latest()
                ->get();

            if ($orders->isEmpty()) {
                session()->flash('not_found', "No paid orders found matching '{$query}'. Please check your email or mobile number.");
            }
        }

        return view('pages.store.my_purchases', compact('orders', 'query'));
    }
}
