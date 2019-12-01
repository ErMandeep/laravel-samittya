<?php

use App\Helpers\General\Timezone;
use App\Helpers\General\HtmlHelper;

/*
 * Global helpers file with misc functions.
 */
if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('timezone')) {
    /**
     * Access the timezone helper.
     */
    function timezone()
    {
        return resolve(Timezone::class);
    }
}

if (!function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('home_route')) {

    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend') && auth()->user()->isAdmin()) {
                return 'admin.dashboard';
            } else {
                return 'frontend.index';
            }
        }

        return 'frontend.index';
    }
}

if (!function_exists('style')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (!function_exists('script')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}

if (!function_exists('form_cancel')) {

    /**
     * @param        $cancel_to
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_cancel($cancel_to, $title, $classes = 'btn btn-danger ')
    {
        return resolve(HtmlHelper::class)->formCancel($cancel_to, $title, $classes);
    }
}

if (!function_exists('form_submit')) {

    /**
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_submit($title, $classes = 'btn btn-success pull-right')
    {
        return resolve(HtmlHelper::class)->formSubmit($title, $classes);
    }
}

if (!function_exists('camelcase_to_word')) {

    /**
     * @param $str
     *
     * @return string
     */
    function camelcase_to_word($str)
    {
        return implode(' ', preg_split('/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x', $str));
    }
}

if (!function_exists('contact_data')) {

    /**
     * @param $str
     *
     * @return array
     */
    function contact_data($str)
    {
        $newElements = [];
        $elements = json_decode($str);
        foreach ($elements as $key => $item) {
            $newElements[$item->name] = ['value' => $item->value, 'status' => $item->status];
        }
        return $newElements;
    }
}

if (!function_exists('section_filter')) {

    /**
     * @param $str
     * Filter according to type selected.
     * 1 = Popular Categories
     * 2 = Featured Course
     * 3 = Trending Courses
     * 4 = Popular Courses
     * 5 = Custom Links
     * @return array
     */
    function section_filter($section)
    {
        $type = $section->type;
        $section_data = "";
        $section_title = "";
        $content = [];

        if ($type == 1) {
            $section_content = \App\Models\Category::has('courses', '>', 7)
                ->where('status', '=', 1)->get()->take(6);
            $section_title = 'Popular Categories';
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->name,
                    'link' => route('courses.category', ['category' => $item->slug])
                ];
                $content[] = $single_item;
            }
        } else if ($type == 2) {
            $section_content = \App\Models\Course::where('featured', '=', 1)
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = 'Featured Courses';
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 3) {
            $section_content = \App\Models\Course::where('trending', '=', 1)
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = 'Trending Courses';
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 4) {
            $section_content = \App\Models\Course::where('popular', '=', 1)
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = 'Popular Courses';
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 5) {
            $section_title = 'Useful Links';
            $section_content = $section->links;
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->label,
                    'link' => $item->link
                ];
                $content[] = $single_item;
            }
        }

        return ['section_content' => $content, 'section_title' => $section_title];
    }
}


if (!function_exists('generateInvoice')) {


    function generateInvoice($order)
    {
        $invoice = ConsoleTVs\Invoices\Classes\Invoice::make();
        $invoice->number($order->id);
        foreach ($order->items as $item) {
            $title = $item->item->title;
            $price = $item->item->price;
            $qty = 1;
            $id = 'prod-'.$item->item->id;
            $invoice->addItem($title, $price, $qty, $id);
        }
        $user = \App\Models\Auth\User::find($order->user_id);

        $invoice->customer([
                'name' => $user->full_name,
                'id' => $user->id,
                'email' => $user->email
            ])
            ->save('public/invoices/invoice-'.$order->id.'.pdf');
//                ->download('invoice-'.$order->id.'.pdf');

        $invoiceEntry = \App\Models\Invoice::where('order_id','=',$order->id)->first();
        if($invoiceEntry == ""){
            $invoiceEntry = new \App\Models\Invoice();
            $invoiceEntry->user_id = $order->user_id;
            $invoiceEntry->order_id = $order->id;
            $invoiceEntry->url = 'invoice-'.$order->id.'.pdf';
            $invoiceEntry->save();
        }
    }
}

if (!function_exists('trashUrl')) {

    /**
     * @param $str
     *
     * @return array
     */
    function trashUrl($request)
    {
        $currentQueries = $request->query();

//Declare new queries you want to append to string:
        $newQueries = ['show_deleted' => 1];

//Merge together current and new query strings:
        $allQueries = array_merge($currentQueries, $newQueries);

//Generate the URL with all the queries:
        return $request->fullUrlWithQuery($allQueries);

    }
}

if (!function_exists('getCurrency')) {

    /**
     * @param $str
     *
     * @return array
     */
    function getCurrency($short_code)
    {
        $currencies = config('currencies');
        $currency = "";
            foreach ($currencies as $key => $val) {
                if ($val['short_code'] == $short_code) {
                    $currency = $val;
                }
            }
       return $currency;
    }
}