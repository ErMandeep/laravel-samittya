@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/amigo-sorter/css/theme-default.css')}}">
    <style>
        ul.sorter > span {
            display: inline-block;
            width: 100%;
            height: 100%;
            background: #f5f5f5;
            color: #333333;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 0px;
        }

        ul.sorter li > span .title {
            padding-left: 15px;
            width: 70%;
        }

        ul.sorter li > span .btn {
            width: 20%;
        }

        @media screen and (max-width: 768px) {

            ul.sorter li > span .btn {
                width: 30%;
            }

            ul.sorter li > span .title {
                padding-left: 15px;
                width: 70%;
                float: left;
                margin: 0 !important;
            }

        }


    </style>
@endpush

@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="page-title mb-0">@lang('labels.backend.courses.title')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.courses.fields.teachers')</th>
                            <td>
                                @foreach ($course->teachers as $singleTeachers)
                                    <span class="label label-info label-many">{{ $singleTeachers->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.title')</th>
                            <td>
                                @if($course->published == 1)
                                    <a target="_blank"
                                       href="{{ route('courses.show', [$course->slug]) }}">{{ $course->title }}</a>
                                @else
                                    {{ $course->title }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.slug')</th>
                            <td>{{ $course->slug }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.category')</th>
                            <td>{{ $course->category->name }}</td>
                        </tr>
                        <tr>
                            <th>Detail</th>
                            <td>{!! $course->description !!}</td>
                        </tr>
                        <tr>
                            <th>Requirement</th>
                            <td>{!! $course->requirement !!}</td>
                        </tr>
                        <tr>
                            <th>Skill Levels</th>
                            <td>{!! $course->skills_level !!}</td>
                        </tr>
                        <tr>
                            <th>Language</th>
                            <td>{!! $course->language_used !!}</td>
                        </tr>
                        <tr>
                            <th>Student Per batch</th>
                            <td>{!! $course->per_batch !!}</td>
                        </tr>
                        <!-- <tr>
                            <th>@lang('labels.backend.courses.fields.price')</th>
                            <td>{{ $course->price.' '.$appCurrency['symbol'] }}</td>
                        </tr> -->
                        <tr>
                            <th>@lang('labels.backend.courses.fields.course_image')</th>
                            <td>@if($course->course_image)<a
                                        href="{{ asset('storage/uploads/' . $course->course_image) }}"
                                        target="_blank"><img
                                            src="{{ asset('storage/uploads/' . $course->course_image) }}"
                                            height="50px"/></a>@endif</td>
                        </tr>
                        <!-- <tr>
                            <th>@lang('labels.backend.courses.fields.start_date')</th>
                            <td>{{ $course->start_date }}</td>
                        </tr> -->
                        <tr>
                            <th>@lang('labels.backend.courses.fields.published')</th>
                            <td>{{ Form::checkbox("published", 1, $course->published == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>
                        
                        <tr>
                            <th>Avg.Price</th>
                            <td>
                                {{ $appCurrency['symbol'].' '.$course->avg_price }}
                            </td>
                        </tr>
                        @if(auth()->user()->hasRole('administrator'))
                        <tr>
                            <th>@lang('labels.backend.courses.fields.sub_plan_1')</th>
                            <td>
                            @if(count($course->sub_price_1) > 0 )
                            {{ $appCurrency['symbol'].' '.$course->sub_price_1 }} For {{ $course->sub_month_1 }} Months
                            @else
                            <p>No Plan</p>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.sub_plan_2')</th>
                            <td>
                            @if(count($course->sub_price_2) > 0 )
                            {{ $appCurrency['symbol'].' '.$course->sub_price_2 }} For {{ $course->sub_month_2 }} Months
                            @else
                            <p>No Plan</p>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.sub_plan_3')</th>
                            <td>
                            @if(count($course->sub_price_3) > 0 )
                            {{ $appCurrency['symbol'].' '.$course->sub_price_3 }} For {{ $course->sub_month_3 }} Months
                            @else
                            <p>No Plan</p>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.meta_title')</th>
                            <td>{{ $course->meta_title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.meta_description')</th>
                            <td>{{ $course->meta_description }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.courses.fields.meta_keywords')</th>
                            <td>{{ $course->meta_keywords }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div><!-- Nav tabs -->

            
        </div>
    </div>
@stop

@push('after-scripts')
    <script src="{{asset('plugins/amigo-sorter/js/amigo-sorter.min.js')}}"></script>
    <script>
        $(function () {
            $('ul.sorter').amigoSorter({
                li_helper: "li_helper",
                li_empty: "empty",
            });
            $(document).on('click', '#save_timeline', function (e) {
                e.preventDefault();
                var list = [];
                $('ul.sorter li').each(function (key, value) {
                    key++;
                    var val = $(value).find('span').data('id');
                    list.push({id: val, sequence: key});
                });

                $.ajax({
                    method: 'POST',
                    url: "{{route('admin.courses.saveSequence')}}",
                    data: {
                        _token: '{{csrf_token()}}',
                        list: list
                    }
                }).done(function () {
                    location.reload();
                });
            })
        });

    </script>
@endpush