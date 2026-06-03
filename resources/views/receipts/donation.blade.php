<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $donation->public_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.35;
            color: #1e293b;
            background: white;
            padding: 32px;
        }
        .receipt {
            max-width: 520px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .tagline {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .receipt-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .receipt-title h1 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }
        .receipt-number {
            font-size: 10px;
            color: #64748b;
            font-family: 'SF Mono', Monaco, monospace;
            margin-top: 3px;
        }
        .amount-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            text-align: center;
            margin: 16px 0 20px;
        }
        .amount-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .amount-value {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .amount-currency {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            vertical-align: super;
        }
        .amount-converted {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .two-col {
            display: flex;
            gap: 24px;
        }
        .col {
            flex: 1;
        }
        .section {
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .row:last-child {
            border-bottom: none;
        }
        .label {
            color: #64748b;
            font-size: 10px;
        }
        .value {
            font-weight: 600;
            color: #0f172a;
            font-size: 10px;
            text-align: right;
        }
        .footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .thank-you {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .footer-note {
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .meta {
            text-align: center;
            margin-top: 16px;
            font-size: 9px;
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
            <div class="amount-converted">
                ≈ {{ $donation->converted_currency }} {{ number_format($donation->converted_amount_cents / 100, 2) }}
            </div>
            @endif
        </div>

        {{-- Two columns: Donor Info + Donation Details --}}
        <div class="two-col">
            <div class="col">
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
            </div>

            <div class="col">
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
                        <span class="value" style="font-family: monospace; font-size: 9px;">{{ $donation->public_id }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="thank-you">Your contribution makes a difference.</div>
            <div class="footer-note">
                This receipt serves as official confirmation of your donation. Please retain this document for your records.
            </div>
        </div>

        <div class="meta">
            Generated on {{ now()->format('F j, Y \a\t g:i A') }}
        </div>
    </div>
</body>
</html>
