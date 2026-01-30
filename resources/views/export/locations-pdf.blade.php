<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Locations</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
<h2>Locations</h2>
<table>
    <thead>
        <tr>
            <th>ID</th><th>Nama</th><th>NOP</th><th>Status</th><th>Lat</th><th>Lng</th><th>Alamat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->nama }}</td>
            <td>{{ $r->nop }}</td>
            <td>{{ $r->status }}</td>
            <td>{{ $r->latitude }}</td>
            <td>{{ $r->longitude }}</td>
            <td>{{ $r->address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
