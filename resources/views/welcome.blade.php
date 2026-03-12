<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VMS – Visitor Management API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        header {
            background: #0f172a;
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 20px;
            font-weight: 600;
        }

        .badge {
            background: #22c55e;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .hero {
            margin-bottom: 50px;
        }

        .hero h2 {
            font-size: 36px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .hero p {
            color: #475569;
            max-width: 650px;
            line-height: 1.6;
            font-size: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            border: 1px solid #e5e7eb;
        }

        .card h3 {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        .endpoint-box {
            margin-top: 50px;
            background: #0f172a;
            color: #e5e7eb;
            padding: 24px;
            border-radius: 10px;
            font-family: monospace;
            font-size: 14px;
        }

        .endpoint-box span {
            color: #22c55e;
        }

        footer {
            text-align: center;
            padding: 30px;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>

<header>
    <h1>VMS – Visitor Management System</h1>
    <span class="badge">API ONLY</span>
</header>

<div class="container">

    <div class="hero">
        <h2>Internal Visitor Management API</h2>
        <p>
            Centralized REST API for managing visitor entries, exits, approvals,
            and audit logs across departments and locations.
            Designed for internal system integrations only.
        </p>
    </div>

    <div class="info-grid">
        <div class="card">
            <h3>🔌 REST APIs</h3>
            <p>Clean and secure REST endpoints for visitor create, update, check-in and check-out.</p>
        </div>

        <div class="card">
            <h3>🔐 Secure Access</h3>
            <p>Token-based authentication with role-level access for departments.</p>
        </div>

        <div class="card">
            <h3>📊 Central Records</h3>
            <p>Single source of truth for all visitor data across company locations.</p>
        </div>

        <div class="card">
            <h3>🧾 Audit Logs</h3>
            <p>Track every visitor action for compliance, reporting, and security audits.</p>
        </div>
    </div>

    <div class="endpoint-box">
        <div><span>GET</span> /api/visitors</div>
        <div><span>POST</span> /api/visitors</div>
        <div><span>PUT</span> /api/visitors/{id}</div>
        <div><span>POST</span> /api/visitors/{id}/check-in</div>
        <div><span>POST</span> /api/visitors/{id}/check-out</div>
    </div>

</div>

<footer>
    © {{ date('Y') }} Visitor Management System • Internal Use Only
</footer>

</body>
</html>
