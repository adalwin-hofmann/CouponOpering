<a href="/reports">BACK</a><br/>
<a href="/reports/active-members/7">7 Days</a> | <a href="/reports/active-members/30">30 Days</a> | <a href="/reports/active-members/60">60 Days</a> | <a href="/reports/active-members/90">90 Days</a>
<h3>Total Active Members (Last {{$days}} Days): {{$totals['total']}}</h3>
<table style="table-layout: fixed; width: 450px;">
    <thead>
        <tr>
            <th>Activity</th>
            <th>Users Doing Activity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($totals['types'] as $type => $count)
        <tr>
            <td style="text-align: center; width: 150px;">{{$type}}</td>
            <td style="text-align: center; width: 150px;">{{$count}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

