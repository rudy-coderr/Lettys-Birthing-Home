<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Baby Registration</title>
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

        /* HEADER */
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

        /* TITLE */
        .document-title {
            text-align: center;
            padding: 15px;
            background: white;
            color: #333;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* CONTENT */
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

        .details-table .value {
            color: #333;
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

        .data-table tr:nth-child(even) {
            background: white;
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
                        <div class="document-title">BABY REGISTRATION FORM</div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <!-- Baby Information -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Baby Information</div>
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
                                        <td>{{ $baby->baby_first_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Middle Name</td>
                                        <td>{{ $baby->baby_middle_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Name</td>
                                        <td>{{ $baby->baby_last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sex</td>
                                        <td>{{ $baby->sex }}</td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth</td>
                                        <td>{{ \Carbon\Carbon::parse($baby->date_of_birth)->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Time of Birth</td>
                                        <td>{{ $baby->time_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <td>Place of Birth</td>
                                        <td>{{ $baby->place_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <td>Type of Birth</td>
                                        <td>{{ $baby->type_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <td>Weight at Birth</td>
                                        <td>{{ $baby->weight_at_birth }}</td>
                                    </tr>
                                    <tr>
                                        <td>Birth Order</td>
                                        <td>{{ $baby->birth_order ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Mother Information -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Mother Information</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Full Name</td>
                                        <td>{{ $delivery->patient->client->first_name }}
                                            {{ $mother->maiden_middle_name ?? '' }}
                                            {{ $delivery->patient->client->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td>{{ $delivery->patient->age ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $delivery->patient->client->full_address ?? ($mother->address ?? '-') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Citizenship</td>
                                        <td>{{ $mother->citizenship ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Religion</td>
                                        <td>{{ $mother->religion ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Children Alive</td>
                                        <td>{{ $mother->total_children_alive ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Children Still Living</td>
                                        <td>{{ $mother->children_still_living ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Children Deceased</td>
                                        <td>{{ $mother->children_deceased ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Occupation</td>
                                        <td>{{ $mother->occupation ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Father Information -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Father Information</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Full Name</td>
                                        <td>{{ $delivery->patient->spouse_fname ?? '-' }}
                                            {{ $father->middle_name ?? '' }}
                                            {{ $delivery->patient->spouse_lname ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <td>Age</td>
                                        <td>{{ $father->age ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $father->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Citizenship</td>
                                        <td>{{ $father->citizenship ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Religion</td>
                                        <td>{{ $father->religion ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Occupation</td>
                                        <td>{{ $father->occupation ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Additional Information -->
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Additional Information</div>
                            <table class="details-table">
                                <tr>
                                    <td class="label">Marriage Date</td>
                                    <td class="value">
                                        {{ $marriage->marriage_date ? \Carbon\Carbon::parse($marriage->marriage_date)->format('F d, Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Marriage Place</td>
                                    <td class="value">{{ $marriage->marriage_place ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Birth Attendant</td>
                                    <td class="value">{{ $marriage->birth_attendant ?? '-' }}</td>
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
