<?php

namespace App\Http\Controllers;

use App\Mail\OfflineOrderMail;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Razorpay\Api;
use App\Models\LiveClasses;
use App\Models\EmailNotification;
use App\Models\Auth\User;
use View;


class CartController extends Controller
{

    private $path;
    private $currency;

    public function __construct()
    {

        $cat_menu = Category::where('status', '=', 1)->where('parent_id', '=', '0')->get();
        $sub = Category::where('parent_id', '<>', '0')->get();
        View::share('cat_menu' , $cat_menu);
        View::share('sub' , $sub);
        
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        $path = 'frontend';
        if (session()->has('display_type')) {
            if (session('display_type') == 'rtl') {
                $path = 'frontend-rtl';
            } else {
                $path = 'frontend';
            }
        } else if (config('app.display_type') == 'rtl') {
            $path = 'frontend-rtl';
        }
        $this->path = $path;
        $this->currency = getCurrency(config('app.currency'));

    }

    public function index(Request $request)
    {
        // print_r(Session::all());die;

     $total = 0;
        $f = Cart::session(auth()->user()->id)->getContent()->toArray();
        foreach ($f as $value ) {
            $total += $value['attributes']['price'];
        }






        $ids = Cart::session(auth()->user()->id)->getContent()->keys();

        $course_ids = [];
        $bundle_ids = [];
        $live_class_id= [];
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            }
            elseif ($item->attributes->type == 'live_class') {
                 $live_class_id[] = $item->id;
             } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $live_classes = LiveClasses::find($live_class_id);
        $courses = $bundles->merge($courses)->merge($live_classes);
        return view($this->path . '.cart.checkout', compact('courses', 'bundles', 'total'));
    }

    public function addToCart(Request $request)
    {

// echo "<pre>"; print_r($request->all());die;
        $product = "";
        $teachers = "";
        $type = "";
        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {
            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $product->price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers
                       
                    ]);
        }
        Session::flash('success', trans('labels.frontend.cart.product_added'));
        return back();
    }

    public function checkout(Request $request)
    {   
//      // $request->session()->flush();die;
        // $data = $request->session()->all();
// echo "<pre>"; print_r($data);die;

     $total = 0;
        $f = Cart::session(auth()->user()->id)->getContent()->toArray();
        foreach ($f as $value ) {
            $total += $value['attributes']['price'];
        }
       


// echo "<pre>"; print_r($total);die;


        
        $price = "";
        $product = "";
        $teachers = "";
        $type = "";
        $bundle_ids = [];
        $course_ids = [];
        $live_class_id= [];
        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }
        else {
            $product = LiveClasses::findOrFail($request->get('liveclass_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'live_class';
             
        }
        $check = $request->sub_plan;
        if($check == 1){
            $price = $product->sub_price_1;
            $duration = $product->sub_month_1;
        }elseif ($check == 2) {
            $price = $product->sub_price_2;
            $duration = $product->sub_month_2;
        } elseif ($check == 3) {
            $price = $product->sub_price_3;
            $duration = $product->sub_month_3;
        }
        elseif($check == 4){
            $price = 0.00;
            $duration = 0;            
        }
        if($request->amount == 'free'){
            $free = 1;
        }else{
            $free = 0;
        }
        // print_r('<pre>');
        // print_r($free);die;


        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {

            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers,
                        'price' => $price,
                        'duration' => $duration,
                        'free' => $free

                    ]);
        }
                if (in_array($product->id, $cart_items)) {

            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers,
                        'price' => $price,
                        'duration' => $duration,
                        'free' => $free

                    ]);
        }
                // $request->session()->put('new', 'demo');
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            }
            elseif ($item->attributes->type == 'live_class') {
                $live_class_id[] = $item->id;
                 
             } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $live_classes = LiveClasses::find($live_class_id);
        $courses = $bundles->merge($courses)->merge($live_classes);
        // $courses = $bundles-;

        $check = $request->sub_plan;

        


       // echo"<pre>"; print_r($courses->category);die;

        return view($this->path . '.cart.checkout', compact('courses', 'total', 'check'));
    }

    public function clear(Request $request)
    {
        Cart::session(auth()->user()->id)->clear();
        return back();
    }

    public function remove(Request $request)
    {

        Cart::session(auth()->user()->id)->remove($request->course);
        return redirect(route('cart.index'));
    }

    public function stripePayment(Request $request)
    {   
        //Making Order
        $order = $this->makeOrder();


        //Charging Customer
        $status = $this->createStripeCharge($request);

        // echo "<pre>";

        $duration =  decrypt($request->sub_dur);
        if ($duration) {
           $duration = $duration;
            // print_r($duration);die('d');
        }
        else{
           $duration = '6';
        }
        


        if ($status == 'success') {
            $order->status = 1;
            $order->payment_type = 1;
            $order->sub_duration= $duration;
            $order->save();
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if($orderItem->item_type == Bundle::class){
                    foreach ($orderItem->item->courses as $course){
                        $course->students()->attach($order->user_id);
                    }
                }
                $orderItem->item->students()->attach($order->user_id);
            }

            //Generating Invoice
            generateInvoice($order);

            Cart::session(auth()->user()->id)->clear();
            return redirect()->route('status');

        } else {
            $order->status = 2;
            $order->save();
            return redirect()->route('cart.index');
        }
    }

    public function razorpayPayment(Request $request)
    {   
       
        //Making Order
        $order = $this->makeOrder();

  

        if ($request->razorpay_payment_id) {
            $order->status = 1;
            $order->payment_type = 4;
            // $order->sub_duration= $duration;
            $order->razorpay_payment_id = $request->razorpay_payment_id;
            $order->save();
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if($orderItem->item_type == Bundle::class){
                    foreach ($orderItem->item->courses as $course){
                        $course->students()->attach($order->user_id);
                    }
                }
                $orderItem->item->students()->attach($order->user_id);
            }

            //Generating Invoice
            generateInvoice($order);
 

           
            Cart::session(auth()->user()->id)->clear();
            
             \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return redirect()->route('status');

        } else {
            $order->status = 2;
            $order->save();
            return redirect()->route('cart.index');
        }
    }


    public function freePayment(Request $request)
    {   
        //Making Order
        $order = $this->freeorder();

// echo "<pre>"; print_r($order);die;


       
            $order->status = 1;
            $order->payment_type = 5;
            // $order->sub_duration= $duration;
            $order->razorpay_payment_id = $request->razorpay_payment_id;
            $order->save();
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if($orderItem->item_type == Bundle::class){
                    foreach ($orderItem->item->courses as $course){
                        $course->students()->attach($order->user_id);
                    }
                }
                $orderItem->item->students()->attach($order->user_id);
            }

            //Generating Invoice
            generateInvoice($order);
 

           
            Cart::session(auth()->user()->id)->clear();
            
             \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return redirect()->route('status');

     
    }





    public function paypalPayment(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $items = [];

        $cartItems = Cart::session(auth()->user()->id)->getContent();
        $cartTotal = Cart::session(auth()->user()->id)->getTotal();
        $currency = $this->currency['short_code'];

        foreach ($cartItems as $cartItem) {

            $item_1 = new Item();
            $item_1->setName($cartItem->name)/** item name **/
            ->setCurrency($currency)
                ->setQuantity(1)
                ->setPrice($cartItem->price);
            /** unit price **/
            $items[] = $item_1;
        }

        $item_list = new ItemList();
        $item_list->setItems($items);

        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($cartTotal);


        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription(auth()->user()->name);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('cart.paypal.status'))/** Specify return URL **/
        ->setCancelUrl(URL::route('cart.paypal.status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('failure', trans('labels.frontend.cart.connection_timeout'));
                return Redirect::route('cart.paypal.status');
            } else {
                \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
                return Redirect::route('cart.paypal.status');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('failure',trans('labels.frontend.cart.unknown_error'));
        return Redirect::route('cart.paypal.status');
    }

    public function offlinePayment(Request $request)
    {
        //Making Order
        $order = $this->makeOrder();
        $order->payment_type = 3;
        $order->status = 0;
        $order->save();
        $content = [];
        $items = [];
        $counter = 0;
        foreach (Cart::session(auth()->user()->id)->getContent() as $key => $cartItem) {
            $counter++;
            array_push($items, ['number' => $counter, 'name' => $cartItem->name, 'price' => $cartItem->price]);
        }

        $content['items'] = $items;
        $content['total'] = Cart::session(auth()->user()->id)->getTotal();
        $content['reference_no'] = $order->reference_no;


        // try {
            \Mail::to(auth()->user()->email)->send(new OfflineOrderMail($content));
        // } catch (\Exception $e) {
            // \Log::info($e->getMessage() . ' for order ' . $order->id);
        // }

        Cart::session(auth()->user()->id)->clear();
        \Session::flash('success', trans('labels.frontend.cart.offline_request'));
        return redirect()->route('courses.all');
    }

    public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            \Session::put('failure', trans('labels.frontend.cart.payment_failed'));
            return Redirect::route('cart');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $order = $this->makeOrder();
        $order->payment_type = 2;
        $order->save();
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            Cart::session(auth()->user()->id)->clear();
            \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            $order->status = 1;
            $order->save();
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if($orderItem->item_type == Bundle::class){
                    foreach ($orderItem->item->courses as $course){
                        $course->students()->attach($order->user_id);
                    }
                }
                $orderItem->item->students()->attach($order->user_id);
            }

            //Generating Invoice
            generateInvoice($order);

            return Redirect::route('status');
        } else {
            \Session::flash('failure', trans('labels.frontend.cart.payment_failed'));
            $order->status = 2;
            $order->save();
            return Redirect::route('cart');
        }


    }

    private function makeOrder()
    {

         $total = 0;
        $f = Cart::session(auth()->user()->id)->getContent()->toArray();
        foreach ($f as $value ) {
            $total += $value['attributes']['price'];
        }
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = $total;
        $order->status = 1;
        $order->payment_type = 3;
        $order->save();
        //Getting and Adding items
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            }
            elseif ($cartItem->attributes->type == 'live_class') {
                 $type = LiveClasses::class;


             } else {
                $type = Course::class;
            }
            $order->items()->create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'price' => $cartItem->attributes->price,
                'subscribe' => '1',
                'duration' => $cartItem->attributes->duration
            ]);

            if ($cartItem->attributes->type == 'bundle') {
            $teacher_id = Bundle::findOrFail($cartItem->id);     
            }
            elseif ($cartItem->attributes->type == 'live_class') {
            $teacher_id = LiveClasses::findOrFail($cartItem->id);     

             } else {
            $teacher_id = Course::findOrFail($cartItem->id);     

            }

   

            EmailNotification::create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'users_id' => auth()->user()->id,
                'subscribe' => '1',
                'duration' => $cartItem->attributes->duration,
                'teacher_id' => $teacher_id->teachers[0]['id'],
            ]);
        }

        return $order;
    }



        private function freeorder()
    {

             $total = 0;
        $f = Cart::session(auth()->user()->id)->getContent()->toArray();
        foreach ($f as $value ) {
            $total += $value['attributes']['price'];
        }
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = $total;
        $order->status = 1;
        $order->payment_type = 5;
        $order->save();
        //Getting and Adding items
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            }
            elseif ($cartItem->attributes->type == 'live_class') {
                 $type = LiveClasses::class;


             } else {
                $type = Course::class;
            }
            $order->items()->create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'price' => $cartItem->attributes->price,
                'subscribe' => '1',
                'duration' => $cartItem->attributes->duration
            ]);

            $teacher_id = Course::findOrFail($cartItem->id);     

            EmailNotification::create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'users_id' => auth()->user()->id,
                'subscribe' => '1',
                'duration' => $cartItem->attributes->duration,
                'teacher_id' => $teacher_id->teachers[0]['id'],
            ]);
        }

        return $order;
    }

    private function createStripeCharge($request)
    {
        
        $status = "";
        Stripe::setApiKey(config('services.stripe.secret'));
        $amount = Cart::session(auth()->user()->id)->getTotal();
        $currency = $this->currency['short_code'];
        try {
            Charge::create(array(
                "amount" => $amount * 100,
                "currency" => strtolower($currency),
                "source" => $request->reservation['stripe_token'], // obtained with Stripe.js
                "description" => auth()->user()->name
            ));
            $status = "success";
            Session::flash('success', trans('labels.frontend.cart.payment_done'));
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' for id = ' . auth()->user()->id);
            Session::flash('failure', trans('labels.frontend.cart.try_again'));
            $status = "failure";
        }
        return $status;
    }



}
