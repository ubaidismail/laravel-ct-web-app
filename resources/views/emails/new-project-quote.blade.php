<div>
    {{-- img --}}
    <div style="text-align: center;"><img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto;"></div>

    <p>Dear Admin, You have received a project quote </p>
    <p>Project Name: {{ $project['project_name'] }}</p>
    <p>Project Desc: {{ $project['project_description'] }}</p>
    <p>Project Budget: {{ $project['budget'] }}</p>
    <p>Customer Name: {{ $project['company_name'] }}</p>
    <p>Customer Email: {{ $project['email'] }}</p>
    <p>Customer Phone: {{ $project['phone'] }}</p>
    <p>Country: {{ $project['country'] }}</p>

    <p>Best regards,</p>
    <p>The {{ config('app.name') }} Team</p>
</div>