<?php

namespace App\Http\Controllers;

use App\Mail\OfflineOrderMail;
use App\Mail\GiftMail;
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
use Auth;


class GiftController extends Controller
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

    public function checkemail(Request $request)

    {   


            $user = User::where('email', '=', $request->email)->pluck('id')->first();

       if($user){
        $request->session()->put('notuser', $user);
        $request->session()->put('newuser', $user);
        $request->session()->put('email', $request->email);
       return $this->checkout($request);
       }else{
       $request->session()->put('notuser', 'notuser');
       $request->session()->put('newactivation', 'newactivation');
        $request->session()->remove('newuser');
        $request->session()->put('email', $request->email);
       return $this->checkout($request);

       }

    }


    public function checkout(Request $request)
    {   
//      // $request->session()->flush();die;
        // $data = $request->session()->all();
// echo "<pre>"; print_r($request->all());die;
// echo "<pre>"; print_r(session()->all());die;


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
        // print_r('<pre>');
        // print_r($price);die;


        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
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
                        'duration' => $duration

                    ]);
        }


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
                        'duration' => $duration

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

        


       // echo"<pre>"; print_r($request->session()->all());die;

        return view($this->path . '.gift.index', compact('courses', 'total', 'check'));
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







    public function razorpayPayment(Request $request)
    {   
        //Making Order
        // die("jgjhg");

if(session()->get('notuser') == 'notuser'){
        


        // $newuser = User::create($request->all());
        $newuser = new User;

        $newuser->email = session()->get('email');
        $newuser->confirmed = 1;
        $newuser->active = 1;
        $newuser->password = 'samitya123' ;
        $newuser->save();

        $newuser->assignRole('student');

        $request->session()->remove('notuser');

        $request->session()->put('newuser' , $newuser->id);

    }

        // echo "<pre>"; print_r($request->all()); die("gfg");
        $order = $this->makeOrder();

        // $duration =  decrypt($request->sub_dur);
        // if ($duration) {
        //    $duration = $duration;
        // }
        // else{
        //    $duration = '6';
        // }
        


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
                        $course->students()->attach($request->session()->get('newuser'));
                    }
                }
                $orderItem->item->students()->attach($request->session()->get('newuser'));
            }

            //Generating Invoice
            // echo "<pre>"; print_r($order);die;
            generateInvoice($order);

            $content['newuser'] = session()->get('newactivation');
            $content['email'] = session()->get('email');


                try {
                \Mail::to($request->session()->get('email'))->send(new GiftMail($content));
                } catch (\Exception $e) {

                }
 

           
            Cart::session(auth()->user()->id)->clear();
            
             \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return redirect()->route('status');

        } else {
            $order->status = 2;
            $order->save();
            return redirect()->route('cart.index');
        }
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
// echo "<pre>";
// print_r(session()->get('newuser'));die("maek orsdse");





             $total = 0;
        $f = Cart::session(auth()->user()->id)->getContent()->toArray();
        foreach ($f as $value ) {
            $total += $value['attributes']['price'];
        }
        $order = new Order();
        $order->user_id = session()->get('newuser');
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
            $teacher_id = LiveClasses::findOrFail($cartItem->id);     


             } else {
                $type = Course::class;
            $teacher_id = Course::findOrFail($cartItem->id);     
                
            }
            $order->items()->create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'price' => $cartItem->attributes->price,
                'subscribe' => '1',
                'duration' => $cartItem->attributes->duration
            ]);


            EmailNotification::create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'user_id' => session()->get('newuser'),
                'subscribe' => '1',
                'duration' => $cartItem->attributes->duration,
                'teacher_id' => $teacher_id->teachers[0]['id'],
            ]);
        }

        return $order;
    }





}
