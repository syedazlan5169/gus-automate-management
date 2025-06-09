<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill of Lading</title>
</head>

@php
    $allContainers = collect();
    foreach ($containersByType as $type => $group) {
        foreach ($group['containers'] as $container) {
            $container['container_type'] = $type;
            $allContainers->push($container);
        }
    }
    $containerChunks = $allContainers->chunk(29); // split into groups of 30

    function numberToWords($number) {
        $ones = array(
            0 => "", 1 => "ONE", 2 => "TWO", 3 => "THREE", 4 => "FOUR",
            5 => "FIVE", 6 => "SIX", 7 => "SEVEN", 8 => "EIGHT", 9 => "NINE",
            10 => "TEN", 11 => "ELEVEN", 12 => "TWELVE", 13 => "THIRTEEN",
            14 => "FOURTEEN", 15 => "FIFTEEN", 16 => "SIXTEEN", 17 => "SEVENTEEN",
            18 => "EIGHTEEN", 19 => "NINETEEN"
        );
        $tens = array(
            2 => "TWENTY", 3 => "THIRTY", 4 => "FORTY", 5 => "FIFTY",
            6 => "SIXTY", 7 => "SEVENTY", 8 => "EIGHTY", 9 => "NINETY"
        );
        
        if ($number < 20) {
            return $ones[$number];
        }
        
        $digit = $number % 10;
        $tens_digit = floor($number / 10);
        
        return $tens[$tens_digit] . ($digit > 0 ? " " . $ones[$digit] : "");
    }
@endphp

