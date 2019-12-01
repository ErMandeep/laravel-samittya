<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Auth\User\AccountController;
use App\Http\Controllers\Backend\Auth\User\ProfileController;
use \App\Http\Controllers\Backend\Auth\User\UpdatePasswordController;

/*
 * All route names are prefixed with 'admin.'.
 */

//===== General Routes =====//
Route::redirect('/', '/user/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


//===== Teachers Routes =====//
Route::resource('teachers', 'Admin\TeachersController');
Route::get('get-teachers-data', ['uses' => 'Admin\TeachersController@getData', 'as' => 'teachers.get_data']);
Route::post('teachers_mass_destroy', ['uses' => 'Admin\TeachersController@massDestroy', 'as' => 'teachers.mass_destroy']);
Route::post('teachers_restore/{id}', ['uses' => 'Admin\TeachersController@restore', 'as' => 'teachers.restore']);
Route::delete('teachers_perma_del/{id}', ['uses' => 'Admin\TeachersController@perma_del', 'as' => 'teachers.perma_del']);

// ======== Offline Users Roites ======
Route::resource('offline_users', 'Admin\OfflineusersController');

//===== Approve Teacher Routes =====//
Route::get('approveteacher', ['uses' => 'Admin\BecomeTeacherController@index', 'as' => 'approveteacher.all']);
Route::get('upgrade', ['uses' => 'Admin\BecomeTeacherController@view', 'as' => 'approveteacher.upgrade']);

Route::post('live-teacher', ['uses' => 'Admin\BecomeTeacherController@become_live_teacher', 'as' => 'approveteacher.live_teacher_resquest']);
Route::post('regular-teacher', ['uses' => 'Admin\BecomeTeacherController@regular_teacher_resquest', 'as' => 'approveteacher.regular_teacher_resquest']);


//===== Categories Routes =====//
Route::resource('categories', 'Admin\CategoriesController');
Route::get('get-categories-data', ['uses' => 'Admin\CategoriesController@getData', 'as' => 'categories.get_data']);
Route::post('categories_mass_destroy', ['uses' => 'Admin\CategoriesController@massDestroy', 'as' => 'categories.mass_destroy']);
Route::post('categories_restore/{id}', ['uses' => 'Admin\CategoriesController@restore', 'as' => 'categories.restore']);
Route::delete('categories_perma_del/{id}', ['uses' => 'Admin\CategoriesController@perma_del', 'as' => 'categories.perma_del']);

//===== Tag Routes =====//
Route::resource('tags', 'Admin\TagController');
Route::get('get-tag-data', ['uses' => 'Admin\TagController@getData', 'as' => 'tags.get_data']);
Route::post('tags_mass_destroy', ['uses' => 'Admin\TagController@massDestroy', 'as' => 'tags.mass_destroy']);
Route::post('tags_restore/{id}', ['uses' => 'Admin\TagController@restore', 'as' => 'tags.restore']);
Route::delete('tags_perma_del/{id}', ['uses' => 'Admin\TagController@perma_del', 'as' => 'tags.perma_del']);

Route::get('old-tags', ['uses' => 'Admin\TagController@old_tags', 'as' => 'tags.old']);



//===== Schedule Routes =====//
Route::resource('schedule', 'Admin\ScheduleController');
Route::post('add_event', ['uses' => 'Admin\ScheduleController@add_event', 'as' => 'schedule.add']);
Route::get('get_event', ['uses' => 'Admin\ScheduleController@get_event', 'as' => 'schedule.get']);
Route::post('get_update', ['uses' => 'Admin\ScheduleController@update_event', 'as' => 'schedule.update']);


//===== Courses Routes =====//
Route::resource('courses', 'Admin\CoursesController');
Route::get('get-courses-data', ['uses' => 'Admin\CoursesController@getData', 'as' => 'courses.get_data']);
Route::post('courses_mass_destroy', ['uses' => 'Admin\CoursesController@massDestroy', 'as' => 'courses.mass_destroy']);
Route::post('courses_restore/{id}', ['uses' => 'Admin\CoursesController@restore', 'as' => 'courses.restore']);
Route::delete('courses_perma_del/{id}', ['uses' => 'Admin\CoursesController@perma_del', 'as' => 'courses.perma_del']);
Route::post('course-save-sequence', ['uses' => 'Admin\CoursesController@saveSequence', 'as' => 'courses.saveSequence']);
Route::get('course-publish/{id}',['uses' => 'Admin\CoursesController@publish' , 'as' => 'courses.publish']);


//===== Live Classes Routes =====//
Route::resource('liveclasses', 'Admin\LiveClassController');

Route::get('get-liveclasses-data', ['uses' => 'Admin\LiveClassController@getData', 'as' => 'liveclasses.get_data']);
Route::post('liveclasses_mass_destroy', ['uses' => 'Admin\LiveClassController@massDestroy', 'as' => 'liveclasses.mass_destroy']);
Route::post('liveclasses_restore/{id}', ['uses' => 'Admin\LiveClassController@restore', 'as' => 'liveclasses.restore']);
Route::delete('liveclasses_perma_del/{id}', ['uses' => 'Admin\LiveClassController@perma_del', 'as' => 'liveclasses.perma_del']);
Route::post('liveclasses-save-sequence', ['uses' => 'Admin\LiveClassController@saveSequence', 'as' => 'liveclasses.saveSequence']);
Route::get('liveclasses-publish/{id}',['uses' => 'Admin\LiveClassController@publish' , 'as' => 'liveclasses.publish']);


Route::get('review-classes', ['uses' => 'Admin\LiveClassController@reviewindex', 'as' => 'liveclasses.reviewindex']);
Route::get('get-liveclasses-reviewdata', ['uses' => 'Admin\LiveClassController@reviewgetData', 'as' => 'liveclasses.reviewget_data']);





//===== Reviewchanges Routes =====//
Route::resource('reviewchanges', 'Admin\ReviewChangesController');
Route::get('get-reviewchanges-data', ['uses' => 'Admin\ReviewChangesController@getData', 'as' => 'reviewchanges.get_data']);
Route::post('reviewchanges_mass_destroy', ['uses' => 'Admin\ReviewChangesController@massDestroy', 'as' => 'reviewchanges.mass_destroy']);
Route::post('reviewchanges_restore/{id}', ['uses' => 'Admin\ReviewChangesController@restore', 'as' => 'reviewchanges.restore']);
Route::delete('reviewchanges_perma_del/{id}', ['uses' => 'Admin\ReviewChangesController@perma_del', 'as' => 'reviewchanges.perma_del']);
Route::post('reviewchanges-save-sequence', ['uses' => 'Admin\ReviewChangesController@saveSequence', 'as' => 'reviewchanges.saveSequence']);
Route::get('reviewchanges-publish/{id}',['uses' => 'Admin\ReviewChangesController@publish' , 'as' => 'reviewchanges.publish']);



//===== Bundles Routes =====//
Route::resource('bundles', 'Admin\BundlesController');
Route::get('get-bundles-data', ['uses' => 'Admin\BundlesController@getData', 'as' => 'bundles.get_data']);
Route::post('bundles_mass_destroy', ['uses' => 'Admin\BundlesController@massDestroy', 'as' => 'bundles.mass_destroy']);
Route::post('bundles_restore/{id}', ['uses' => 'Admin\BundlesController@restore', 'as' => 'bundles.restore']);
Route::delete('bundles_perma_del/{id}', ['uses' => 'Admin\BundlesController@perma_del', 'as' => 'bundles.perma_del']);
Route::post('bundle-save-sequence', ['uses' => 'Admin\BundlesController@saveSequence', 'as' => 'bundles.saveSequence']);
Route::get('bundle-publish',['uses' => 'Admin\BundlesController@publish' , 'as' => 'bundles.publish']);



//===== Lessons Routes =====//
Route::resource('lessons', 'Admin\LessonsController');
Route::get('get-lessons-data', ['uses' => 'Admin\LessonsController@getData', 'as' => 'lessons.get_data']);
Route::post('lessons_mass_destroy', ['uses' => 'Admin\LessonsController@massDestroy', 'as' => 'lessons.mass_destroy']);
Route::post('lessons_restore/{id}', ['uses' => 'Admin\LessonsController@restore', 'as' => 'lessons.restore']);
Route::delete('lessons_perma_del/{id}', ['uses' => 'Admin\LessonsController@perma_del', 'as' => 'lessons.perma_del']);


//===== Review Lessons Routes =====//
Route::resource('reviewlession', 'Admin\ReviewLessionController');
Route::get('get-reviewlession-data', ['uses' => 'Admin\ReviewLessionController@getData', 'as' => 'reviewlession.get_data']);
Route::post('reviewlession_mass_destroy', ['uses' => 'Admin\ReviewLessionController@massDestroy', 'as' => 'reviewlession.mass_destroy']);
Route::post('reviewlession_restore/{id}', ['uses' => 'Admin\ReviewLessionController@restore', 'as' => 'reviewlession.restore']);
Route::delete('reviewlession_perma_del/{id}', ['uses' => 'Admin\ReviewLessionController@perma_del', 'as' => 'reviewlession.perma_del']);
Route::get('reviewlession-publish/{id}',['uses' => 'Admin\ReviewLessionController@publish' , 'as' => 'reviewlession.publish']);


//===== Questions Routes =====//
Route::resource('questions', 'Admin\QuestionsController');
Route::get('get-questions-data', ['uses' => 'Admin\QuestionsController@getData', 'as' => 'questions.get_data']);
Route::post('questions_mass_destroy', ['uses' => 'Admin\QuestionsController@massDestroy', 'as' => 'questions.mass_destroy']);
Route::post('questions_restore/{id}', ['uses' => 'Admin\QuestionsController@restore', 'as' => 'questions.restore']);
Route::delete('questions_perma_del/{id}', ['uses' => 'Admin\QuestionsController@perma_del', 'as' => 'questions.perma_del']);


//===== Questions Options Routes =====//
Route::resource('questions_options', 'Admin\QuestionsOptionsController');
Route::get('get-qo-data', ['uses' => 'Admin\QuestionsOptionsController@getData', 'as' => 'questions_options.get_data']);
Route::post('questions_options_mass_destroy', ['uses' => 'Admin\QuestionsOptionsController@massDestroy', 'as' => 'questions_options.mass_destroy']);
Route::post('questions_options_restore/{id}', ['uses' => 'Admin\QuestionsOptionsController@restore', 'as' => 'questions_options.restore']);
Route::delete('questions_options_perma_del/{id}', ['uses' => 'Admin\QuestionsOptionsController@perma_del', 'as' => 'questions_options.perma_del']);


//===== Tests Routes =====//
Route::resource('tests', 'Admin\TestsController');
Route::get('get-tests-data', ['uses' => 'Admin\TestsController@getData', 'as' => 'tests.get_data']);
Route::post('tests_mass_destroy', ['uses' => 'Admin\TestsController@massDestroy', 'as' => 'tests.mass_destroy']);
Route::post('tests_restore/{id}', ['uses' => 'Admin\TestsController@restore', 'as' => 'tests.restore']);
Route::delete('tests_perma_del/{id}', ['uses' => 'Admin\TestsController@perma_del', 'as' => 'tests.perma_del']);


//===== Media Routes =====//
Route::post('media/remove', ['uses' => 'Admin\MediaController@destroy', 'as' => 'media.destroy']);
Route::post('media/removes', ['uses' => 'Admin\MediaController@destroyfile', 'as' => 'media.destroyfile']);



//===== GallaryMedia Routes =====//
Route::post('mediadelete/remove', ['uses' => 'Admin\GallaryController@mediadelete', 'as' => 'mediadelete.destroy']);
Route::post('newsdelete/remove', ['uses' => 'Admin\NewsController@newsmediadelete', 'as' => 'newsmediadelete.destroy']);

//===== User Account Routes =====//
Route::group(['middleware' => ['auth', 'password_expires']], function () {
    Route::get('account', [AccountController::class, 'index'])->name('account');
    Route::patch('account', [UpdatePasswordController::class, 'update'])->name('account.post');
    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/bankupdate', [ProfileController::class, 'bankupdate'])->name('profile.bankupdate');
});


//==== Messages Routes =====//
Route::get('messages', ['uses' => 'MessagesController@index', 'as' => 'messages']);
Route::post('messages/unread', ['uses' => 'MessagesController@getUnreadMessages', 'as' => 'messages.unread']);
Route::post('messages/send', ['uses' => 'MessagesController@send', 'as' => 'messages.send']);
Route::post('messages/reply', ['uses' => 'MessagesController@reply', 'as' => 'messages.reply']);
Route::post('messages/send_audio', ['uses' => 'MessagesController@send_audio', 'as' => 'messages.send_audio']);
Route::get('messages/download', ['uses' => 'MessagesController@download', 'as' => 'messages.download']);
Route::post('messages/report_user', ['uses' => 'MessagesController@report_user', 'as' => 'messages.report_user']);
Route::get('messages/show_report', ['uses' => 'MessagesController@show_report', 'as' => 'messages.show_report']);
Route::get('messages/get_report', ['uses' => 'MessagesController@get_report', 'as' => 'messages.get_report']);
Route::post('messages/report_by_admin/{id}', ['uses' => 'MessagesController@report_by_admin', 'as' => 'messages.report_by_admin']);
Route::post('messages/ignore_by_admin/{id}', ['uses' => 'MessagesController@ignore_by_admin', 'as' => 'messages.ignore_by_admin']);
Route::get('messages/show_messages', ['uses' => 'MessagesController@show_messages', 'as' => 'messages.show_messages']);






//===== Orders Routes =====//
Route::resource('orders', 'Admin\OrderController');
Route::get('get-orders-data', ['uses' => 'Admin\OrderController@getData', 'as' => 'orders.get_data']);
Route::post('orders_mass_destroy', ['uses' => 'Admin\OrderController@massDestroy', 'as' => 'orders.mass_destroy']);
Route::post('orders/complete', ['uses' => 'Admin\OrderController@complete', 'as' => 'orders.complete']);
Route::delete('orders_perma_del/{id}', ['uses' => 'Admin\OrderController@perma_del', 'as' => 'orders.perma_del']);


//=== Invoice Routes =====//
Route::get('invoice/download', ['uses' => 'Admin\InvoiceController@getInvoice', 'as' => 'invoice.download']);
Route::get('invoices', ['uses' => 'Admin\InvoiceController@getIndex', 'as' => 'invoices.index']);

Route::get('invoice/downloadfiles', ['uses' => 'Admin\InvoiceController@downloadfiles', 'as' => 'invoice.downloadfiles']);


//Route::group(['middleware' => 'role:admin'], function () {

//===== Settings Routes =====//
    Route::get('settings/general', ['uses' => 'Admin\ConfigController@getGeneralSettings', 'as' => 'general-settings']);
    Route::post('settings/general', ['uses' => 'Admin\ConfigController@saveGeneralSettings'])->name('general-settings');
    Route::get('settings/social', ['uses' => 'Admin\ConfigController@getSocialSettings'])->name('social-settings');
    Route::post('settings/social', ['uses' => 'Admin\ConfigController@saveSocialSettings'])->name('social-settings');

    Route::get('contact', ['uses' => 'Admin\ConfigController@getContact'])->name('contact-settings');
    Route::get('footer', ['uses' => 'Admin\ConfigController@getFooter'])->name('footer-settings');
    Route::get('newsletter', ['uses' => 'Admin\ConfigController@getNewsletterConfig'])->name('newsletter-settings');
    Route::post('newsletter/sendgrid-lists', ['uses' => 'Admin\ConfigController@getSendGridLists'])->name('newsletter.getSendGridLists');

//});



//===== Slider Routes =====/
Route::resource('sliders', 'Admin\SliderController');
Route::get('sliders/status/{id}', 'Admin\SliderController@status')->name('sliders.status', 'id');
Route::post('sliders/save-sequence', ['uses' => 'Admin\SliderController@saveSequence', 'as' => 'sliders.saveSequence']);


//===== Sponsors Routes =====//
Route::resource('sponsors', 'Admin\SponsorController');
Route::get('get-sponsors-data', ['uses' => 'Admin\SponsorController@getData', 'as' => 'sponsors.get_data']);
Route::post('sponsors_mass_destroy', ['uses' => 'Admin\SponsorController@massDestroy', 'as' => 'sponsors.mass_destroy']);
Route::get('sponsors/status/{id}', 'Admin\SponsorController@status')->name('sponsors.status', 'id');


//===== Testimonials Routes =====//
Route::resource('testimonials', 'Admin\TestimonialController');
Route::get('get-testimonials-data', ['uses' => 'Admin\TestimonialController@getData', 'as' => 'testimonials.get_data']);
Route::post('testimonials_mass_destroy', ['uses' => 'Admin\TestimonialController@massDestroy', 'as' => 'testimonials.mass_destroy']);
Route::get('testimonials/status/{id}', 'Admin\TestimonialController@status')->name('testimonials.status', 'id');


//======= Blog Routes =====//
Route::group(['prefix' => 'blog'], function () {
    Route::get('/create', 'Admin\BlogController@create');
    Route::post('/create', 'Admin\BlogController@store');
    Route::get('delete/{id}', 'Admin\BlogController@destroy')->name('blogs.delete');
    Route::get('edit/{id}', 'Admin\BlogController@edit')->name('blogs.edit');
    Route::post('edit/{id}', 'Admin\BlogController@update');
    Route::get('view/{id}', 'Admin\BlogController@show');
//        Route::get('{blog}/restore', 'BlogController@restore')->name('blog.restore');
    Route::post('{id}/storecomment', 'Admin\BlogController@storeComment')->name('storeComment');
});
Route::resource('blogs', 'Admin\BlogController');
Route::get('get-blogs-data', ['uses' => 'Admin\BlogController@getData', 'as' => 'blogs.get_data']);
Route::post('blogs_mass_destroy', ['uses' => 'Admin\BlogController@massDestroy', 'as' => 'blogs.mass_destroy']);


//======= Pages Routes =====//
Route::resource('pages', 'Admin\PageController');
Route::get('get-pages-data', ['uses' => 'Admin\PageController@getData', 'as' => 'pages.get_data']);
Route::post('pages_mass_destroy', ['uses' => 'Admin\PageController@massDestroy', 'as' => 'pages.mass_destroy']);
Route::post('pages_restore/{id}', ['uses' => 'Admin\PageController@restore', 'as' => 'pages.restore']);
Route::delete('pages_perma_del/{id}', ['uses' => 'Admin\PageController@perma_del', 'as' => 'pages.perma_del']);


//======= Gallary Routes =====//
Route::resource('gallary', 'Admin\GallaryController');
Route::get('get-gallary-data', ['uses' => 'Admin\GallaryController@getData', 'as' => 'gallary.get_data']);
Route::post('gallary_mass_destroy', ['uses' => 'Admin\GallaryController@massDestroy', 'as' => 'gallary.mass_destroy']);
Route::post('gallary_restore/{id}', ['uses' => 'Admin\GallaryController@restore', 'as' => 'gallary.restore']);
Route::delete('gallary_perma_del/{id}', ['uses' => 'Admin\GallaryController@perma_del', 'as' => 'gallary.perma_del']);

//======= News Routes =====//
Route::resource('news', 'Admin\NewsController');
Route::get('get-news-data', ['uses' => 'Admin\NewsController@getData', 'as' => 'news.get_data']);
Route::post('news_mass_destroy', ['uses' => 'Admin\NewsController@massDestroy', 'as' => 'news.mass_destroy']);
Route::post('news_restore/{id}', ['uses' => 'Admin\NewsController@restore', 'as' => 'news.restore']);
Route::delete('news_perma_del/{id}', ['uses' => 'Admin\NewsController@perma_del', 'as' => 'news.perma_del']);


//===== FAQs Routes =====//
Route::resource('faqs', 'Admin\FaqController');
Route::get('get-faqs-data', ['uses' => 'Admin\FaqController@getData', 'as' => 'faqs.get_data']);
Route::post('faqs_mass_destroy', ['uses' => 'Admin\FaqController@massDestroy', 'as' => 'faqs.mass_destroy']);
Route::get('faqs/status/{id}', 'Admin\FaqController@status')->name('faqs.status');


//==== Reasons Routes ====//
Route::resource('reasons', 'Admin\ReasonController');
Route::get('get-reasons-data', ['uses' => 'Admin\ReasonController@getData', 'as' => 'reasons.get_data']);
Route::post('reasons_mass_destroy', ['uses' => 'Admin\ReasonController@massDestroy', 'as' => 'reasons.mass_destroy']);
Route::get('reasons/status/{id}', 'Admin\ReasonController@status')->name('reasons.status');


Route::get('menu-manager', 'MenuController@index')->name('menu-manager');


//====== Contacts Routes =====//
Route::resource('contact-requests', 'ContactController');
Route::get('get-contact-requests-data', ['uses' => 'ContactController@getData', 'as' => 'contact_requests.get_data']);

//====== Review Routes =====//
Route::resource('reviews', 'ReviewController');
Route::get('get-reviews-data', ['uses' => 'ReviewController@getData', 'as' => 'reviews.get_data']);




//====== Reports Routes =====//
Route::get('report/sales', ['uses' => 'ReportController@getSalesReport','as' => 'reports.sales']);
Route::get('report/students', ['uses' => 'ReportController@getStudentsReport','as' => 'reports.students']);

Route::get('get-course-reports-data', ['uses' => 'ReportController@getCourseData', 'as' => 'reports.get_course_data']);
Route::get('get-live_course-reports-data', ['uses' => 'ReportController@getliveCourseData', 'as' => 'reports.get_live_course_data']);
Route::get('get-bundle-reports-data', ['uses' => 'ReportController@getBundleData', 'as' => 'reports.get_bundle_data']);
Route::get('get-students-reports-data', ['uses' => 'ReportController@getStudentsData', 'as' => 'reports.get_students_data']);


//==== Remove Locale FIle ====//
Route::post('delete-locale', function () {
    \Barryvdh\TranslationManager\Models\Translation::where('locale', request('locale'))->delete();

    \Illuminate\Support\Facades\File::deleteDirectory(public_path('../resources/lang/' . request('locale')));
})->name('delete-locale');


//==== Certificates ====//
Route::get('certificates', 'CertificateController@getCertificates')->name('certificates.index');
Route::post('certificates/generate', 'CertificateController@generateCertificate')->name('certificates.generate');
Route::get('certificates/download', ['uses' => 'CertificateController@download', 'as' => 'certificates.download']);


//==== Update Theme Routes ====//
Route::get('update-theme','UpdateController@index')->name('update-theme');
Route::post('update-theme','UpdateController@updateTheme')->name('update-files');
Route::post('list-files','UpdateController@listFiles')->name('list-files');
Route::get('backup','BackupController@index')->name('backup');
Route::get('generate-backup','BackupController@generateBackup')->name('generate-backup');

Route::post('backup','BackupController@storeBackup')->name('backup.store');

// ==== Ofline Cities Routes === // 

Route::resource('cities', 'Admin\CitiesController');
Route::get('get-cities-data', ['uses' => 'Admin\CitiesController@getData', 'as' => 'cities.get_data']);
Route::post('cities_mass_destroy', ['uses' => 'Admin\CitiesController@massDestroy', 'as' => 'cities.mass_destroy']);
Route::post('cities_restore/{id}', ['uses' => 'Admin\CitiesController@restore', 'as' => 'cities.restore']);
Route::delete('cities_perma_del/{id}', ['uses' => 'Admin\CitiesController@perma_del', 'as' => 'cities.perma_del']);



// ==== offline Branches Route === // 
Route::resource('branches', 'Admin\BranchesController');
Route::get('get-branches-data', ['uses' => 'Admin\BranchesController@getData', 'as' => 'branches.get_data']);
Route::post('branches_mass_destroy', ['uses' => 'Admin\BranchesController@massDestroy', 'as' => 'branches.mass_destroy']);
Route::post('branches_restore/{id}', ['uses' => 'Admin\BranchesController@restore', 'as' => 'branches.restore']);
Route::delete('branches_perma_del/{id}', ['uses' => 'Admin\BranchesController@perma_del', 'as' => 'branches.perma_del']);

// ==== offline States Route === // 
Route::resource('states', 'Admin\StatesController');
Route::get('get-states-data', ['uses' => 'Admin\StatesController@getData', 'as' => 'states.get_data']);
Route::post('states_mass_destroy', ['uses' => 'Admin\StatesController@massDestroy', 'as' => 'states.mass_destroy']);
Route::post('states_restore/{id}', ['uses' => 'Admin\StatesController@restore', 'as' => 'states.restore']);
Route::delete('states_perma_del/{id}', ['uses' => 'Admin\StatesController@perma_del', 'as' => 'states.perma_del']);




Route::post('get_branches','Admin\OfflineStudentController@get_branches')->name('get_branches');
Route::post('get_cities','Admin\OfflineStudentController@get_cities')->name('get_cities');
// === Offline Student Route === // 
Route::resource('offline-student', 'Admin\OfflineStudentController');
Route::get('get-offline-student-data', ['uses' => 'Admin\OfflineStudentController@getData', 'as' => 'offline-student.get_data']);
Route::post('offline_student_restore/{id}', ['uses' => 'Admin\OfflineStudentController@restore', 'as' => 'offline-student.restore']);
Route::delete('offline-student_perma_del/{id}', ['uses' => 'Admin\OfflineStudentController@perma_del', 'as' => 'offline-student.perma_del']);
Route::delete('temp_off/{id}', ['uses' => 'Admin\OfflineStudentController@temp_off', 'as' => 'offline-student.temp_off']);
Route::delete('payment_reminder/{id}', ['uses' => 'Admin\OfflineStudentController@payment_reminder', 'as' => 'offline-student.payment_reminder']);




//===== Teachers Routes =====//
Route::resource('supervisor', 'Admin\SupervisorController');
Route::get('get-supervisor-data', ['uses' => 'Admin\SupervisorController@getData', 'as' => 'supervisor.get_data']);
Route::post('supervisor_mass_destroy', ['uses' => 'Admin\SupervisorController@massDestroy', 'as' => 'supervisor.mass_destroy']);
Route::post('supervisor_restore/{id}', ['uses' => 'Admin\SupervisorController@restore', 'as' => 'supervisor.restore']);
Route::delete('supervisor_perma_del/{id}', ['uses' => 'Admin\SupervisorController@perma_del', 'as' => 'supervisor.perma_del']);