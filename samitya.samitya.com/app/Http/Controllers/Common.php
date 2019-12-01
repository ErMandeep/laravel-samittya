<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;
use App\Models\Tag;
use App\Models\Faq;

class Common extends Controller
{

    public function __construct()
    {

$result = "common controller";

return $result;

    }

    public function test()
    {
    $result = "common controller";

return $result;
    }




}
