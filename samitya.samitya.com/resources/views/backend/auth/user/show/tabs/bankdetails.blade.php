<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            

            <tr>
                <th>Account Number</th>
                <td>{{ $user->account_number }}</td>
            </tr>

            <tr>
                <th>IFSC Code</th>
                <td>{{ $user->ifsc_code }}</td>
            </tr>

            <tr>
                <th>Bank Name</th>
                <td>{!! $user->bank_name !!}</td>
            </tr>

            <tr>
                <th>Account Type</th>
                <td>{!! $user->account_type !!}</td>
            </tr>

            <tr>
                <th>UPI ID</th>
                <td>{{ $user->upi_id }}</td>
            </tr>


            <tr>
                <th>Mobile</th>
                <td>{{ $user->phone }}</td>
            </tr>
        </table>
    </div>
</div><!--table-responsive-->
