
@extends('backend.offline_student.dashboard')
@section('title', ' Dashboard | '.app_name())

@push('offline-after-styles')
    <style>
.couse-pagination li.active {
    color: #333333!important;
    font-weight: 700;
}
.page-link {
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #c7c7c7;
    background-color: white;
    border: none;
}
.page-item.active .page-link {
    z-index: 1;
    color: #333333;
    background-color:white;
    border:none;

}
ul.pagination{
    display: inline;
    text-align: center;
}
.cat-item.active{
    background: black;
    color: white;
    font-weight: bold;
}
.razorpay_wrapper .razorpay_uppersec {
       padding: 5px;
}       
.razorpay_wrapper .razorpay_uppersec h4 {
    font-size: 15px;
    display: inline-block;
}
.razorpay_wrapper form {
        padding: 0px;
    text-align: center;
}
form .razorpay-payment-button {
    text-transform: uppercase;
    font-size: 15px;
    font-weight: 700;
    line-height: 30px;
    padding: 5px 25px;
    border: 0;
    color: #fff;
    cursor: pointer;
    border-radius: 25px;
    background: linear-gradient(90deg ,rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
}
.note_wrapper {
    padding: 80px 0px;
}
.note_wrapper .note {
    text-align: center;
    color: #3d2d48;
    font-weight: bold;
}
.razorpay_wrapper {
    padding: 15px 20px;
        box-shadow: 0px 0px 10px -1px #333;
    margin: 15px 0px;
}
.current_payment{
    margin-top: 50px;
}
.razorpay_wrapper th{
    color: #3d2d48;
}
.clear{
	clear: both;
}
.float-left{
	float: left;
}
.float-right{
	float: right;
}
.month_heading{
	padding: 8px 0px;
    color: #3d2d48;
}


</style>
@endpush
@section('offline-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                        <strong>@lang('strings.backend.dashboard.welcome') {{ auth('offline')->user()->name }}!</strong>
                </div><!--card-header-->
                <div class="card-body"> 
                    @if($pending_payments && $pending_payments->count() > 0)
                        <h2>Pending Fees</h2>
                        @foreach($pending_payments as $payment)

                        <div class="razorpay_wrapper">
                                <div class="details_wrapper table-responsive">
                                	<h4 class="month_heading">Month: {{$payment->month}} - {{ date('F Y' , strtotime($payment->fee_plan.'Month' , strtotime($payment->month))) }} </h4>
                                   <table class="table table-bordered ">
                                        <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Plan</th>
                                            <th>Valid Upto</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $offline_logged_in->category->name }}</td>
                                                <td>{{ $payment->fee_plan }} Month</td>
                                                <td>{{ $date .' '. date('F Y' , strtotime($payment->fee_plan.'Month' , strtotime($payment->month))) }}</td>
                                                <td>₹ {{ $payment->fees }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="razorpay_uppersec">
                                	<div class="col-sm-10 float-left">
                                		<h4>Pay securely by Credit Card/Debit Card/NetBanking</h4>
                                    	<img src="{{ asset('assets/images/razorpay_logo.png') }}">	
                                	</div>
                                	<div class="col-sm-2 float-right">
                                		{{ Form::open(array('url' => route('offline.pending_payment' ,[$payment->id] ), 'method' => 'put')) }}
		                                  @php  $fees = $payment->fees*100   @endphp
		                                    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ config('services.razorpay.key') }}" data-amount="{{ $fees }}" data-buttontext="Pay Now" data-name="Samitya">
		                                    </script>
		                                    <input type="hidden" name="_token" value="{!!csrf_token()!!}">
		                                {{ Form::close() }}
                                	</div>
                                	<div class="clear"></div>
                                </div>
                                
                            </div>
                        @endforeach
                    @endif

                    @if($offline_logged_in->paid_this_month == 0 && $offline_logged_in->temp_off != 1)   
                            <div class="razorpay_wrapper">
                                <div class="details_wrapper">
                                	<h4 class="month_heading">Month: {{$month}} - {{ date('F Y' , strtotime($offline_logged_in->fee_plan.'Month' , strtotime($month))) }} </h4>
                                   <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Plan</th>
                                            <th>Valid Upto</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $offline_logged_in->category->name }}</td>
                                                <td>{{ $offline_logged_in->fee_plan }} Month</td>
                                                <td>{{ $date .' '. date('F Y' , strtotime($offline_logged_in->fee_plan.'Month' , strtotime($month))) }}</td>
                                                <td>₹ {{ $offline_logged_in->fees }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="razorpay_uppersec">
                                	<div class="col-sm-10 float-left">
	                                    <h4>Pay securely by Credit Card/Debit Card/NetBanking</h4>
	                                    <img src="{{ asset('assets/images/razorpay_logo.png') }}"> 
                                	</div>
                                	<div class="col-sm-2 float-right">
                                		<form action="{{ route('offline.payment') }}" method="POST">
		                                  @php  $fees = auth('offline')->user()->fees*100   @endphp
		                                    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ config('services.razorpay.key') }}" data-amount="{{ $fees }}" data-buttontext="Pay Now" data-name="Samitya">
		                                    </script>
		                                    <input type="hidden" name="_token" value="{!!csrf_token()!!}">
		                                </form>
                                	</div>
                                	<div class="clear"></div>
                                </div>
                            </div>
                    @elseif($offline_logged_in->paid_this_month)
                       <h5>Your Plan  will expire on {{ $date }} {{ $old_payment->expire_on }} </h5> 
                    @elseif($offline_logged_in->temp_off == 1)
                        <h5>You are Temporary on leave , Contact your Supervisor to continue.</h5>
                    @endif
                </div>

              <!--   <div class="note_wrapper">
                    @if(date('d') > 5 )
                    <h3 class="note">You Paid For @php echo date('F') @endphp Month</h3>
                    @else
                    <h3 class="note">You Paid For @php echo date('F', strtotime("-1 month")) @endphp Month</h3>
                    @endif
                </div> -->

            </div><!--card-body-->

            

        </div><!--card-->
    </div><!--col-->


@endsection

@push('offline-after-scripts')

    <script type="text/javascript">
       $('form').submit(function () {
            setTimeout(function() {
             $('.razorpay-payment-button').removeAttr("disabled") 
                return true;
        }, 1000);
        });    
    </script>

@endpush