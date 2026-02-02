<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        /* ═══════════════════════════════
           VARIABLES — ONE PALETTE
        ═══════════════════════════════ */
        :root {
            --cream:      #FDF8F0;
            --cream-dark: #F5EDE0;
            --sunflower:  #E8A825;
            --sun-light:  #F0C660;
            --sun-dark:   #C88A1A;
            --text:       #3D3226;
            --text-soft:  #7A6E5E;
            --text-faint: #A89A87;
            --border:     #E8DDD0;
            --white:      #FFFFFF;
            --red:        #D9534F;
            --red-light:  #F2D5D4;
            --green:      #3A9D6E;
            --green-light:#E6F5EE;
            --blue:       #5B8DB8;
            --blue-light: #E4EEF6;
            --purple:     #8B6EB5;
            --purple-light:#EDE8F5;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
        }

        .cursive-font { font-family: 'Dancing Script', cursive; }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .anim-fade-up { animation: fadeUp 0.4s ease both; }

        /* ═══════════════════════════════
           LAYOUT
        ═══════════════════════════════ */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2.5rem 1rem 4rem;
        }

        .bookings-container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* ═══════════════════════════════
           PAGE TITLE
        ═══════════════════════════════ */
        .page-title-wrap {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.5s ease both;
        }

        .page-title {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--sunflower);
            margin-bottom: 0.4rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-soft);
            font-weight: 400;
        }

        .title-line {
            width: 48px;
            height: 3px;
            background: var(--sunflower);
            border-radius: 2px;
            margin: 1rem auto 0;
        }

        /* ═══════════════════════════════
           STAT CARDS ROW
        ═══════════════════════════════ */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.9rem;
            margin-bottom: 1.8rem;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1rem;
            text-align: center;
            animation: fadeUp 0.4s ease both;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }

        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .10s; }
        .stat-card:nth-child(3) { animation-delay: .15s; }
        .stat-card:nth-child(4) { animation-delay: .20s; }

        .stat-number {
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .stat-card.c-all    .stat-number { color: var(--text); }
        .stat-card.c-pend   .stat-number { color: var(--sunflower); }
        .stat-card.c-conf   .stat-number { color: var(--green); }
        .stat-card.c-comp   .stat-number { color: var(--blue); }

        /* ═══════════════════════════════
           FILTER TABS
        ═══════════════════════════════ */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.8rem;
            animation: fadeUp 0.4s ease .25s both;
        }

        .filter-tab {
            padding: 0.5rem 1.1rem;
            border-radius: 50px;
            border: 1.5px solid var(--border);
            background: var(--white);
            color: var(--text-soft);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .filter-tab:hover {
            border-color: var(--sunflower);
            color: var(--sun-dark);
        }
        .filter-tab.active {
            background: var(--sunflower);
            border-color: var(--sunflower);
            color: var(--white);
        }
        .filter-tab i { font-size: 0.75rem; }

        /* ═══════════════════════════════
           BOOKING CARD
        ═══════════════════════════════ */
        .booking-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            animation: fadeUp 0.4s ease both;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(61, 50, 38, 0.07);
        }
        .booking-card.filtered-out { display: none !important; }

        /* header row */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.2rem;
        }

        .card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .meta-item {
            font-size: 0.78rem;
            color: var(--text-soft);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .meta-item i {
            color: var(--sunflower);
            width: 13px;
            font-size: 0.78rem;
        }

        /* status badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }
        .badge i { font-size: 0.7rem; }

        .badge-pending   { background: #FEF3E0; color: var(--sun-dark); }
        .badge-confirmed { background: var(--green-light); color: var(--green); }
        .badge-completed { background: var(--blue-light); color: var(--blue); }
        .badge-cancelled { background: var(--red-light); color: var(--red); }
        .badge-refunded  { background: var(--purple-light); color: var(--purple); }

        /* card body */
        .card-body {
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 1.2rem;
            margin-bottom: 1.1rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.7rem;
        }

        .detail-box {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 0.8rem;
        }
        .detail-box h4 {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .detail-box h4 i { color: var(--sunflower); font-size: 0.7rem; }
        .detail-box p {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
        }

        /* price summary mini */
        .price-box {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.9rem;
        }
        .price-box h4 {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.6rem;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.77rem;
            font-weight: 500;
            color: var(--text-soft);
            padding: 0.2rem 0;
        }
        .price-row span:last-child { font-weight: 600; color: var(--text); }
        .price-row.total {
            margin-top: 0.45rem;
            padding-top: 0.45rem;
            border-top: 1px solid var(--border);
            font-weight: 600;
            color: var(--text);
        }
        .price-row.total span:last-child { color: var(--green); font-weight: 700; }

        /* card actions */
        .card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            border: none;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn i { font-size: 0.72rem; }

        .btn-primary {
            background: var(--sunflower);
            color: var(--white);
        }
        .btn-primary:hover { background: var(--sun-dark); }

        .btn-danger {
            background: var(--red-light);
            color: var(--red);
        }
        .btn-danger:hover { background: #e8c3c2; }

        .btn-ghost {
            background: transparent;
            color: var(--text-soft);
            border: 1.5px solid var(--border);
        }
        .btn-ghost:hover { border-color: var(--text-soft); color: var(--text); }

        /* ═══════════════════════════════
           EMPTY STATE
        ═══════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            animation: fadeUp 0.4s ease both;
        }
        .empty-icon {
            width: 72px;
            height: 72px;
            background: var(--cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            color: var(--sunflower);
        }
        .empty-state h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3rem; }
        .empty-state p { font-size: 0.85rem; color: var(--text-soft); margin-bottom: 1.2rem; }

        /* ═══════════════════════════════
           LOADING
        ═══════════════════════════════ */
        .loading-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3.5rem 1rem;
        }
        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid var(--border);
            border-top-color: var(--sunflower);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin-bottom: 0.8rem;
        }
        .loading-wrap p { font-size: 0.85rem; color: var(--text-faint); font-weight: 500; }

        /* ═══════════════════════════════
           MODAL — FULLY OPAQUE
        ═══════════════════════════════ */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        .modal-overlay.show { display: flex; }

        .modal {
            background: var(--white);
            border-radius: 18px;
            width: 100%;
            max-width: 680px;
            max-height: 88vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: fadeUp 0.3s ease;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.3rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background: var(--cream);
            border-radius: 18px 18px 0 0;
        }
        .modal-head h2 {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text);
        }
        .modal-head h2 i { color: var(--sunflower); font-size: 1rem; }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-faint);
            font-size: 1.1rem;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .modal-close:hover { background: var(--cream); color: var(--text); }

        .modal-body {
            padding: 1.5rem;
            background: var(--white);
        }

        /* modal sections */
        .modal-section { margin-bottom: 1.5rem; }
        .modal-section-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .modal-section-title i { color: var(--sunflower); font-size: 0.72rem; }

        /* dates grid in modal */
        .modal-dates {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
        }
        .modal-date-item {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 0.8rem;
        }
        .modal-date-item .label {
            font-size: 0.65rem;
            color: var(--text-faint);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 0.2rem;
        }
        .modal-date-item .value {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text);
        }

        /* accommodation item */
        .accom-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0.9rem;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }
        .accom-item .name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .accom-item .type {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 0.15rem;
        }
        .type.room { color: var(--blue); }
        .type.cottage { color: var(--green); }

        .accom-item .price {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--sunflower);
        }

        /* payment item */
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 0.9rem;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }
        .payment-item .ref { font-size: 0.82rem; font-weight: 600; color: var(--text); }
        .payment-item .info { font-size: 0.7rem; color: var(--text-faint); margin-top: 0.1rem; }
        .payment-item .amount { font-size: 0.9rem; font-weight: 700; color: var(--green); }

        /* modal price summary */
        .modal-price-box {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.9rem;
            margin-bottom: 1.2rem;
        }
        .modal-price-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--text-soft);
            padding: 0.2rem 0;
            font-weight: 500;
        }
        .modal-price-row span:last-child { color: var(--text); font-weight: 600; }
        .modal-price-row.total {
            border-top: 1px solid var(--border);
            margin-top: 0.4rem;
            padding-top: 0.4rem;
            font-weight: 600;
            color: var(--text);
        }
        .modal-price-row.total span:last-child { color: var(--green); font-weight: 700; font-size: 0.88rem; }

        /* modal top row with badge */
        .modal-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.3rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .modal-top h3 { font-size: 1.15rem; font-weight: 700; }
        .modal-top .sub { font-size: 0.78rem; color: var(--text-soft); }

        /* special req box */
        .info-box {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.8rem 0.9rem;
            margin-bottom: 1.2rem;
        }
        .info-box h4 {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .info-box h4 i { color: var(--sunflower); font-size: 0.68rem; }
        .info-box p { font-size: 0.8rem; color: var(--text-soft); line-height: 1.5; }

        /* modal actions */
        .modal-actions {
            display: flex;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }

        /* ═══════════════════════════════
           RESPONSIVE
        ═══════════════════════════════ */
        @media (max-width: 768px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .card-body { grid-template-columns: 1fr; }
            .details-grid { grid-template-columns: repeat(2, 1fr); }
            .modal-dates { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 500px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); gap: 0.6rem; }
            .stat-card { padding: 0.8rem 0.6rem; }
            .details-grid { grid-template-columns: 1fr; }
            .filter-tab { font-size: 0.75rem; padding: 0.4rem 0.85rem; }
            .booking-card { padding: 1.1rem; }
            .modal-dates { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    @include('customerFolder.partials.navbar')

    <div class="bookings-page-wrapper">
        <main class="main-content">
            <div class="bookings-container">

                <!-- Title -->
                <div class="page-title-wrap">
                    <h1 class="page-title cursive-font">My Bookings</h1>
                    <p class="page-subtitle">Track and manage all your reservations</p>
                    <div class="title-line"></div>
                </div>

                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-card c-all">
                        <div class="stat-number" id="total-bookings">0</div>
                        <div class="stat-label">Total</div>
                    </div>
                    <div class="stat-card c-pend">
                        <div class="stat-number" id="pending-bookings">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card c-conf">
                        <div class="stat-number" id="confirmed-bookings">0</div>
                        <div class="stat-label">Confirmed</div>
                    </div>
                    <div class="stat-card c-comp">
                        <div class="stat-number" id="completed-bookings">0</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="filter-bar">
                    <button class="filter-tab active" data-status="all"><i class="fas fa-list"></i> All</button>
                    <button class="filter-tab" data-status="pending"><i class="fas fa-clock"></i> Pending</button>
                    <button class="filter-tab" data-status="confirmed"><i class="fas fa-check-circle"></i> Confirmed</button>
                    <button class="filter-tab" data-status="completed"><i class="fas fa-flag-checkered"></i> Completed</button>
                    <button class="filter-tab" data-status="cancelled"><i class="fas fa-times-circle"></i> Cancelled</button>
                </div>

                <!-- Bookings List -->
                <div id="bookings-list">
                    <div class="loading-wrap">
                        <div class="spinner"></div>
                        <p>Loading your bookings...</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="booking-modal">
        <div class="modal">
            <div class="modal-head">
                <h2><i class="fas fa-calendar-alt"></i> Booking Details</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="booking-details">
                <!-- filled by JS -->
            </div>
        </div>
    </div>

    @include('customerFolder.partials.footer')

<script>
let currentStatus = 'all';
let allBookings = [];

document.addEventListener('DOMContentLoaded', function () {
    loadBookings();
    setupFilters();
});

/* ─── FILTERS ─── */
function setupFilters() {
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.dataset.status;
            applyFilter();
        });
    });
}

