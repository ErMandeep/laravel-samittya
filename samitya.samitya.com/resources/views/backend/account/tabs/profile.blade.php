<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered">
        <tr>
            <th>@lang('labels.frontend.user.profile.avatar')</th>
            <td><img src="{{ $logged_in_user->picture }}" height="100px" class="user-profile-image" /></td>
        </tr>
        <tr>
            <th>@lang('labels.frontend.user.profile.name')</th>
            <td>{{ $logged_in_user->name }}</td>
        </tr>
        <tr>
            <th>@lang('labels.frontend.user.profile.email')</th>
            <td>{{ $logged_in_user->email }}</td>
        </tr>
        @if ($logged_in_user->hasRole('live teacher') ||  @$logged_in_user->hasRole('teacher'))
        <tr>
            <th>Phone</th>
            <td>{{ $logged_in_user->phone }}</td>
        </tr> 
        <tr>
            <th> Gender </th>
            <td>{{ $logged_in_user->gender }}</td>
        </tr>
        <tr>
            <th> Year of Birth </th>
            <td>{{ $logged_in_user->dob }}</td>
        </tr>
        <tr>
            <th> About </th>
            <td>{{ $logged_in_user->description }}</td>
        </tr>                     
        <tr>
            <th> Software </th>
            <td>{{ $logged_in_user->software }}</td>
        </tr>
        <tr>
            <th> Facebook Profile </th>
            <td>{{ $logged_in_user->facebookurl }}</td>
        </tr>
        <tr>
            <th> Instagram Profile </th>
            <td>{{ $logged_in_user->instagramurl }}</td>
        </tr>     
        <tr>
            <th> Youtube Profile </th>
            <td>{{ $logged_in_user->youtubeurl }}</td>
        </tr>  
        @endif                                               
        <tr>
            <th>@lang('labels.frontend.user.profile.created_at')</th>
            <td>{{ timezone()->convertToLocal($logged_in_user->created_at) }} ({{ $logged_in_user->created_at->diffForHumans() }})</td>
        </tr>
        <tr>
            <th>@lang('labels.frontend.user.profile.last_updated')</th>
            <td>{{ timezone()->convertToLocal($logged_in_user->updated_at) }} ({{ $logged_in_user->updated_at->diffForHumans() }})</td>
        </tr>
        @if(config('registration_fields') != NULL)
            @php
                $fields = json_decode(config('registration_fields'));
            @endphp
            @foreach($fields as $item)
                <tr>
                    <th>{{__('labels.backend.general_settings.user_registration_settings.fields.'.$item->name)}}</th>
                    <td>{{$logged_in_user[$item->name]}}</td>
                </tr>
            @endforeach
        @endif
    </table>
</div>
