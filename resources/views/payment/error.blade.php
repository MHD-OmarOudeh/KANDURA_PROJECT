<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .error-icon {
            width: 100px;
            height: 100px;
            background: #ef4444;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease;
        }

        .error-icon svg {
            width: 60px;
            height: 60px;
            stroke: white;
            stroke-width: 3;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            color: #1e293b;
            font-size: 2em;
            margin-bottom: 15px;
        }

        .message {
            color: #64748b;
            font-size: 1.1em;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .error-details {
            background: #fee2e2;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            color: #991b1b;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .close-message {
            color: #94a3b8;
            font-size: 0.9em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <h1>❌ Error</h1>
        <p class="message">An error occurred while processing your request.</p>

        <div class="error-details">
            <strong>{{ $message }}</strong>
        </div>

        <p class="close-message">Please contact support if this issue persists.</p>
    </div>
</body>
</html>
