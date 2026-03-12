<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Visitor Scheduled</title>
</head>

<body style="margin:0;padding:0;background-color:#eef2f6;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
    <tr>
        <td align="center">

            <!-- Main Container -->
            <table width="650" cellpadding="0" cellspacing="0"
                style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#0d6efd;padding:20px 24px;color:#ffffff;">
                        <h2 style="margin:0;font-size:22px;font-weight:600;">
                            👋 New Visitor Scheduled
                        </h2>
                        <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">
                            Visitor Management System Notification
                        </p>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:24px;color:#333;font-size:14px;line-height:1.6;">

                        <p style="margin-top:0;">
                            Hello <strong>{{ $meet_person_name }}</strong>,
                        </p>

                        <p>
                            A new visitor has been registered and is scheduled to meet you.
                            Below are the complete visit details for your reference.
                        </p>

                        <!-- Visitor Details Card -->
                        <div style="margin:20px 0;padding:16px;border:1px solid #e5e7eb;border-radius:8px;">
                            <h3 style="margin:0 0 12px;font-size:15px;color:#0d6efd;">
                                👤 Visitor Details
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;width:35%;">Name</td>
                                    <td style="padding:6px 0;"><strong>{{ $name }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Email</td>
                                    <td style="padding:6px 0;">{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Mobile</td>
                                    <td style="padding:6px 0;">{{ $mobile_no }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Company ID</td>
                                    <td style="padding:6px 0;">{{ $company_id }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Visit Info Card -->
                        <div style="margin-bottom:20px;padding:16px;border:1px solid #e5e7eb;border-radius:8px;">
                            <h3 style="margin:0 0 12px;font-size:15px;color:#198754;">
                                📅 Visit Information
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;width:35%;">Purpose</td>
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
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Duration</td>
                                    <td style="padding:6px 0;">{{ $approx_total_time }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Note -->
                        <div style="background:#f8fafc;padding:12px 14px;border-left:4px solid #0d6efd;border-radius:4px;">
                            <strong>Note:</strong> Please ensure your availability at the scheduled time.
                        </div>

                        <p style="margin-top:24px;">
                            Regards,<br>
                            <strong>Visitor Management System</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f1f5f9;text-align:center;padding:12px;font-size:12px;color:#6b7280;">
                        This is an auto-generated email. Please do not reply.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
