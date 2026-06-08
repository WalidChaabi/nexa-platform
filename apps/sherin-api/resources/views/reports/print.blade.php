<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sherin report — {{ $type }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .meta { color: #555; margin-bottom: 20px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        .summary { margin: 16px 0; font-size: 13px; }
    </style>
</head>
<body>
    <h1>Sherin — {{ ucfirst($type) }} report</h1>
    <p class="meta">
        Store: {{ $report['store']['name'] }} (#{{ $report['store']['id'] }})<br>
        Period: {{ $report['period']['from'] }} → {{ $report['period']['to'] }}<br>
        Generated: {{ $generatedAt }}
    </p>

    <div class="summary">
        @if ($type === 'sales')
            Invoices: {{ $report['summary']['invoice_count'] }} —
            Total paid: {{ number_format($report['summary']['total_paid'], 2) }} MAD
        @elseif ($type === 'customers')
            New customers: {{ $report['summary']['customer_count'] }}
        @else
            Products: {{ $report['summary']['product_count'] }} —
            Qty: {{ $report['summary']['total_quantity'] }} —
            Revenue: {{ number_format($report['summary']['total_revenue'], 2) }} MAD
        @endif
    </div>

    <table>
        <thead>
            <tr>
                @if ($type === 'sales')
                    <th>Date</th><th>Customer</th><th>Phone</th><th>HT</th><th>TTC</th><th>Paid</th>
                @elseif ($type === 'customers')
                    <th>Name</th><th>Phone</th><th>Email</th><th>Points</th><th>Created</th>
                @else
                    <th>Product</th><th>Qty</th><th>Revenue</th><th>Margin</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($report['rows'] as $row)
                <tr>
                    @if ($type === 'sales')
                        <td>{{ $row['invoice_date'] }}</td>
                        <td>{{ $row['customer_name'] }}</td>
                        <td>{{ $row['customer_phone'] }}</td>
                        <td>{{ number_format($row['total_ht'], 2) }}</td>
                        <td>{{ number_format($row['total_ttc'], 2) }}</td>
                        <td>{{ number_format($row['paid_amount'], 2) }}</td>
                    @elseif ($type === 'customers')
                        <td>{{ $row['display_name'] }}</td>
                        <td>{{ $row['phone'] }}</td>
                        <td>{{ $row['email'] }}</td>
                        <td>{{ $row['points'] }}</td>
                        <td>{{ $row['created_at'] }}</td>
                    @else
                        <td>{{ $row['label'] }}</td>
                        <td>{{ $row['quantity_sold'] }}</td>
                        <td>{{ number_format($row['revenue'], 2) }}</td>
                        <td>{{ number_format($row['margin'], 2) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
