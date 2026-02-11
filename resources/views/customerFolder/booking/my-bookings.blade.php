<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        /* ═══════════════════════════════════════════════════════════
           VARIABLES - MATCHING BOOKING PAGE THEME
        ═══════════════════════════════════════════════════════════ */
        :root {
            --booking-primary-yellow: #FFD709;
            --booking-secondary-yellow: #FFA500;
            --booking-yellow-dark: #F59E0B;
            --booking-text-dark: #1F2937;
            --booking-text-medium: #6B7280;
            --booking-text-light: #9CA3AF;
            --booking-border-color: #E5E7EB;
            --booking-green: #10B981;
            --booking-green-dark: #059669;
            --booking-red: #EF4444;
            --booking-red-dark: #DC2626;
            --booking-red-light: rgba(239, 68, 68, 0.1);
            --booking-yellow-light: rgba(255, 215, 0, 0.15);
            --booking-blue: #3B82F6;
            --booking-blue-light: rgba(59, 130, 246, 0.1);
            --booking-purple: #8B5CF6;
            --booking-purple-light: rgba(139, 92, 246, 0.1);
            --white: #FFFFFF;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #FFFFFF;
            color: var(--booking-text-dark);
            min-height: 100vh;
        }

        .cursive-font { font-family: 'Dancing Script', cursive; }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes expandLine {
            from { width: 0; opacity: 0; }
            to { width: 100px; opacity: 1; }
        }

        .anim-fade-up { animation: fadeUp 0.4s ease both; }

        /* ═══════════════════════════════════════════════════════════
           LAYOUT
        ═══════════════════════════════════════════════════════════ */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2.5rem 1rem 4rem;
        }

        .bookings-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .content-layout {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        /* ═══════════════════════════════════════════════════════════
           PAGE TITLE
        ═══════════════════════════════════════════════════════════ */
        .page-title-wrap {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeInDown 0.6s ease-out;
        }

        .page-title {
            font-size: 2.8rem;
            font-weight: 800;
            /* background: linear-gradient(135deg, #F59E0B, #D97706, #B45309); */
            background: #1F2937;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            /* text-shadow: 0 4px 12px rgba(245, 158, 11, 0.2); */
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--booking-text-medium);
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .page-title-line {
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark));
            border-radius: 3px;
            margin: 0.8rem auto 0;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
            animation: expandLine 0.8s ease-out 0.3s both;
        }

        /* ═══════════════════════════════════════════════════════════
           LEFT SIDEBAR - STATS & FILTERS
        ═══════════════════════════════════════════════════════════ */
        .sidebar {
            position: sticky;
            top: 100px;
            min-width: 280px;
            animation: fadeUp 0.5s ease both;
        }

        /* Stats Cards */
        .stats-section {
            background: var(--white);
            border: 2px solid var(--booking-border-color);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            /* background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark)); */
            border-radius: 20px 20px 0 0;
        }

        .stats-section {
            position: relative;
        }

        .stats-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--booking-text-medium);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-title i {
            color: var(--booking-primary-yellow);
            font-size: 1rem;
        }

        .stat-item {
            padding: 1rem;
            background: linear-gradient(135deg, #FAFAFA, #FFFFFF);
            border: 2px solid var(--booking-border-color);
            border-radius: 12px;
            margin-bottom: 0.8rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        .stat-item:hover {
            transform: translateX(4px);
            border-color: var(--booking-primary-yellow);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.2);
        }

        .stat-item:last-child {
            margin-bottom: 0;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.3rem;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--booking-text-medium);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-item.c-all .stat-number { 
            background: linear-gradient(135deg, var(--booking-text-dark), var(--booking-text-medium));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-item.c-pend .stat-number { 
            background: linear-gradient(135deg, var(--booking-secondary-yellow), var(--booking-yellow-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-item.c-conf .stat-number { 
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-item.c-comp .stat-number { 
            background: linear-gradient(135deg, var(--booking-blue), #2563EB);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            border: 2px solid var(--booking-border-color);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            position: relative;
        }

        .filter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            /* background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark)); */
            border-radius: 20px 20px 0 0;
        }

        .filter-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--booking-text-medium);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-title i {
            color: var(--booking-primary-yellow);
            font-size: 1rem;
        }

        .filter-tab {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border-radius: 12px;
            border: 2px solid var(--booking-border-color);
            background: linear-gradient(135deg, #FFFFFF, #FAFAFA);
            color: var(--booking-text-medium);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.6rem;
            font-family: inherit;
        }

        .filter-tab:last-child {
            margin-bottom: 0;
        }

        .filter-tab i { 
            font-size: 0.9rem;
            transition: transform 0.3s;
        }

        .filter-tab:hover {
            border-color: var(--booking-primary-yellow);
            color: var(--booking-text-dark);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.2);
        }

        .filter-tab:hover i {
            transform: scale(1.2);
        }

        .filter-tab.active {
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow));
            border-color: var(--booking-primary-yellow);
            color: var(--white);
            transform: translateX(4px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        .filter-tab.active i {
            transform: scale(1.2);
        }

        /* ═══════════════════════════════════════════════════════════
           RIGHT CONTENT - BOOKINGS LIST
        ═══════════════════════════════════════════════════════════ */
        .bookings-main {
            flex: 1;
        }

        /* ═══════════════════════════════════════════════════════════
           BOOKING CARD
        ═══════════════════════════════════════════════════════════ */
        .booking-card {
            background: var(--white);
            border: 2px solid var(--booking-border-color);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.2rem;
            animation: fadeUp 0.4s ease both;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .booking-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark));
        }

        .booking-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-color: var(--booking-primary-yellow);
        }

        .booking-card.filtered-out { 
            display: none !important; 
        }

        /* header row */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card-header h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--booking-text-dark);
            margin-bottom: 0.5rem;
        }

        .card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
        }

        .meta-item {
            font-size: 0.8rem;
            color: var(--booking-text-medium);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .meta-item i {
            color: var(--booking-primary-yellow);
            font-size: 0.85rem;
        }

        /* status badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        .badge i { font-size: 0.75rem; }

        .badge-pending   { 
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), rgba(255, 165, 0, 0.1));
            color: var(--booking-yellow-dark);
            border: 2px solid var(--booking-secondary-yellow);
        }
        .badge-confirmed { 
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            color: var(--booking-green);
            border: 2px solid var(--booking-green);
        }
        .badge-completed { 
            background: linear-gradient(135deg, var(--booking-blue-light), rgba(59, 130, 246, 0.05));
            color: var(--booking-blue);
            border: 2px solid var(--booking-blue);
        }
        .badge-cancelled { 
            background: linear-gradient(135deg, var(--booking-red-light), rgba(239, 68, 68, 0.05));
            color: var(--booking-red);
            border: 2px solid var(--booking-red);
        }
        .badge-refunded  { 
            background: linear-gradient(135deg, var(--booking-purple-light), rgba(139, 92, 246, 0.05));
            color: var(--booking-purple);
            border: 2px solid var(--booking-purple);
        }

        /* card body */
        .card-body {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .detail-box {
            background: linear-gradient(135deg, #FAFAFA, #FFFFFF);
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            padding: 1rem;
            transition: all 0.3s;
        }

        .detail-box:hover {
            border-color: var(--booking-primary-yellow);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.15);
        }

        .detail-box h4 {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--booking-text-light);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .detail-box h4 i { 
            color: var(--booking-primary-yellow); 
            font-size: 0.8rem;
        }

        .detail-box p {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--booking-text-dark);
            line-height: 1.4;
        }

        /* price summary mini */
        .price-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(5, 150, 105, 0.05));
            border: 3px solid var(--booking-green);
            border-radius: 16px;
            padding: 1.2rem;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.15);
        }

        .price-box h4 {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--booking-green-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .price-box h4 i {
            color: var(--booking-green);
            font-size: 0.9rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--booking-text-medium);
            padding: 0.4rem 0;
        }

        .price-row span:last-child { 
            font-weight: 700; 
            color: var(--booking-text-dark); 
        }

        .price-row.total {
            margin-top: 0.6rem;
            padding-top: 0.6rem;
            border-top: 2px solid rgba(16, 185, 129, 0.2);
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--booking-text-dark);
        }

        .price-row.total span:last-child { 
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
            font-size: 1.1rem;
        }

        /* card actions */
        .card-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn i { 
            font-size: 0.8rem;
            position: relative;
            z-index: 1;
        }

        .btn span {
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark));
            color: var(--white);
            box-shadow: 0 4px 16px rgba(255, 215, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(255, 215, 0, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--booking-red-light), rgba(239, 68, 68, 0.05));
            color: var(--booking-red);
            border: 2px solid var(--booking-red);
        }

        .btn-danger:hover {
            background: var(--booking-red);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-ghost {
            background: linear-gradient(135deg, #FFFFFF, #FAFAFA);
            color: var(--booking-text-medium);
            border: 2px solid var(--booking-border-color);
        }

        .btn-ghost:hover { 
            border-color: var(--booking-text-dark); 
            color: var(--booking-text-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        /* ═══════════════════════════════════════════════════════════
           EMPTY STATE
        ═══════════════════════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border: 2px solid var(--booking-border-color);
            border-radius: 20px;
            animation: fadeUp 0.4s ease both;
            position: relative;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark));
            border-radius: 20px 20px 0 0;
        }

        .empty-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), rgba(255, 165, 0, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.2rem;
            color: var(--booking-secondary-yellow);
            box-shadow: 0 8px 24px rgba(255, 215, 0, 0.2);
        }

        .empty-state h3 { 
            font-size: 1.3rem; 
            font-weight: 800; 
            margin-bottom: 0.5rem;
            color: var(--booking-text-dark);
        }

        .empty-state p { 
            font-size: 0.9rem; 
            color: var(--booking-text-medium); 
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════════════════════
           LOADING
        ═══════════════════════════════════════════════════════════ */
        .loading-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 4rem 2rem;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 215, 0, 0.2);
            border-top-color: var(--booking-primary-yellow);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-bottom: 1rem;
        }

        .loading-wrap p { 
            font-size: 0.9rem; 
            color: var(--booking-text-medium); 
            font-weight: 600; 
        }

        /* ═══════════════════════════════════════════════════════════
           MODAL
        ═══════════════════════════════════════════════════════════ */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .modal-overlay.show { display: flex; }

        .modal {
            background: var(--white);
            border-radius: 24px;
            width: 100%;
            max-width: 780px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            animation: fadeUp 0.3s ease;
            border: 2px solid var(--booking-border-color);
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.8rem 2rem;
            border-bottom: 2px solid var(--booking-border-color);
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.05), transparent);
            border-radius: 24px 24px 0 0;
            position: relative;
        }

        .modal-head::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark));
            border-radius: 24px 24px 0 0;
        }

        .modal-head h2 {
            font-size: 1.3rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--booking-text-dark);
        }

        .modal-head h2 i { 
            color: var(--booking-primary-yellow); 
            font-size: 1.2rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--booking-text-light);
            font-size: 1.3rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .modal-close:hover { 
            background: var(--booking-red-light);
            color: var(--booking-red);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
            background: var(--white);
        }

        /* modal sections */
        .modal-section { 
            margin-bottom: 2rem; 
        }

        .modal-section-title {
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--booking-text-medium);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid var(--booking-border-color);
        }

        .modal-section-title::before {
            content: '';
            width: 4px;
            height: 18px;
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow));
            border-radius: 2px;
        }

        .modal-section-title i { 
            color: var(--booking-primary-yellow); 
            font-size: 0.85rem;
        }

        /* dates grid in modal */
        .modal-dates {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .modal-date-item {
            background: linear-gradient(135deg, #FAFAFA, #FFFFFF);
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            padding: 1rem;
        }

        .modal-date-item .label {
            font-size: 0.7rem;
            color: var(--booking-text-light);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 0.4rem;
        }

        .modal-date-item .value {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--booking-text-dark);
        }

        /* accommodation item */
        .accom-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.2rem;
            background: linear-gradient(135deg, #FAFAFA, #FFFFFF);
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            margin-bottom: 0.8rem;
            transition: all 0.3s;
        }

        .accom-item:hover {
            border-color: var(--booking-primary-yellow);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.15);
        }

        .accom-item .name { 
            font-size: 0.9rem; 
            font-weight: 700; 
            color: var(--booking-text-dark); 
        }

        .accom-item .type {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 0.2rem;
        }

        .type.room { color: var(--booking-blue); }
        .type.cottage { color: var(--booking-green); }

        .accom-item .price {
            font-size: 1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--booking-secondary-yellow), var(--booking-yellow-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* payment item */
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.2rem;
            background: linear-gradient(135deg, #FAFAFA, #FFFFFF);
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            margin-bottom: 0.8rem;
            transition: all 0.3s;
        }

        .payment-item:hover {
            border-color: var(--booking-green);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .payment-item .ref { 
            font-size: 0.85rem; 
            font-weight: 700; 
            color: var(--booking-text-dark); 
        }

        .payment-item .info { 
            font-size: 0.72rem; 
            color: var(--booking-text-medium); 
            margin-top: 0.2rem;
            font-weight: 600;
        }

        .payment-item .amount { 
            font-size: 1rem; 
            font-weight: 800;
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* modal price summary */
        .modal-price-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(5, 150, 105, 0.05));
            border: 3px solid var(--booking-green);
            border-radius: 16px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.15);
        }

        .modal-price-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--booking-text-medium);
            padding: 0.4rem 0;
            font-weight: 600;
        }

        .modal-price-row span:last-child { 
            color: var(--booking-text-dark); 
            font-weight: 700; 
        }

        .modal-price-row.total {
            border-top: 2px solid rgba(16, 185, 129, 0.2);
            margin-top: 0.6rem;
            padding-top: 0.6rem;
            font-weight: 700;
            color: var(--booking-text-dark);
            font-size: 0.9rem;
        }

        .modal-price-row.total span:last-child { 
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
            font-size: 1.2rem;
        }

        /* modal top row with badge */
        .modal-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.8rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .modal-top h3 { 
            font-size: 1.4rem; 
            font-weight: 800;
            color: var(--booking-text-dark);
        }

        .modal-top .sub { 
            font-size: 0.82rem; 
            color: var(--booking-text-medium);
            font-weight: 600;
        }

        /* special req box */
        .info-box {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.08), rgba(255, 165, 0, 0.05));
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
        }

        .info-box h4 {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--booking-text-medium);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .info-box h4 i { 
            color: var(--booking-primary-yellow); 
            font-size: 0.8rem;
        }

        .info-box p { 
            font-size: 0.85rem; 
            color: var(--booking-text-dark); 
            line-height: 1.6;
            font-weight: 500;
        }

        /* modal actions */
        .modal-actions {
            display: flex;
            gap: 0.8rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--booking-border-color);
            flex-wrap: wrap;
        }

        /* ═══════════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .content-layout {
                flex-direction: column;
            }

            .sidebar {
                position: static;
                width: 100%;
                min-width: auto;
            }

            .stats-section,
            .filter-section {
                margin-bottom: 1.5rem;
            }

            .stats-title,
            .filter-title {
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .card-body { 
                grid-template-columns: 1fr; 
            }

            .details-grid { 
                grid-template-columns: repeat(2, 1fr); 
            }

            .modal-dates { 
                grid-template-columns: 1fr 1fr; 
            }

            .page-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 500px) {
            .details-grid { 
                grid-template-columns: 1fr; 
            }

            .booking-card { 
                padding: 1.5rem; 
            }

            .modal-dates { 
                grid-template-columns: 1fr; 
            }

            .page-title {
                font-size: 1.8rem;
            }

            .sidebar {
                padding: 0;
            }

            .stat-item,
            .filter-tab {
                font-size: 0.8rem;
            }

            .card-header h3 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    @include('customerFolder.partials.navbar')

    <div class="bookings-page-wrapper">
        <main class="main-content">
            <div class="bookings-wrapper">

                <!-- Title -->
                <div class="page-title-wrap">
                    <h1 class="page-title">My Bookings</h1>
                    <p class="page-subtitle">Track and manage all your reservations</p>
                    <div class="page-title-line"></div>
                </div>

                <!-- Layout: Sidebar Left, Content Right -->
                <div class="content-layout">
                    <!-- LEFT SIDEBAR -->
                    <aside class="sidebar">
                        <!-- Stats Section -->
                        <div class="stats-section">
                            <div class="stats-title">
                                <i class="fas fa-chart-bar"></i>
                                Statistics
                            </div>
                            <div class="stat-item c-all">
                                <div class="stat-top">
                                    <span class="stat-label">Total</span>
                                </div>
                                <div class="stat-number" id="total-bookings">0</div>
                            </div>
                            <div class="stat-item c-pend">
                                <div class="stat-top">
                                    <span class="stat-label">Pending</span>
                                </div>
                                <div class="stat-number" id="pending-bookings">0</div>
                            </div>
                            <div class="stat-item c-conf">
                                <div class="stat-top">
                                    <span class="stat-label">Confirmed</span>
                                </div>
                                <div class="stat-number" id="confirmed-bookings">0</div>
                            </div>
                            <div class="stat-item c-comp">
                                <div class="stat-top">
                                    <span class="stat-label">Completed</span>
                                </div>
                                <div class="stat-number" id="completed-bookings">0</div>
                            </div>
                        </div>

                        <!-- Filter Section -->
                        <div class="filter-section">
                            <div class="filter-title">
                                <i class="fas fa-filter"></i>
                                Filter By Status
                            </div>
                            <button class="filter-tab active" data-status="all">
                                <i class="fas fa-list"></i> 
                                <span>All Bookings</span>
                            </button>
                            <button class="filter-tab" data-status="pending">
                                <i class="fas fa-clock"></i> 
                                <span>Pending</span>
                            </button>
                            <button class="filter-tab" data-status="confirmed">
                                <i class="fas fa-check-circle"></i> 
                                <span>Confirmed</span>
                            </button>
                            <button class="filter-tab" data-status="completed">
                                <i class="fas fa-flag-checkered"></i> 
                                <span>Completed</span>
                            </button>
                            <button class="filter-tab" data-status="cancelled">
                                <i class="fas fa-times-circle"></i> 
                                <span>Cancelled</span>
                            </button>
                        </div>
                    </aside>

                    <!-- RIGHT CONTENT -->
                    <div class="bookings-main">
                        <div id="bookings-list">
                            <div class="loading-wrap">
                                <div class="spinner"></div>
                                <p>Loading your bookings...</p>
                            </div>
                        </div>
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
                <h4><i class="fas fa-wallet"></i> Payment</h4>
                <div class="price-row"><span>Total</span><span>₱${total}</span></div>
                <div class="price-row"><span>Paid</span><span style="color:var(--booking-green)">₱${paid}</span></div>
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
        body.innerHTML = `<div class="empty-state"><div class="empty-icon" style="color:var(--booking-red)"><i class="fas fa-exclamation-triangle"></i></div><h3>Error</h3><p>${err.message}</p></div>`;
    });
}

function renderModal(b) {
    const status  = b.bookingStatus || 'pending';
    const guests  = b.numGuests || b.cart?.numGuests || 1;
    const start   = b.formatted_details?.event_start || 'N/A';
    const end     = b.formatted_details?.event_end || 'N/A';
    const booked  = b.formatted_details?.created_at ? b.formatted_details.created_at.split(' ')[0] : 'N/A';

    // accommodations
    let accomHTML = '<p style="font-size:0.85rem;color:var(--booking-text-medium);text-align:center;padding:1.5rem 0;">No accommodations found.</p>';
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
    let payHTML = '<p style="font-size:0.85rem;color:var(--booking-text-medium);text-align:center;padding:1.5rem 0;">No payment records.</p>';
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
                <div class="modal-price-row"><span>Amount Paid</span><span style="color:var(--booking-green)">${b.formatted_details?.total_paid || '₱0.00'}</span></div>
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