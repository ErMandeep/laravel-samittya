
@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.blog.title').' | '.app_name())

@push('after-styles')
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
    </style>
@endpush
@section('content')
	
	<div class="razorpay_wrapper">
 	<div class="razorpay_uppersec">
 		<h4>Pay securely by Credit Card/Debit Card/NetBanking</h4>
 		<img src="https://samitya.samitya.com/assets/images/razorpay_logo.png"> 
 	</div>
    <form action="https://samitya.samitya.com/cart/razorpay-payment" method="POST">
        <input type="hidden" name="sub_dur" value="eyJpdiI6Iis5ZFk0NHJ2UDBqdlBZeTZoZnhUanc9PSIsInZhbHVlIjoiOFNcL09SMlNvajlkOHk3N3lNc25iaXc9PSIsIm1hYyI6ImVhZjQ2NWEzMzdhODA1NTExNWYzZDBiMGM0MzcxYWZlOTdlNzg5MmMwZmYzMjdkNzQ1NzJiMzkxYjJkYzU1NjQifQ==">
        <!-- Note that the amount is in paise = 50 INR -->
        <!--amount need to be in paisa-->
      @php  $fees = auth('offline')->user()->fees*100   @endphp
        <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="rzp_test_p9XqmeYa783C82" data-amount="{{ $fees }}" data-buttontext="Pay Now" data-name="Samitya">
        </script>
        <!-- <input type="submit" name="">             -->
    </form>
</div>




@endsection





