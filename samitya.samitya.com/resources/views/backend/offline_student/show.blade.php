@extends('backend.layouts.app')

@section('title','Student | '.app_name())



@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Profile
                </h4>
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4 mb-4">
            <div class="col">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-controls="overview" aria-expanded="true"><i class="fas fa-user"></i> @lang('labels.backend.access.users.tabs.titles.overview')</a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-expanded="true"><i class="fas fa-user"></i> Payments</a>
                    </li>
                </ul>
 
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane" id="overview" role="tabpanel" aria-labelledby="overview-tab" >
                        @include('backend.auth.user.show.tabs.student')
                    </div><!--tab-->
                    <div class="tab-pane show active" id="payment" role="tabpanel" aria-labelledby="payment-tab" >
                        @include('backend.auth.user.show.tabs.payment')
                    </div><!--tab-->
                </div><!--tab-content-->
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->

</div><!--card-->
@endsection

@push('after-scripts')
<script type="text/javascript">
    $(document).ready(function() {
    $('#table').DataTable();
} );
</script>

@endpush