function applyFilter() {
    const cards = document.querySelectorAll('.booking-card');
    let visible = 0;

    cards.forEach(card => {
        const match = (currentStatus === 'all' || card.dataset.status === currentStatus);
        card.classList.toggle('filtered-out', !match);
        if (match) visible++;
    });

    // remove any stale empty state
    const old = document.getElementById('empty-placeholder');
    if (old) old.remove();

    // show empty state only if cards exist but none visible
    if (cards.length > 0 && visible === 0) {
        const el = document.createElement('div');
        el.id = 'empty-placeholder';
        el.innerHTML = emptyHTML(
            `No ${currentStatus} bookings yet.`,
            `<button onclick="resetFilter()" class="btn btn-primary"><i class="fas fa-list"></i> View All</button>`
        );
        document.getElementById('bookings-list').appendChild(el);
    }
}

function resetFilter() {
    currentStatus = 'all';
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    document.querySelector('.filter-tab[data-status="all"]').classList.add('active');
    applyFilter();
}

/* ─── LOAD BOOKINGS ─── */
function loadBookings() {
    document.getElementById('bookings-list').innerHTML =
        '<div class="loading-wrap"><div class="spinner"></div><p>Loading your bookings...</p></div>';

    fetch('/api/my-bookings?status=all', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
    .then(data => {
        if (!data.success) throw new Error(data.message);
        allBookings = data.bookings;
        updateStats(data);

        if (data.bookings.length === 0) {
            document.getElementById('bookings-list').innerHTML = emptyHTML(
                "You haven't made any bookings yet.",
                `<a href="{{ route('roomBooking') }}" class="btn btn-primary" style="text-decoration:none;"><i class="fas fa-plus"></i> Make a Booking</a>`
            );
        } else {
            document.getElementById('bookings-list').innerHTML = data.bookings.map((b, i) => cardHTML(b, i)).join('');
            applyFilter();
        }
    })
    .catch(err => {
        document.getElementById('bookings-list').innerHTML = emptyHTML(
            err.message || 'Something went wrong.',
            `<button onclick="loadBookings()" class="btn btn-primary"><i class="fas fa-redo"></i> Retry</button>`
        );
    });
}

/* ─── STATS ─── */
function updateStats(d) {
    document.getElementById('total-bookings').textContent     = d.total || 0;
    document.getElementById('pending-bookings').textContent   = d.pending || 0;
    document.getElementById('confirmed-bookings').textContent = d.confirmed || 0;
    document.getElementById('completed-bookings').textContent = d.completed || 0;
}

/* ─── HELPERS ─── */
function badgeClass(status) {
    const map = { pending:'badge-pending', confirmed:'badge-confirmed', completed:'badge-completed', cancelled:'badge-cancelled', refunded:'badge-refunded' };
    return map[status] || 'badge-pending';
}
function badgeIcon(status) {
    const map = { pending:'fa-clock', confirmed:'fa-check-circle', completed:'fa-flag-checkered', cancelled:'fa-times-circle', refunded:'fa-undo' };
    return map[status] || 'fa-clock';
}
function cap(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : 'Pending'; }

/* ─── CARD HTML ─── */
function cardHTML(b, i) {
    const total    = parseFloat(b.totalPrice || 0).toFixed(2);
    const paid     = parseFloat(b.total_paid || 0).toFixed(2);
    const balance  = (parseFloat(total) - parseFloat(paid)).toFixed(2);
    const guests   = b.numGuests || b.cart?.numGuests || 1;
    const start    = b.formatted_event_start || 'N/A';
    const end      = b.formatted_event_end || 'N/A';
    const booked   = b.formatted_created_at ? b.formatted_created_at.split(' ')[0] : 'N/A';
    const accomCnt = b.accommodations ? b.accommodations.length : (b.cart?.items?.length || 0);
    const status   = b.bookingStatus || 'pending';

    return `
    <div class="booking-card" data-status="${status}" style="animation-delay:${i * 0.06}s">
        <div class="card-header">
            <div>
                <h3>Booking #${b.bookingID}</h3>
                <div class="card-meta">
                    <span class="meta-item"><i class="far fa-calendar"></i> ${start}</span>
                    <span class="meta-item"><i class="fas fa-users"></i> ${guests} guest${guests > 1 ? 's' : ''}</span>
                    <span class="meta-item"><i class="far fa-clock"></i> Booked ${booked}</span>
                </div>
            </div>
            <span class="badge ${badgeClass(status)}"><i class="fas ${badgeIcon(status)}"></i> ${cap(status)}</span>
        </div>

        <div class="card-body">
            <div class="details-grid">
                <div class="detail-box">
                    <h4><i class="fas fa-calendar-day"></i> Dates</h4>
                    <p>${start} – ${end}</p>
                </div>
                <div class="detail-box">
                    <h4><i class="fas fa-home"></i> Accommodations</h4>
                    <p>${accomCnt} item${accomCnt !== 1 ? 's' : ''}</p>
                </div>
                <div class="detail-box">
                    <h4><i class="fas fa-star"></i> Type</h4>
                    <p>${b.eventType || 'Standard'}</p>
                </div>
            </div>
            <div class="price-box">
                <h4>Payment</h4>
                <div class="price-row"><span>Total</span><span>₱${total}</span></div>
                <div class="price-row"><span>Paid</span><span style="color:var(--green)">₱${paid}</span></div>
                <div class="price-row total"><span>Balance</span><span>₱${balance}</span></div>
            </div>
        </div>

        <div class="card-actions">
            <button onclick="viewBooking(${b.bookingID})" class="btn btn-primary"><i class="fas fa-eye"></i> View Details</button>
            ${(status === 'pending' || status === 'confirmed') ?
                `<button onclick="cancelBooking(${b.bookingID})" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>` : ''}
        </div>
    </div>`;
}

/* ─── EMPTY STATE ─── */
function emptyHTML(msg, btn) {
    return `
    <div class="empty-state">
        <div class="empty-icon"><i class="far fa-calendar-alt"></i></div>
        <h3>No bookings found</h3>
        <p>${msg}</p>
        ${btn}
    </div>`;
}

/* ─── VIEW BOOKING MODAL ─── */
function viewBooking(id) {
    const modal = document.getElementById('booking-modal');
    const body  = document.getElementById('booking-details');
    body.innerHTML = '<div class="loading-wrap"><div class="spinner"></div><p>Loading...</p></div>';
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    fetch(`/api/my-bookings/${id}`, {
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(r => { if(!r.ok) throw new Error('Failed to load'); return r.json(); })
    .then(d => { if(d.success) renderModal(d.booking); else throw new Error(d.message); })
    .catch(err => {
        body.innerHTML = `<div class="empty-state"><div class="empty-icon" style="color:var(--red)"><i class="fas fa-exclamation-triangle"></i></div><h3>Error</h3><p>${err.message}</p></div>`;
    });
}

function renderModal(b) {
    const status  = b.bookingStatus || 'pending';
    const guests  = b.numGuests || b.cart?.numGuests || 1;
    const start   = b.formatted_details?.event_start || 'N/A';
    const end     = b.formatted_details?.event_end || 'N/A';
    const booked  = b.formatted_details?.created_at ? b.formatted_details.created_at.split(' ')[0] : 'N/A';

    // accommodations
    let accomHTML = '<p style="font-size:0.8rem;color:var(--text-faint);text-align:center;padding:1rem 0;">No accommodations found.</p>';
    if (b.cart?.items?.length) {
        accomHTML = b.cart.items.map(item => {
            const unit = item.unit || {};
            const days = b.cart.daysCount || 1;
            let price = 0;
            if (unit.unitType === 'room') {
                price = parseFloat(unit.unitRatePrice || 0) * (guests < 2 ? 2 : guests) * days;
            } else {
                price = parseFloat(item.subtotalPrice || unit.unitRatePrice || 0);
            }
            return `
            <div class="accom-item">
                <div>
                    <div class="name">${unit.unitName || 'N/A'}</div>
                    <div class="type ${unit.unitType || ''}">${unit.unitType ? cap(unit.unitType) : ''} • ${guests} guest${guests>1?'s':''}</div>
                </div>
                <div class="price">₱${price.toFixed(2)}</div>
            </div>`;
        }).join('');
    }

    // payments
    let payHTML = '<p style="font-size:0.8rem;color:var(--text-faint);text-align:center;padding:1rem 0;">No payment records.</p>';
    if (b.payments?.length) {
        payHTML = b.payments.map(p => {
            const date = new Date(p.paymentDate).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
            return `
            <div class="payment-item">
                <div>
                    <div class="ref">${p.paymentReference || 'Payment'}</div>
                    <div class="info">${date} • ${p.paymentMethod || 'N/A'} • ${p.paymentType || 'Payment'}</div>
                </div>
                <div>
                    <div class="amount">₱${parseFloat(p.amountPaid || 0).toFixed(2)}</div>
                </div>
            </div>`;
        }).join('');
    }

    document.getElementById('booking-details').innerHTML = `
        <div class="modal-top">
            <div>
                <h3>Booking #${b.bookingID}</h3>
                <p class="sub">${b.eventType || 'Standard Booking'}</p>
            </div>
            <span class="badge ${badgeClass(status)}"><i class="fas ${badgeIcon(status)}"></i> ${cap(status)}</span>
        </div>

        <!-- Dates -->
        <div class="modal-section">
            <div class="modal-section-title"><i class="fas fa-calendar-alt"></i> Dates</div>
            <div class="modal-dates">
                <div class="modal-date-item"><div class="label">Start</div><div class="value">${start}</div></div>
                <div class="modal-date-item"><div class="label">End</div><div class="value">${end}</div></div>
                <div class="modal-date-item"><div class="label">Booked On</div><div class="value">${booked}</div></div>
            </div>
        </div>

        <!-- Price Summary -->
        <div class="modal-section">
            <div class="modal-section-title"><i class="fas fa-wallet"></i> Payment Summary</div>
            <div class="modal-price-box">
                <div class="modal-price-row"><span>Total Amount</span><span>${b.formatted_details?.total_price || '₱0.00'}</span></div>
                <div class="modal-price-row"><span>Amount Paid</span><span style="color:var(--green)">${b.formatted_details?.total_paid || '₱0.00'}</span></div>
                <div class="modal-price-row total"><span>Balance</span><span>${b.formatted_details?.remaining_balance || '₱0.00'}</span></div>
            </div>
        </div>

        <!-- Accommodations -->
        <div class="modal-section">
            <div class="modal-section-title"><i class="fas fa-home"></i> Accommodations</div>
            ${accomHTML}
        </div>

        <!-- Payments -->
        <div class="modal-section">
            <div class="modal-section-title"><i class="fas fa-receipt"></i> Payment History</div>
            ${payHTML}
        </div>

        ${b.specialRequirements ? `
        <div class="info-box">
            <h4><i class="fas fa-sticky-note"></i> Special Requirements</h4>
            <p>${b.specialRequirements}</p>
        </div>` : ''}

        <div class="modal-actions">
            ${(status === 'pending' || status === 'confirmed') ?
                `<button onclick="cancelBooking(${b.bookingID}, true)" class="btn btn-danger"><i class="fas fa-times"></i> Cancel Booking</button>` : ''}
            <button onclick="closeModal()" class="btn btn-ghost"><i class="fas fa-times"></i> Close</button>
        </div>
    `;
}

/* ─── CANCEL ─── */
function cancelBooking(id, fromModal = false) {
    if (!confirm('Cancel this booking? This cannot be undone.')) return;
    fetch(`/api/my-bookings/${id}/cancel`, {
        method: 'POST',
        headers: { 'Accept':'application/json', 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) { alert('Booking cancelled.'); if (fromModal) closeModal(); loadBookings(); }
        else alert('Failed: ' + d.message);
    })
    .catch(e => alert('Error: ' + e.message));
}

/* ─── MODAL CONTROLS ─── */
function closeModal() {
    document.getElementById('booking-modal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

document.getElementById('booking-modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
</body>
</html>