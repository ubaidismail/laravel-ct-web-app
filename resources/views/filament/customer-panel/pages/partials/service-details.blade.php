<div class="space-y-2">
    <p><strong>Service:</strong> {{ $record->service_name }}</p>
    <p><strong>Description:</strong> {{ $record->service_description }}</p>
    <p><strong>Price:</strong> {{ '$'.  $record->service_price }}</p>
    <p><strong>Duration:</strong> {{  $record->service_duration }}</p>
    <p><strong>Billing Cycle:</strong> {{ ucfirst($record->recurring_interval )}}</p>
    <p><strong>Starts At:</strong> {{ \Carbon\Carbon::parse($record->start_date)->format('M d Y') }}</p>
    <p><strong>Expires At:</strong> <span class="text-red-800">{{ \Carbon\Carbon::parse($record->end_date)->format('M d Y') }}</span></p>

    {{-- Add more fields as needed --}}
</div>
