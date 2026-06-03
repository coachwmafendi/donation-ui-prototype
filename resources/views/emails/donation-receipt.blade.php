<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.">
    <title>Donation Receipt</title>
    <style>
        body {
            background-color: #f7f7fb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 560px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            padding: 48px 40px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        }
        .logo {
            text-align: center;
            margin-bottom: 40px;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
        .heading {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            color: #0f172a;
        }
        .subheading {
            font-size: 16px;
            text-align: center;
            color: #64748b;
            margin-bottom: 40px;
        }
        .amount-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin-bottom: 40px;
        }
        .amount {
            font-size: 36px;
            font-weight: 700;
            color: #15803d;
        }
        .amount-label {
            font-size: 14px;
            color: #16a34a;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .details-table tr {
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table tr:last-child {
            border-bottom: none;
        }
        .details-table td {
            padding: 16px 0;
            font-size: 15px;
        }
        .details-table td:first-child {
            color: #64748b;
            width: 40%;
        }
        .details-table td:last-child {
            color: #0f172a;
            font-weight: 500;
            text-align: right;
        }
        .footer {
            margin-top: 48px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .footer strong {
            color: #0f172a;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Donation UI</div>

        <h1 class="heading">Thank you, {{ $profile->first_name }}!</h1>
        <p class="subheading">Your generous donation makes a real difference.</p>

        <div class="amount-box">
            <div class="amount">{{ $donation->amount }}</div>
            <div class="amount-label">Donation received</div>
        </div>

        <table class="details-table">
            <tr>
                <td>Donation ID</td>
                <td>{{ $donation->public_id }}</td>
            </tr>
            <tr>
                <td>Campaign</td>
                <td>{{ $donation->campaign }}</td>
            </tr>
            <tr>
                <td>Payment method</td>
                <td>{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td>{{ $donation->donation_date->format('M d, Y, g:i A') }}</td>
            </tr>
            @if($donation->comment)
            <tr>
                <td>Your message</td>
                <td>{{ $donation->comment }}</td>
            </tr>
            @endif
        </table>

        <div class="footer">
            <p>
                <strong>Tax receipt</strong><br>
                This email serves as your official donation receipt for tax purposes. Please keep it for your records.
            </p>
            <p style="margin-top: 24px;">
                Have questions? Reply to this email — we are happy to help.<br>
                 Donation UI. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
