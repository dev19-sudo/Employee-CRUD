<p></p>
<h2 style="text-align:center;">Student Report</h2>
<p>Date: <b>{{ $date }}</b></p>

<table border="1" cellpadding="6">
  <thead>
    <tr style="font-weight:bold;">
      <th width="15%">Roll No.</th>
      <th width="45%">Name</th>
      <th width="20%">Marks</th>
      <th width="20%">DOB</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($rows as $r)
      <tr>
        <td width="15%">{{ $r['rollno'] }}</td>
        <td width="45%">{{ $r['name'] }}</td>
        <td width="20%">{{ $r['marks'] }}</td>
        <td width="20%">{{ $r['dob'] }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
