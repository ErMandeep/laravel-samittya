<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use Illuminate\Support\Facades\Session;
use View;
use Illuminate\Http\Request;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{

    private $path;

    public function __construct()
    {

        $cat_menu = Category::where('status', '=', 1)->where('parent_id', '=', '0')->get();
        $sub = Category::where('parent_id', '<>', '0')->get();
        View::share('cat_menu' , $cat_menu);
        View::share('sub' , $sub);

        
        $path = 'frontend';
        if(session()->has('display_type')){
            if(session('display_type') == 'rtl'){
                $path = 'frontend-rtl';
            }else{
                $path = 'frontend';
            }
        }else if(config('app.display_type') == 'rtl'){
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->path.'.contact');
    }

    /**
     * @param SendContactRequest $request
     *
     * @return mixed
     */
    public function send(Request $request)
    {


        $contact_data = config('access.captcha.contact');


        echo "<pre>"; print_r($request->all());
         // print_r($contact_data);die;

        // print_r($validation);die;
        

        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required' ,
            'subject' => 'required' ,
            'g-recaptcha-response' => (config('access.captcha.contact') ? 'required' : ''),      

        ],['g-recaptcha-response' => 'Captcha' ],['g-recaptcha-response' => 'Captcha']);

        // if($contact_data == 1){
        // $this->validate($request,[
        //     'g-recaptcha-response' => 'required'
        // ]);            
        // }


        $contact = new Contact();
        $contact->name = $request->name;
        $contact->number = $request->phone;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        Mail::send(new SendContact($request));

        Session::flash('alert','Contact mail sent successfully!');
        return redirect()->back();
    }
}
