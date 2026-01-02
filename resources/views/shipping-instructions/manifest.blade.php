<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifests</title>
</head>

@php
    $allContainers = collect();
    foreach ($containersByType as $type => $group) {
        foreach ($group['containers'] as $container) {
            $container['container_type'] = $type;
            $allContainers->push($container);
        }
    }
    $containerChunks = $allContainers->chunk(44); // split into groups of 44
@endphp

@foreach ($containerChunks as $chunkIndex => $chunk)
<body style="width: 1029px; margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 10px; border: 1px solid #000;">
    <table style="width: 1029px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <img src="{{ public_path('images/logo-header.webp') }}" alt="Logo" style="max-width: 150px; max-height: 60px;">
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 90%;">
                    <span style="font-weight: normal;"><strong>GU SHIPPING SDN BHD (1051565-U)</strong><br>
                    5-01 (FIRST FLOOR), JALAN PONDEROSA 2/2 <br>
                    TAMAN PONDEROSA, 81100 JOHOR BAHRU<br>
                    JOHOR, MALAYSIA<br></span>
                </th>
            </tr>
        </tbody>
    </table>

    <table style="width: 1029px; border-collapse: collapse; margin: 0; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 100%;">
                    <strong>*** EXPORT CARGO MANIFEST ***</strong><br>
                </th>
            </tr>
        </tbody>
    </table>

    <table style="width: 1029px; border-collapse: collapse; margin-bottom: 13px; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>CARRIER: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        GU SHIPPING<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>DATE: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ now()->format('d/m/Y') }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>PREPARED BY: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        GUCS1<br>
                    </span>
                </th>
            </tr>
        </tbody>
    </table>

    <table style="width: 1029px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>VESSEL / TUG: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->vessel }} / {{ $shippingInstruction->booking->tug }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>POL: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->pol }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>ETD POL: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->ets->format('d/m/Y') }}<br>
                    </span>
                </th>
            </tr>
        </tbody>
    </table>

    <table style="width: 1029px; border-collapse: collapse; margin-bottom: 12.5px; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>VOYAGE: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->voyage->voyage_number }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>POD: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->pod }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 10%;">
                    <strong>ETA DEST: </strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 20%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->eta->format('d/m/Y') }}<br>
                    </span>
                </th>
            </tr>
        </tbody>
    </table>

    <table style="width: 1029px; table-layout: fixed; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif; font-size: 8px; !important;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 8%;">
                    <strong>B/L</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 18%;">
                    <strong>SHIPPER</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 18%;">
                    <strong>CONSIGNEE</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 18%;">
                    <strong>CONTAINER / SEAL NO</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 5%;">
                    <strong>PKG</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 18%;">
                    <strong>GOODS DESCRIPTION</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 8%;">
                    <strong>WEIGHT</strong><br>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 7%;">
                    <strong>VOLUME</strong><br>
                </th>
            </tr>
        </tbody>
    </table>

    <table style="width: 1029px; table-layout: fixed; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif; font-size: 8px; !important;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; width: 8%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">
                        {{ $shippingInstruction->bl_number }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; width: 18%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">{{ $shippingInstruction->shipper }}<br>
                    {{ $shippingInstruction->shipper_address['line1'] ? $shippingInstruction->shipper_address['line1'] : '' }}<br>
                    {{ $shippingInstruction->shipper_address['line2'] ? $shippingInstruction->shipper_address['line2'] : '' }}<br>
                    {{ $shippingInstruction->shipper_address['line3'] ? $shippingInstruction->shipper_address['line3'] : '' }}<br>
                    {{ $shippingInstruction->shipper_address['line4'] ? $shippingInstruction->shipper_address['line4'] : '' }}<br></span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; width: 18%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">{{ $shippingInstruction->consignee }}<br>
                    {{ $shippingInstruction->consignee_address['line1'] ? $shippingInstruction->consignee_address['line1'] : '' }}<br>
                    {{ $shippingInstruction->consignee_address['line2'] ? $shippingInstruction->consignee_address['line2'] : '' }}<br>
                    {{ $shippingInstruction->consignee_address['line3'] ? $shippingInstruction->consignee_address['line3'] : '' }}<br>
                    {{ $shippingInstruction->consignee_address['line4'] ? $shippingInstruction->consignee_address['line4'] : '' }}<br></span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 18%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">
                        @foreach ($chunk as $container)
                            {{ $container['container_number'] }} / {{ $container['seal_number'] }} / {{ $container['container_type'] }}<br>
                        @endforeach
                        @for ($i = count($chunk); $i < 44; $i++)
                            <br>
                        @endfor
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; width: 5%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">
                        {{ $allContainers->count() }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: top; width: 18%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; display: block; text-align: center; font-family: 'Courier New', Courier, monospace;"><!-- @foreach ($containersByType as $type => $group)
                            ({{ $type }} x {{ $group['count'] }})
                        @endforeach
                        CONTAINER/S STC:<br> --><span style="white-space: pre-wrap; text-align: center;">{{ trim($shippingInstruction->cargo_description) }}</span><br><!-- HS CODE : {{ $shippingInstruction->hs_code }}<br>
                        BOOKING NO : {{ $shippingInstruction->sub_booking_number }}<br> --></span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; width: 8%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">
                        {{ number_format($shippingInstruction->cargos->sum('total_weight'), 2, '.', ',') }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; width: 7%; word-wrap: break-word; white-space: normal;">
                    <span style="font-weight: normal; font-family: 'Courier New', Courier, monospace;">
                        {{ number_format($shippingInstruction->volume, 2, '.', ',') }}<br>
                    </span>
                </th>
            </tr>
        </tbody>
    </table>
</body>
@endforeach
</html>
