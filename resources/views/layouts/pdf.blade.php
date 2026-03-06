<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Document' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { padding: 20px 0; border-bottom: 2px solid #1a237e; margin-bottom: 20px; }
        .header .logo { font-size: 20px; font-weight: bold; color: #1a237e; }
        .header .org-info { font-size: 10px; color: #666; margin-top: 4px; }
        .document-title { font-size: 18px; font-weight: bold; color: #1a237e; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        table th { background: #f9fafb; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #6b7280; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #9ca3af; text-align: center; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-gray { background: #f3f4f6; color: #374151; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="header">
        <div class="logo">Pharmaceutical Society of Kenya</div>
        <div class="org-info">Pamstech House, Woodlands Road, Nairobi | info@psk.or.ke | +254 20 2717077</div>
    </div>

    {{ $slot }}

    <div class="footer">
        Generated on {{ now()->format('d M Y, H:i') }} | {{ config('app.name') }}
    </div>
</body>
</html>
