@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
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
     
        .nopadding1 {
   padding-left: 15px !important;
   padding-right: 0 !important;
}
        .nopadding2 {
   padding: 0 !important;
   /*margin: 0 !important;*/
   margin-top: 8px !important;
}
    </style>
@endpush

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">@lang('labels.backend.courses.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.courses.index') }}"
                   class="btn btn-success">@lang('labels.backend.courses.view')</a>
            </div>
        </div>

        <div class="card-body">
            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-10 form-group">
                        {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                        {!! Form::select('teachers[]', $teachers, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple']) !!}
                    </div>
                    <div class="col-2 d-flex form-group flex-column">
                        OR <a target="_blank" class="btn btn-primary mt-auto"
                              href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-10 form-group">
                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false]) !!}
                </div>
                <div class="col-2 d-flex form-group flex-column">
                    OR <a target="_blank" class="btn btn-primary mt-auto"
                          href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title'), 'required' => false]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',  trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}

                </div>
            </div>
            <div class="row">

                <div class="col-12 form-group">
                    {!! Form::label('description',  trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description')]) !!}

                </div>
                     <div class="col-12 form-group">
                    {!! Form::label('requirement',  'Requirement', ['class' => 'control-label']) !!}
                    {!! Form::textarea('requirement', old('requirement'), ['class' => 'form-control ', 'placeholder' => 'Requirement' , 'rows' => '3' ])  !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('price',  trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'), 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('course_image',  trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label']) !!}
                    {!! Form::file('course_image',  ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('course_image_max_size', 8) !!}
                    {!! Form::hidden('course_image_max_width', 4000) !!}
                    {!! Form::hidden('course_image_max_height', 4000) !!}

                </div>
                <div class="col-12 col-lg-4  form-group">
                    {!! Form::label('start_date', trans('labels.backend.courses.fields.start_date').' (yyyy-mm-dd)', ['class' => 'control-label']) !!}
                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date','pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.courses.fields.start_date').' (Ex . 2019-01-01)', 'autocomplete' => 'off']) !!}

                </div>
            </div>
<!-- ******* -->
         @if(Auth::user()->isAdmin())
         <div class="row">
            <div class="col-12 col-lg-12 form-group">
                <h5> Free </h5>
            </div>
            <div class="col-1">
                {{ html()->label(
                        html()->checkbox('free', @$course['free'])
                              ->class('switch-input')->value(1)
                        . '<span class="switch-label"></span><span class="switch-handle"></span>')
                    ->class('switch switch-sm switch-3d switch-primary')
                }}
                
            </div>
         </div>

<div class="free">
         <div class="row">
            <div class="col-12 col-lg-12 form-group">
                <h5> @lang('labels.backend.courses.fields.sub_plan_1')</h5>
            </div>
         </div>
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('sub_price_1', trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('sub_price_1', old('sub_price_1'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price')]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">

               {!! Form::label('sub_month_1','Subscription Duration *' , ['class' => 'control-label']) !!}
                {!!  Form::select('sub_month_1', array(  '0'=>'Unlimited', '1' => '1 Month',
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

         <div class="row">
            <div class="col-12 col-lg-12 form-group">
                <h5> @lang('labels.backend.courses.fields.sub_plan_2')</h5>
            </div>
         </div>
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('sub_price_2', trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('sub_price_2', old('sub_price_2'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price')]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">

               {!! Form::label('sub_month_2','Subscription Duration *' , ['class' => 'control-label']) !!}
                {!!  Form::select('sub_month_2', array(  '0'=>'Unlimited', '1' => '1 Month',
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
         <div class="row">
            <div class="col-12 col-lg-12 form-group">
                <h5> @lang('labels.backend.courses.fields.sub_plan_3')</h5>
            </div>
         </div>
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('sub_price_3', trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('sub_price_3', old('sub_price_3'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price')]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">

               {!! Form::label('sub_month_3','Subscription Duration *' , ['class' => 'control-label']) !!}
                {!!  Form::select('sub_month_3', array(  '0'=>'Unlimited', '1' => '1 Month',
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

            </div>     </div>                

@endif
<!-- ******* -->            
            <div class="row">
              <!--   <div class="col-4 form-group">
                    {!! Form::label('duration','Duration' , ['class' => 'control-label']) !!}
                    {!! Form::text('display_duration', old('display_duration'), ['class' => 'form-control', 'placeholder' => 'Display duration info' ]) !!}

                </div> -->
                <div class="col-2 nopadding1">
                    {!! Form::label('duration','Duration' , ['class' => 'control-label']) !!}
                    {!! Form::number('display_duration1', old('display_duration1'), ['class' => 'form-control', 'placeholder' => 'Display duration info' ]) !!} 

                </div> 

                <div class="col-2 nopadding2 form-group">
                    {!! Form::label('duration', '       ' , ['class' => 'control-label']) !!}
                    {!!  Form::select('display_duration2',   array( 
                            'minute' => 'Minute(S)', 
                            'hour' => 'Hour(S)',
                            ),
                             null, ['class' => 'form-control' , 'name'=>'display_duration2']   );  !!}   

                </div>
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
                    {!! Form::label('language','Languages' , ['class' => 'control-label']) !!}
                    {!! Form::text('language_used', old('language_used'), ['class' => 'form-control', 'placeholder' => "Language's used for studying" ]) !!}

                </div>
                <div class="col-md-12 form-group">
                    {!! Form::label('tags','Course Tags' , ['class' => 'control-label']) !!}
                    {!! Form::text('tags', old('tags'), ['class' => 'form-control','data-role' => 'tagsinput', 'placeholder' => trans('labels.backend.blogs.fields.tags_placeholder'),'id'=>'tags']) !!}

                </div>
                <div class="col-12 form-group">
                    {!! Form::label('media','Media Intro' , ['class' => 'control-label']) !!}
                   {!! Form::text('videolink', old('videolink'), ['class' => 'form-control', 'placeholder' => 'Add Vimeo link' ]) !!}

                </div>
            </div>
            <div class="row">
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
            </div>

            <div class="row">
                <div class="col-12 form-group">
                     {!! Form::label('downloadable_files', trans('labels.backend.courses.fields.downloadable_files'), ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png,application/msword,audio/mpeg,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/pdf,video/mp4']) !!}
                    {!! Form::file('downloadable_files', ['class' => 'form-control']) !!}
                    {!! Form::hidden('downloadable_files', 8) !!}
                    {!! Form::hidden('downloadable_files', 4000) !!}
                    {!! Form::hidden('downloadable_files', 4000) !!}
           
                </div>
            </div>

        @if(!Auth::user()->isAdmin())
                   {{ Form::hidden('reviewchanges', 0, array('id' => 'reviewchanges_id')) }}

        @endif

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
            @endif





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

            <div class="row">
                <div class="col-12  text-center form-group">



                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}


@stop

@push('after-scripts')
<script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
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


        $('.switch-input').change(function(){
            console.log("dsfsdf");
           $(".free").toggleClass('hide');
         // $(this).parents('.free').find(".form-control").val('');
        })

    </script>

@endpush