<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\Auth\LoginController;
/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__ . '/frontend/');
});



/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__ . '/backend/');
});


/// Offline Route
Route::group([ 'prefix' => 'offline', 'as' => 'offline.'], function () {
    
    include_route_files(__DIR__ . '/offline/');

});







Route::get('category/{category}/blogs', 'BlogController@getByCategory')->name('blogs.category');
Route::get('tag/{tag}/blogs', 'BlogController@getByTag')->name('blogs.tag');
Route::get('blog/{slug?}', 'BlogController@getIndex')->name('blogs.index');
Route::post('blog/{id}/comment', 'BlogController@storeComment')->name('blogs.comment');
Route::get('blog/comment/delete/{id}', 'BlogController@deleteComment')->name('blogs.comment.delete');

Route::get('teachers', 'Frontend\HomeController@getTeachers')->name('teachers.index');
Route::get('teachers/{id}/show', 'Frontend\HomeController@showTeacher')->name('teachers.show');


Route::post('newsletter/subscribe', 'Frontend\HomeController@subscribe')->name('subscribe');

//============Become Teacher Routes=================//
Route::get('becometeacher', ['uses' => 'BecomeTeacherController@all', 'as' => 'becometeacher.all']);

Route::post('save-teacher-info', ['uses' => 'BecomeTeacherController@store', 'as' => 'becometeacher.saveSequence']);
Route::post('become-live-teacher', ['uses' => 'BecomeTeacherController@become_live_teacher', 'as' => 'becometeacher.live_teacher_resquest']);
Route::post('become-regular-teacher', ['uses' => 'BecomeTeacherController@regular_teacher_resquest', 'as' => 'becometeacher.regular_teacher_resquest']);


//============Gallery Routes=================//
Route::get('gallery', ['uses' => 'GallaryController@allshow', 'as' => 'gallary.all']);
Route::get('news', ['uses' => 'NewsController@allshow', 'as' => 'news.all']);

