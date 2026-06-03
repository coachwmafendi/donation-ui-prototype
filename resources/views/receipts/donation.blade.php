<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $donation->public_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #1e293b;
            background: white;
            padding: 48px;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .tagline {
            font-size: 13px;
            color: #64748b;
        }
        .receipt-title {
            text-align: center;
            margin-bottom: 32px;
        }
        .receipt-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .receipt-number {
            font-size: 13px;
            color: #64748b;
            font-family: 'SF Mono', Monaco, monospace;
        }
        .section {
            margin-bottom: 28px;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 12px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .row:last-child {
            border-bottom: none;
        }
        .label {
            color: #64748b;
            font-size: 13px;
        }
        .value {
            font-weight: 600;
            color: #0f172a;
            font-size: 13px;
        }
        .amount-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 32px 0;
        }
        .amount-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .amount-value {
            font-size: 36px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -1px;
        }
        .amount-currency {
            font-size: 18px;
            font-weight: 600;
            color: #64748b;
            vertical-align: super;
        }
        .footer {
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .thank-you {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .footer-note {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .meta {
            text-align: center;
            margin-top: 32px;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="receipt">
        {{-- Header --}}
        <div class="header">
            <div class="logo">Donation UI</div>
            <div class="tagline">Official Receipt</div>
        </div>

        {{-- Title --}}
        <div class="receipt-title">
            <h1>Thank you!</h1>
            <div class="receipt-number">Receipt #{{ $donation->public_id }}</div>
        </div>

        {{-- Amount --}}
        <div class="amount-box">
            <div class="amount-label">Donation Amount</div>
            <div class="amount-value">
                <span class="amount-currency">{{ $donation->currency }}</span>
                {{ number_format($donation->amount_cents / 100, 2) }}
            </div>
            @if($donation->converted_amount_cents)
            <div style="margin-top: 8px; font-size: 12px; color: #64748b;">
                ≈ {{ $donation->converted_currency }} {{ number_format($donation->converted_amount_cents / 100, 2) }}
            </div>
            @endif
        </div>

        {{-- Donor Info --}}
        <div class="section">
            <div class="section-title">Donor Information</div>
            <div class="row">
                <span class="label">Name</span>
                <span class="value">{{ $donation->profile->full_name ?? 'Anonymous' }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $donation->profile->email ?? '—' }}</span>
            </div>
            @if($donation->profile->address_line_1)
            <div class="row">
                <span class="label">Address</span>
                <span class="value">
                    {{ $donation->profile->address_line_1 }}
                    @if($donation->profile->address_line_2), {{ $donation->profile->address_line_2 }}@endif
                </span>
            </div>
            <div class="row">
                <span class="label">City / State / ZIP</span>
                <span class="value">
                    {{ collect([$donation->profile->city, $donation->profile->state, $donation->profile->postal_code])->filter()->join(', ') }}
                </span>
            </div>
            @endif
        </div>

        {{-- Donation Details --}}
        <div class="section">
            <div class="section-title">Donation Details</div>
            <div class="row">
                <span class="label">Date</span>
                <span class="value">{{ $donation->donation_date->format('F j, Y') }}</span>
            </div>
            <div class="row">
                <span class="label">Campaign</span>
                <span class="value">{{ $donation->campaign }}</span>
            </div>
            @if($donation->designation)
            <div class="row">
                <span class="label">Designation</span>
                <span class="value">{{ $donation->designation }}</span>
            </div>
            @endif
            <div class="row">
                <span class="label">Frequency</span>
                <span class="value">{{ ucfirst($donation->frequency) }}</span>
            </div>
            <div class="row">
                <span class="label">Payment Method</span>
                <span class="value">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</span>
            </div>
            <div class="row">
                <span class="label">Status</span>
                <span class="value">{{ ucfirst($donation->status) }}</span>
            </div>
            <div class="row">
                <span class="label">Transaction ID</span>
                <span class="value" style="font-family: monospace; font-size: 11px;">{{ $donation->public_id }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="thank-you">Your contribution makes a difference.</div>
            <div class="footer-note">
                This receipt serves as official confirmation of your donation.<br>
                Please retain this document for your records.
            </div>
        </div>

        <div class="meta">
            Generated on {{ now()->format('F j, Y \a\t g:i A') }}
        </div>
    </div>
</body>
</html>
