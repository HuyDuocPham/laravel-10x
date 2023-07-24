<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderSuccessEvent;
use App\Http\Controllers\Controller;
use App\Mail\OrderAdminEmail;
use App\Mail\OrderMail;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        return view('client.pages.cart', compact('cart'));
    }
    public function addProductToCart($productId, $qty = 1)
    {
        $product = Product::find($productId);
        if ($product) {
            $cart = session()->get('cart') ?? [];

            $imageLink = (is_null($product->image_url) || !file_exists("images/" . $product->image_url))
                ? 'default-product-image.png' : $product->image_url;

            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => asset('images/' . $imageLink),
                'qty' => ($cart[$productId]['qty'] ?? 0) + $qty
            ];
            //Add cart into session
            session()->put('cart', $cart);
            $totalProduct = count($cart);
            $totalPrice = $this->calculateTotalPrice($cart);

            return response()->json(['message' => 'Add product success!', 'total_product' => $totalProduct, 'total_price' => $totalPrice]);
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
        return response()->json(['message' => 'Remove product success!', 'total_product' => $totalProduct, 'total_price' => $totalPrice]);
    }

    public function deleteCart()
    {
        session()->put('cart', []);
        return response()->json(['message' => 'Remove product success!', 'total_product' => 0, 'total_price' => 0]);
    }

    public function placeOrder(Request $request)
    {
        //Validate from request

        try {
            DB::beginTransaction();

            // save database
            $cart = session()->get('cart', []);
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['qty'] * $item['price'];
            }
            //--> Create record order
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'address' => $request->address,
                'city' => $request->city,
                'status' => Order::STATUS_PENDING,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'subtotal' => $totalPrice,
                'total' => $totalPrice,
            ]);

            //Create evetn order success


            // Create record order items
            foreach ($cart as $productId => $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'name' => $item['name'],
                ]);
            }


            //Create record into table OrderPaymentMethod
            $orderPaymentMethod = OrderPaymentMethod::create([
                'order_id' => $order->id,
                'payment_provider' => $request->payment_method,
                'total_balance' => $totalPrice,
                'status' => OrderPaymentMethod::STATUS_PENDING,
            ]);


            $user = User::find(Auth::user()->id);
            $user->phone = $request->phone;
            $user->save();


            // Reset session cart
            session()->put('cart', []);

            event(new OrderSuccessEvent($order));


            // Send mail to customer to confirm that order
            // Mail::to('huyduocphamm@gmail.com')->send(new OrderMail($order));

            // Send mail to admin to prepare order
            // Mail::to('huyduocphamm@gmail.com')->send(new OrderAdminEmail($order));

            // Sens sms to customer(https://console.twilio.com/?frameUrl=%2Fconsole%3Fx-target-region%3Dus1)
            // $receiverNumber = '+84366321516';
            // $client = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
            // $client->messages->create($receiverNumber, [
            //     'from' => env('TWILIO_PHONE_NUMBER'),
            //     'body' => 'PHD'
            // ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }



        return redirect()->route('home')->with('msg', 'Order Success!');
    }
}