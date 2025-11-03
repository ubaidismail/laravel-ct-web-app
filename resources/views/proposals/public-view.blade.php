<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal - {{ $proposal->prepared_for_customer_name ?? 'CloudTach' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('filament.resources.proposal-resource.pages.proposal-builder', ['proposal' => $proposal])
</body>
</html>
