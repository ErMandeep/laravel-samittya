<div class="col">
    <div class="table-responsive">
        <table class="table table-hover" id="table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Fees</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Fee Plan</th>
                    <th>Status</th>
                    <th>Paid at</th>
                </tr>
            </thead>
            <tbody>
                @php $n = 1;  @endphp
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $n }}</td>
<!--                         <td> @if($payment->status) -  @else {{ $payment->fees }} @endif   </td> -->
                        <td> {{ $payment->status == 2 ? '-' : $payment->fees }}</td>
                        <td>{{ $date }}  {{ $payment->month }}</td>
                        <td> {{ $date }} {{ $payment->expire_on }}</td>
                        <td>{{ $payment->fee_plan }} Month</td>
                        @if($payment->status == 2 )
                            <td> Temporary Leave </td>
                            <td> - </td>
                        @elseif($payment->status == 0)
                            <td> Pending </td>
                            <td> - </td>
                        @else
                            <td> Paid </td>
                            <td> {{ $payment->paid_at }} </td>
                        @endif
                    </tr>
                @php $n++ @endphp
                @endforeach
            </tbody>

        </table>
    </div>
</div><!--table-responsive-->