@foreach ($containerChunks as $chunkIndex => $chunk)
<body style="width: 700px; margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 10px; border: 1px solid #000;">

    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                    <strong>Shipper</strong><br>
                    <span style="font-weight: normal;">{{ $shippingInstruction->shipper }}<br>
                    {{ $shippingInstruction->shipper_address['line1'] ? $shippingInstruction->shipper_address['line1'] : '' }}<br>
                    {{ $shippingInstruction->shipper_address['line2'] ? $shippingInstruction->shipper_address['line2'] : '' }}<br>
                    {{ $shippingInstruction->shipper_address['line3'] ? $shippingInstruction->shipper_address['line3'] : '' }}<br>
                    {{ $shippingInstruction->shipper_address['line4'] ? $shippingInstruction->shipper_address['line4'] : '' }}<br></span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 25%;">
                    <img src="{{ public_path('images/logo-header.webp') }}" alt="Logo" style="max-width: 150px; max-height: 60px;">
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: right; width: 25%;">
                    <strong>B/L NO </strong><span style="font-weight: normal;">{{ $shippingInstruction->bl_number }}</span><br>
                    <strong>Page </strong><span style="font-weight: normal;">{{ $chunkIndex + 1 }}/{{ $containerChunks->count() }}</span>
                </th>
            </tr>
        </tbody>
    </table>
    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                    <strong>Consignee</strong><br>
                    <span style="font-weight: normal;">{{ $shippingInstruction->consignee }}<br>
                    {{ $shippingInstruction->consignee_address['line1'] ? $shippingInstruction->consignee_address['line1'] : '' }}<br>
                    {{ $shippingInstruction->consignee_address['line2'] ? $shippingInstruction->consignee_address['line2'] : '' }}<br>
                    {{ $shippingInstruction->consignee_address['line3'] ? $shippingInstruction->consignee_address['line3'] : '' }}<br>
                    {{ $shippingInstruction->consignee_address['line4'] ? $shippingInstruction->consignee_address['line4'] : '' }}<br></span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                    <strong>Notify Party</strong><br>
                    <span style="font-weight: normal;">{{ $shippingInstruction->notify_party }}<br>
                    {{ $shippingInstruction->notify_party_address['line1'] ? $shippingInstruction->notify_party_address['line1'] : '' }}<br>
                    {{ $shippingInstruction->notify_party_address['line2'] ? $shippingInstruction->notify_party_address['line2'] : '' }}<br>
                    {{ $shippingInstruction->notify_party_address['line3'] ? $shippingInstruction->notify_party_address['line3'] : '' }}<br>
                    {{ $shippingInstruction->notify_party_address['line4'] ? $shippingInstruction->notify_party_address['line4'] : '' }}<br></span>
                </th>
            </tr>
        </tbody>
    </table>
    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Vessel</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->vessel }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Voyage</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->voyage->voyage_number }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Port of Loading</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->pol }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Place of Receipt</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->place_of_receipt }}<br>
                    </span>
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Tug</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->tug }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Delivery Terms</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->delivery_terms }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Port of Discharge</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->pod }}<br>
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Place of Delivery</strong>
                    <br>
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->booking->place_of_delivery }}<br>
                    </span>
                </th>
            </tr>
        </tbody>
    </table>
    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 40%;">
                    <strong>Marks and Numbers</strong>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 30%;">
                    <strong>Goods Description</strong>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 15%;">
                    <strong>Gross Weight (KGS)</strong>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 15%;">
                    <strong>Volume (M3)</strong>
                </th>
            </tr>
            <!-- Data details here -->
            
            <tr>
                <td style="font-weight: normal; border: 1px solid #000; padding: 8px; text-align: left; width: 40%;">
                    @foreach ($chunk as $container)
                        {{ $container['container_number'] }} / {{ $container['seal_number'] }} / {{ $container['container_type'] }}<br>
                    @endforeach
                    @for ($i = count($chunk); $i < 29; $i++)
                        <br>
                    @endfor
                </td>
                <td style="border: 1px solid #000; padding: 8px; vertical-align: top; text-align: left; width: 30%;">
                    <span style="font-weight: normal;">
                    @foreach ($containersByType as $type => $group)
                        ({{ $type }} x {{ $group['count'] }})
                    @endforeach
                    CONTAINER/S STC:<br>
                    {{ $shippingInstruction->cargo_description}}<br>
                    HS CODE : {{ $shippingInstruction->hs_code }}<br>
                    BOOKING NO : {{ $shippingInstruction->sub_booking_number }}<br>
                    </span>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 8px; text-align: center; width: 15%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->gross_weight }}<br>
                    </span>
                </td>
                <td style="border: 1px solid #000; padding: 8px; vertical-align: top; text-align: center; width: 15%;">
                    <span style="font-weight: normal;">
                        {{ $shippingInstruction->volume }}<br>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 100%;">
                    <span style="font-weight: normal;">
                        @if($loop->last)
                            SHIPPED ON BOARD {{ $shippingInstruction->booking->eta->format('d/m/Y') }}<br>
                            TOTAL {{ strtoupper(numberToWords($allContainers->count())) }} ( {{ $allContainers->count() }} )  CONTAINER(S) ONLY
                        @else
                            CONTINUED ON NEXT PAGE<br>
                            <br>
                        @endif
                    </span>
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 100%;">
                    <span style="font-weight: normal;">
                    RECEIVED by Carrier the Goods as specified above in apparent good order and condition unless otherwise 
                            stated, to be transported to such place as agreed, authorized or permitted therein and subject to all 
                            the terms and conditions appearing on the front and reverse of this Bill of Lading to which the Merchant 
                            agrees by accepting this Bill of Lading, any local previleges and customs not withstanding.
                            <br>
                            <br>
                            The particulars given below as stated by the shipper are stated by shipper and the weight, measure, 
                            quantity, condition contents and value of the Goods are unknown to the Carrier.
                            The WITNESS whereof one (1) original Bill of Lading has been signed if not otherwise stated below, the 
                            same being accomplished the other(s), if any, to be void. If required by the Carrier one (1) original 
                            Bill of Lading must be surrendered duly endorsed in exchange for the Goods or delivery order.
                            <br>
                            <br>
                            The cargo is carried on deck at the sole risk of the shipper and the carrier shall have no liability 
                            whatsoever for loss or damages of whatsoever nature arising during carriage, even if caused by 
                            unseaworthiness of the vessel or negligence of the carrier or his servants or agents.
                    </span>
                </th>
            </tr>
        </tbody>
    </table>
    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr style="vertical-align: top;">
                <th style="height: 42px; border: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                        OUTWARD / INWARD CARRIER AGENT:
                </th>
                <th style="height: 42px; border: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                        REMARKS:
                </th>
            </tr>
        </tbody>
    </table>
    <table style="width: 700px; border-collapse: collapse; margin: 0 auto; font-family: Arial, sans-serif;">
        <tbody>
            <tr style="vertical-align: top;">
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Place & Date of Issue</strong><br>
                    <span style="font-weight: normal;"> 
                        {{ $shippingInstruction->booking->place_of_receipt }}<br>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Port of Discharge ETA</strong>
                    <span style="font-weight: normal;">
                    {{ $shippingInstruction->booking->eta->format('d/m/Y') }}<br>
                    </span>
                </th>
                <th style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                    <strong>SIGNED ON BEHALF OF THE CARRIER</strong><br>
                    <span style="font-weight: normal;">
                        GU SHIPPING SDN BHD
                    </span>
                </th>
            </tr>
            <tr style="vertical-align: top;">
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>Freight Payable at</strong><br>
                    <span style="font-weight: normal;">
                        MALAYSIA
                    </span>
                </th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 25%;">
                    <strong>No. of Original B/L</strong><br>
                    <span style="font-weight: normal;">
                        {{ $containerChunks->count() }}
                    </span>
                </th>
                <th style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 8px; text-align: left; width: 50%;">
                    
                </th>
            </tr>
        </tbody>
    </table>
</body>
@endforeach
</html>
