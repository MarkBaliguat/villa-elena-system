<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Payment - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .wrap { background: #fff; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); padding: 3rem 2.5rem; text-align: center; max-width: 420px; width: 90%; }
        .spinner { width: 50px; height: 50px; border: 5px solid rgba(16,185,129,0.2); border-top-color: #10B981; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 1.5rem; }
        @keyframes spin { to { transform: rotate(360deg); } }
        h2 { font-size: 1.4rem; font-weight: 800; color: #1F2937; margin-bottom: 0.5rem; }
        p { font-size: 0.85rem; color: #6B7280; }
        .icon-wrap { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; }
        .icon-wrap.success { background: rgba(16,185,129,0.15); color: #10B981; }
        .icon-wrap.error   { background: rgba(239,68,68,0.12); color: #EF4444; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; border-radius: 14px; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; margin-top: 1.5rem; font-family: 'Poppins', sans-serif; }
        .btn-primary { background: linear-gradient(135deg, #FFD709, #FFA500); color: #fff; }
        .btn-secondary { background: #F3F4F6; color: #6B7280; }
        .error-msg { color: #EF4444; font-size: 0.8rem; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="wrap" id="content">
        <div class="spinner"></div>
        <h2>Verifying your payment…</h2>
        <p>Please wait while we confirm your card transaction.</p>
    </div>

    <script>
        const paymentIntentId = '{{ $payment_intent_id }}';
        const csrfToken       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function verify() {
            try {
                const r = await fetch(`/payment/card/verify?payment_intent_id=${paymentIntentId}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const d = await r.json();

                if (d.success) {
                    document.getElementById('content').innerHTML = `
                        <div class="icon-wrap success"><i class="fas fa-check"></i></div>
                        <h2>Payment Confirmed!</h2>
                        <p>Your booking at Villa Elena is confirmed. A confirmation email has been sent to you.</p>
                        <div style="display:flex;gap:0.8rem;justify-content:center;flex-wrap:wrap;">
                            <a href="/" class="btn btn-secondary"><i class="fas fa-home"></i> Home</a>
                            <a href="/my-bookings" class="btn btn-primary"><i class="fas fa-calendar-check"></i> My Bookings</a>
                        </div>
                    `;
                } else {
                    throw new Error(d.message || 'Payment verification failed');
                }
            } catch (e) {
                document.getElementById('content').innerHTML = `
                    <div class="icon-wrap error"><i class="fas fa-times"></i></div>
                    <h2>Payment Failed</h2>
                    <p class="error-msg">${e.message}</p>
                    <a href="/booking-page" class="btn btn-primary">Try Again</a>
                `;
            }
        }

        verify();
    </script>
</body>
</html>