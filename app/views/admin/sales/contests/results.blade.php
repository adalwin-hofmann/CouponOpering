<table class="table table-striped">
    <thead>
      <tr>
        <th>Merchant Name</th>
        <th>Contest Display</th>
        <th>Contest Start Date</th>
        <th>Expiration Date</th>
        <th>Entry Count</th>
      </tr>
    </thead>

    <tbody id="offers">
    @foreach ($contests as $contest)
    <tr>
        <td>{{ $contest->merchant_name }}</td>
        <td>{{ $contest->display_name }}</td>
        <td>{{ date('m/d/Y', strtotime($contest->starts_at)) }}</td>
        <td>{{ date('m/d/Y', strtotime($contest->expires_at)) }}</td>
        <td>
            <a class="app_link btn"
                role="button"
                data-toggle="modal"
                style='cursor:pointer;'
                id="{{ $contest->id }}">
                {{ $contest->applicants }}
            </a>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@foreach($contests as $contest)
<!-- Modal Contents -->
<div id="contest_stats_{{ $contest->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Applicant Details</h3>
            </div>
            <div class="modal-body">
            <!-- gets populated by javascript -->
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Done</a>
            </div>
        </div>
    </div>
</div>
<!-- end modal conents -->
@endforeach