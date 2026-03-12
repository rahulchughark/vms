<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visitor Reassigned</title>
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
                    <td style="background:#ffc107;padding:20px 24px;color:#ffffff;">
                        <h2 style="margin:0;font-size:22px;font-weight:600;">
                            🔔 Visitor Reassigned
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
                            Hello <strong>{{ $reassign_name }}</strong>,
                        </p>

                        <p>
                            A visitor has been <strong>reassigned</strong> to you. Please find the details below:
                        </p>

                        <!-- Visitor Details Card -->
                        <div style="margin:20px 0;padding:16px;border:1px solid #e5e7eb;border-radius:8px;background:#fef9f0;">
                            <h3 style="margin:0 0 12px;font-size:15px;color:#d97706;">
                                👤 Visitor Details
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;width:35%;">Name</td>
                                    <td style="padding:6px 0;"><strong>{{ $visitor_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Email</td>
                                    <td style="padding:6px 0;">{{ $visitor_email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Phone</td>
                                    <td style="padding:6px 0;">{{ $visitor_phone }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Company</td>
                                    <td style="padding:6px 0;">{{ $company_id }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Reassignment Info Card -->
                        <div style="margin-bottom:20px;padding:16px;border:1px solid #e5e7eb;border-radius:8px;background:#f0f9ff;">
                            <h3 style="margin:0 0 12px;font-size:15px;color:#0d6efd;">
                                🔄 Reassignment Information
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;width:35%;">Assigned By</td>
                                    <td style="padding:6px 0;">{{ $action_by }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Reassign Date</td>
                                    <td style="padding:6px 0;">{{ $reassign_date }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Note -->
                        <div style="background:#fef3c7;padding:12px 14px;border-left:4px solid #d97706;border-radius:4px;">
                            <strong>Note:</strong> Please reach out to the visitor at the scheduled time and update the system if necessary.
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
