
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
    padding: 20px;
    border-bottom: 1px solid #b5b5b5;
}       
.razorpay_wrapper .razorpay_uppersec h4 {
    font-size: 15px;
    display: inline-block;
}
.razorpay_wrapper form {
    text-align: right;
    padding: 20px;
}
.razorpay_wrapper form .razorpay-payment-button {
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


</style>
@endpush
@section('offline-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title d-inline">Payments</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Fees</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $n = 1;  @endphp
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $n }}</td>
                                            <td> @if($payment->temp_off) -  @else {{ $payment->fees }} @endif   </td>
                                            <td>{{ $payment->month }}</td>
                                            <td>{{ $payment->year }}</td>
                                            @if($payment->temp_off)
                                                <td> Temporary Leave </td>
                                                <td>N/A</td>
                                            @else
                                                <td> Paid </td>
                                                <td> 
                                                    <a class="btn btn-success" target="_blank" href="{{asset('storage/offline-invoices/'.$payment->invoice['name'])}}">
                                                    @lang('labels.backend.orders.view_invoice')
                                                    </a>
                                                    <a class="btn btn-primary" href="{{route('offline.download_invoice',['id'=>$payment->id])}}">
                                                    @lang('labels.backend.orders.download_invoice')
                                                    </a> 
                                                </td>
                                            @endif 
                                        </tr>
                                    @php $n++ @endphp
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--card-body-->
        </div><!--card-->
    </div><!--col-->


@endsection

@push('offline-after-scripts')

<script type="text/javascript">
        $(document).ready(function() {
        $('#table').DataTable();
    } );   
</script>

@endpush