<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #1d4ed8; /* Blue accent for general communication */
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
        }
        .content {
            font-size: 15px;
            line-height: 1.7;
            color: #1e293b;
        }
        .footer {
            text-align: center;
            margin-top: 45px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        .btn-link {
            display: inline-block;
            background-color: #1d4ed8;
            color: white !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Smart Crea Cities</div>
            <p style="color: #64748b; margin: 5px 0 0 0;">CREA-PR - Conselho Regional de Engenharia e Agronomia do Paraná</p>
        </div>

        <div class="content">
            {!! nl2br(e($bodyContent)) !!}
        </div>

        <div class="footer">
            <p><strong>CREA-PR - Conselho Regional de Engenharia e Agronomia do Paraná</strong></p>
            <p>Smart Crea Cities - Trilha Formativa dos 3E's</p>
            <p style="margin-top: 10px;">Este é um comunicado oficial enviado pela equipe do CREA-PR.</p>
        </div>
    </div>
</body>
</html>
