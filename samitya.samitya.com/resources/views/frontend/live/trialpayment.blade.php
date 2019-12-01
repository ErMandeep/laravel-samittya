@extends('frontend.layouts.app'.config('theme_layout'))


@push('after-styles')

@endpush
<style type="text/css">
  .teacher_desc_wrapper .t_img{
    background-color: #eee;
    width: 60px;
    height: 60px;
    border-radius: 60px;
    background-size: contain;
  }
  #trial_payment .container{
        padding: 80px 0px;
  }
  #trial_payment{
    background: #eee;
  }
  .payment_wrapper {
    background-color: #fff;
    border-radius: 4px;
    position: relative;
    word-break: break-word;
    overflow-wrap: break-word;
    padding: 20px 5px;
}
.teacher_desc_wrapper .col-md-1 {
    float: left;
}
.time_desc_wrapper {
    padding: 15px;
    border-bottom: 1px solid #e3e5e6;
    border-top: 1px solid #e3e5e6;
}
.time_desc_wrapper .text {
    color: #6f757b;
    margin-bottom: 2px;
}
.time_desc_wrapper  .date_time {
    font-size: 20px;
    margin-bottom: 0px;
}
.time_desc_wrapper  .timezone {
    color: #6f757b;
    margin-bottom: 2px;
}
.total_wrapper {
  padding-top: 15px;
}
.total_wrapper .total {
    float: left;
    font-size: 25px;

}
.total_wrapper .price{
    float: right;
    text-align: right;
    font-size: 25px;
    font-weight: 700;
    color: #3d2d48;
}
.razorpay_wrapper {
    margin-top: 50px;
}
</style>


@section('content')

@if(config('services.razorpay.active') == 1)



<div class="row" id="trial_payment">
  <div class="container">
    <div class="col-md-12 payment_wrapper">
      <div class="teacher_desc_wrapper">
        <div class="col-md-1">
          <div class="t_img" style='background-image: url("{{ $teacher->picture }}")'>
          </div>
        </div>
        <div class="col-md-6">
          <h3 class="t_name">{{ $teacher->full_name }}</h3>
          <i class="fas fa-book-open"></i>
          <span class="course_title">{{ $course->title }}</span>          
        </div>
      </div>

      <div class="time_desc_wrapper">
          <p class="text">Your trial lesson is scheduled on</p>
          <p class="date_time">{{ $day.', '.$date.', '.$time }}</p>
          <p class="timezone">UTC+5:30</p>
      </div>

      <div class="total_wrapper">
        <p class="total col-sm-6" >Total</p>
        <p class="price col-sm-6"> {{ $course->trial_price == '' ? 'Free' : $appCurrency["symbol"].' '.$course->trial_price  }}  </p>
        <div class="clearfix"></div>
        
      </div>

      <div>
        
      </div>
      
    </div>

      <div class="razorpay_wrapper">
        <div class="razorpay_uppersec">
          <h4>Pay securely by Credit Card/Debit Card/NetBanking</h4>
          <img src="{{ asset('assets/images/razorpay_logo.png') }}"> 
        </div>
          <form action="{{ route('trialconfirm') }}" method="POST" >
             <input type="hidden" name="_token" value="{!!csrf_token()!!}"> 
             <input type="hidden" name="course_id" value="{{ $course->id }}">

              @if($course->trial_price)
              @php   $amount =  $course->trial_price*100    @endphp
              <script src="https://checkout.razorpay.com/v1/checkout.js"
                      data-key="{{ config('services.razorpay.key') }}"
                      data-amount="{{ $amount }}"
                      data-buttontext="Pay Now"
                      data-name="Samitya">
              </script>
              @else
              <input type="submit" value="Confirm Booking" class="razorpay-payment-button">
              @endif
              
          </form>

      </div>

  </div>
</div>
  

@endif



@endsection


@push('after-scripts')


@endpush