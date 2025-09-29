<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Prenatal Check-up Record</title>
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

        /* ===== HEADER ===== */
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

        .patient-name {
            color: #333;
            font-weight: bold;
            font-size: 13px;
        }

        .total-row td {
            color: #333 !important;
            font-size: 12.5px;
        }

        /* FOOTER */
        .footer {
            padding: 12px 30px;
            border-top: 1px solid #ddd;
            background: white;
            font-size: 10px;
            color: #333;
            text-align: center;
        }

        .pagenum:before {
            content: counter(page);
        }

        .pagecount:before {
            content: counter(pages);
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
                        <div class="document-title">PRENATAL CHECK-UP RECORD</div>
                    </td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Visit Details</div>
                            <table class="details-table">
                                <tr>
                                    <td class="label">Visit Date:</td>
                                    <td class="value">{{ $latestVisitInfo->visit_date ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Next Visit Date:</td>
                                    <td class="value">{{ $latestVisitInfo->next_visit_date ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Next Visit Time:</td>
                                    <td class="value">
                                        {{ $latestVisitInfo->next_visit_time
                                            ? \Carbon\Carbon::parse($latestVisitInfo->next_visit_time)->format('h:i A')
                                            : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Visit Number:</td>
                                    <td class="value">{{ $latestVisitInfo->visit_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

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
                                        <td>{{ $patient->first_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Name</td>
                                        <td>{{ $patient->last_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $patient->full_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $patient->client_phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td>{{ $patient->patient->age ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Spouse Name</td>
                                        <td>{{ ($patient->patient->spouse_fname ?? '') . ' ' . ($patient->patient->spouse_lname ?? '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Marital Status</td>
                                        <td>{{ $patient->patient->maritalStatus->marital_status_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Contact Phone</td>
                                        <td>{{ $patient->client_phone ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Pregnancy Details</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>LMP</td>
                                        <td>{{ $latestPrenatal->lmp ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>EDC</td>
                                        <td>{{ $latestPrenatal->edc ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>AOG</td>
                                        <td>{{ $latestPrenatal->aog ?? 'N/A' }} weeks</td>
                                    </tr>
                                    <tr>
                                        <td>Gravida</td>
                                        <td>{{ $latestPrenatal->gravida ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Para</td>
                                        <td>{{ $latestPrenatal->para ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Maternal Vital Signs & Physical Exam</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>FHT</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->fht ?? 'N/A' }} bpm</td>
                                    </tr>
                                    <tr>
                                        <td>FH</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->fh ?? 'N/A' }} cm</td>
                                    </tr>
                                    <tr>
                                        <td>Weight</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->weight ?? 'N/A' }} kg</td>
                                    </tr>
                                    <tr>
                                        <td>Blood Pressure</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->blood_pressure ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Temperature</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->temperature ?? 'N/A' }} °C
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Respiratory Rate</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->respiratory_rate ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pulse Rate</td>
                                        <td>{{ $latestPrenatal->maternalVitals->first()->pulse_rate ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Vaccines Taken</div>
                            @if ($immunizations->isNotEmpty())
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Vaccine</th>
                                            <th>Date Given</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($immunizations as $immunization)
                                            @foreach ($immunization->items as $item)
                                                <tr>
                                                    <td>{{ $item->item->item_name ?? 'N/A' }}</td>
                                                    <td>{{ $immunization->immunized_at ? \Carbon\Carbon::parse($immunization->immunized_at)->format('d/m/Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                </tr>
                                            @endforeach
                                            <!-- ✅ One row for the remarks -->
                                            @if ($immunization->notes)
                                                <tr>
                                                    <td colspan="3" style="font-style: italic; color: #555;">
                                                        <strong>Remarks:</strong> {{ $immunization->notes }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p style="font-size:12px; color:#555;">No vaccines recorded for this visit.</p>
                            @endif
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Remarks</div>
                            <table class="details-table">
                                <tr>
                                    <td class="label">Notes:</td>
                                    <td class="value">{{ $latestPrenatal->remarks->notes ?? 'No remarks added' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Generated By:</td>
                                    <td class="value">
                                        {{ $latestPrenatal->staff->first_name ?? '' }}
                                        {{ $latestPrenatal->staff->last_name ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>



                <tr>
                    <td>
                        <div class="content">
                            <div class="section-title">Summary</div>
                            <table class="details-table">
                                <tr>
                                    <td class="label">Visit Number:</td>
                                    <td class="value">{{ $latestVisitInfo->visit_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Gestational Age:</td>
                                    <td class="value">{{ $latestPrenatal->aog ?? 'N/A' }} weeks</td>
                                </tr>
                                <tr class="total-row">
                                    <td class="label">Next Appointment:</td>
                                    <td class="value">
                                        @if ($latestVisitInfo->next_visit_date && $latestVisitInfo->next_visit_time)
                                            {{ \Carbon\Carbon::parse($latestVisitInfo->next_visit_date . ' ' . $latestVisitInfo->next_visit_time)->format('d/m/Y h:i A') }}
                                        @elseif($latestVisitInfo->next_visit_date)
                                            {{ \Carbon\Carbon::parse($latestVisitInfo->next_visit_date)->format('d/m/Y') }}
                                        @else
                                            To be scheduled
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
