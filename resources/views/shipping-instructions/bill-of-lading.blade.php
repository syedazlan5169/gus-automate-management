<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bill of Lading - {{ $shippingInstruction->sub_booking_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 5px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
            background-color: #f5f5f5;
        }
        .container-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .container-table th {
            background-color: #f5f5f5;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .container-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 1px solid #000;
            padding-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <x-application-logo-pdf class="logo" />
        <div class="title">BILL OF LADING</div>
        <div class="subtitle">{{ $shippingInstruction->sub_booking_number }}</div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="label">Shipper</td>
            <td>
                {{ $shippingInstruction->shipper }}<br>
                {{ $shippingInstruction->contact_shipper }}
            </td>
        </tr>
        <tr>
            <td class="label">Consignee</td>
            <td>
                {{ $shippingInstruction->consignee }}<br>
                {{ $shippingInstruction->contact_consignee }}
            </td>
        </tr>
        <tr>
            <td class="label">Notify Party</td>
            <td>
                {{ $shippingInstruction->notify_party }}<br>
                {{ $shippingInstruction->notify_party_contact }}<br>
                {{ $shippingInstruction->notify_party_address }}
            </td>
        </tr>
        <tr>
            <td class="label">Vessel & Voyage</td>
            <td>{{ $shippingInstruction->booking->vessel }} / {{ $shippingInstruction->booking->voyage }}</td>
        </tr>
        <tr>
            <td class="label">Port of Loading</td>
            <td>{{ $shippingInstruction->booking->pol }}</td>
        </tr>
        <tr>
            <td class="label">Port of Discharge</td>
            <td>{{ $shippingInstruction->booking->pod }}</td>
        </tr>
        <tr>
            <td class="label">Place of Receipt</td>
            <td>{{ $shippingInstruction->booking->place_of_receipt }}</td>
        </tr>
        <tr>
            <td class="label">Place of Delivery</td>
            <td>{{ $shippingInstruction->booking->place_of_delivery }}</td>
        </tr>
    </table>

    <table class="container-table">
        <thead>
            <tr>
                <th>Container Type</th>
                <th>Quantity</th>
                <th>Total Weight</th>
                <th>Container Numbers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($containersByType as $type => $data)
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ $data['count'] }}x</td>
                    <td>{{ number_format($data['total_weight'], 2) }} KG</td>
                    <td>{{ implode(', ', $data['containers']->toArray()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <strong>Cargo Description:</strong><br>
        {{ $shippingInstruction->cargo_description }}
    </div>

    <div style="margin-top: 10px;">
        <strong>HS Code:</strong> {{ $shippingInstruction->hs_code }}
    </div>

    <div class="signatures">
        <div class="signature-box">
            <p>For the Carrier<br>
            As Agent Only</p>
        </div>
        <div class="signature-box">
            <p>Shipper/Exporter<br>
            (Signature)</p>
        </div>
    </div>

    <div class="footer">
        <p>Date of Issue: {{ now()->format('d M Y') }}</p>
        <p>This is a computer generated document and requires no signature.</p>
    </div>
</body>
</html>