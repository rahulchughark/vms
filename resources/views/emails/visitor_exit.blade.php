<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visitor Exit Notification</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;margin:30px 0;border-radius:6px;overflow:hidden;">
                
                <!-- Header -->
                <tr>
                    <td style="background:#16a34a;padding:20px;color:#ffffff;">
                        <h2 style="margin:0;">Visitor Exit Confirmed</h2>
                        <p style="margin:6px 0 0;font-size:14px;opacity:0.9;">
                            Visit successfully completed
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:25px;color:#333;">
                        <p>Hello <strong>{{ $meet_person_name }}</strong>,</p>

                        <p>
                            This is to inform you that the visitor has successfully completed their visit and exited the premises.
                        </p>

                        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;margin-top:15px;">
                            <tr style="background:#f9fafb;">
                                <td width="40%"><strong>Visitor Name</strong></td>
                                <td>{{ $visitor_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Visitor Email</strong></td>
                                <td>{{ $visitor_email }}</td>
                            </tr>
                            <tr style="background:#f9fafb;">
                                <td><strong>Purpose</strong></td>
                                <td>{{ $purpose }}</td>
                            </tr>
                            <tr>
                                <td><strong>Visit Date</strong></td>
                                <td>{{ $visit_date }}</td>
                            </tr>
                            <tr style="background:#f9fafb;">
                                <td><strong>Exit Time</strong></td>
                                <td>{{ $exit_time }}</td>
                            </tr>
                            <tr>
                                <td><strong>Card Number</strong></td>
                                <td>{{ $card_number }}</td>
                            </tr>
                        </table>

                        <p style="margin-top:20px;">
                            Thank you for your cooperation.
                        </p>

                        <p style="margin-top:25px;">
                            Regards,<br>
                            <strong>Visitor Management System</strong>
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f1f5f9;text-align:center;padding:12px;font-size:12px;color:#6b7280;">
                        © {{ date('Y') }} Visitor Management System
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
