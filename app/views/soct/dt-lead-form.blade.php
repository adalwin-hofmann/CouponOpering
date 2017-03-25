<form method="POST" action="/post-dt-lead">
    <label>Year</label>
    <input type="text" name="year"><br>
    <label>Make</label>
    <input type="text" name="make"><br>
    <label>Model</label>
    <input type="text" name="model"><br>
    <label>First Name</label>
    <input type="text" name="first_name"><br>
    <label>Last Name</label>
    <input type="text" name="last_name"><br>
    <label>Email</label>
    <input type="text" name="email"><br>
    <label>Zipcode</label>
    <input type="text" name="zipcode"><br>
    <label>Directed Lead?</label>
    <select name="directed_to">
        <option value="">Not Directed</option>
        @foreach($franchises as $franchise)
        <option value="{{$franchise->netlms_id}}">{{$franchise->display}}</option>
        @endforeach
    </select><br>
    <button type="submit">Submit</button>
</form>