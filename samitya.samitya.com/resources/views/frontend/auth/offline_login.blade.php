@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', app_name() . ' | Offline Login')


@push('after-styles')
    <style>
        .form-control{
            width: 87%;
            margin: 0 auto;
            padding: 11px 12px;
            height: auto;
        }
        .submit{
            background: linear-gradient(90deg, rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
            border: 1px solid rgb(244, 67, 54);
            border-radius: 20px;
            box-shadow: rgb(51, 51, 51) 0px 5px 5px -5px;
            padding: 8px 57px;
            color: #fff !important;
            font-weight: bold;
            margin-top: 40px;
            text-transform: uppercase;
        }
        .card-body{
            border-radius: 0px;
            padding: 60px 0px;
            background: none 0 0 repeat scroll #FFFFFF;
            display: inline-block;
            margin: 0 auto;
            width: 50%;
            -webkit-box-shadow: 0px 3px 30px 1px rgba(204,204,204,1);
            -moz-box-shadow: 0px 3px 30px 1px rgba(204,204,204,1);
            box-shadow: 0px 3px 30px 1px rgba(204,204,204,1);
            margin-top: 8%;
            margin-bottom: 8%;
        }
        .card-body .heading {
            text-align: center;
            color: #3d2d48;
            padding-bottom: 40px;
            font-weight: bold;
        }
        .form-control::placeholder{
            color:  #9d9fa0;
            } 
        .error-response{
            padding: 0px 40px;
        }
        @media (min-width: 320px) and (max-width: 768px) {
        .card-body {
            width: 90%;
            padding: 86px 0px;
            margin-top: 5%;
            margin-bottom: 5%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center align-items-center offlinelogin_wrapper">
        <div class="col col-sm-12 align-self-center" style="text-align: center;">
            <div class="card-body active" id="offline">
                <div class="heading">
                    <h2>STUDENT LOGIN</h2>
                </div>
                <!-- {{-- html()->form('POST', route('offline.login.post'))->id('offlinelogin')->open() --}} -->
                
                <form class="offlinelogin" id="offlinelogin" action="{{route('offline.login.post')}}"
                                  method="POST" enctype="multipart/form-data">
                                  {{ csrf_field() }}
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                {{ html()->text('s_id')
                                    ->class('form-control')
                                    ->placeholder('Student Number')
                                    ->required() }}
                            <span class="error-response text-danger"></span>
                            </div><!--form-group-->
                        </div><!--col-->
                    </div><!--row-->
                    <div class="row">
                        <div class="col">
                            <div class="form-group clearfix" style="text-align: center;">
                                <!-- {{-- form_submit(__('labels.frontend.auth.login_button')) --}} -->
                                <button class="btn  submit" type="submit" > Login </button>
                            </div><!--form-group-->
                        </div><!--col-->
                    </div><!--row-->
                </form>
                <!-- {{ html()->form()->close() }} -->
            </div><!--card body-->
        </div><!-- col-md-8 -->
    </div><!-- row -->
@endsection

@push('after-scripts')

<script>

    $('#offlinelogin').submit(function (e) {
                e.preventDefault();

                var $this = $(this);

                $.ajax({
                    type: $this.attr('method'),
                    url: $this.attr('action'),
                    data: $this.serializeArray(),
                    dataType: $this.data('type'),
                    success: function (response) {
                        if (response.success) {
                            $('#offline').find('span.error-response').html('')
                            $('#offlinelogin')[0].reset();
                                window.location.href = "{{route('offline.dashboard')}}"
                        }
                    },
                    error: function (jqXHR) {
                        var response = $.parseJSON(jqXHR.responseText);
                        if (response.message) {
                            $('#offline').find('span.error-response').html(response.message)
                        }
                    }
                });
            });
</script>

@endpush