//============Course Routes=================//
Route::get('courses', ['uses' => 'CoursesController@allshow', 'as' => 'courses.all']);
Route::get('regular-courses', ['uses' => 'CoursesController@allshow', 'as' => 'courses.allshow']);
Route::get('course/{slug}', ['uses' => 'CoursesController@show', 'as' => 'courses.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('course/{course_id}/rating', ['uses' => 'CoursesController@rating', 'as' => 'courses.rating']);
Route::get('category/{category}/courses', ['uses' => 'CoursesController@getByCategory', 'as' => 'courses.category']);
Route::post('courses/{id}/review', ['uses' => 'CoursesController@addReview', 'as' => 'courses.review']);
Route::get('courses/review/{id}/edit', ['uses' => 'CoursesController@editReview', 'as' => 'courses.review.edit']);
Route::post('courses/review/{id}/edit', ['uses' => 'CoursesController@updateReview', 'as' => 'courses.review.update']);
Route::get('courses/review/{id}/delete', ['uses' => 'CoursesController@deleteReview', 'as' => 'courses.review.delete']);
Route::get('tag/{tag}/courses', 'CoursesController@getByTag')->name('courses.tag');

Route::get('live-classes', ['uses' => 'LiveClassController@all', 'as' => 'liveclass.all']);
Route::get('live-courses', ['uses' => 'LiveClassController@alllive', 'as' => 'liveclass.alllive']);
Route::get('live-classes/{slug}', ['uses' => 'LiveClassController@show', 'as' => 'liveclass.show']);
Route::get('live-classes/{category}/courses', ['uses' => 'LiveClassController@getByCategory', 'as' => 'liveclass.category']);
Route::post('live-classes/{id}/review', ['uses' => 'LiveClassController@addReview', 'as' => 'liveclass.review']);
Route::get('live-classes/review/{id}/edit', ['uses' => 'LiveClassController@editReview', 'as' => 'liveclass.review.edit']);
Route::post('live-classes/review/{id}/edit', ['uses' => 'LiveClassController@updateReview', 'as' => 'liveclass.review.update']);
Route::get('live-classes/review/{id}/delete', ['uses' => 'LiveClassController@deleteReview', 'as' => 'liveclass.review.delete']);


//============Bundle Routes=================//
Route::get('bundles', ['uses' => 'BundlesController@all', 'as' => 'bundles.all']);
Route::get('bundle/{slug}', ['uses' => 'BundlesController@show', 'as' => 'bundles.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('bundle/{bundle_id}/rating', ['uses' => 'BundlesController@rating', 'as' => 'bundles.rating']);
Route::get('category/{category}/bundles', ['uses' => 'BundlesController@getByCategory', 'as' => 'bundles.category']);
Route::post('bundles/{id}/review', ['uses' => 'BundlesController@addReview', 'as' => 'bundles.review']);
Route::get('bundles/review/{id}/edit', ['uses' => 'BundlesController@editReview', 'as' => 'bundles.review.edit']);
Route::post('bundles/review/{id}/edit', ['uses' => 'BundlesController@updateReview', 'as' => 'bundles.review.update']);
Route::get('bundles/review/{id}/delete', ['uses' => 'BundlesController@deleteReview', 'as' => 'bundles.review.delete']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('lesson/{course_id}/{slug}/', ['uses' => 'LessonsController@show', 'as' => 'lessons.show']);
    Route::post('lesson/{slug}/test', ['uses' => 'LessonsController@test', 'as' => 'lessons.test']);
    Route::post('lesson/{slug}/retest', ['uses' => 'LessonsController@retest', 'as' => 'lessons.retest']);
    Route::post('video/progress', 'LessonsController@videoProgress')->name('update.videos.progress');
    Route::post('lesson/progress', 'LessonsController@courseProgress')->name('update.course.progress');
});

Route::get('/search', [HomeController::class, 'searchCourse'])->name('search');
Route::get('/search-blog', [HomeController::class, 'searchBlog'])->name('blogs.search');


Route::get('/faqs', 'Frontend\HomeController@getFaqs')->name('faqs');





/*=============== Theme blades routes ends ===================*/

Route::get('/cron_job', 'EmailNotificationController@index');
Route::get('/offline_cron_job', 'OfflineCronController@index');

/*===============  Trial booking route ===================*/

Route::post('/store_selectedtime', 'LiveClassController@selected_time')->name('sel_time');
Route::post('/trial_confirm', 'LiveClassController@trial_confirm')->name('trialconfirm');
Route::get('trial-payment','LiveClassController@trial_payment')->name('trialpayment');

/*===============  Trial booking route ===================*/


Route::get('contact', 'Frontend\ContactController@index')->name('contact');
Route::post('contact/send', 'Frontend\ContactController@send')->name('contact.send');


Route::get('download', ['uses' => 'Frontend\HomeController@getDownload', 'as' => 'download']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('gift/checkout', ['uses' => 'GiftController@checkout', 'as' => 'gift.checkout']);
    Route::post('gift/gift', ['uses' => 'GiftController@checkemail', 'as' => 'gift.checkemail']);
    
    Route::post('cart/checkout', ['uses' => 'CartController@checkout', 'as' => 'cart.checkout']);
    Route::post('cart/add', ['uses' => 'CartController@addToCart', 'as' => 'cart.addToCart']);
    Route::get('cart', ['uses' => 'CartController@index', 'as' => 'cart.index']);
    Route::get('cart/clear', ['uses' => 'CartController@clear', 'as' => 'cart.clear']);
    Route::get('cart/remove', ['uses' => 'CartController@remove', 'as' => 'cart.remove']);
    Route::post('cart/stripe-payment', ['uses' => 'CartController@stripePayment', 'as' => 'cart.stripe.payment']);
    Route::post('cart/razorpay-payment', ['uses' => 'CartController@razorpayPayment', 'as' => 'cart.razorpay.payment']);
    Route::post('cart/free-payment', ['uses' => 'CartController@freePayment', 'as' => 'cart.razorpay.free']);
    Route::post('gift/razorpay-payment', ['uses' => 'GiftController@razorpayPayment', 'as' => 'gift.razorpay.payment']);
    Route::post('cart/paypal-payment', ['uses' => 'CartController@paypalPayment', 'as' => 'cart.paypal.payment']);
    Route::get('cart/paypal-payment/status', ['uses' => 'CartController@getPaymentStatus'])->name('cart.paypal.status');

    Route::get('status', function () {
        return view('frontend.cart.status');
    })->name('status');
    Route::post('cart/offline-payment', ['uses' => 'CartController@offlinePayment', 'as' => 'cart.offline.payment']);


});

//============= Menu  Manager Routes ===============//
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => config('menu.middleware')], function () {

    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    Route::post('add-custom-menu', 'MenuController@addcustommenu')->name('haddcustommenu');
    Route::post('delete-item-menu', 'MenuController@deleteitemmenu')->name('hdeleteitemmenu');
    Route::post('delete-menug', 'MenuController@deletemenug')->name('hdeletemenug');
    Route::post('create-new-menu', 'MenuController@createnewmenu')->name('hcreatenewmenu');
    Route::post('generate-menu-control', 'MenuController@generatemenucontrol')->name('hgeneratemenucontrol');
    Route::post('update-item', 'MenuController@updateitem')->name('hupdateitem');
    Route::post('save-custom-menu', 'MenuController@saveCustomMenu')->name('hcustomitem');
    Route::post('change-location', 'MenuController@updateLocation')->name('update-location');
});

Route::get('certificate-verification','Backend\CertificateController@getVerificationForm')->name('frontend.certificates.getVerificationForm');
Route::post('certificate-verification','Backend\CertificateController@verifyCertificate')->name('frontend.certificates.verify');
Route::get('certificates/download', ['uses' => 'Backend\CertificateController@download', 'as' => 'certificates.download']);


Route::group(['middleware' => 'auth'], function () {

    Route::get('offline', 'OfflineorderController@index')->name('offline');
});


Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::get('/{page?}', [HomeController::class, 'index'])->name('index');
});

// Route::get('/cronjob', ['uses' => 'CronController@sendmail', 'as' => 'cronjob.sendmail']);



Route::post('/courses_all', 'Frontend\HomeController@courses_name')->name('all_courses');

// Route::get('/cronjob', function () {
//     return redirect('home/dashboard');
//     // print_r('expression');
// });











