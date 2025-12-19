<?php

namespace App\Http\Controllers;

use App\Jobs\NotificationJob;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'comment' => 'nullable|string|max:500',
            'cart_data' => 'required|array',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create the order
            $order = Order::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'address' => $request->address,
                'comment' => $request->comment,
                'cart_data' => $request->cart_data,
                'total_amount' => $request->total_amount,
                'status' => 'pending'
            ]);

            // Format notification message
            $message = $this->formatOrderNotification($order);

            // Dispatch notification job to queue
            NotificationJob::dispatch($message);

            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно оформлен!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при оформлении заказа. Попробуйте еще раз.'
            ], 500);
        }
    }

    // Admin methods
    public function index()
    {
        return view('admin.orders.index');
    }

    public function data(Request $request)
    {
        $orders = Order::select(['id', 'name', 'surname', 'phone', 'total_amount', 'status', 'created_at']);

        return DataTables::of($orders)
            ->addColumn('customer', function ($order) {
                return $order->name . ' ' . $order->surname;
            })
            ->addColumn('formatted_amount', function ($order) {
                return number_format($order->total_amount, 2) . ' СОМ';
            })
            ->addColumn('status_badge', function ($order) {
                $badgeClass = match($order->status) {
                    'pending' => 'warning',
                    'processing' => 'info',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    default => 'secondary'
                };
                $statusText = match($order->status) {
                    'pending' => 'В ожидании',
                    'processing' => 'В обработке',
                    'completed' => 'Завершен',
                    'cancelled' => 'Отменен',
                    default => ucfirst($order->status)
                };
                return '<span class="badge bg-' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->addColumn('created_date', function ($order) {
                return $order->created_at->format('d.m.Y H:i');
            })
            ->addColumn('actions', function ($order) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Действия
                        </button>
                        <a href="'.route('admin.orders.show', $order->id).'" class="view btn btn-info btn-sm me-1">View</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="' . route('admin.orders.show', $order->id) . '">
                                <i class="fas fa-eye"></i> Просмотр
                            </a></li>
                            <li><a class="dropdown-item status-change" href="#" data-id="' . $order->id . '" data-status="' . $order->status . '">
                                <i class="fas fa-edit"></i> Изменить статус
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger delete-order" href="#" data-id="' . $order->id . '">
                                <i class="fas fa-trash"></i> Удалить
                            </a></li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['status_badge', 'actions'])
            ->make(true);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        // Extract product IDs from cart_data and load all products in one query
        $productIds = collect($order->cart_data)->pluck('id')->unique()->toArray();
        $products = \App\Models\Product::whereIn('id', $productIds)
            ->with('primaryImage')
            ->get()
            ->keyBy('id');

        // Add products to order for easy access in view
        $order->products = $products;

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Статус заказа успешно обновлен!'
        ]);
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно удален!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении заказа.'
            ], 500);
        }
    }

    /**
     * Format order notification message
     *
     * @param Order $order
     * @return string
     */
    private function formatOrderNotification(Order $order): string
    {
        $adminOrderUrl = route('admin.orders.show', $order->id);
        $formattedAmount = number_format($order->total_amount, 2) . ' СОМ';
        $totalItems = collect($order->cart_data)->sum('quantity');

        $productIds = collect($order->cart_data)->pluck('id')->toArray();
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = collect($order->cart_data)->map(function ($item) use ($products) {
            $product = $products->get($item['id']);
            if ($product) {
                $itemTotal = $product->price * $item['quantity'];
                return "• {$product->name} × {$item['quantity']} = " . number_format($itemTotal, 2) . ' СОМ';
            }
            return "• Товар ID#{$item['id']} × {$item['quantity']}";
        })->join("\n");

        $message = "🛒 <b>Новый заказ #{$order->id}</b>\n\n";
        $message .= "👤 <b>Клиент:</b> {$order->name} {$order->surname}\n";
        $message .= "📞 <b>Телефон:</b> {$order->phone}\n";
        $message .= "📍 <b>Адрес:</b> {$order->address}\n";

        if ($order->comment) {
            $message .= "💬 <b>Комментарий:</b> {$order->comment}\n";
        }

        $message .= "\n📦 <b>Товары ({$totalItems} шт.):</b>\n{$cartItems}\n\n";
        $message .= "💰 <b>Общая сумма:</b> {$formattedAmount}\n";
        $message .= "📅 <b>Дата заказа:</b> " . $order->created_at->format('d.m.Y H:i') . "\n\n";
        $message .= "🔗 <a href=\"{$adminOrderUrl}\">Открыть заказ в админке</a>";

        return $message;
    }

}
