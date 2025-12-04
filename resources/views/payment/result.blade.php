<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>وضعیت پرداخت</title>
    <style>
        body {
            font-family: Vazirmatn, sans-serif;
            background: #f6f6f6;
            padding: 40px;
            text-align: center;
        }
        .box {
            background: white;
            max-width: 450px;
            margin: auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px #0001;
        }
        .ok { color: #00a66c; font-size: 22px; margin-bottom: 15px; }
        .fail { color: #e74c3c; font-size: 22px; margin-bottom: 15px; }
        a.btn {
            display: block;
            margin-top: 25px;
            padding: 12px;
            border-radius: 10px;
            background: #5E2B85;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="box">

    @if($success)
        <div class="ok">✔ پرداخت موفق بود</div>
        <div>شماره پیگیری: <b>{{ $ref }}</b></div>
        <div>کد سفارش: <b>{{ $orderCode }}</b></div>
    @else
        <div class="fail">✖ پرداخت ناموفق</div>
        <div>{{ $message ?? 'تراکنش ناموفق بود' }}</div>
    @endif

    <a class="btn" href="https://YOUR_FRONTEND_DOMAIN.com/payment/result?token={{ $token }}">
        بازگشت به سایت
    </a>

</div>
</body>
</html>
