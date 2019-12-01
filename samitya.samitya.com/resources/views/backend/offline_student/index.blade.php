@extends('backend.layouts.app')

@section('title', 'Offline Students | '.app_name())


@push('after-styles')
<style type="text/css">
   
    .filter_wrapper {
        padding: 25px;
        box-shadow: 0px 0px 10px -1px #333;
    }
    .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 0;
    border-radius: 0px;
    }
    .filter_wrapper .control-label{
        font-size: 16px;
        font-weight: 700;
    }
    .select2-selection__rendered {
    display: block;
    width: 100%;
    height: calc(2.0625rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5 !important;
    color: #5c6873 !important;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #e4e7ea;
    }
</style>
@endpush

@section('content')


    <div class="card">

        <div class="card-header">

                <h3 class="page-title d-inline">Offline Students</h3>
                <div class="float-right">
                    <a href="{{ route('admin.offline-student.create') }}"
                       class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
                </div>
        </div>
        <div class="filter_wrapper">
            <div class="row">
                @if(Auth::user()->isAdmin())
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="state">
                            {!! Form::label('state', 'State', ['class' => 'control-label']) !!}
                           {!! Form::select('state', $states, (request('state')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'state']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        {!! Form::label('city', 'City', ['class' => 'control-label']) !!}
                           {!! Form::select('city', $city,  (request('city')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'city']) !!}
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        {!! Form::label('branch', 'Branch', ['class' => 'control-label']) !!}
                           {!! Form::select('branch', $branch,  (request('branch')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'branch']) !!}
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        {!! Form::label('category', 'Category', ['class' => 'control-label']) !!}
                           {!! Form::select('category', $category,  (request('category')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'category']) !!}
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                           {!! Form::select('status', $status,  (request('status')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'status']) !!}
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        {!! Form::label('Fees', 'Fees', ['class' => 'control-label']) !!}
                           {!! Form::select('fees', $fees,  (request('fees')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'fees']) !!}
                    </div>
                @else
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        {!! Form::label('category', 'Category', ['class' => 'control-label']) !!}
                           {!! Form::select('category', $category,  (request('category')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'category']) !!}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                           {!! Form::select('status', $status,  (request('status')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'status']) !!}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        {!! Form::label('Fees', 'Fees', ['class' => 'control-label']) !!}
                           {!! Form::select('fees', $fees,  (request('fees')) , ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'fees']) !!}
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        @if(Auth::user()->isAdmin())
                        <div class="d-block">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.offline-student.index') }}"
                                       style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                                </li>
                                |
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.offline-student.index') }}?show_deleted=1"
                                       style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                                </li>
                            </ul>
                        </div>
                        @endif
                        <table id="myTable"
                               class="table table-bordered table-striped dt-select">
                            <thead>
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>Student Number</th>
                                <th>@lang('labels.backend.tags.fields.name')</th>
                                <th>E-mail</th>
                                @if(Auth::user()->isAdmin())
                                <th>Phone Number</th>
                                @endif
                                <th>Fee Plan</th>
                                <th>Fees</th>
                                <th>Category</th>
                                <th>Payment For This Month</th>
                                <th>Status</th>
                                @if( request('show_deleted') == 1 )
                                    <th>&nbsp; @lang('strings.backend.general.actions')</th>
                                @else
                                    <th>&nbsp; @lang('strings.backend.general.actions')</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            var route = '{!! route("admin.offline-student.get_data") !!}';

            @if(request('show_deleted') == 1 && Auth::user()->isAdmin())
                route = '{!! route("admin.offline-student.get_data",['show_deleted' => 1]) !!}';
            @endif

            // filter(route);

            $('#state').select2({
             placeholder: "State",
              allowClear: true,
            });
            $('#city').select2({
              placeholder: "City",
               allowClear: true,
            });
            $('#branch').select2({
              placeholder: "Branch",
               allowClear: true,
            });
            $('#category').select2({
              placeholder: "Category",
               allowClear: true,
            });
            $('#status').select2({
              placeholder: "Status",
              minimumResultsForSearch: -1,
               allowClear: true,
            });
            $('#fees').select2({
              placeholder: "Fees",
              minimumResultsForSearch: -1,
               allowClear: true,
            });

            $('#state').change(function(){
            var state = $(this).val();

            $.ajax({
              type: "POST",
              url: "{{ route('admin.get_cities') }}",
              cache: true,
              data:  {
                "_token": "{{ csrf_token() }}",
                "id": state,
            },

              success: function(result){

                 $("#city").html('').select2({data:  [
                        {
                          "id": "",
                          "text": ""
                        }, ]    });
                 $("#city").select2({ placeholder: "City" , allowClear: true, data:  result  });
                 $("#branch").html('').select2({placeholder: "Branch"});

                }
                });
            })

            $('#city').change(function(){

            var city = $(this).val();      

            $.ajax({
              type: "POST",
              url: "{{ route('admin.get_branches') }}",
              cache: true,
              data:  {
                "_token": "{{ csrf_token() }}",
                "id": city,
            },

              success: function(result){
                 $("#branch").html('').select2({data:  [
                        {
                          "id": "",
                          "text": ""
                        }, ]    });
                 $("#branch").select2({placeholder: "Branch" , allowClear: true, data:  result});    
          
                }
                });
            })
            var id  =  [ 'state','city','branch','status' , 'category' ,'fees' ];

            $.each(id, (index, item) => {
                
                $('#'+item).change(function(){

                    var url = window.location.href;
                    // var hash = location.hash;
                    // url = url.replace(hash, '');
                    var paramName =  item;
                    var parameter =  item;
                    var paramValue = $('#'+item).val();

                    if (url.indexOf(paramName + "=") >= 0)
                    {
                        var prefix = url.substring(0, url.indexOf(paramName + "=")); 
                        var suffix = url.substring(url.indexOf(paramName + "="));
                        suffix = suffix.substring(suffix.indexOf("=") + 1);
                        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";

                        if (paramValue === ''  ) {
                            
                          var newurl =  removeURLParameter(url,paramName) 
                            url = newurl;
                        }
                        else{
                            url = prefix + paramName + "=" + paramValue + suffix;
                        }

                    }
                    else
                    {
                    if (url.indexOf("?") < 0 ) {
                            url += "?" + paramName + "=" + paramValue;
                    }
                    else{
                        url += "&" + paramName + "=" + paramValue;
                    }
                }

                    window.history.pushState("data","Title",url);

                     table.ajax.reload( null, false );
                })

                function removeURLParameter(url, parameter) {
                    //prefer to use l.search if you have a location/link object
                    var urlparts = url.split('?');   
                    if (urlparts.length >= 2) {

                        var prefix = encodeURIComponent(parameter) + '=';
                        var pars = urlparts[1].split(/[&;]/g);

                        //reverse iteration as may be destructive
                        for (var i = pars.length; i-- > 0;) {    
                            //idiom for string.startsWith
                            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                                pars.splice(i, 1);
                            }
                        }

                        return urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
                    }
                    return url;
                }
        
            });

              var table =  $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ]
                        }
                    },
                    'colvis'
                ],
                'ajax': {
                    'url':route,
                   'data': function(data){
                      // Read values
                      data.state = $('#state').val();
                      data.city = $('#city').val();
                      data.branch = $('#branch').val();
                      data.category = $('#category').val(); 
                      data.status = $('#status').val();
                      data.fees = $('#fees').val();
                   }
                },

                columns: [
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "student_id", name: 'Student Number'},
                    {data: "name", name: 'name'},
                    {data: "email", name: 'email'},
                    @if(Auth::user()->isAdmin())
                    {data: "phone_no", name: 'phone_no'},
                    @endif
                    {data: "fee_plan", name: 'fee_plan'},
                    {data: "fees", name: 'fees'},
                    {data: "category", name: 'category'},
                    {data: "payment", name: 'payment'},
                    {data: "status", name: 'status'},
                    {data: "actions", name: "actions"}
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });


            

            $('.actions').html('<a href="' + '{{ route('admin.categories.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');


        });
    
        
        

    </script>

@endpush