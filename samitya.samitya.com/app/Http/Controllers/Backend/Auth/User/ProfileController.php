<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Http\Requests\Frontend\User\UpdateBankRequest;
use App\Models\Auth\User;
use Auth;
/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function update(UpdateProfileRequest $request)
    {

        // echo "<pre>";print_r($request->all());die;
 // update query for some specific column

    $current_user = Auth::user()->id;
    $user = User::findOrFail($current_user);

    $request->all();

    $user->update($request->all());
            
            if ($request->image) {
            $user->avatar_type = 'storage';
            $user->avatar_location = $request->image->store('/avatars', 'public');
            }

    $user->save();    
 // update query for some specific column

        $fieldsList = [];
        if(config('registration_fields') != NULL){
            $fields = json_decode(config('registration_fields'));

            foreach ($fields  as $field){
                $fieldsList[] =  ''.$field->name;
            }
        }
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name','dob', 'phone', 'gender', 'address','description','software', 'city', 'pincode', 'state', 'country', 'avatar_type', 'avatar_location', 'facebookurl', 'instagramurl', 'youtubeurl'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );

        // echo "<pre>";print_r($output);die;

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('admin.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }

    public function bankupdate(UpdateBankRequest $request)
    {

        // echo "<pre>";print_r($request->all());die;

    $current_user = Auth::user()->id;
    $user = User::findOrFail($current_user);

    $request->all();

    $user->update($request->all());

    $user->save();

        return redirect()->route('admin.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    
    }
}
