<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderSuccessEvent;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ProductRepository;
use App\Http\Services\VnpayService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    //DI: Dependency Injection
    private $vnpayService;

    private $productRepository;
    public function __construct(VnpayService $vnpayService, ProductRepository $productRepository)
    {
        $this->vnpayService = $vnpayService;
        $this->productRepository = $productRepository;
    }


    public function index()
    {
        $cart = session()->get('cart') ?? [];

        // $products = Product::latest()->take($5)->get();
        $products = $this->productRepository->getTopProducts(5);
        return view('client.pages.cart', compact('cart', 'products'));
    }
    public function addProductToCart($productId, $qty = 1)
    {
        $product = Product::find($productId);
        if ($product) {
            $cart = session()->get('cart') ?? [];

            $imageLink = (is_null($product->image_url) || !file_exists("images/" . $product->image_url))
                ? 'default-product-image.png' : $product->image_url;

            $cart[$product->id]  = [
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => asset('images/' . $imageLink),
                'qty' => ($cart[$productId]['qty'] ?? 0) + $qty
            ];
            //Add cart into session
            session()->put('cart', $cart);
            $totalProduct = count($cart);
            $totalPrice = $this->calculateTotalPrice($cart);

            return response()->json(['message' => 'Add product success!', 'total_product' => $totalProduct, 'total_price' =>  $totalPrice]);
        } else {
            return response()->json(['message' => 'Add product failed!'], Response::HTTP_NOT_FOUND);
        }
    }

    public function calculateTotalPrice(array $cart)
    {
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['qty'] * $item['price'];
        }
        return number_format($totalPrice, 2);
    }

    public function deleteProductInCart($productId)
    {
        $cart = session()->get('cart') ?? [];
        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        } else {
            return response()->json(['message' => 'Remove product failed!'], Response::HTTP_BAD_REQUEST);
        }
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);
        return response()->json(['message' => 'Remove product success!', 'total_product' => $totalProduct, 'total_price' =>  $totalPrice]);
    }

    public function updateProductInCart($productId, $qty)
    {
        $cart = session()->get('cart') ?? [];
        if (array_key_exists($productId, $cart)) {
            $cart[$productId]['qty'] = $qty;
            if (!$qty) {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);
        return response()->json(['message' => 'Update product success!', 'total_product' => $totalProduct, 'total_price' =>  $totalPrice]);
    }

    public function deleteCart()
    {
        session()->put('cart', []);
        return response()->json(['message' => 'Delete cart success!', 'total_product' => 0, 'total_price' => 0]);
    }

    public function placeOrder(Request $request)
    {
        //Validate from request
        try {
            DB::beginTransaction();

            //Calculate total price in cart
            $cart = session()->get('cart', []);
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['qty'] * $item['price'];
            }

            //Create record order
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'city' => $request->city,
                'note' => $request->note,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $totalPrice,
                'total' => $totalPrice,
            ]);

            //Create record order items
            foreach ($cart as $productId => $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'name' => $item['name'],
                ]);
            }

            //Create records into table OrderPaymentMethod
            $orderPaymentMethod = OrderPaymentMethod::create([
                'order_id' =>  $order->id,
                'payment_provider' => $request->get('payment_method'),
                'total_balance' => $totalPrice,
                'status' => OrderPaymentMethod::STATUS_PENDING,
            ]);

            $user = User::find(Auth::user()->id);
            $user->phone = $request->phone;
            $user->save();

            //Reset session
            session()->put('cart', []);

            if (in_array($request->payment_method, ['vnpay_atm', 'vnpay_credit'])) {
                $vnp_Url = $this->vnpayService->getVnpayUrl($order, $request->payment_method);
                return Redirect::to($vnp_Url);
            } else {
                event(new OrderSuccessEvent($order));
            }

            DB::commit();



            return redirect()->route('home')->with('message', 'Order Success!');

        } catch (\Exception $message) {
            DB::rollBack();
        }

    }

    public function callBackVnpay(Request $request)
    {
        $order = Order::find($request->vnp_TxnRef);
        if ($request->vnp_ResponseCode === '00') {
            //Create event order success
            if ($order) {
                event(new OrderSuccessEvent($order));
            }
        } else if ($request->vnp_ResponseCode === '10') {
            if ($order) {
                $order->status = 'cancel';
                $orderPaymentMethod = $order->order_payment_methods[0];
                $orderPaymentMethod->status = 'cancel';
                // $orderPaymentMethod->note = 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần';
            }
        }
    }
}
