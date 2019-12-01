@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <style>
        .select2-container--default .select2-selection--single {
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }
        .bootstrap-tagsinput{
            width: 100%!important;
            display: inline-block;
        }
        .bootstrap-tagsinput .tag{
            line-height: 1;
            margin-right: 2px;
            background-color: #2f353a ;
            color: white;
            padding: 3px;
            border-radius: 3px;
        }
        .tt-menu {
            background: #eee;
            color: #000;
            display: block;
            padding: 5px 20px;
        }
        .hide{
        	display: none;
        }
        p.note {
            font-size: 13px;
            margin-top: -5px;
            color: #6f6c6c;
        }

    </style>
@endpush

@section('content')
                    @php  
                    function hoursRange( $lower = 0, $upper = 86400, $step = 3600, $format = '' ) {
                    $times = array();

                    if ( empty( $format ) ) {
                    $format = 'g:i A';
                    }

                    foreach ( range( $lower, $upper, $step ) as $increment ) {
                    $increment = gmdate( 'h:i A', $increment );

                    list( $hour, $minutes ) = explode( ':', $increment );

                    $date = new DateTime( $hour . ':' . $minutes );

                    $times[(string) $increment] = $date->format( $format );
                    }

                    return $times;
                    }

                    $every_30_minutes = hoursRange( 0, 86400, 60 * 60, 'h:i A' );

                    @endphp

    {!! Form::open(['method' => 'POST', 'route' => ['admin.liveclasses.store'], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">@lang('labels.backend.courses.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.liveclasses.index') }}"
                   class="btn btn-success">@lang('labels.backend.courses.view')</a>
            </div>
        </div>

        <div class="card-body">
            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                        {!! Form::select('teachers[]', $teachers, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                    </div>
                    <!-- <div class="col-2 d-flex form-group flex-column">
                        OR <a target="_blank" class="btn btn-primary mt-auto"
                              href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                    </div> -->
                </div>
            @endif

            <div class="row">
                <div class="{{Auth::user()->isAdmin() ? 'col-10' : 'col-12' }}  form-group">
                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false]) !!}
                </div>
                @if(Auth::user()->isAdmin())
	                <div class="col-2 d-flex form-group flex-column">
	                    OR <a target="_blank" class="btn btn-primary mt-auto"
	                          href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
	                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-12 col-lg-12 form-group">
                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title'), 'required' => false]) !!}
                </div>
                <!-- @if(Auth::user()->isAdmin())
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',  trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}
				
                </div>
                @endif -->

            </div>
            <div class="row">

                <div class="col-12 form-group">
                    {!! Form::label('description',  'Course Detail', ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description') , 'rows' => '3' ])  !!}
                </div>
                <div class="col-12 form-group">
                    {!! Form::label('requirement',  'Requirement', ['class' => 'control-label']) !!}
                    {!! Form::textarea('requirement', old('requirement'), ['class' => 'form-control ', 'placeholder' => 'Requirement' , 'rows' => '3' ])  !!}
                </div>
                <div class="col-12 col-lg-12 form-group">
                    {!! Form::label('course_image',  trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label']) !!}
                    {!! Form::file('course_image',  ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}

                </div>
            </div>
            
                
           
            <div class="row">
                <!-- <div class="col-4 form-group">
                    {!! Form::label('duration','Duration' , ['class' => 'control-label']) !!}
                    <div class="row display_duration">
                        <div class="col-sm-8 one_sec">
                            {!! Form::text('display_duration', old('display_duration'), ['class' => 'form-control', 'placeholder' => 'Display duration info' ]) !!}
                        </div>
                        <div class="col-sm-4 two_sec">
                            {!!  Form::select('display_in', array(  
                                'minute' => 'Minute(s)',
                                'hour' => 'Hour(s)',
                                'day' => 'Day(s)',
                                'week' => 'Week(s)'),
                                 null, ['class' => 'form-control' , 'name'=>'display_in']   );  !!}
                        </div>
                    </div>
                </div> -->
                <div class="col-4 form-group">
                    {!! Form::label('skills','Skill Levels' , ['class' => 'control-label']) !!}
                    {!!  Form::select('skills_level', array( 
                    		'All Level' => 'All Level', 
                            'Advance' => 'Advance',
                            'Intermediate' => 'Intermediate',
                            'Beginner' => 'Beginner',
                            ),
                             null, ['class' => 'form-control' , 'name'=>'skills_level']   );  !!}

                </div>
                <div class="col-4 form-group">
                    {!! Form::label('language','Language' , ['class' => 'control-label']) !!}
                    {!! Form::text('language_used', old('language_used'), ['class' => 'form-control', 'placeholder' => "Language's used for teaching" ]) !!}

                </div>
                <div class="col-4 form-group">
                    {!! Form::label('per_batch','Student Per batch' , ['class' => 'control-label']) !!}
                    {!! Form::text('per_batch', old('per_batch'), ['class' => 'form-control', 'placeholder' => "Student Per batch" ]) !!}

                </div>
                <!-- <div class="col-md-12 form-group">
                    {!! Form::label('tags','Course Tags' , ['class' => 'control-label']) !!}
                    {!! Form::text('tags', old('tags'), ['class' => 'form-control','data-role' => 'tagsinput', 'placeholder' => trans('labels.backend.blogs.fields.tags_placeholder'),'id'=>'tags']) !!}

                </div> -->
                <div class="col-12 form-group">
                    {!! Form::label('media','Media Intro' , ['class' => 'control-label']) !!}
                   {!! Form::text('videolink', old('videolink'), ['class' => 'form-control', 'placeholder' => 'Add Video link' ]) !!}

                </div>
                <div class="col-12 form-group">
                    {!! Form::label('trial_price','Trial Price' , ['class' => 'control-label']) !!}
                   {!! Form::text('trial_price', old('trial_price'), ['class' => 'form-control', 'placeholder' => 'Leave Blank for Free Trial' ]) !!}
                </div>
                <div class="col-12 form-group">
                    {!! Form::label('avg_price','Price' , ['class' => 'control-label']) !!}
                   {!! Form::text('avg_price', old('avg_price'), ['class' => 'form-control', 'placeholder' => 'Add avg. Price for 1 month' ]) !!}
                </div>
                
            </div>
            @if(Auth::user()->isAdmin())
                <div class="row">
                    <div class="subscription_plan col-12">
                        <!-- ----Subscription Plan 1---- -->
                        <div class="sub_paln_1 row">
                            <h5 class="col-12">Subscription Plan 1</h5>
                            <div class="col-4 form-group">
                            {!! Form::label('sub_price_1','Price' , ['class' => 'control-label']) !!}
                            {!! Form::number('sub_price_1', old('sub_price_1'), ['class' => 'form-control', 'placeholder' => 'Price' ]) !!}
                            </div>
                            <div class="col-4 form-group">
                              {!! Form::label('sub_month_1','Subscription Duration *' , ['class' => 'control-label']) !!}
                                {!!  Form::select('sub_month_1', array(  '1' => '1 Month',
                                '2' => '2 Months' , 
                                '3' => '3 Months' , 
                                '4' => '4 Months'
                                , '5' => '5 Months'
                                ,'6' => '6 Months'
                                , '7' => '7 Months'
                                ,'8' => '8 Months'
                                , '9' => '9 Months'
                                ,'10' => '10 Months'
                                , '11' => '11 Months'
                                ,'12' => '12 Months') , null, ['class' => 'form-control' , 'name'=>'sub_month_1']   );  !!}
                            </div>
                        </div>
                        <!-- ----/Subscription Plan 1---- -->

                        <!-- ----Subscription Plan 2---- -->
                        <div class="sub_paln_2 row">
                            <h5 class="col-12">Subscription Plan 2</h5>
                            <div class="col-4 form-group">
                            {!! Form::label('sub_price_2','Price' , ['class' => 'control-label']) !!}
                            {!! Form::number('sub_price_2', old('sub_price_2'), ['class' => 'form-control', 'placeholder' => 'Price' ]) !!}
                            </div>
                            <div class="col-4 form-group">
                              {!! Form::label('sub_month_2','Subscription Duration *' , ['class' => 'control-label']) !!}
                                {!!  Form::select('sub_month_2', array(   '1' => '1 Month',
                                '2' => '2 Months' , 
                                '3' => '3 Months' , 
                                '4' => '4 Months'
                                , '5' => '5 Months'
                                ,'6' => '6 Months'
                                , '7' => '7 Months'
                                ,'8' => '8 Months'
                                , '9' => '9 Months'
                                ,'10' => '10 Months'
                                , '11' => '11 Months'
                                ,'12' => '12 Months') , null, ['class' => 'form-control' , 'name'=>'sub_month_2']   );  !!}
                            </div>
                        </div>
                        <!-- ----/Subscription Plan 2---- -->

                        <!-- ----Subscription Plan 3---- -->
                        <div class="sub_paln_1 row">
                            <h5 class="col-12">Subscription Plan 3</h5>
                            <div class="col-4 form-group">
                            {!! Form::label('sub_price_3','Price' , ['class' => 'control-label']) !!}
                            {!! Form::number('sub_price_3', old('sub_price_3'), ['class' => 'form-control', 'placeholder' => 'Price' ]) !!}
                            </div>
                            <div class="col-4 form-group">
                              {!! Form::label('sub_month_3','Subscription Duration *' , ['class' => 'control-label']) !!}
                                {!!  Form::select('sub_month_1', array(  '1' => '1 Month',
                                '2' => '2 Months' , 
                                '3' => '3 Months' , 
                                '4' => '4 Months'
                                , '5' => '5 Months'
                                ,'6' => '6 Months'
                                , '7' => '7 Months'
                                ,'8' => '8 Months'
                                , '9' => '9 Months'
                                ,'10' => '10 Months'
                                , '11' => '11 Months'
                                ,'12' => '12 Months') , null, ['class' => 'form-control' , 'name'=>'sub_month_3']   );  !!}
                            </div>
                        </div>
                        <!-- ----/Subscription Plan 3---- -->
                    </div>
                </div>
            @endif   

            <div class="row">
                <div class="col-12">
                     {!! Form::label('dur','Class Duration' , ['class' => 'control-label']) !!}
                    {!!  Form::select('duration', array( 
                            '0.5 Hr' => '0.5 Hr', 
                            '1 Hr' => '1 Hr',
                            '1.5 Hrs' => '1.5 Hrs',
                            '2 Hrs' => '2 Hrs',
                            ),
                             null, ['class' => 'form-control class_dur' , 'name'=>'duration']   );  !!}  
                </div>
            </div>
            
            <div class="row">
	            <div class="col-12">
	            	
                    <h5>Regular Lesson Schedule</h5>
                    <p class="note">Please select you timing for weekly regular Lesson</p>
	            </div>
	            	
	            	<!-- ------Monday---- -->
                    <div class="day row col-12" >
                        <div class="col-12 form-group">
                           <div class="checkbox row ">
                            <div class="col-1">
                                {!! Form::label('monday','Monday' , ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-1">
                                {{ html()->label(
                                        html()->checkbox('mon',0)
                                              ->class('switch-input')->value(1)
                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                    ->class('switch switch-sm switch-3d switch-primary')
                                }}
                            </div>
                                
                            </div>
                        </div>
                        <div class="display {{ old('mon') == '1' ? ' ' : 'hide' }}  row col-12">
                            <div class="col-4 form-group">
                               {!! Form::text('mon_start_time', old('mon_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' , 'autocomplete' => 'off' ]) !!}
                            </div>
                            <div class="col-4 form-group">
                               {!! Form::text('mon_end_time', old('mon_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time', 'readonly' ]) !!}
                            </div>    
                        </div>    
                    </div>
	                <!-- ------/Monday---- -->

	                <!-- ---------Tuesday------- -->
                    <div class="day row col-12">
                        <div class="col-12 form-group">
                          <div class="checkbox row ">
                            <div class="col-1">
                            {!! Form::label('schedule','Tuesday' , ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-1">
                                {{ html()->label(
                                        html()->checkbox('tue',0)
                                              ->class('switch-input')->value(1)
                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                    ->class('switch switch-sm switch-3d switch-primary')
                                }}
                            </div>
                                
                                
                            </div>
                        </div>
                        <div  class="display {{ old('tue') == '1' ? ' ' : 'hide' }}   row col-12">
	                        <div class="col-4 form-group">
	                           {!! Form::text('tue_start_time', old('tue_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' , 'autocomplete' => 'off'   ]) !!}
	                        </div>
	                        <div class="col-4 form-group">
	                           {!! Form::text('tue_end_time', old('tue_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time' , 'readonly' ]) !!}
	                        </div>
	                    </div>
                    </div>
	                <!-- ---------/Tuesday------- -->

                    <!-- ---------Wednesday------- -->
                    <div class="day row col-12">
                        <div class="col-12 form-group">
                          <div class="checkbox row ">
                            <div class="col-1">
                                {!! Form::label('schedule','Wednesday' , ['class' => 'control-label']) !!}
                            </div>
                           <div class="col-1">
                                {{ html()->label(
                                        html()->checkbox('wed',0)
                                              ->class('switch-input')->value(1)
                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                    ->class('switch switch-sm switch-3d switch-primary')
                                }}
                           </div>
                            </div>
                        </div>
                        <div class="display {{ old('wed') == '1' ? ' ' : 'hide' }} hide row col-12">
	                        <div class="col-4 form-group">
	                           {!! Form::text('wed_start_time', old('wed_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' ,'autocomplete' => 'off'  ]) !!}
	                        </div>
	                        <div class="col-4 form-group">
	                           {!! Form::text('wed_end_time', old('wed_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time', 'readonly' ]) !!}
	                        </div> 
	                    </div>
                    </div>
                    <!-- ---------/Wednesday------- -->

                    <!-- ---------Thursday------- -->
				                    <div class="day row col-12">
				                        <div class="col-12 form-group">
				                          <div class="checkbox row">
                                            <div class="col-1">
				                            {!! Form::label('schedule','Thursday' , ['class' => 'control-label']) !!}
                                            </div>
                                            <div class="col-1">
				                                {{ html()->label(
				                                        html()->checkbox('thur',0)
				                                              ->class('switch-input')->value(1)
				                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
				                                    ->class('switch switch-sm switch-3d switch-primary')
				                                }}
                                            </div>
				                            </div>
				                        </div>
				                        <div class="display {{ old('thur') == '1' ? ' ' : 'hide' }}  row col-12">
					                        <div class="col-4 form-group">
					                           {!! Form::text('thur_start_time', old('thur_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' ,'autocomplete' => 'off' ]) !!}
					                        </div>
					                        <div class="col-4 form-group">
					                           {!! Form::text('thur_end_time', old('thur_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time', 'readonly' ]) !!}
					                        </div> 
					                    </div>
				                    </div>
                    <!-- ---------/Thursday------- -->

                    <!-- ---------Friday------- -->
				                    <div class="day row col-12">
				                        <div class="col-12 form-group">
				                          <div class="checkbox row">
                                            <div class="col-1">
				                            {!! Form::label('schedule','Friday' , ['class' => 'control-label']) !!}
                                                
                                            </div>
                                            <div class="col-1">
				                                {{ html()->label(
				                                        html()->checkbox('fri',0)
				                                              ->class('switch-input')->value(1)
				                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
				                                    ->class('switch switch-sm switch-3d switch-primary')
				                                }}
                                                
                                            </div>
				                            </div>
				                        </div>
				                        <div class="display {{ old('fri') == '1' ? ' ' : 'hide' }}  row col-12">
					                        <div class="col-4 form-group">
					                           {!! Form::text('fri_start_time', old('fri_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' , 'autocomplete' => 'off' ]) !!}
					                        </div>
					                        <div class="col-4 form-group">
					                           {!! Form::text('fri_end_time', old('fri_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time', 'readonly' ,'autocomplete' => 'off' ]) !!}
					                        </div> 
					                    </div>
				                    </div>
                    <!-- ---------/Friday------- -->

                    <!-- ---------Saturday------- -->
				                    <div class="day row col-12">
				                        <div class="col-12 form-group">
				                          <div class="checkbox row">
                                            <div class="col-1">
                                                
				                            {!! Form::label('schedule','Saturday' , ['class' => 'control-label']) !!}
                                            </div>
                                            <div class="col-1">
				                                {{ html()->label(
				                                        html()->checkbox('sat',0)
				                                              ->class('switch-input')->value(1)
				                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
				                                    ->class('switch switch-sm switch-3d switch-primary')
				                                }}
                                                
                                            </div>
				                            </div>
				                        </div>
				                        <div class="display {{ old('sat') == '1' ? ' ' : 'hide' }}  row col-12">
					                        <div class="col-4 form-group">
					                           {!! Form::text('sat_start_time', old('sat_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' , 'autocomplete' => 'off' ]) !!}
					                        </div>
					                        <div class="col-4 form-group">
					                           {!! Form::text('sat_end_time', old('sat_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time', 'readonly' ,'autocomplete' => 'off' ]) !!}
					                        </div> 
					                    </div>
				                    </div>
                    <!-- ---------/Saturday------- -->

                    <!-- ---------Sunday------- -->
				                    <div class="day row col-12">
				                        <div class="col-12 form-group">
				                          <div class="checkbox row">
                                            <div class="col-1">
				                            {!! Form::label('schedule','Sunday' , ['class' => 'control-label']) !!}
                                                
                                            </div>
                                            <div class="col-1">
				                                {{ html()->label(
				                                        html()->checkbox('sun',0)
				                                              ->class('switch-input')->value(1)
				                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
				                                    ->class('switch switch-sm switch-3d switch-primary')
				                                }}
                                                
                                            </div>
				                            </div>
				                        </div>
				                        <div class="display {{ old('sun') == '1' ? ' ' : 'hide' }}  row col-12">
					                        <div class="col-4 form-group">
					                           {!! Form::text('sun_start_time', old('sun_start_time'), ['class' => 'form-control timepicker', 'placeholder' => 'Select Time' , 'autocomplete' => 'off' ]) !!}
					                        </div>
					                        <div class="col-4 form-group">
					                           {!! Form::text('sun_end_time', old('sun_end_time'), ['class' => 'form-control end_time', 'placeholder' => 'End Time', 'readonly' ,'autocomplete' => 'off' ]) !!}
					                        </div> 
					                    </div>
				                    </div>
                    <!-- ---------/Sunday------- -->



            </div>


<!-- trial schedule -->
            <div class="row">
                <div class="col-12">
                    
                    <h5>Trial Lesson Schedule</h5>
                    <p class="note">You can select multiple timing for trial lesson on single day</p>
                </div>
                    
                    <!-- ------Monday---- -->
                    <div class="day row col-12" >
                        <div class="col-12 form-group">
                           <div class="checkbox row ">
                            <div class="col-1">
                                {!! Form::label('monday','Monday' , ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-1">
                                {{ html()->label(
                                        html()->checkbox('mon1',0)
                                              ->class('switch-input')->value(1)
                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                    ->class('switch switch-sm switch-3d switch-primary')
                                }}
                            </div>
                                
                            </div>
                        </div>
                        <div class="display {{ old('mon1') == '1' ? ' ' : 'hide' }}  row col-12">
                            <div class="col-12 form-group">
                            {!! Form::select('trial_mon_start_time[]', $every_30_minutes, old('trial_mon_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>    
                    </div>
                    <!-- ------/Monday---- -->

                    <!-- ---------Tuesday------- -->
                    <div class="day row col-12">
                        <div class="col-12 form-group">
                          <div class="checkbox row ">
                            <div class="col-1">
                            {!! Form::label('schedule','Tuesday' , ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-1">
                                {{ html()->label(
                                        html()->checkbox('tue1',0)
                                              ->class('switch-input')->value(1)
                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                    ->class('switch switch-sm switch-3d switch-primary')
                                }}
                            </div>
                                
                                
                            </div>
                        </div>
                        <div  class="display {{ old('tue1') == '1' ? ' ' : 'hide' }}   row col-12">
                            <div class="col-12 form-group">
                               
                     {!! Form::select('trial_tue_start_time[]', $every_30_minutes, old('trial_tue_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}

                            </div>

                        </div>
                    </div>
                    <!-- ---------/Tuesday------- -->

                    <!-- ---------Wednesday------- -->
                    <div class="day row col-12">
                        <div class="col-12 form-group">
                          <div class="checkbox row ">
                            <div class="col-1">
                                {!! Form::label('schedule','Wednesday' , ['class' => 'control-label']) !!}
                            </div>
                           <div class="col-1">
                                {{ html()->label(
                                        html()->checkbox('wed1',0)
                                              ->class('switch-input')->value(1)
                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                    ->class('switch switch-sm switch-3d switch-primary')
                                }}
                           </div>
                            </div>
                        </div>
                        <div class="display {{ old('wed1') == '1' ? ' ' : 'hide' }} hide row col-12">
  
                            <div class="col-12 form-group">
{!! Form::select('trial_wed_start_time[]', $every_30_minutes, old('trial_wed_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                            </div> 
                        </div>
                    </div>
                    <!-- ---------/Wednesday------- -->

                    <!-- ---------Thursday------- -->
                                    <div class="day row col-12">
                                        <div class="col-12 form-group">
                                          <div class="checkbox row">
                                            <div class="col-1">
                                            {!! Form::label('schedule','Thursday' , ['class' => 'control-label']) !!}
                                            </div>
                                            <div class="col-1">
                                                {{ html()->label(
                                                        html()->checkbox('thur1',0)
                                                              ->class('switch-input')->value(1)
                                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                                    ->class('switch switch-sm switch-3d switch-primary')
                                                }}
                                            </div>
                                            </div>
                                        </div>
                                        <div class="display {{ old('thur1') == '1' ? ' ' : 'hide' }}  row col-12">
      
                                            <div class="col-12 form-group">
{!! Form::select('trial_thur_start_time[]', $every_30_minutes, old('trial_thur_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                                            </div> 
                                        </div>
                                    </div>
                    <!-- ---------/Thursday------- -->

                    <!-- ---------Friday------- -->
                                    <div class="day row col-12">
                                        <div class="col-12 form-group">
                                          <div class="checkbox row">
                                            <div class="col-1">
                                            {!! Form::label('schedule','Friday' , ['class' => 'control-label']) !!}
                                                
                                            </div>
                                            <div class="col-1">
                                                {{ html()->label(
                                                        html()->checkbox('fri1',0)
                                                              ->class('switch-input')->value(1)
                                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                                    ->class('switch switch-sm switch-3d switch-primary')
                                                }}
                                                
                                            </div>
                                            </div>
                                        </div>
                                        <div class="display {{ old('fri1') == '1' ? ' ' : 'hide' }}  row col-12">
                            
                                            <div class="col-12 form-group">
          {!! Form::select('trial_fri_start_time[]', $every_30_minutes, old('trial_fri_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                                            </div> 
                                        </div>
                                    </div>
                    <!-- ---------/Friday------- -->

                    <!-- ---------Saturday------- -->
                                    <div class="day row col-12">
                                        <div class="col-12 form-group">
                                          <div class="checkbox row">
                                            <div class="col-1">
                                                
                                            {!! Form::label('schedule','Saturday' , ['class' => 'control-label']) !!}
                                            </div>
                                            <div class="col-1">
                                                {{ html()->label(
                                                        html()->checkbox('sat1',0)
                                                              ->class('switch-input')->value(1)
                                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                                    ->class('switch switch-sm switch-3d switch-primary')
                                                }}
                                                
                                            </div>
                                            </div>
                                        </div>
                                        <div class="display {{ old('sat1') == '1' ? ' ' : 'hide' }}  row col-12">
                          
                                            <div class="col-12 form-group">
         {!! Form::select('trial_sat_start_time[]', $every_30_minutes, old('trial_sat_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                                            </div> 
                                        </div>
                                    </div>
                    <!-- ---------/Saturday------- -->

                    <!-- ---------Sunday------- -->
                                    <div class="day row col-12">
                                        <div class="col-12 form-group">
                                          <div class="checkbox row">
                                            <div class="col-1">
                                            {!! Form::label('schedule','Sunday' , ['class' => 'control-label']) !!}
                                                
                                            </div>
                                            <div class="col-1">
                                                {{ html()->label(
                                                        html()->checkbox('sun1',0)
                                                              ->class('switch-input')->value(1)
                                                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                                    ->class('switch switch-sm switch-3d switch-primary')
                                                }}
                                                
                                            </div>
                                            </div>
                                        </div>
                                        <div class="display {{ old('sun1') == '1' ? ' ' : 'hide' }}  row col-12">
                                   
                                            <div class="col-12 form-group">
         {!! Form::select('trial_sun_start_time[]', $every_30_minutes, old('trial_sun_start_time'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                                            </div> 
                                        </div>
                                    </div>
                    <!-- ---------/Sunday------- -->



            </div>
<!-- /trial schedule -->

            <!-- <div class="row">
                <div class="col-md-6  form-group">
                    {!! Form::label('trial_price','Trial Price *' , ['class' => 'control-label']) !!}
                    {!! Form::text('trial_price', old('trial_price'), ['class' => 'form-control', 'placeholder' => 'Trial Price' ]) !!}
                </div>
                <div class="col-6 form-group">
                    {!! Form::label('subscription_duration','Subscription Duration *' , ['class' => 'control-label']) !!}
                            {!!  Form::select('sub_duration', array(  '0'=>'Unlimited', '1' => '1',
                            '2' => '2' , 
                            '3' => '3' , 
                            '4' => '4'
                            , '5' => '5'
                            ,'6' => '6'
                            , '7' => '7'
                            ,'8' => '8'
                            , '9' => '9'
                            ,'10' => '10'
                            , '11' => '11'
                            ,'12' => '12') , null, ['class' => 'form-control' , 'name'=>'sub_duration']   );  !!}
                </div>
            </div> -->
                @if(Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-12 form-group">
                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('published', 0) !!}
                            {!! Form::checkbox('published', 1, false, []) !!}
                            {!! Form::label('published',  trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('featured', 0) !!}
                            {!! Form::checkbox('featured', 1, false, []) !!}
                            {!! Form::label('featured',  trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('trending', 0) !!}
                            {!! Form::checkbox('trending', 1, false, []) !!}
                            {!! Form::label('trending',  trans('labels.backend.courses.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('popular', 0) !!}
                            {!! Form::checkbox('popular', 1, false, []) !!}
                            {!! Form::label('popular',  trans('labels.backend.courses.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('premium', 0) !!}
                            {!! Form::checkbox('premium', 1, false, []) !!}
                            {!! Form::label('premium',  'Premium Course' , ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('budget', 0) !!}
                            {!! Form::checkbox('budget', 1, false, []) !!}
                            {!! Form::label('budget',  'Budget Course' , ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

                    </div>
                </div>
               
                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('meta_title',trans('labels.backend.courses.fields.meta_title'), ['class' => 'control-label']) !!}
                        {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_title')]) !!}

                    </div>
                    <div class="col-12 form-group">
                        {!! Form::label('meta_description',trans('labels.backend.courses.fields.meta_description'), ['class' => 'control-label']) !!}
                        {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_description')]) !!}
                    </div>
                    <div class="col-12 form-group">
                        {!! Form::label('meta_keywords',trans('labels.backend.courses.fields.meta_keywords'), ['class' => 'control-label']) !!}
                        {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_keywords')]) !!}
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-12  text-center form-group">
                        

                        @if(Auth::user()->isAdmin())
                        {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                        @else
                        {!! Form::submit('Submit For Review', ['class' => 'btn btn-lg btn-danger']) !!}
                        @endif
                    </div>


                </div>
        </div>
    </div>
    {!! Form::close() !!}


@stop

@push('after-scripts')
<script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });
             // $('#start_date').datetimepicker();

            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.courses.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{trans('labels.backend.courses.select_teachers')}}",
            });
        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        })

        var citynames = new Bloodhound({
          datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          prefetch: {
            url: '{{ route('admin.tags.old') }}',
            filter: function(list) {
              return $.map(list, function(name) {
                return { name: name }; });
            }
          }
        });
        citynames.initialize();

        $('#tags').tagsinput({
          typeaheadjs: {
            name: 'name',
            displayKey: 'name',
            valueKey: 'name',
            source: citynames.ttAdapter()
          }
        });

        $('#duration').change(function(){

            $('.day input').val(null);

        })

        $('.timepicker').each(function(){
        	$(this).timepicker({
		    timeFormat: 'h:mm p',
		    interval: 30,
            minTime: '07:00am',
            maxTime: '10:00pm',
		    dynamic: false,
		    dropdown: true,
		    scrollbar: true,
		       change: function(time) {
            // the input field
            var element = $(this), text;
            var timepicker = element.timepicker();


            var duration =  $('#duration').val();
            console.log(duration);

            var timeElements = timepicker.format(time).split(":");    
            var theHour = parseInt(timeElements[0]);
            var min = timeElements[1].split(' ');
            var am_pm = min[1];
            var theMintute = timeElements[1];

            if (duration == '0.5 Hr') {

                var startTime = $(this).timepicker('getTime');
                var endtime = new Date(startTime.getTime() + 30*60000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});;   // add 30 minutes
            }
            if (duration == '1 Hr') {

                var startTime = $(this).timepicker('getTime');
                var endtime = new Date(startTime.getTime() + 60*60000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});;
            }
            if (duration == '1.5 Hrs') {

                var startTime = $(this).timepicker('getTime');
                var endtime = new Date(startTime.getTime() + 90*60000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});;
            }
            if (duration == '2 Hrs') {

                var startTime = $(this).timepicker('getTime');
                var endtime = new Date(startTime.getTime() + 120*60000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }

            // $('.mytime').text(newHour + ":" + theMintute);
            $(this).parents('.day').find(".end_time").val(endtime)
            // $('.r').text(newHour + ":" + theMintute);

       	 }
		});
        })
        $('.switch-input').change(function(){
		 $(this).parents('.day').children(".display").toggleClass('hide');
		 $(this).parents('.day').find(".form-control").val('');
		})
        
</script>

@endpush