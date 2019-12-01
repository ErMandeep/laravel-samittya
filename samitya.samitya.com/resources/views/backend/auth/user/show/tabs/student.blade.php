<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>Student Number</th>
                <td>{{ $user->student_id }}</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>{{ $user->email }}</td>
            </tr>

            <tr>
                <th>Joining Date</th>
                <td>{!! $user->joining_date !!}</td>
            </tr>

            <tr>
                <th>State</th>
                <td>{!! $user->state->name !!}</td>
            </tr>

            <tr>
                <th>City</th>
                <td>{{ $user->city->city }}</td>
            </tr>

            <tr>
                <th>Branch</th>
                <td>{{ $user->branch->branch }}</td>
            </tr>

            <tr>
                <th>Category</th>
                <td>{{ $user->category->name }}</td>
            </tr>

            <tr>
                <th>Fee Plan</th>
                <td>{{ $user->fee_plan }} Month</td>
            </tr>

            <tr>
                <th>Fees</th>
                <td>{{ $user->fees }}</td>
            </tr>

            <tr>
                @if(Auth::user()->isAdmin())
                    <th>Phone</th>
                    <td>{{ $user->phone_no }}</td>
                @endif
            </tr>

        </table>
    </div>
</div><!--table-responsive-->
