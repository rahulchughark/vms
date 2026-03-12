<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visitor Approval Status</title>
</head>
@php
    $headerColor = match($status) {
        'Approved' => '#198754',  // green
        'Rejected' => '#dc3545',  // red
        'Pending'  => '#ffc107',  // yellow
        default    => '#0d6efd',  // default blue
    };
@endphp
<body style="margin:0;padding:0;background:#eef2f6;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
    <tr>
        <td align="center">

            <table width="650" cellpadding="0" cellspacing="0"
                style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:{{ $headerColor }};padding:20px 24px;color:#ffffff;">
                    <!-- <td style="background:#0d6efd;padding:20px 24px;color:#ffffff;"> -->
                        <h2 style="margin:0;font-size:22px;font-weight:600;">
                            Visitor {{ $status }}
                        </h2>
                        <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">
                            Approval Status Confirmation
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:24px;color:#333;font-size:14px;line-height:1.6;">

                        <p style="margin-top:0;">
                            Hello <strong>{{ $meet_person_name }}</strong>,
                        </p>

                        <p>
                            This is to confirm that the visitor listed below has been
                            <strong style="color:{{ $status == 'Approved' ? '#198754' : '#dc3545' }}">
                                {{ $status }}
                            </strong>
                            by you.
                        </p>

                        <!-- Visitor Summary -->
                        <div style="margin:20px 0;padding:16px;border:1px solid #e5e7eb;border-radius:8px;">
                            <h3 style="margin:0 0 12px;font-size:15px;color:#0d6efd;">
                                👤 Visitor Summary
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;width:35%;">Visitor Name</td>
                                    <td style="padding:6px 0;"><strong>{{ $visitor_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Purpose</td>
                                    <td style="padding:6px 0;">{{ $purpose }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Visit Date</td>
                                    <td style="padding:6px 0;"><strong>{{ $visit_date }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">In Time</td>
                                    <td style="padding:6px 0;">{{ $in_time }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Status Message -->
                        @if($status === 'Approved')
                            <div style="background:#e7f5ee;padding:12px 14px;border-left:4px solid #198754;border-radius:4px;">
                                ✅ The visitor is now approved and expected as per the scheduled time.
                            </div>
                        @else
                            <div style="background:#fdecea;padding:12px 14px;border-left:4px solid #dc3545;border-radius:4px;">
                                ❌ The visitor request has been rejected. No further action is required.
                            </div>
                        @endif

                        <p style="margin-top:24px;">
                            Regards,<br>
                            <strong>Visitor Management System</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f1f5f9;text-align:center;padding:12px;font-size:12px;color:#6b7280;">
                        This is an auto-generated email for your action record.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
