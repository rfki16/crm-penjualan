<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice #{{ $sale->id }}</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f2f2f2; }

.container { max-width:900px; }

@media print {
    body { background:white; }
    .no-print, .btn, nav, .sidebar { display:none !important; }
}
</style>
</head>
<body>

<div class="container my-4">
    @yield('content')
</div>

</body>
</html>