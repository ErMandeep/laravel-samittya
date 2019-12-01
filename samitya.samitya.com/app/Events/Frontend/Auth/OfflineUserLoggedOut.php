<?php

namespace App\Events\Frontend\Auth;

use App\Models\Offline_student;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedOut.
 */
class OfflineUserLoggedOut
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(Offline_student $user)
    {
        $this->user = $user;
    }
}
