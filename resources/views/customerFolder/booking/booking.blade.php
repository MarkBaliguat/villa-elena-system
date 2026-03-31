<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        .booking-page-wrapper {
            --booking-primary-yellow: #FFD709;
            --booking-secondary-yellow: #FFA500;
            --booking-yellow-dark: #F59E0B;
            --booking-gcash-blue: #007DFF;
            --booking-card-purple: #6366F1;
            --booking-light-bg: #FFFBF0;
            --booking-card-bg: #FFFFFF;
            --booking-text-dark: #1F2937;
            --booking-text-medium: #6B7280;
            --booking-text-light: #9CA3AF;
            --booking-border-color: #E5E7EB;
            --booking-green: #10B981;
            --booking-green-dark: #059669;
            --booking-green-light: rgb(171, 249, 223);
            --booking-red: #EF4444;
            --booking-red-dark: #DC2626;
            --booking-red-light: rgba(239, 68, 68, 0.1);
            --booking-yellow-light: rgba(255, 215, 0, 0.15);
            --booking-yellow-mid: rgba(255, 215, 0, 0.35);
            --booking-blue-light: rgba(0, 125, 255, 0.1);
            --booking-purple: #8B5CF6;
            --booking-purple-light: rgba(139, 92, 246, 0.1);
        }

        body { font-family: 'Poppins', sans-serif; background: #FFFFFF; color: #1F2937; }
        .cursive-font { font-family: 'Dancing Script', cursive; }
        html { scroll-behavior: smooth; }

        /* ─── TOAST ─── */
        .toast {
            position: fixed; top: 5rem; right: 1.5rem;
            background: var(--booking-text-dark); color: #fff;
            padding: 1rem 1.3rem; border-radius: 14px; font-size: 0.85rem;
            max-width: 380px; min-width: 280px; display: flex; gap: 0.7rem;
            align-items: flex-start; z-index: 99999;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
            animation: toastSlide 0.4s cubic-bezier(0.34,1.56,0.64,1); font-weight: 500;
        }
        .toast.error   { background: linear-gradient(135deg,#EF4444,#DC2626); border-left:4px solid #fff3; }
        .toast.success { background: linear-gradient(135deg,#10B981,#059669); border-left:4px solid #fff3; }
        .toast.warning { background: linear-gradient(135deg,#F59E0B,#D97706); border-left:4px solid #fff3; }
        @keyframes toastSlide { from{opacity:0;transform:translateX(120px) scale(0.9)} to{opacity:1;transform:translateX(0) scale(1)} }
        .toast-close { margin-left:auto; cursor:pointer; opacity:.7; flex-shrink:0; padding:2px 4px; border-radius:4px; transition:opacity .2s; }
        .toast-close:hover { opacity:1; }
        .toast i.toast-icon { flex-shrink:0; margin-top:2px; font-size:1.1rem; }

        /* ─── LAYOUT ─── */
        .booking-page-wrapper { background: transparent; }
        .main-content { margin-top:80px; min-height:calc(100vh - 300px); padding:2.5rem 1rem 3rem; }
        .page-title-wrap { text-align:center; margin-bottom:2.5rem; animation:fadeInDown .6s ease-out; }
        @keyframes fadeInDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(0)} }
        .page-title { font-size:2.8rem; font-weight:800; background:#1F2937; -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; display:inline-block; letter-spacing:-.5px; }
        .page-title-line { width:100px; height:5px; background:linear-gradient(90deg,var(--booking-primary-yellow),var(--booking-secondary-yellow),var(--booking-yellow-dark)); border-radius:3px; margin:.8rem auto 0; box-shadow:0 2px 8px rgba(255,215,0,.4); animation:expandLine .8s ease-out .3s both; }
        @keyframes expandLine { from{width:0;opacity:0} to{width:100px;opacity:1} }

        .content-layout { display:flex; gap:2rem; max-width:1200px; margin:0 auto; align-items:flex-start; }

        /* ─── STEPPER ─── */
        .stepper { position:sticky; top:100px; display:flex; flex-direction:column; gap:0; min-width:200px; animation:fadeIn .8s ease-out .2s both; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .step-item { display:flex; align-items:flex-start; gap:1rem; position:relative; padding-bottom:2.5rem; }
        .step-item:last-child { padding-bottom:0; }
        .step-item::before { content:''; position:absolute; left:24px; top:50px; width:3px; height:calc(100% - 50px); background:linear-gradient(180deg,#E5E7EB,#D1D5DB); transition:all .6s cubic-bezier(.4,0,.2,1); }
        .step-item:last-child::before { display:none; }
        .step-item.active::before,.step-item.completed::before { background:linear-gradient(180deg,var(--booking-primary-yellow),var(--booking-secondary-yellow)); }
        .step-circle { width:50px; height:50px; border-radius:50%; border:4px solid #E5E7EB; background:linear-gradient(135deg,#FFFFFF,#F9FAFB); display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:700; color:var(--booking-text-light); position:relative; z-index:2; transition:all .5s cubic-bezier(.34,1.56,.64,1); box-shadow:0 4px 12px rgba(0,0,0,.08); flex-shrink:0; }
        .step-circle.active,.step-circle.completed { border-color:transparent; background:linear-gradient(135deg,var(--booking-primary-yellow),var(--booking-secondary-yellow)); color:#fff; box-shadow:0 8px 20px rgba(255,215,0,.4); transform:scale(1.15); }
        .step-circle i { font-size:1.1rem; }
        .step-content { display:flex; flex-direction:column; gap:.3rem; padding-top:.5rem; }
        .step-label { font-size:.95rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--booking-text-light); transition:all .4s; }
        .step-desc { font-size:.75rem; color:var(--booking-text-medium); line-height:1.4; }
        .step-item.active .step-label { color:var(--booking-secondary-yellow); }
        .step-item.active .step-desc  { color:var(--booking-text-dark); }

        /* ─── PANELS ─── */
        .panels-container { position:relative; overflow:hidden; flex:1; }
        .step-panel { display:none; }
        .step-panel.active { display:block; }

        /* ─── BOOKING CARD ─── */
        .booking-card { background:#FFFFFF; border-radius:24px; box-shadow:0 10px 40px rgba(0,0,0,.1); border:2px solid #E5E7EB; overflow:hidden; margin-bottom:1.5rem; position:relative; }
        .booking-card::before { content:''; position:absolute; top:0;left:0;right:0; height:6px; background:linear-gradient(90deg,var(--booking-primary-yellow),var(--booking-secondary-yellow),var(--booking-yellow-dark)); }
        .card-header { padding:2rem 2.5rem .5rem; background:linear-gradient(180deg,rgba(255,215,0,.05),transparent); }
        .card-header h2 { font-size:1.6rem; font-weight:800; background:linear-gradient(135deg,var(--booking-text-dark),var(--booking-text-medium)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:.3rem; letter-spacing:-.3px; }
        .card-header p  { font-size:.85rem; color:var(--booking-text-medium); font-weight:500; }
        .card-body { padding:2rem 2.5rem 2.5rem; }

        /* ─── FORM ─── */
        .form-group { margin-bottom:1.5rem; animation:fadeInUp .5s ease-out both; }
        .form-group:nth-child(1){animation-delay:.1s} .form-group:nth-child(2){animation-delay:.2s} .form-group:nth-child(3){animation-delay:.3s} .form-group:nth-child(4){animation-delay:.4s}
        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .form-label { display:flex; align-items:center; gap:8px; font-weight:700; margin-bottom:.7rem; color:var(--booking-text-dark); font-size:.85rem; letter-spacing:.3px; text-transform:uppercase; }
        .form-label i { color:var(--booking-primary-yellow); font-size:1.1rem; filter:drop-shadow(0 2px 4px rgba(255,215,0,.3)); }
        .form-label .required { color:var(--booking-red); margin-left:2px; }
        .form-input,.form-textarea { width:100%; padding:14px 18px; border:2px solid var(--booking-border-color); border-radius:14px; font-size:.95rem; transition:all .3s cubic-bezier(.4,0,.2,1); background:linear-gradient(135deg,#FFFFFF,#FAFAFA); color:var(--booking-text-dark); font-family:'Poppins',sans-serif; font-weight:500; }
        .form-input:focus,.form-textarea:focus { outline:none; border-color:var(--booking-primary-yellow); box-shadow:0 0 0 4px var(--booking-yellow-light),0 4px 12px rgba(255,215,0,.2); transform:translateY(-2px); background:#FFFFFF; }
        .form-input:disabled { background:linear-gradient(135deg,#F3F4F6,#E5E7EB); color:var(--booking-text-light); cursor:not-allowed; border-color:#D1D5DB; }
        .form-input.error,.form-textarea.error { border-color:var(--booking-red)!important; box-shadow:0 0 0 3px rgba(239,68,68,.15)!important; animation:shake .4s ease-in-out; }
        @keyframes shake { 0%,100%{transform:translateX(0)} 20%{transform:translateX(-6px)} 40%{transform:translateX(6px)} 60%{transform:translateX(-4px)} 80%{transform:translateX(4px)} }
        .form-textarea { resize:vertical; min-height:100px; }
        .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; }
        .row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
        .error-message { color:var(--booking-red); font-size:.75rem; margin-top:.4rem; display:none; font-weight:600; align-items:center; gap:4px; }
        .error-message.show { display:flex; }

        /* ─── PAY CARDS ─── */
        .pay-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-top:.5rem; }
        .pay-card { border:3px solid var(--booking-border-color); border-radius:18px; padding:1.8rem 1.2rem 1.5rem; text-align:center; cursor:pointer; transition:all .4s cubic-bezier(.34,1.56,.64,1); background:linear-gradient(135deg,#FFFFFF,#FAFAFA); position:relative; overflow:hidden; }
        .pay-card::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,var(--booking-yellow-light),transparent); opacity:0; transition:opacity .4s; }
        .pay-card:hover { border-color:var(--booking-primary-yellow); transform:translateY(-6px) scale(1.02); box-shadow:0 12px 28px rgba(255,215,0,.25); }
        .pay-card:hover::before { opacity:1; }
        .pay-card.selected { border-color:var(--booking-primary-yellow); background:linear-gradient(135deg,var(--booking-yellow-light),rgba(255,215,0,.05)); box-shadow:0 0 0 4px var(--booking-yellow-light),0 8px 24px rgba(255,215,0,.3); transform:scale(1.05); }
        .pay-card.selected::before { opacity:1; }
        .pay-card.selected .pay-check { opacity:1; transform:scale(1) rotate(360deg); }
        .pay-check { position:absolute; top:12px; right:12px; width:28px; height:28px; background:linear-gradient(135deg,var(--booking-green),var(--booking-green-dark)); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; opacity:0; transform:scale(0) rotate(0deg); transition:all .5s cubic-bezier(.34,1.56,.64,1); z-index:1; box-shadow:0 4px 12px rgba(16,185,129,.4); }
        .pay-card .pay-icon { position:relative; z-index:1; font-size:2.5rem; margin-bottom:.8rem; transition:all .4s; filter:drop-shadow(0 4px 8px rgba(0,0,0,.1)); }
        .pay-card .pay-icon i { background:linear-gradient(135deg,var(--booking-text-medium),var(--booking-text-dark)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
        .pay-card.selected .pay-icon,.pay-card:hover .pay-icon { transform:scale(1.15) rotate(5deg); }
        .pay-card.selected .pay-icon i { background:linear-gradient(135deg,var(--booking-secondary-yellow),var(--booking-yellow-dark)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
        .pay-card .pay-title { position:relative; z-index:1; font-size:1rem; font-weight:700; color:var(--booking-text-dark); margin-bottom:.3rem; letter-spacing:.3px; }
        .pay-card .pay-sub { position:relative; z-index:1; font-size:.75rem; color:var(--booking-text-medium); font-weight:500; }
        .pay-grid.error-highlight .pay-card { border-color:rgba(239,68,68,.4); }

        /* ─── AMOUNT CHIPS ─── */
        .amount-row { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-top:.5rem; }
        .amount-chip { border:3px solid var(--booking-border-color); border-radius:18px; padding:1.5rem 1rem; text-align:center; cursor:pointer; transition:all .4s cubic-bezier(.34,1.56,.64,1); position:relative; background:linear-gradient(135deg,#FFFFFF,#FAFAFA); overflow:hidden; }
        .amount-chip::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,var(--booking-green-light),transparent); opacity:0; transition:opacity .4s; z-index:0; }
        .chip-label,.chip-amount,.chip-note { position:relative; z-index:1; }
        .amount-chip:hover { border-color:var(--booking-green); transform:translateY(-6px) scale(1.02); box-shadow:0 6px 14px rgb(43,195,63); }
        .amount-chip:hover::before { opacity:1; }
        .amount-chip.selected { border-color:var(--booking-green); background:linear-gradient(135deg,var(--booking-purple-light),rgba(43,195,246,.05)); box-shadow:0 0 0 4px var(--booking-purple-light),0 8px 24px rgba(43,195,63,.3); transform:scale(1.05); }
        .amount-chip.selected::before { opacity:1; }
        .amount-chip.selected .chip-check { opacity:1; transform:scale(1) rotate(360deg); }
        .chip-check { position:absolute; top:10px; right:10px; width:26px; height:26px; background:linear-gradient(135deg,var(--booking-green),var(--booking-green-dark)); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.7rem; opacity:0; transform:scale(0) rotate(0deg); transition:all .5s cubic-bezier(.34,1.56,.64,1); box-shadow:0 4px 12px rgba(16,185,129,.4); }
        .chip-label { font-size:.75rem; font-weight:700; color:var(--booking-text-medium); text-transform:uppercase; letter-spacing:.8px; margin-bottom:.5rem; }
        .chip-amount { font-size:1.4rem; font-weight:800; background:linear-gradient(135deg,var(--booking-text-dark),#1F2937); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:.3rem; }
        .chip-note { font-size:.7rem; color:var(--booking-text-light); font-weight:500; }

        /* ─── SUMMARY ─── */
        .section-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:1.5px; color:var(--booking-text-medium); margin-bottom:1rem; padding-bottom:.6rem; border-bottom:2px solid var(--booking-border-color); display:flex; align-items:center; gap:8px; }
        .section-label::before { content:''; width:4px; height:16px; background:linear-gradient(135deg,var(--booking-primary-yellow),var(--booking-secondary-yellow)); border-radius:2px; }
        .summary-block { margin-bottom:2rem; }
        .s-row { display:flex; justify-content:space-between; align-items:center; padding:.7rem 0; font-size:.9rem; border-bottom:1px solid #F3F4F6; }
        .s-row:last-child { border-bottom:none; }
        .s-row .s-label { color:var(--booking-text-medium); font-weight:500; }
        .s-row .s-value { font-weight:700; color:var(--booking-text-dark); }
        .accom-item { display:flex; justify-content:space-between; align-items:flex-start; padding:1rem 0; border-bottom:2px solid #F3F4F6; }
        .accom-item:last-child { border-bottom:none; }
        .accom-name { font-size:.95rem; font-weight:700; color:var(--booking-text-dark); margin-bottom:.4rem; }
        .accom-badge { display:inline-flex; align-items:center; gap:5px; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; padding:4px 12px; border-radius:20px; }
        .accom-badge.room    { background:linear-gradient(135deg,rgba(59,130,246,.15),rgba(29,78,216,.1)); color:#1e40af; }
        .accom-badge.cottage { background:linear-gradient(135deg,rgba(16,185,129,.15),rgba(5,150,105,.1)); color:#065f46; }
        .accom-calc { font-size:.75rem; color:var(--booking-text-light); margin-top:.3rem; font-weight:500; }
        .accom-price { font-size:1.1rem; font-weight:800; background:linear-gradient(135deg,#3B82F6,#2563EB); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; white-space:nowrap; }
        .total-price-box { background:linear-gradient(135deg,rgba(16,185,129,.15),rgba(5,150,105,.08)); border:3px solid var(--booking-green); border-radius:18px; padding:1.5rem 1.8rem; display:flex; justify-content:space-between; align-items:center; margin-top:1rem; box-shadow:0 8px 24px rgba(16,185,129,.2); }
        .total-price-box .t-label { font-size:.85rem; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:var(--booking-green-dark); }
        .total-price-box .t-amount { font-size:2rem; font-weight:900; background:linear-gradient(135deg,var(--booking-green),var(--booking-green-dark)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

        /* ─── RULES ─── */
        .rules-card { margin-bottom:1.2rem; }
        .rules-header { display:flex; align-items:center; gap:1rem; padding:2rem 2.5rem 1rem; }
        .rules-icon { width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0; box-shadow:0 4px 12px rgba(0,0,0,.1); }
        .rules-icon.room-icon    { background:linear-gradient(135deg,rgba(59,130,246,.15),rgba(29,78,216,.1)); color:#2563eb; }
        .rules-icon.cottage-icon { background:linear-gradient(135deg,rgba(16,185,129,.15),rgba(5,150,105,.1)); color:#10b981; }
        .rules-icon.general-icon { background:linear-gradient(135deg,rgba(255,215,0,.2),rgba(255,165,0,.15)); color:var(--booking-secondary-yellow); }
        .rules-header h3 { font-size:1.1rem; font-weight:800; color:var(--booking-text-dark); }
        .rules-header p  { font-size:.75rem; color:var(--booking-text-medium); margin-top:.2rem; font-weight:500; }
        .rules-list { list-style:none; padding:0 2.5rem 2rem; }
        .rules-list li { display:flex; gap:.8rem; padding:.7rem 0; font-size:.85rem; color:var(--booking-text-medium); border-bottom:1px solid #F3F4F6; align-items:flex-start; line-height:1.6; font-weight:500; }
        .rules-list li:last-child { border-bottom:none; }
        .rules-list .ri { font-size:.5rem; margin-top:6px; flex-shrink:0; }
        .rules-list .ri.y{color:var(--booking-secondary-yellow)} .rules-list .ri.g{color:var(--booking-green)} .rules-list .ri.r{color:var(--booking-red)}
        .cancel-notice { background:linear-gradient(135deg,var(--booking-red-light),rgba(239,68,68,.05)); border:2px solid var(--booking-red); border-radius:14px; padding:1.2rem 1.3rem; display:flex; gap:.8rem; align-items:flex-start; }
        .cancel-notice i { color:var(--booking-red); flex-shrink:0; margin-top:2px; font-size:1.1rem; }
        .cancel-notice p { font-size:.8rem; color:var(--booking-red-dark); line-height:1.6; font-weight:500; }
        .contact-strip { display:flex; gap:1.5rem; flex-wrap:wrap; margin-top:1rem; padding:1.2rem 1.3rem; background:linear-gradient(135deg,#F9FAFB,#F3F4F6); border:2px solid var(--booking-border-color); border-radius:14px; }
        .contact-item { display:flex; align-items:center; gap:.6rem; font-size:.8rem; color:var(--booking-text-medium); font-weight:600; }
        .contact-item i { color:var(--booking-primary-yellow); font-size:1rem; filter:drop-shadow(0 2px 4px rgba(255,215,0,.3)); }

        /* ─── BUTTONS ─── */
        .btn-row { display:flex; gap:1rem; margin-top:2rem; }
        .action-btn { display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:16px 28px; border-radius:14px; font-weight:700; font-size:.9rem; border:none; cursor:pointer; transition:all .3s cubic-bezier(.34,1.56,.64,1); font-family:'Poppins',sans-serif; letter-spacing:.3px; text-decoration:none; position:relative; overflow:hidden; }
        .action-btn::before { content:''; position:absolute; top:50%;left:50%; width:0;height:0; border-radius:50%; background:rgba(255,255,255,.3); transform:translate(-50%,-50%); transition:width .6s,height .6s; }
        .action-btn:hover::before { width:300px;height:300px; }
        .action-btn:active { transform:scale(.95); }
        .action-btn:disabled { opacity:.5;cursor:not-allowed;transform:none!important; }
        .action-btn:disabled::before { display:none; }
        .action-btn i,.action-btn span { position:relative;z-index:1; }
        .btn-primary  { background:linear-gradient(135deg,var(--booking-primary-yellow),var(--booking-secondary-yellow),var(--booking-yellow-dark)); color:#fff; flex:1; box-shadow:0 6px 20px rgba(255,215,0,.4); text-shadow:0 1px 2px rgba(0,0,0,.1); }
        .btn-primary:hover:not(:disabled) { box-shadow:0 10px 30px rgba(255,215,0,.5); transform:translateY(-3px); }
        .btn-secondary { background:linear-gradient(135deg,#FFFFFF,#F9FAFB); color:var(--booking-text-medium); border:2px solid var(--booking-border-color); box-shadow:0 4px 12px rgba(0,0,0,.08); }
        .btn-secondary:hover { background:linear-gradient(135deg,#F9FAFB,#F3F4F6); border-color:var(--booking-text-medium); transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.12); }
        .btn-success  { background:linear-gradient(135deg,var(--booking-green),var(--booking-green-dark)); color:#fff; flex:1; box-shadow:0 6px 20px rgba(16,185,129,.4); text-shadow:0 1px 2px rgba(0,0,0,.1); }
        .btn-success:hover:not(:disabled) { background:linear-gradient(135deg,var(--booking-green-dark),#047857); box-shadow:0 10px 30px rgba(16,185,129,.5); transform:translateY(-3px); }
        .btn-gcash { background:linear-gradient(135deg,var(--booking-gcash-blue),#0062CC); color:#fff; flex:1; box-shadow:0 6px 20px rgba(0,125,255,.4); text-shadow:0 1px 2px rgba(0,0,0,.1); }
        .btn-gcash:hover:not(:disabled) { background:linear-gradient(135deg,#0062CC,#004C99); box-shadow:0 10px 30px rgba(0,125,255,.5); transform:translateY(-3px); }
        .btn-card { background:linear-gradient(135deg,#6366F1,#4F46E5); color:#fff; flex:1; box-shadow:0 6px 20px rgba(99,102,241,.4); text-shadow:0 1px 2px rgba(0,0,0,.1); }
        .btn-card:hover:not(:disabled) { background:linear-gradient(135deg,#4F46E5,#4338CA); box-shadow:0 10px 30px rgba(99,102,241,.5); transform:translateY(-3px); }
        .btn-danger-ghost { background:transparent; color:var(--booking-red); border:2px solid var(--booking-red); width:100%; margin-top:1rem; justify-content:center; }
        .btn-danger-ghost:hover { background:var(--booking-red-light); border-color:var(--booking-red-dark); transform:translateY(-3px); box-shadow:0 6px 20px rgba(239,68,68,.3); }

        /* ─── SUCCESS ─── */
        .success-wrap { text-align:center; padding:3rem 1.5rem 2rem; }
        .success-icon { width:90px;height:90px; background:linear-gradient(135deg,rgba(16,185,129,.15),rgba(5,150,105,.1)); border-radius:50%; display:flex;align-items:center;justify-content:center; margin:0 auto 1.5rem; font-size:2.5rem;color:var(--booking-green); animation:scaleIn .6s cubic-bezier(.34,1.56,.64,1); box-shadow:0 8px 24px rgba(16,185,129,.3); }
        @keyframes scaleIn { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
        .success-wrap h3 { font-size:1.8rem;font-weight:800; background:linear-gradient(135deg,var(--booking-green),var(--booking-green-dark)); -webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent; margin-bottom:.5rem; }
        .success-wrap > p { font-size:.9rem;color:var(--booking-text-medium);margin-bottom:1.5rem;font-weight:500; }
        .ref-box { background:linear-gradient(135deg,#F9FAFB,#F3F4F6); border:2px solid var(--booking-border-color); border-radius:14px; padding:1.3rem 1.5rem; text-align:left;display:inline-block; min-width:300px; box-shadow:0 4px 16px rgba(0,0,0,.08); }
        .ref-row { display:flex;justify-content:space-between; font-size:.8rem;padding:.4rem 0; color:var(--booking-text-medium);font-weight:600; }
        .ref-row span:last-child { font-weight:700;color:var(--booking-text-dark); }
        .success-btns { display:flex;gap:.8rem;justify-content:center; margin-top:2rem;flex-wrap:wrap; }
        .success-btns .action-btn { flex:0 0 auto;min-width:150px; }

        .spinner { width:40px;height:40px; border:4px solid rgba(255,215,0,.2); border-top-color:var(--booking-primary-yellow); border-radius:50%; animation:spin .8s linear infinite; margin:3rem auto; }
        @keyframes spin { to{transform:rotate(360deg)} }

        /* ═══════════════════════════════════════
           CARD PAYMENT MODAL
        ═══════════════════════════════════════ */
        .card-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(6px);
            z-index: 10000;
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
            opacity: 0; pointer-events: none;
            transition: opacity 0.3s;
            overflow-y: auto;
        }
        .card-modal-overlay.open { opacity: 1; pointer-events: all; }

        .card-modal {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 460px;
            max-height: calc(100dvh - 2rem);
            overflow-y: auto;
            overflow-x: hidden;
            transform: translateY(30px) scale(0.97);
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            margin: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .card-modal::-webkit-scrollbar { display: none; }
        .card-modal-overlay.open .card-modal { transform: translateY(0) scale(1); }

        .card-modal-header {
            padding: 1.4rem 1.5rem 0.9rem;
            background: linear-gradient(135deg, #6366F1, #4F46E5);
            color: #fff;
            position: sticky; top: 0; z-index: 2;
        }
        .card-modal-header h3 { font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.2rem; }
        .card-modal-header p { font-size: 0.75rem; opacity: 0.85; font-weight: 500; }
        .card-modal-close {
            position: absolute; top: 1rem; right: 1rem;
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.2);
            border: none; border-radius: 50%;
            color: #fff; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            transition: background 0.2s;
            touch-action: manipulation;
        }
        .card-modal-close:hover { background: rgba(255,255,255,0.35); }

        .card-preview {
            margin: 0 1.2rem;
            background: linear-gradient(135deg, #1e1b4b, #312e81, #4338ca);
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(67,56,202,0.35);
            min-height: 90px;
            margin-top: -0.3rem;
        }
        .card-preview::before { content:''; position:absolute; top:-25px; right:-25px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.07); }
        .card-preview::after  { content:''; position:absolute; bottom:-15px; left:15px; width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.05); }
        .card-preview-chip { width:32px; height:24px; background:linear-gradient(135deg,#d4af37,#f0d060); border-radius:5px; margin-bottom:0.6rem; display:flex; align-items:center; justify-content:center; }
        .card-preview-chip::after { content:''; width:18px; height:13px; border:1.5px solid rgba(0,0,0,0.3); border-radius:3px; }
        .card-preview-number { font-size: clamp(0.85rem, 3.5vw, 1.1rem); font-weight:700; letter-spacing:2px; font-family:'Courier New',monospace; margin-bottom:0.5rem; opacity:0.95; position:relative; z-index:1; }
        .card-preview-bottom { display:flex; justify-content:space-between; align-items:flex-end; position:relative; z-index:1; gap:0.5rem; }
        .card-preview-label { font-size:0.55rem; opacity:0.7; text-transform:uppercase; letter-spacing:1px; margin-bottom:2px; }
        .card-preview-value { font-size: clamp(0.7rem, 2.5vw, 0.85rem); font-weight:700; letter-spacing:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:120px; }
        .card-preview-brand { font-size:1.4rem; opacity:0.9; flex-shrink:0; }

        .card-modal-body { padding: 1.2rem 1.5rem 1.5rem; }
        .card-input-group { margin-bottom: 1rem; }
        .card-input-label { display:flex; align-items:center; gap:6px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--booking-text-dark); margin-bottom:0.45rem; }
        .card-input-label i { color:#6366F1; font-size:0.85rem; }

        .card-field { width:100%; padding:11px 14px; border:2px solid #E5E7EB; border-radius:11px; font-size:0.9rem; font-family:'Poppins',sans-serif; font-weight:600; color:#1F2937; background:#FAFAFA; transition:all 0.3s; box-sizing:border-box; -webkit-appearance:none; appearance:none; }
        .card-field:focus { outline:none; border-color:#6366F1; box-shadow:0 0 0 3px rgba(99,102,241,0.15); background:#fff; }
        .card-field.error { border-color:var(--booking-red)!important; box-shadow:0 0 0 3px rgba(239,68,68,0.15)!important; animation:shake 0.4s ease-in-out; }
        .card-field::placeholder { color:#D1D5DB; font-weight:400; }

        .card-number-wrap { position:relative; }
        .card-number-wrap .card-field { padding-right:46px; letter-spacing:1.5px; }
        .card-brand-icon { position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:1.3rem; opacity:0.7; }
        .card-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:0.8rem; }
        .card-error-msg { color:var(--booking-red); font-size:0.7rem; margin-top:0.3rem; display:none; font-weight:600; }
        .card-error-msg.show { display:flex; align-items:center; gap:4px; }

        .secure-badge { display:flex; align-items:center; gap:0.5rem; background:#F0FDF4; border:1.5px solid #86EFAC; border-radius:9px; padding:0.6rem 0.9rem; font-size:0.72rem; color:#166534; font-weight:600; margin-bottom:1rem; }
        .secure-badge i { color:#16A34A; font-size:0.85rem; flex-shrink:0; }

        .card-submit-btn { width:100%; padding:14px; background:linear-gradient(135deg,#6366F1,#4F46E5); color:#fff; border:none; border-radius:12px; font-size:0.95rem; font-weight:700; cursor:pointer; font-family:'Poppins',sans-serif; display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.3s cubic-bezier(0.34,1.56,0.64,1); box-shadow:0 4px 16px rgba(99,102,241,0.35); touch-action: manipulation; }
        .card-submit-btn:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 24px rgba(99,102,241,0.45); }
        .card-submit-btn:disabled { opacity:0.6; cursor:not-allowed; transform:none!important; }

        .accepted-cards { display:flex; align-items:center; gap:0.5rem; justify-content:center; flex-wrap:wrap; margin-top:0.8rem; font-size:0.7rem; color:var(--booking-text-light); font-weight:500; }
        .accepted-cards span { display:inline-flex; align-items:center; justify-content:center; background:#F3F4F6; border-radius:5px; padding:3px 8px; font-weight:700; font-size:0.65rem; color:var(--booking-text-dark); letter-spacing:0.5px; }

        @media (max-width: 480px) {
            .card-modal-overlay { padding: 0; align-items: flex-end; }
            .card-modal { border-radius: 20px 20px 0 0; max-height: 93dvh; max-height: 93vh; width: 100%; }
            .card-modal-header { padding: 1.2rem 1.2rem 0.8rem; }
            .card-modal-header h3 { font-size: 1rem; }
            .card-preview { margin: 0 1rem; padding: 1rem 1.1rem; border-radius: 12px; }
            .card-modal-body { padding: 1rem 1.2rem 1.4rem; }
            .card-row-2 { grid-template-columns: 1fr 1fr; gap: 0.7rem; }
        }
        @media (max-width: 360px) {
            .card-row-2 { grid-template-columns: 1fr; }
            .card-preview-number { font-size: 0.8rem; letter-spacing: 1px; }
            .card-preview-value { max-width: 90px; }
        }
        @media (min-width: 481px) and (max-width: 768px) {
            .card-modal { max-width: 420px; border-radius: 20px; }
            .card-modal-overlay { padding: 1rem; align-items: center; }
        }
        @media (min-width: 769px) {
            .card-modal { max-width: 460px; }
            .card-modal-overlay { align-items: center; }
        }
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            @media (max-width: 480px) {
                .card-modal-body { padding-bottom: calc(1.4rem + env(safe-area-inset-bottom)); }
            }
        }
    </style>
</head>
<body>
    @include('customerFolder.partials.navbar')

    <div class="booking-page-wrapper">
        <div class="main-content">
            <div class="page-title-wrap">
                <h1 class="page-title">Complete Your Booking</h1>
                <div class="page-title-line"></div>
            </div>

            <div class="content-layout">
                <!-- STEPPER -->
                <div class="stepper">
                    <div class="step-item active" id="step-nav-1">
                        <div class="step-circle active"><i class="fas fa-user"></i></div>
                        <div class="step-content">
                            <div class="step-label">Details</div>
                            <div class="step-desc">Enter your information</div>
                        </div>
                    </div>
                    <div class="step-item" id="step-nav-2">
                        <div class="step-circle"><i class="fas fa-file-invoice"></i></div>
                        <div class="step-content">
                            <div class="step-label">Summary</div>
                            <div class="step-desc">Review your booking</div>
                        </div>
                    </div>
                    <div class="step-item" id="step-nav-3">
                        <div class="step-circle"><i class="fas fa-check-circle"></i></div>
                        <div class="step-content">
                            <div class="step-label">Confirm</div>
                            <div class="step-desc">Finalize your stay</div>
                        </div>
                    </div>
                </div>

                <div class="panels-container">
                    <!-- ══ STEP 1 ══ -->
                    <div class="step-panel active" id="panel-1">
                        <div class="booking-card">
                            <div class="card-header">
                                <h2>Guest Information</h2>
                                <p>Fill in your details and choose how you'd like to pay.</p>
                            </div>
                            <div class="card-body">
                                <form id="bookingForm">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-user"></i><span>Full Name</span><span class="required">*</span></label>
                                        <input type="text" name="full_name" id="input-name" class="form-input" readonly>
                                        <div class="error-message" id="error-name"><i class="fas fa-exclamation-circle"></i> Full name is required</div>
                                    </div>
                                    <div class="row-2">
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-envelope"></i><span>Email</span><span class="required">*</span></label>
                                            <input type="email" name="email" id="input-email" class="form-input" readonly>
                                            <div class="error-message" id="error-email"><i class="fas fa-exclamation-circle"></i> Valid email is required</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-phone"></i><span>Phone (09XXXXXXXXX)</span><span class="required">*</span></label>
                                            <input type="tel" name="phone" class="form-input" id="phone-input" placeholder="09XXXXXXXXX" maxlength="11">
                                            <div class="error-message" id="error-phone"><i class="fas fa-exclamation-circle"></i> Must start with 09 and be exactly 11 digits</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-comment-dots"></i><span>Special Requirements</span></label>
                                        <textarea name="special_requirements" class="form-textarea" placeholder="Any special requests or requirements…"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-credit-card"></i><span>Payment Method</span><span class="required">*</span></label>
                                        <div class="pay-grid" id="pay-grid">
                                            <div class="pay-card" id="pay-card" onclick="selectPayMethod('card')">
                                                <div class="pay-check"><i class="fas fa-check"></i></div>
                                                <div class="pay-icon"><i class="fas fa-credit-card"></i></div>
                                                <div class="pay-title">Card</div>
                                                <div class="pay-sub">Credit / Debit card</div>
                                            </div>
                                            <div class="pay-card" id="pay-gcash" onclick="selectPayMethod('gcash')">
                                                <div class="pay-check"><i class="fas fa-check"></i></div>
                                                <div class="pay-icon"><i class="fas fa-mobile-alt"></i></div>
                                                <div class="pay-title">GCash</div>
                                                <div class="pay-sub">Pay online now</div>
                                            </div>
                                        </div>
                                        <div class="error-message" id="error-payment"><i class="fas fa-exclamation-circle"></i> Please select a payment method</div>
                                    </div>
                                    <div id="amount-section" style="display:none;">
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-peso-sign"></i><span>Payment Amount</span><span class="required">*</span></label>
                                            <div class="amount-row">
                                                <div class="amount-chip" id="chip-down" onclick="selectAmount('downpayment')">
                                                    <div class="chip-check"><i class="fas fa-check"></i></div>
                                                    <div class="chip-label">Downpayment</div>
                                                    <div class="chip-amount" id="chip-down-amt">₱0.00</div>
                                                    <div class="chip-note">50% of total</div>
                                                </div>
                                                <div class="amount-chip" id="chip-full" onclick="selectAmount('full')">
                                                    <div class="chip-check"><i class="fas fa-check"></i></div>
                                                    <div class="chip-label">Full Payment</div>
                                                    <div class="chip-amount" id="chip-full-amt">₱0.00</div>
                                                    <div class="chip-note">100% of total</div>
                                                </div>
                                            </div>
                                            <div class="error-message" id="error-amount"><i class="fas fa-exclamation-circle"></i> Please select a payment amount</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="payment_method" id="h-payment-method">
                                    <input type="hidden" name="payment_amount" id="h-payment-amount">
                                    <input type="hidden" name="booking_type" id="h-booking-type">
                                    <input type="hidden" name="event_type" value="normal-booking">
                                </form>
                                <div class="btn-row">
                                    <button class="action-btn btn-primary" id="btn-next1" onclick="goToStep2()" disabled>
                                        <span>Next Step</span><i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══ STEP 2 ══ -->
                    <div class="step-panel" id="panel-2">
                        <div class="booking-card">
                            <div class="card-header">
                                <h2>Booking Summary</h2>
                                <p>Review your stay details and pricing before we proceed.</p>
                            </div>
                            <div class="card-body" id="summary-body"><div class="spinner"></div></div>
                        </div>
                        <div class="btn-row">
                            <button class="action-btn btn-secondary" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i><span>Back</span></button>
                            <button class="action-btn btn-primary" onclick="goToStep3()"><span>Next Step</span><i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ══ STEP 3 ══ -->
                    <div class="step-panel" id="panel-3">
                        <div class="booking-card rules-card" id="room-rules-card" style="display:none;">
                            <div class="rules-header">
                                <div class="rules-icon room-icon"><i class="fas fa-bed"></i></div>
                                <div><h3>Room Guidelines</h3><p>Rules that apply to your room booking.</p></div>
                            </div>
                            <ul class="rules-list">
                                <li><i class="fas fa-circle ri g"></i> Check-in starts at <strong>2:00 PM</strong>; check-out is by <strong>11:00 AM</strong>.</li>
                                <li><i class="fas fa-circle ri y"></i> Bed linens and towels are provided — extra sets available on request.</li>
                                <li><i class="fas fa-circle ri y"></i> No outside food or beverages are allowed inside the rooms.</li>
                                <li><i class="fas fa-circle ri r"></i> Smoking is strictly prohibited inside the room and balcony.</li>
                                <li><i class="fas fa-circle ri r"></i> Damages or misuse of room amenities will be charged to the guest.</li>
                                <li><i class="fas fa-circle ri y"></i> Quiet hours are from <strong>10:00 PM – 7:00 AM</strong>.</li>
                            </ul>
                        </div>
                        <div class="booking-card rules-card" id="cottage-rules-card" style="display:none;">
                            <div class="rules-header">
                                <div class="rules-icon cottage-icon"><i class="fas fa-home"></i></div>
                                <div><h3>Cottage Guidelines</h3><p>Rules that apply to your cottage booking.</p></div>
                            </div>
                            <ul class="rules-list">
                                <li><i class="fas fa-circle ri g"></i> Cottage access begins at <strong>9:00 AM</strong> and ends at <strong>5:00 PM</strong> (day use).</li>
                                <li><i class="fas fa-circle ri y"></i> Entrance fee per guest is included in your total.</li>
                                <li><i class="fas fa-circle ri y"></i> Cooking is allowed inside the cottage using provided facilities only.</li>
                                <li><i class="fas fa-circle ri r"></i> Open flames or grills are not permitted inside or near the cottage.</li>
                                <li><i class="fas fa-circle ri r"></i> Guests are responsible for cleaning up before departure.</li>
                                <li><i class="fas fa-circle ri y"></i> Children must be accompanied by an adult at all times near the pool area.</li>
                            </ul>
                        </div>
                        <div class="booking-card rules-card">
                            <div class="rules-header">
                                <div class="rules-icon general-icon"><i class="fas fa-info-circle"></i></div>
                                <div><h3>Cancellation & Contact</h3><p>Important details before you confirm.</p></div>
                            </div>
                            <div class="card-body" style="padding-top:0;">
                                <div class="cancel-notice">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>Cancellations made <strong>less than 48 hours</strong> before check-in are non-refundable. Cancellations made 48 hours or more in advance will receive a full downpayment refund.</p>
                                </div>
                                <div style="margin-top:1rem;">
                                    <div class="section-label" style="margin-top:0;">Reach Us</div>
                                    <div class="contact-strip">
                                        <div class="contact-item"><i class="fas fa-phone"></i>0917-301-0790</div>
                                        <div class="contact-item"><i class="fas fa-envelope"></i>evelynbalaisserrano@gmail.com</div>
                                        <div class="contact-item"><i class="fas fa-map-marker-alt"></i> Cabisuculan, Science City of Munoz</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-row">
                            <button class="action-btn btn-secondary" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i><span>Back</span></button>
                            <button class="action-btn btn-card" id="btn-confirm-card" style="display:none;" onclick="openCardModal()">
                                <i class="fas fa-credit-card"></i><span>Pay with Card</span>
                            </button>
                            <button class="action-btn btn-gcash" id="btn-confirm-gcash" style="display:none;" onclick="submitGCashBooking()">
                                <i class="fas fa-mobile-alt"></i><span>Pay with GCash</span>
                            </button>
                        </div>
                        <button class="action-btn btn-danger-ghost" onclick="cancelBooking()">
                            <i class="fas fa-times-circle"></i><span>Cancel Booking</span>
                        </button>
                    </div>

                    <!-- ══ SUCCESS ══ -->
                    <div class="step-panel" id="panel-success">
                        <div class="booking-card">
                            <div class="card-body">
                                <div class="success-wrap">
                                    <div class="success-icon"><i class="fas fa-check"></i></div>
                                    <h3>Booking Confirmed!</h3>
                                    <p>Your reservation at Villa Elena is all set.</p>
                                    <div class="ref-box" id="ref-box"></div>
                                    <div class="success-btns">
                                        <a href="/" class="action-btn btn-secondary"><i class="fas fa-home"></i><span>Home</span></a>
                                        <a href="/my-bookings" class="action-btn btn-primary"><i class="fas fa-calendar-check"></i><span>My Bookings</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD PAYMENT MODAL -->
    <div class="card-modal-overlay" id="cardModalOverlay" onclick="handleModalOverlayClick(event)">
        <div class="card-modal" id="cardModal">
            <div class="card-modal-header">
                <h3><i class="fas fa-lock"></i> Secure Card Payment</h3>
                <p>Your card details are encrypted and secure.</p>
                <button class="card-modal-close" onclick="closeCardModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="card-preview" id="cardPreview">
                <div class="card-preview-chip"></div>
                <div class="card-preview-number" id="previewNumber">•••• •••• •••• ••••</div>
                <div class="card-preview-bottom">
                    <div>
                        <div class="card-preview-label">Card Holder</div>
                        <div class="card-preview-value" id="previewName">YOUR NAME</div>
                    </div>
                    <div>
                        <div class="card-preview-label">Expires</div>
                        <div class="card-preview-value" id="previewExpiry">MM / YY</div>
                    </div>
                    <div class="card-preview-brand" id="previewBrand">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>
            <div class="card-modal-body">
                <div class="secure-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>256-bit SSL encrypted · Powered by PayMongo</span>
                </div>
                <div class="card-input-group">
                    <div class="card-input-label"><i class="fas fa-credit-card"></i> Card Number</div>
                    <div class="card-number-wrap">
                        <input type="text" id="cardNumber" class="card-field" placeholder="1234 5678 9012 3456"
                               maxlength="19" inputmode="numeric" autocomplete="cc-number"
                               oninput="formatCardNumber(this)" onblur="validateCardField('cardNumber')">
                        <span class="card-brand-icon" id="cardBrandIcon"><i class="fas fa-credit-card"></i></span>
                    </div>
                    <div class="card-error-msg" id="err-cardNumber"><i class="fas fa-exclamation-circle"></i> <span></span></div>
                </div>
                <div class="card-input-group">
                    <div class="card-input-label"><i class="fas fa-user"></i> Cardholder Name</div>
                    <input type="text" id="cardName" class="card-field" placeholder="JUAN DELA CRUZ"
                           maxlength="60" autocomplete="cc-name"
                           oninput="updatePreviewName(this.value)" onblur="validateCardField('cardName')">
                    <div class="card-error-msg" id="err-cardName"><i class="fas fa-exclamation-circle"></i> <span></span></div>
                </div>
                <div class="card-row-2">
                    <div class="card-input-group">
                        <div class="card-input-label"><i class="fas fa-calendar"></i> Expiry Date</div>
                        <input type="text" id="cardExpiry" class="card-field" placeholder="MM / YY"
                               maxlength="7" inputmode="numeric" autocomplete="cc-exp"
                               oninput="formatExpiry(this)" onblur="validateCardField('cardExpiry')">
                        <div class="card-error-msg" id="err-cardExpiry"><i class="fas fa-exclamation-circle"></i> <span></span></div>
                    </div>
                    <div class="card-input-group">
                        <div class="card-input-label"><i class="fas fa-lock"></i> CVV / CVC</div>
                        <input type="password" id="cardCVV" class="card-field" placeholder="•••"
                               maxlength="4" inputmode="numeric" autocomplete="cc-csc"
                               oninput="validateCardField('cardCVV')" onblur="validateCardField('cardCVV')">
                        <div class="card-error-msg" id="err-cardCVV"><i class="fas fa-exclamation-circle"></i> <span></span></div>
                    </div>
                </div>
                <button class="card-submit-btn" id="cardSubmitBtn" onclick="submitCardPayment()">
                    <i class="fas fa-lock"></i>
                    <span id="cardSubmitLabel">Pay ₱<span id="cardPayAmount">0.00</span></span>
                </button>
                <div class="accepted-cards">
                    <i class="fas fa-shield-alt" style="color:#10B981;"></i>
                    Accepted:
                    <span>VISA</span>
                    <span>MASTERCARD</span>
                    <span>JCB</span>
                </div>
            </div>
        </div>
    </div>

    @include('customerFolder.partials.footer')

<script>
// ═══════════════════════════════════════════════
//  STATE
// ═══════════════════════════════════════════════
let bookingTotal         = 0;
let selectedPayMethod    = null;
let selectedAmountType   = null;
let selectedAmount       = 0;
let daysCount            = 1;
let entranceFeeAmount    = 0;
let hasActiveEntranceFee = false;
let hasRoom    = false;
let hasCottage = false;
let cartItems  = [];
let cartData   = null;
let currentStep = 1;
let pendingBookingData  = null;
let pendingPaymentData  = null;

const PAYMONGO_PUBLIC_KEY = '{{ config("services.paymongo.public_key") }}';

// ═══════════════════════════════════════════════
//  INIT
// ═══════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    Promise.all([loadEntranceFee(), loadBookingSummary()]).then(() => {
        prefillUserInfo();
        validateForm();
    });
});

// ═══════════════════════════════════════════════
//  HELPERS
// ═══════════════════════════════════════════════
function validatePhoneNumber(phone) { return /^09\d{9}$/.test(phone); }

function showToast(type, msg) {
    document.querySelectorAll('.toast').forEach(t => t.remove());
    const icons = { success:'check-circle', error:'times-circle', warning:'exclamation-triangle', info:'info-circle' };
    const t = document.createElement('div');
    t.className = 'toast ' + type;
    t.innerHTML = `<i class="fas fa-${icons[type]||'info-circle'} toast-icon"></i><span style="flex:1;">${msg}</span><span class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></span>`;
    document.body.appendChild(t);
    setTimeout(() => { if (t.parentElement) t.remove(); }, 5000);
}

function showInlineError(id) {
    const el = document.getElementById('error-' + id);
    if (el) { el.classList.add('show'); setTimeout(() => el.classList.remove('show'), 4500); }
}

function hideInlineError(id) {
    const el = document.getElementById('error-' + id);
    if (el) el.classList.remove('show');
}

function resetCardSubmitBtn() {
    const submitBtn = document.getElementById('cardSubmitBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-lock"></i><span id="cardSubmitLabel">Pay ₱<span id="cardPayAmount">${selectedAmount.toFixed(2)}</span></span>`;
    }
}

// ═══════════════════════════════════════════════
//  LOAD DATA
// ═══════════════════════════════════════════════
async function loadEntranceFee() {
    try {
        const r = await fetch('/api/entrance-fee', { headers: { 'Accept':'application/json','X-CSRF-TOKEN': csrf() } });
        if (r.ok) {
            const d = await r.json();
            if (d.success && d.entrance_fee) { entranceFeeAmount = parseFloat(d.entrance_fee.amount); hasActiveEntranceFee = true; }
        }
    } catch(e) { console.error('Entrance fee:', e); }
}

function loadBookingSummary() {
    return fetch('/api/cart/items').then(r => r.json()).then(data => {
        if (data.success && data.cart && data.items.length > 0) {
            cartData  = data.cart;
            cartItems = data.items;
            computeTotals();
        } else {
            document.getElementById('summary-body').innerHTML =
                '<p style="color:#dc2626;font-size:.82rem;text-align:center;padding:1rem 0;">No items in cart. Please add accommodations first.</p>';
        }
    }).catch(e => console.error('Cart:', e));
}

function csrf() { return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); }

// ═══════════════════════════════════════════════
//  COMPUTE TOTALS
//  numGuests is now per cart item — each item has
//  its own guest count from cart_items.numGuests
// ═══════════════════════════════════════════════
function computeTotals() {
    daysCount = parseInt(cartData.daysCount) || 1;

    const checkIn  = new Date(cartData.checkInDate);
    const checkOut = new Date(cartData.checkOutDate);
    document.getElementById('h-booking-type').value =
        checkIn.toDateString() === checkOut.toDateString() ? 'day-use' : 'overnight';

    let roomSubtotal    = 0;
    let cottageSubtotal = 0;
    let totalEntranceFees = 0;
    hasRoom = false; hasCottage = false;

    cartItems.forEach(item => {
        const unit       = item.unit;
        const price      = parseFloat(unit.unitRatePrice);
        // ✅ Use per-item numGuests — this is the key fix
        const itemGuests = parseInt(item.numGuests) || 1;

        if (unit.unitType === 'room') {
            hasRoom = true;
            // Minimum multiplier of 2 when only 1 guest (same as backend logic)
            const mult      = itemGuests === 1 ? 2 : itemGuests;
            const roomTotal = price * mult * daysCount;
            roomSubtotal   += roomTotal;
            item._total     = roomTotal;
            item._calc      = `₱${price.toFixed(2)} × ${mult} guest${mult > 1 ? 's' : ''} × ${daysCount} day${daysCount > 1 ? 's' : ''}`;

        } else if (unit.unitType === 'cottage') {
            hasCottage = true;
            cottageSubtotal += price;
            if (hasActiveEntranceFee) {
                const ef = entranceFeeAmount * itemGuests;
                totalEntranceFees += ef;
                item._total = price + ef;
                item._calc  = `Cottage: ₱${price.toFixed(2)} + Entrance: ₱${ef.toFixed(2)} (₱${entranceFeeAmount.toFixed(2)} × ${itemGuests})`;
            } else {
                item._total = price;
                item._calc  = 'Cottage price only';
            }

        } else {
            item._total = price * daysCount;
            item._calc  = `₱${price.toFixed(2)} × ${daysCount} day${daysCount > 1 ? 's' : ''}`;
        }
    });

    bookingTotal = roomSubtotal + cottageSubtotal + totalEntranceFees;
    cartData._roomSubtotal    = roomSubtotal;
    cartData._cottageSubtotal = cottageSubtotal;
    cartData._entranceFees    = totalEntranceFees;

    updateAmountChips();
}

function updateAmountChips() {
    const down = Math.round(bookingTotal * 0.5 * 100) / 100;
    document.getElementById('chip-down-amt').textContent = '₱' + down.toFixed(2);
    document.getElementById('chip-full-amt').textContent = '₱' + bookingTotal.toFixed(2);
}

// ═══════════════════════════════════════════════
//  PREFILL
// ═══════════════════════════════════════════════
function prefillUserInfo() {
    document.getElementById('input-name').value  = '{{ Auth::user()->name ?? "" }}'.trim();
    document.getElementById('input-email').value = '{{ Auth::user()->email ?? "" }}';
    const p = '{{ Auth::user()->phoneNumber ?? "" }}';
    const phoneInput = document.getElementById('phone-input');
    if (p && p.trim()) phoneInput.value = p;
    phoneInput.readOnly = false;

    phoneInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
        if (validatePhoneNumber(this.value)) { this.classList.remove('error'); hideInlineError('phone'); }
        validateForm();
    });
    phoneInput.addEventListener('blur', function () {
        const v = this.value.trim();
        if (v && !validatePhoneNumber(v)) { this.classList.add('error'); showInlineError('phone'); }
        else if (validatePhoneNumber(v))  { this.classList.remove('error'); hideInlineError('phone'); }
    });
}

// ═══════════════════════════════════════════════
//  FORM VALIDATION
// ═══════════════════════════════════════════════
function validateForm() {
    const name       = document.getElementById('input-name').value.trim();
    const email      = document.getElementById('input-email').value.trim();
    const phone      = document.getElementById('phone-input').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const ok = name && email && emailRegex.test(email) && validatePhoneNumber(phone) && selectedPayMethod && selectedAmountType;
    document.getElementById('btn-next1').disabled = !ok;
    return ok;
}

// ═══════════════════════════════════════════════
//  PAYMENT METHOD / AMOUNT SELECTION
// ═══════════════════════════════════════════════
function selectPayMethod(method) {
    selectedPayMethod = method;
    document.getElementById('pay-card').classList.toggle('selected', method === 'card');
    document.getElementById('pay-gcash').classList.toggle('selected', method === 'gcash');
    document.getElementById('h-payment-method').value = method;
    document.getElementById('amount-section').style.display = 'block';
    document.getElementById('pay-grid').classList.remove('error-highlight');
    hideInlineError('payment');
    validateForm();
}

function selectAmount(type) {
    selectedAmountType = type;
    document.getElementById('chip-down').classList.toggle('selected', type === 'downpayment');
    document.getElementById('chip-full').classList.toggle('selected', type === 'full');
    selectedAmount = type === 'downpayment'
        ? Math.round(bookingTotal * 0.5 * 100) / 100
        : bookingTotal;
    document.getElementById('h-payment-amount').value = selectedAmount.toFixed(2);
    hideInlineError('amount');
    validateForm();
}

// ═══════════════════════════════════════════════
//  STEP NAVIGATION
// ═══════════════════════════════════════════════
function goToStep(n) {
    document.getElementById('panel-' + currentStep)?.classList.remove('active');
    document.getElementById('panel-' + n)?.classList.add('active');
    currentStep = n;
    for (let i = 1; i <= 3; i++) {
        const nav    = document.getElementById('step-nav-' + i);
        const circle = nav.querySelector('.step-circle');
        const icon   = circle.querySelector('i');
        nav.classList.toggle('active', i === n);
        circle.classList.toggle('active', i === n);
        circle.classList.toggle('completed', i < n);
        if (i < n)        icon.className = 'fas fa-check';
        else if (i === 1) icon.className = 'fas fa-user';
        else if (i === 2) icon.className = 'fas fa-file-invoice';
        else if (i === 3) icon.className = 'fas fa-check-circle';
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToStep2() {
    const name       = document.getElementById('input-name');
    const email      = document.getElementById('input-email');
    const phone      = document.getElementById('phone-input');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!name.value.trim())  { name.classList.add('error');  showInlineError('name');  showToast('error','Full name is required.'); name.focus(); return; }
    name.classList.remove('error'); hideInlineError('name');
    if (!email.value.trim() || !emailRegex.test(email.value)) { email.classList.add('error'); showInlineError('email'); showToast('error','Please enter a valid email address.'); email.focus(); return; }
    email.classList.remove('error'); hideInlineError('email');
    if (!validatePhoneNumber(phone.value.trim())) { phone.classList.add('error'); showInlineError('phone'); showToast('error','Phone number must start with 09 and be exactly 11 digits.'); phone.focus(); return; }
    phone.classList.remove('error'); hideInlineError('phone');
    if (!selectedPayMethod) { document.getElementById('pay-grid').classList.add('error-highlight'); showInlineError('payment'); showToast('error','Please select a payment method.'); document.getElementById('pay-grid').scrollIntoView({behavior:'smooth',block:'center'}); return; }
    if (!selectedAmountType) { showInlineError('amount'); showToast('error','Please select a payment amount.'); document.getElementById('amount-section').scrollIntoView({behavior:'smooth',block:'center'}); return; }

    renderSummary();
    goToStep(2);
}

function goToStep3() {
    document.getElementById('room-rules-card').style.display    = hasRoom    ? 'block' : 'none';
    document.getElementById('cottage-rules-card').style.display = hasCottage ? 'block' : 'none';
    document.getElementById('btn-confirm-card').style.display   = selectedPayMethod === 'card'  ? 'flex' : 'none';
    document.getElementById('btn-confirm-gcash').style.display  = selectedPayMethod === 'gcash' ? 'flex' : 'none';
    goToStep(3);
}

// ═══════════════════════════════════════════════
//  RENDER SUMMARY
// ═══════════════════════════════════════════════
function renderSummary() {
    const d         = cartData;
    const checkIn   = new Date(d.checkInDate);
    const checkOut  = new Date(d.checkOutDate);
    const bookingType = checkIn.toDateString() === checkOut.toDateString() ? 'Day Use' : 'Overnight';
    const payLabel    = selectedAmountType === 'downpayment' ? 'Downpayment (50%)' : 'Full Payment';
    const methodLabel = selectedPayMethod === 'card' ? 'Credit / Debit Card' : 'GCash';

    // Build per-item accommodation rows
    const itemsHTML = cartItems.map(item => {
        const u          = item.unit;
        const itemGuests = parseInt(item.numGuests) || 1;
        return `
            <div class="accom-item">
                <div>
                    <div class="accom-name">${u.unitName}</div>
                    <span class="accom-badge ${u.unitType}">
                        <i class="fas fa-${u.unitType === 'room' ? 'bed' : 'home'}"></i> ${u.unitType}
                    </span>
                    <span style="font-size:.72rem;color:#6B7280;margin-left:.5rem;">
                        <i class="fas fa-users"></i> ${itemGuests} guest${itemGuests > 1 ? 's' : ''}
                    </span>
                    <div class="accom-calc">${item._calc}</div>
                </div>
                <div class="accom-price">₱${item._total.toFixed(2)}</div>
            </div>`;
    }).join('');

    // Build pricing breakdown rows
    let pricingRows = '';
    if (d._roomSubtotal    > 0) pricingRows += `<div class="s-row"><span class="s-label">Rooms Subtotal</span><span class="s-value">₱${d._roomSubtotal.toFixed(2)}</span></div>`;
    if (d._cottageSubtotal > 0) pricingRows += `<div class="s-row"><span class="s-label">Cottages Subtotal</span><span class="s-value">₱${d._cottageSubtotal.toFixed(2)}</span></div>`;
    if (d._entranceFees    > 0) pricingRows += `<div class="s-row"><span class="s-label">Entrance Fees</span><span class="s-value">₱${d._entranceFees.toFixed(2)}</span></div>`;

    document.getElementById('summary-body').innerHTML = `
        <div class="summary-block">
            <div class="section-label">Stay Details</div>
            <div class="s-row"><span class="s-label">Check-in</span><span class="s-value">${d.checkInDate}</span></div>
            <div class="s-row"><span class="s-label">Check-out</span><span class="s-value">${d.checkOutDate}</span></div>
            <div class="s-row"><span class="s-label">Duration</span><span class="s-value">${daysCount} day(s)</span></div>
            <div class="s-row"><span class="s-label">Booking Type</span><span class="s-value">${bookingType}</span></div>
        </div>
        <div class="summary-block">
            <div class="section-label">Accommodations</div>
            ${itemsHTML}
        </div>
        <div class="summary-block">
            <div class="section-label">Pricing</div>
            ${pricingRows}
            <div class="total-price-box">
                <span class="t-label">Total Amount</span>
                <span class="t-amount">₱${bookingTotal.toFixed(2)}</span>
            </div>
        </div>
        <div class="summary-block">
            <div class="section-label">Payment</div>
            <div class="s-row"><span class="s-label">Method</span><span class="s-value">${methodLabel}</span></div>
            <div class="s-row"><span class="s-label">${payLabel}</span><span class="s-value" style="color:#10b981;">₱${selectedAmount.toFixed(2)}</span></div>
        </div>`;
}

// ═══════════════════════════════════════════════
//  PRE-VALIDATE CART
// ═══════════════════════════════════════════════
async function validateCartBeforeSubmit() {
    try {
        const r = await fetch('/api/cart/pre-validate', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/json' }
        });
        const d = await r.json();
        if (!d.success) {
            showToast('error', d.validation_errors ? d.validation_errors.join(' ') : (d.message || 'Validation failed.'));
            return false;
        }
        return true;
    } catch(e) {
        showToast('error', 'Validation error. Please try again.');
        return false;
    }
}

// ═══════════════════════════════════════════════
//  CARD MODAL — OPEN / CLOSE
// ═══════════════════════════════════════════════
async function openCardModal() {
    const btn = document.getElementById('btn-confirm-card');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Validating…</span>';

    if (!(await validateCartBeforeSubmit())) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-credit-card"></i><span>Pay with Card</span>';
        return;
    }

    const phone = document.getElementById('phone-input').value.trim();
    if (!validatePhoneNumber(phone)) {
        showToast('error', 'Invalid phone number.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-credit-card"></i><span>Pay with Card</span>';
        return;
    }

    const fd = new FormData(document.getElementById('bookingForm'));
    fd.set('phone', phone);
    fd.set('payment_amount', selectedAmount.toFixed(2));
    fd.set('payment_method', 'card');
    fd.set('booking_type', document.getElementById('h-booking-type').value);
    fd.set('event_type', 'normal-booking');

    try {
        const r = await fetch('/api/customer-bookings', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
            body: fd
        });
        const d = await r.json();

        if (!d.success || !d.requires_payment_first) throw new Error(d.message || 'Validation failed');

        pendingBookingData = d.booking_data;
        pendingPaymentData = d.payment_data;

        document.getElementById('cardPayAmount').textContent = selectedAmount.toFixed(2);

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-credit-card"></i><span>Pay with Card</span>';

        document.getElementById('cardModalOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';

    } catch(e) {
        showToast('error', e.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-credit-card"></i><span>Pay with Card</span>';
    }
}

function closeCardModal() {
    document.getElementById('cardModalOverlay').classList.remove('open');
    document.body.style.overflow = '';

    ['cardNumber', 'cardName', 'cardExpiry', 'cardCVV'].forEach(id => {
        const el  = document.getElementById(id);
        const err = document.getElementById('err-' + id);
        if (el)  { el.value = ''; el.classList.remove('error'); }
        if (err) err.classList.remove('show');
    });

    document.getElementById('previewNumber').textContent = '•••• •••• •••• ••••';
    document.getElementById('previewName').textContent   = 'YOUR NAME';
    document.getElementById('previewExpiry').textContent = 'MM / YY';
    document.getElementById('previewBrand').innerHTML    = '<i class="fas fa-credit-card"></i>';
    document.getElementById('cardBrandIcon').innerHTML   = '<i class="fas fa-credit-card"></i>';

    resetCardSubmitBtn();
}

function handleModalOverlayClick(e) {
    if (e.target === document.getElementById('cardModalOverlay')) closeCardModal();
}

// ═══════════════════════════════════════════════
//  CARD FIELD FORMATTING
// ═══════════════════════════════════════════════
function formatCardNumber(input) {
    let v = input.value.replace(/\D/g, '').slice(0, 16);
    input.value = v.match(/.{1,4}/g)?.join(' ') || v;

    const padded = v.padEnd(16, '•');
    document.getElementById('previewNumber').textContent = padded.match(/.{1,4}/g).join(' ');

    const brand      = detectCardBrand(v);
    const brandIcons = {
        visa:       '<i class="fab fa-cc-visa" style="color:#1A1F71;"></i>',
        mastercard: '<i class="fab fa-cc-mastercard" style="color:#EB001B;"></i>',
        jcb:        '<i class="fab fa-cc-jcb" style="color:#003087;"></i>',
        default:    '<i class="fas fa-credit-card"></i>'
    };
    const icon = brandIcons[brand] || brandIcons.default;
    document.getElementById('previewBrand').innerHTML  = icon;
    document.getElementById('cardBrandIcon').innerHTML = icon;

    validateCardField('cardNumber');
}

function detectCardBrand(num) {
    if (/^4/.test(num))               return 'visa';
    if (/^5[1-5]/.test(num))          return 'mastercard';
    if (/^2[2-7]/.test(num))          return 'mastercard';
    if (/^35(2[89]|[3-8])/.test(num)) return 'jcb';
    return 'default';
}

function formatExpiry(input) {
    let v = input.value.replace(/\D/g, '').slice(0, 4);
    if (v.length >= 2) v = v.slice(0, 2) + ' / ' + v.slice(2);
    input.value = v;

    const raw = input.value.replace(/\D/g, '');
    if (raw.length >= 4)      document.getElementById('previewExpiry').textContent = raw.slice(0, 2) + ' / ' + raw.slice(2, 4);
    else if (raw.length >= 2) document.getElementById('previewExpiry').textContent = raw.slice(0, 2) + ' / ';
    else                      document.getElementById('previewExpiry').textContent = 'MM / YY';

    validateCardField('cardExpiry');
}

function updatePreviewName(val) {
    document.getElementById('previewName').textContent = val.toUpperCase() || 'YOUR NAME';
    validateCardField('cardName');
}

// ═══════════════════════════════════════════════
//  CARD FIELD VALIDATION
// ═══════════════════════════════════════════════
function validateCardField(fieldId) {
    const field = document.getElementById(fieldId);
    const errEl = document.getElementById('err-' + fieldId);
    let msg = '';

    switch (fieldId) {
        case 'cardNumber':
            const num = field.value.replace(/\s/g, '');
            if (!num)             msg = 'Card number is required.';
            else if (num.length < 13) msg = 'Card number is too short.';
            else if (num.length > 16) msg = 'Card number is too long.';
            break;
        case 'cardName':
            if (!field.value.trim()) msg = 'Cardholder name is required.';
            break;
        case 'cardExpiry':
            const raw = field.value.replace(/\D/g, '');
            if (!raw)              { msg = 'Expiry date is required.'; break; }
            if (raw.length < 4)    { msg = 'Enter a valid expiry date.'; break; }
            const month = parseInt(raw.slice(0, 2));
            const year  = parseInt('20' + raw.slice(2, 4));
            const now   = new Date();
            if (month < 1 || month > 12)                                                          { msg = 'Invalid month.'; break; }
            if (year < now.getFullYear() || (year === now.getFullYear() && month < now.getMonth() + 1)) { msg = 'Card has expired.'; break; }
            break;
        case 'cardCVV':
            if (!field.value)           msg = 'CVV is required.';
            else if (field.value.length < 3) msg = 'CVV must be 3–4 digits.';
            break;
    }

    if (msg) {
        field.classList.add('error');
        errEl.querySelector('span').textContent = msg;
        errEl.classList.add('show');
    } else {
        field.classList.remove('error');
        errEl.classList.remove('show');
    }
    return !msg;
}

function validateAllCardFields() {
    return ['cardNumber', 'cardName', 'cardExpiry', 'cardCVV'].every(id => validateCardField(id));
}

// ═══════════════════════════════════════════════
//  SUBMIT CARD PAYMENT
// ═══════════════════════════════════════════════
async function submitCardPayment() {
    if (!validateAllCardFields()) {
        showToast('error', 'Please fix the card details errors before proceeding.');
        return;
    }

    const btn = document.getElementById('cardSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking availability…';

    try {
        const validationRes  = await fetch('/api/cart/pre-validate', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/json' }
        });
        const validationData = await validationRes.json();
        if (!validationData.success) {
            showToast('error', validationData.validation_errors
                ? validationData.validation_errors.join(' ')
                : (validationData.message || 'One or more units are no longer available.'));
            resetCardSubmitBtn();
            return;
        }
    } catch(e) {
        showToast('error', 'Could not verify availability. Please try again.');
        resetCardSubmitBtn();
        return;
    }

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';

    try {
        const expiryRaw = document.getElementById('cardExpiry').value.replace(/\D/g, '');

        // ✅ Tokenize card directly with PayMongo — raw details never touch our server
        const pmResponse = await fetch('https://api.paymongo.com/v1/payment_methods', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Basic ' + btoa(PAYMONGO_PUBLIC_KEY + ':')
            },
            body: JSON.stringify({
                data: {
                    attributes: {
                        type: 'card',
                        details: {
                            card_number: document.getElementById('cardNumber').value.replace(/\s/g, ''),
                            exp_month:   parseInt(expiryRaw.slice(0, 2)),
                            exp_year:    parseInt('20' + expiryRaw.slice(2, 4)),
                            cvc:         document.getElementById('cardCVV').value,
                        },
                        billing: {
                            name:  document.getElementById('cardName').value.trim(),
                            email: pendingBookingData?.email ?? '{{ Auth::user()->email ?? "" }}',
                            phone: pendingBookingData?.phone ?? null,
                        }
                    }
                }
            })
        });

        const pmData = await pmResponse.json();
        if (!pmResponse.ok) {
            const errMsg = pmData.errors?.[0]?.detail || 'Card tokenization failed. Please check your card details.';
            throw new Error(errMsg);
        }

        // ✅ Send only the token ID to our server
        const r = await fetch('/card/process-payment', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                booking_data:      pendingBookingData,
                payment_data:      pendingPaymentData,
                payment_method_id: pmData.data.id
            })
        });

        const d = await r.json();

        if (d.success) {
            if (d.requires_3ds && d.redirect_url) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting to bank…';
                window.location.href = d.redirect_url;
            } else {
                closeCardModal();
                showSuccess({
                    booking_reference: d.payment?.paymentReference || 'N/A',
                    booking_id:        d.booking?.bookingID || 'N/A',
                    booking_status:    d.booking?.bookingStatus || 'confirmed'
                });
            }
        } else {
            throw new Error(d.message || 'Card payment failed.');
        }

    } catch(e) {
        showToast('error', e.message);
        resetCardSubmitBtn();
    }
}

// ═══════════════════════════════════════════════
//  SUBMIT GCASH
// ═══════════════════════════════════════════════
async function submitGCashBooking() {
    const btn = document.getElementById('btn-confirm-gcash');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Validating…</span>';

    if (!(await validateCartBeforeSubmit())) { resetGCashBtn(btn); return; }

    const phone = document.getElementById('phone-input').value.trim();
    if (!validatePhoneNumber(phone)) { showToast('error', 'Invalid phone number.'); resetGCashBtn(btn); return; }

    const fd = new FormData(document.getElementById('bookingForm'));
    fd.set('phone', phone);
    fd.set('payment_amount', selectedAmount.toFixed(2));
    fd.set('payment_method', 'gcash');
    fd.set('booking_type', document.getElementById('h-booking-type').value);
    fd.set('event_type', 'normal-booking');

    try {
        const vr = await fetch('/api/customer-bookings', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
            body: fd
        });
        const vd = await vr.json();
        if (!vd.success || !vd.requires_payment_first) throw new Error(vd.message || 'Validation failed');

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing payment…</span>';

        const pr = await fetch('/gcash/process-payment', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ booking_data: vd.booking_data, payment_data: vd.payment_data })
        });
        const pd = await pr.json();
        if (pd.success && pd.checkout_url) { window.location.href = pd.checkout_url; }
        else throw new Error(pd.message || 'GCash payment failed');

    } catch(e) {
        showToast('error', e.message);
        resetGCashBtn(btn);
    }
}

function resetGCashBtn(btn) {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-mobile-alt"></i><span>Pay with GCash</span>';
}

// ═══════════════════════════════════════════════
//  SUCCESS
// ═══════════════════════════════════════════════
function showSuccess(data) {
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-success').classList.add('active');
    document.querySelector('.stepper').style.display         = 'none';
    document.querySelector('.page-title-wrap').style.display = 'none';
    document.getElementById('ref-box').innerHTML = `
        <div class="ref-row"><span>Reference</span><span>${data.booking_reference}</span></div>
        <div class="ref-row"><span>Booking ID</span><span>${data.booking_id}</span></div>
        <div class="ref-row"><span>Status</span><span>${data.booking_status}</span></div>`;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ═══════════════════════════════════════════════
//  CANCEL
// ═══════════════════════════════════════════════
function cancelBooking() {
    Swal.fire({
        title: 'Cancel Booking?',
        html: `<div style="font-size:0.9rem;color:#6B7280;line-height:1.7;">Are you sure you want to cancel?<br><strong style="color:#EF4444;">Your cart will be cleared</strong> and you'll be taken back to the home page.</div>`,
        icon: 'warning', iconColor: '#EF4444',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-times-circle"></i> Yes, Cancel Booking',
        cancelButtonText:  '<i class="fas fa-arrow-left"></i> Go Back',
        confirmButtonColor: '#EF4444', cancelButtonColor: '#6B7280',
        reverseButtons: true, focusCancel: true,
        customClass: { popup:'swal-booking-popup', title:'swal-booking-title', confirmButton:'swal-confirm-btn', cancelButton:'swal-cancel-btn' },
        backdrop: 'rgba(0,0,0,0.5)', showLoaderOnConfirm: true,
        preConfirm: () => fetch('/api/cart/clear', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf() } })
            .then(r => r.json())
            .then(d => { if (!d.success) Swal.showValidationMessage('Failed to clear cart.'); return d; })
            .catch(() => Swal.showValidationMessage('Network error.')),
        allowOutsideClick: () => !Swal.isLoading()
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Booking Cancelled', text: 'Your cart has been cleared.',
                icon: 'success', iconColor: '#10B981',
                confirmButtonColor: '#10B981', confirmButtonText: 'Go to Home',
                timer: 3000, timerProgressBar: true
            }).then(() => { window.location.href = '{{ route("home") }}'; });
        }
    });
}
</script>

<style>
    .swal-booking-popup  { border-radius:20px!important; font-family:'Poppins',sans-serif!important; padding:2rem!important; box-shadow:0 25px 60px rgba(0,0,0,.2)!important; }
    .swal-booking-title  { font-size:1.4rem!important; font-weight:800!important; color:#1F2937!important; }
    .swal2-icon.swal2-warning { border-color:#EF4444!important; color:#EF4444!important; }
    .swal-confirm-btn,.swal-cancel-btn { border-radius:12px!important; padding:12px 22px!important; font-weight:700!important; font-size:.88rem!important; font-family:'Poppins',sans-serif!important; display:flex!important; align-items:center!important; gap:8px!important; }
</style>
</body>
</html>