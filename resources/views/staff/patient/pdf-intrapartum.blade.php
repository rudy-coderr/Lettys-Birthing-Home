<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Intrapartum Record</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            background: white;
            padding: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .document-container {
            max-width: 820px;
            margin: 0 auto;
            background: white;
            border-radius: 6px;
            overflow: hidden;
        }

        .wrapper-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header {
            padding: 15px 30px;
            border-bottom: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            background: white;
            position: relative;
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: absolute;
            left: 30px;
            border: none;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .header-text {
            text-align: center;
        }

        .header-text h1 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .header-text p {
            font-size: 11px;
            color: #333;
            margin: 1px 0;
        }

        .document-title {
            text-align: center;
            padding: 15px;
            background: white;
            color: #333;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .content {
            padding: 15px 30px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin: 15px 0 5px;
            border-left: 4px solid #333;
            padding-left: 8px;
        }

        .details-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }

        .details-table td {
            padding: 6px 5px;
            vertical-align: top;
            font-size: 12px;
        }

        .details-table .label {
            width: 160px;
            font-weight: bold;
            color: #333;
            white-space: nowrap;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #333;
            font-size: 11.5px;
            padding: 7px 8px;
            text-align: left;
            word-wrap: break-word;
        }

        .data-table th {
            background: white;
            color: #333;
            font-weight: bold;
        }

        .patient-name {
            color: #333;
            font-weight: bold;
            font-size: 13px;
        }

        .total-row td {
            color: #333 !important;
            font-size: 12.5px;
        }

        .footer {
            padding: 12px 30px;
            border-top: 1px solid #ddd;
            background: white;
            font-size: 10px;
            color: #333;
            text-align: center;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .document-container {
                border: none;
                box-shadow: none;
                border-radius: 0;
                overflow: visible !important;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            .document-title {
                page-break-after: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="document-container">
        <table class="wrapper-table">
            <thead>
                <tr>
                    <td>
                        <div class="header">
                            <div class="logo">
                                <img src="{{ public_path('img/imglogo.png') }}" alt="Logo">
                            </div>
                            <div class="header-text">
                                <h1>Letty's Birthing Home</h1>
                                <p>Buhi Camarines Sur, Philippines</p>
                                <p>Professional Birthing and Maternity Care Services</p>
                            </div>
                        </div>
                        <div class="document-title">INTRAPARTUM RECORD</div>
                    </td>
                </tr>
            </thead>

            <tbody>
                <!-- Visit / Delivery details -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Delivery Details</div>
                            <table class="details-table">
                                <tr>
                                    <td class="label">Delivery ID:</td>
                                    <td class="value">{{ $delivery->id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Intrapartum Date:</td>
                                    <td class="value">
                                        {{ $intrapartum->created_at ? \Carbon\Carbon::parse($intrapartum->created_at)->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Attending Staff:</td>
                                    <td class="value">
                                        @if ($staff)
                                            {{ $staff->first_name ?? '' }} {{ $staff->last_name ?? '' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Patient Information -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Patient Information</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>First Name</td>
                                        <td>{{ $delivery->patient->client->first_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Name</td>
                                        <td>{{ $delivery->patient->client->last_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $delivery->patient->client->full_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $delivery->patient->client->client_phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td>{{ $delivery->patient->age ?? 'N/A' }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Intrapartum Details -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Intrapartum Details</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>BP</td>
                                        <td>{{ $intrapartum->bp ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Temperature</td>
                                        <td>{{ $intrapartum->temp ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Respiratory Rate</td>
                                        <td>{{ $intrapartum->rr ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pulse Rate</td>
                                        <td>{{ $intrapartum->pr ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fundic Height</td>
                                        <td>{{ $intrapartum->fundic_height ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fetal Heart Tone</td>
                                        <td>{{ $intrapartum->fetal_heart_tone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Internal Exam</td>
                                        <td>{{ $intrapartum->internal_exam ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bag of Water</td>
                                        <td>{{ $intrapartum->bag_of_water ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Baby Delivered</td>
                                        <td>{{ $intrapartum->baby_delivered ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Placenta Delivered</td>
                                        <td>{{ $intrapartum->placenta_delivered ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Baby Sex</td>
                                        <td>{{ $intrapartum->baby_sex ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Remarks -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Remarks</div>
                            <table class="details-table">
                                <tr>
                                    <td class="label">Notes:</td>
                                    <td class="value">{{ $remarks->notes ?? 'No remarks added' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Generated By:</td>
                                    <td class="value">
                                        @if ($staff)
                                            {{ $staff->first_name ?? '' }} {{ $staff->last_name ?? '' }}
                                        @else
                                            {{ Auth::user()->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td>
                        <div class="footer">
                            Generated on {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>
</body>

</html>
