<!DOCTYPE html>
<html>
<head>
    {{-- {{dd($invoice->items)}} --}}
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }
        .header, .footer {
            margin-bottom: 20px;
        }
        .details, .items {
            width: 100%;
            margin-bottom: 20px;
        }
        .items th, .items td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .items th {
            background-color: #f4f4f4;
        }
        .flex_div {
        width: 100%;
        /* overflow: hidden; */
        margin-bottom: 20px;
    }
    .left_head_meta {
        float: left;
        width: 40%;
        text-align: left;
    }
    .right_head_meta {
        float: right;
        width: 55%;
        text-align: right;
        text-align: right;
    }
    .clearfix {
        clear: both;
    }
        .right { text-align: right; }
        .right_head_meta h2{
            display: inline-block;
            padding: 5px;
            margin-top: 30px;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="flex_div">
            <div class="left_head_meta">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width: 150px; height: auto;">
                <div class="details">
                    <h4>Billed To:</h4>
                    <p>Name: {{ $invoice->company_name }}</p>
                    <p>Address: {{ $invoice->address }}</p>
                    {{-- condition --}}
                    @if($invoice->client_phone)
                        <p>Phone: {{ $invoice->client_phone }}</p>
                    @endif
            
                    @if($invoice->client_email)
                        <p>Email: {{ $invoice->client_email }}</p>
                    @endif
                    
            
                    <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d Y') }}</p>
                </div>
            </div>
        
            <div class="right_head_meta">
                <h1>INVOICE #{{ $invoice->id }}</h1>
                <p>Issue Date: {{ \Carbon\Carbon::parse($invoice->created_at)->format('M d Y - H:i:s') }}</p>

                @if($invoice->invoice_type == 'pending')
                <h2 style="color: red; border:2px dashed red;">PENDING INVOICE</h2>
            @else
                <h2 style="color: green; border:2px dashed green;">INVOICE PAID</h2>
            @endif
            </div>
            
        </div>
        <div class="clearfix"></div>

        
    </div>


    <div class="details">
        <h4>Project Details:</h4>
        <p>Project Name: {{ $invoice->project_name }}</p>
    </div>

    <table class="items" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Services</th>
                <th>Quantity</th>
                <th>Price</th>
                <th class="right">Totals</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td class="right">$ {{ $item->total }}</td>
                </tr>   
            @endforeach
           
        </tbody>
    </table>

    <table width="100%">
        <tr>
            <td class="right"><strong>Subtotal:</strong></td>
            <td class="right">$ {{ number_format($invoice->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td class="right"><strong>Tax amount:</strong></td>
            <td class="right">
                $ {{($invoice->tax_amount == '' ? ' 0.00' : $invoice->tax_amount) }}
            </td>
            
        </tr>
        <tr>
            
            @if($invoice->client_currency == 'PKR')
                <td class="right"><strong>Total Amount In PKR :</strong></td>
            @else
                <td class="right"><strong>Total Amount :</strong></td>
            @endif

            @if($invoice->client_currency == 'PKR')
            <td class="right">{{$invoice->client_currency}} {{ number_format($invoice->amount_in_PKR, 2) }}</td>
            @else
            <td class="right">{{$invoice->client_currency}} {{ number_format($invoice->total_amount, 2) }}</td>
            @endif
        </tr>
    </table>

    <div class="footer">
        <p><strong>Payment Terms:</strong> Net 5 of the invoice date.</p>
        <p>A late fee of 1.5% after 10 days will be applied to overdue balances.</p>
        <p>Payments can be made via bank transfer, Wise, Payoneer, or credit card.</p>
        <p>If you have any questions regarding this invoice, please contact us.</p>
    </div>
</body>
</html>
