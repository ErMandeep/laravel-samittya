@extends('backend.layouts.app')
@section('title', __('labels.backend.pages.title').' | '.app_name())

@push('after-styles')
    <style>
        .blog-detail-content p img{
            margin: 2px;
        }
        .label{
            margin-bottom: 5px;
            display: inline-block;
            border-radius: 0!important;
            font-size: 0.9em;
        }
        img {
     vertical-align: top; 
    border-style: none;
}
    </style>
@endpush

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0"> Gallery </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">

                        <tr>
                            <th>@lang('labels.backend.pages.fields.title')</th>
                            <td>{{ $page->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.pages.fields.slug')</th>
                            <td>{{ $page->slug }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.pages.fields.featured_image')</th>
                           <!--  <td>@if($page->image)<a href="{{ asset('storage/uploads/' . $page->name) }}" target="_blank"><img src="{{ asset('storage/uploads/' . $page->image) }}" height="100px"/></a>@endif</td> -->

                            <td>
                                
         
                
   @if(count($mediafiles) > 0)
                @foreach($mediafiles as $media)
                @if($media->type == 'video/mp4')

<video height="100px" controls>
<source src="{{ asset('storage/uploads/'.$media->name) }}" type="video/mp4">
<source src="{{ asset('storage/uploads/'.$media->name) }}" type="video/ogg">
</video>


                @else
             
<a href="{{ asset('storage/uploads/'. $media->name) }}" target="_blank"><img src="{{ asset('storage/uploads/'. $media->name) }}" height="100px"/></a>

                    
                @endif
                    @endforeach
                @endif
            



                            </td>


                        </tr>

                        <tr>
                            <th>@lang('labels.backend.pages.fields.content')</th>
                            <td>{!! $page->content !!}</td>
                        </tr>
                       
                        <tr>
                            <th>@lang('labels.backend.pages.fields.created_at')</th>
                            <td>{{ $page->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

            <!-- Tab panes -->


            <a href="{{ route('admin.gallary.index') }}"
               class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
        </div>
    </div>

@endsection

@push('after-scripts')
@endpush