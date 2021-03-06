@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Live Classes | '.app_name())

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Live Classes For Review</h3>
            
        </div>

        <div class="card-body">
            <div class="table-responsive">
                


                <table id="myTable" class="table table-bordered table-striped  @if ( request('show_deleted') != 1 ) dt-select @endif ">
                    <thead>
                    <tr>

                        
                            @if ( request('show_deleted') != 1 )
                                <th style="text-align:center;"><input type="checkbox" class="mass" id="select-all"/></th>
                            @endif
                       

                        @if (Auth::user()->isAdmin())
                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.courses.fields.teachers')</th>
                                <th>E-mail</th>
                        @else
                                <th>@lang('labels.general.sr_no')</th>
                            @endif

                        <th>@lang('labels.backend.courses.fields.title')</th>
                        <th>@lang('labels.backend.courses.fields.category')</th>
                        <th>@lang('labels.backend.courses.fields.status')</th>
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
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            var route = '{{route('admin.liveclasses.reviewget_data')}}';


            @if(request('show_deleted') == 1)
                route = '{{route('admin.liveclasses.reviewget_data',['show_deleted' => 1])}}';
            @endif

            @if(request('teacher_id') != "")
                route = '{{route('admin.liveclasses.reviewget_data',['teacher_id' => request('teacher_id')])}}';
            @endif

            @if(request('cat_id') != "")
                route = '{{route('admin.liveclasses.reviewget_data',['cat_id' => request('cat_id')])}}';
            @endif

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4,5,6 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4,5,6 ]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                        @if(request('show_deleted') != 1)
                    { "data": function(data){
                        return '<input type="checkbox" class="single" name="id[]" value="'+ data.id +'" />';
                    }, "orderable": false, "searchable":false, "name":"id" },
                        @endif
                        @if (Auth::user()->isAdmin())
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},
                    {data: "teachers", name: 'teachers'},
                    {data:"email" , name:"E-mail"},

                    @else
                    {data: "DT_RowIndex", name: 'DT_RowIndex'},

                    @endif
                    {data: "title", name: 'title'},
                    {data: "category", name: 'category'},
                    // {data: "price", name: "price"},
                    {data: "status", name: "status"},
                    // {data: "lessons", name: "lessons"},
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
              $('.actions').html('<a href="' + '{{ route("admin.courses.mass_destroy") }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            {{--@can('course_delete')--}}
            {{--@if(request('show_deleted') != 1)--}}
           
            {{--@endif--}}
            {{--@endcan--}}
        });

    </script>
@endpush