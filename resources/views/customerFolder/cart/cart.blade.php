<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        /* ═══════════════════════════════
           PROFESSIONAL COLOR PALETTE
        ═══════════════════════════════ */
        :root {
            /* White variations for depth */
            --white-pure:     #FFFFFF;
            --white-soft:     #FAFAFA;
            --white-warm:     #F8F8F8;
            --white-pearl:    #F5F5F5;
            --white-ivory:    #F2F2F2;
            
            --cream:        #FDF8F0;
            --cream-dark:   #F5EDE0;
            --sunflower:    #E8A825;
            --sun-light:    #F0C660;
            --sun-dark:     #C88A1A;
            --text:         #3D3226;
            --text-soft:    #7A6E5E;
            --text-faint:   #A89A87;
            --border:       #E8DDD0;
            --border-light: #F0E8DC;
            --white:        #FFFFFF;
            --red:          #D9534F;
            --red-light:    #F2D5D4;
            --red-dark:     #B8403C;
            --green:        #3A9D6E;
            --green-light:  #E6F5EE;
            --green-dark:   #2E7D58;
            --blue:         #5B8DB8;
            --blue-light:   #E4EEF6;
            --blue-dark:    #4A7296;
            --dark:         #2D2420;
            --dark-hover:   #3D3530;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--white-soft);
            color: var(--text);
            min-height: 100vh;
        }

        .cursive-font { font-family: 'Dancing Script', cursive; }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ═══════════════════════════════
           LAYOUT
        ═══════════════════════════════ */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 3rem 1.5rem 5rem;
        }

        .cart-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ═══════════════════════════════
           PAGE TITLE - PROFESSIONAL
        ═══════════════════════════════ */
        .page-title-wrap {
            text-align: center;
            margin-bottom: 3.5rem;
            animation: fadeUp 0.6s ease both;
        }

        .page-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--sunflower);
            margin-bottom: 0.8rem;
            text-shadow: 0 2px 8px rgba(232, 168, 37, 0.15);
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-soft);
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .title-line {
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--sun-dark), var(--sunflower), var(--sun-light));
            border-radius: 4px;
            margin: 1.5rem auto 0;
            box-shadow: 0 2px 8px rgba(232, 168, 37, 0.3);
        }

        /* ═══════════════════════════════
           LOADING STATE
        ═══════════════════════════════ */
        .loading-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 6rem 1rem;
        }
        .spinner {
            width: 56px;
            height: 56px;
            border: 5px solid var(--white-pearl);
            border-top-color: var(--sunflower);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-bottom: 1.5rem;
        }
        .loading-wrap p { 
            font-size: 1rem; 
            color: var(--text-soft); 
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* ═══════════════════════════════
           SUMMARY HEADER - REFINED WHITE
        ═══════════════════════════════ */
        .summary-card {
            background: var(--white-pure);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 2px 12px rgba(61, 50, 38, 0.04);
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--sunflower), var(--sun-light));
        }

        .summary-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .summary-left h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.6rem;
            letter-spacing: -0.3px;
        }

        /* booking type pill */
        .type-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.1rem;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }
        .type-pill.room {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
            color: var(--white);
        }
        .type-pill.cottage {
            background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
            color: var(--white);
        }
        .type-pill i { font-size: 0.8rem; }

        /* ═══════════════════════════════
           DATE PILLS - REFINED WHITE
        ═══════════════════════════════ */
        .date-pills {
            display: inline-flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .date-pill {
            background: var(--white-warm);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 0.7rem 1rem;
            text-align: center;
            width: auto;
            min-width: fit-content;
            transition: all 0.3s ease;
            box-shadow: 0 1px 4px rgba(61, 50, 38, 0.03);
        }
        .date-pill:hover {
            border-color: var(--sunflower);
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(232, 168, 37, 0.15);
            background: var(--white-pure);
        }
        .date-pill .label {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.3rem;
            white-space: nowrap;
        }
        .date-pill .value {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            white-space: nowrap;
        }

        /* ═══════════════════════════════
           WARNING BANNERS - ENHANCED
        ═══════════════════════════════ */
        .warning-banner {
            border-radius: 20px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .warning-banner .w-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .warning-banner h4 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }
        .warning-banner p {
            font-size: 0.88rem;
            line-height: 1.6;
            font-weight: 500;
        }
        .warning-banner .w-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .warning-banner.yellow {
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
            border: 2px solid #F0D68A;
        }
        .warning-banner.yellow .w-icon { background: var(--white-pure); color: var(--sunflower); }
        .warning-banner.yellow h4 { color: #92400E; }
        .warning-banner.yellow p { color: #78350F; }

        .warning-banner.red {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 2px solid #FECACA;
        }
        .warning-banner.red .w-icon { background: var(--white-pure); color: var(--red); }
        .warning-banner.red h4 { color: #991B1B; }
        .warning-banner.red p { color: #7F1D1D; }

        /* ═══════════════════════════════
           CART ITEM CARD - PURE WHITE
        ═══════════════════════════════ */
        .item-card {
            background: var(--white-pure);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            padding: 0;
            margin-bottom: 1.2rem;
            animation: fadeUp 0.5s ease both;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(61, 50, 38, 0.04);
            position: relative;
        }

        .item-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            transition: all 0.3s ease;
        }

        .item-card.room::before { background: linear-gradient(to bottom, var(--blue), var(--blue-dark)); }
        .item-card.cottage::before { background: linear-gradient(to bottom, var(--green), var(--green-dark)); }
        .item-card.cottage.unavailable::before { background: linear-gradient(to bottom, var(--red), var(--red-dark)); }

        .item-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(61, 50, 38, 0.08);
        }

        .item-card-inner {
            padding: 0;
            display: flex;
        }

        .item-row {
            display: flex;
            width: 100%;
            min-height: 100%;
        }

        /* ═══════════════════════════════
           IMAGE CONTAINER - FULL HEIGHT WITH BETTER QUALITY
        ═══════════════════════════════ */
        .item-img-wrapper {
            position: relative;
            width: 280px;
            flex-shrink: 0;
            overflow: hidden;
            background: var(--white-warm);
        }

        .item-img {
            width: 100%;
            height: 100%;
            min-height: 100%;
            object-fit: contain;
            display: block;
            transition: transform 0.4s ease;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }

        .item-card:hover .item-img {
            transform: scale(1.05);
        }

        .item-img.grayscale { 
            filter: grayscale(1) opacity(0.6); 
        }

        /* ═══════════════════════════════
           DELETE BUTTON - TOP RIGHT CORNER AS X
        ═══════════════════════════════ */
        .btn-delete {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(217, 83, 79, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid var(--white-pure);
            color: var(--white-pure);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(217, 83, 79, 0.35);
            font-size: 0.95rem;
            z-index: 10;
        }

        .btn-delete:hover {
            background: var(--red-dark);
            transform: scale(1.15) rotate(90deg);
            box-shadow: 0 6px 24px rgba(217, 83, 79, 0.5);
        }

        .btn-delete:active {
            transform: scale(0.95) rotate(90deg);
        }

        /* ═══════════════════════════════
           ITEM CONTENT - RIGHT SIDE
        ═══════════════════════════════ */
        .item-content { 
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            padding: 2rem 4.5rem 2rem 2rem;
            flex: 1;
            min-width: 0;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .item-title-section {
            flex: 1;
        }

        .item-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.5rem;
            letter-spacing: -0.3px;
            line-height: 1.3;
        }

        .item-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 0.3rem 0.75rem;
            border-radius: 100px;
        }
        .item-badge.room { background: var(--blue-light); color: var(--blue); }
        .item-badge.cottage { background: var(--green-light); color: var(--green); }

        .item-price-section {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.6rem;
        }

        .item-price {
            font-size: 1.7rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: -0.5px;
        }
        .item-price.blue { color: var(--blue); }
        .item-price.green { color: var(--green); }
        .item-price.red   { color: var(--red); }

        /* tags row */
        .item-tags {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .item-tag {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.85rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }
        .item-tag:hover {
            transform: translateY(-2px);
        }
        .item-tag.blue  { background: var(--blue-light); color: var(--blue); }
        .item-tag.green { background: var(--green-light); color: var(--green); }
        .item-tag.red   { background: var(--red-light); color: var(--red); }
        .item-tag i { font-size: 0.75rem; }

        /* calculation note */
        .calc-note {
            background: var(--white-warm);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }
        .calc-note i { 
            color: var(--sunflower); 
            font-size: 0.9rem; 
            margin-top: 2px; 
            flex-shrink: 0; 
        }
        .calc-note p { 
            font-size: 0.8rem; 
            color: var(--text-soft); 
            line-height: 1.6; 
            font-weight: 500; 
        }
        .calc-note p strong { color: var(--text); font-weight: 700; }

        .calc-note.red { 
            border-color: #FECACA; 
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); 
        }
        .calc-note.red i { color: var(--red); }
        .calc-note.red p { color: #991B1B; }
        .calc-note.red p strong { color: #991B1B; }

        /* ═══════════════════════════════
           PRICE BREAKDOWN - PURE WHITE
        ═══════════════════════════════ */
        .breakdown-card {
            background: var(--white-pure);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            overflow: hidden;
            margin-top: 2rem;
            animation: fadeUp 0.5s ease .2s both;
            box-shadow: 0 2px 12px rgba(61, 50, 38, 0.04);
        }

        .breakdown-content {
            padding: 2.5rem;
        }

        .breakdown-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--white-pearl);
        }
        .breakdown-title i { 
            color: var(--sunflower); 
            font-size: 0.9rem;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            font-size: 0.95rem;
            color: var(--text-soft);
            font-weight: 500;
        }
        .breakdown-row span:first-child {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .breakdown-row span:last-child { 
            font-weight: 700; 
            color: var(--text);
            font-size: 1.05rem;
        }
        .breakdown-row .row-icon { 
            color: var(--sunflower); 
            font-size: 0.85rem;
        }

        .breakdown-row.total {
            border-top: 3px solid var(--sunflower);
            margin-top: 1rem;
            padding-top: 1.2rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
        }
        .breakdown-row.total span:last-child { 
            color: var(--green); 
            font-size: 1.8rem;
            font-weight: 800;
        }

        /* ═══════════════════════════════
           ACTION BUTTONS - SOFT WHITE BG
        ═══════════════════════════════ */
        .actions-section {
            background: var(--white-warm);
            border-top: 1px solid var(--border-light);
            padding: 2rem 2.5rem;
        }

        .actions-section-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .actions-section-title i { 
            color: var(--sunflower); 
            font-size: 0.75rem; 
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 1rem 1.5rem;
            border-radius: 14px;
            border: none;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
            letter-spacing: 0.3px;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(0,0,0,0.18);
        }
        .btn:active {
            transform: translateY(-1px);
        }
        .btn i { font-size: 0.85rem; }

        /* Professional Button Colors */
        .btn-rooms {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-hover) 100%);
            color: var(--white);
        }
        .btn-rooms:hover { 
            background: linear-gradient(135deg, #1A1310 0%, var(--dark) 100%);
        }

        .btn-cottages {
            background: linear-gradient(135deg, var(--sunflower) 0%, var(--sun-dark) 100%);
            color: var(--white);
        }
        .btn-cottages:hover { 
            background: linear-gradient(135deg, var(--sun-dark) 0%, #A66F15 100%);
        }

        .btn-checkout {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
            color: var(--white);
            font-size: 1.05rem;
            padding: 1.3rem 1.8rem;
            box-shadow: 0 4px 16px rgba(58, 157, 110, 0.3);
        }
        .btn-checkout:hover { 
            background: linear-gradient(135deg, var(--green-dark) 0%, #25654A 100%);
            box-shadow: 0 6px 28px rgba(58, 157, 110, 0.4);
        }

        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(0.3);
        }

        .btn-warning {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--sunflower) 0%, var(--sun-dark) 100%);
            color: var(--white);
            font-size: 1.05rem;
            padding: 1.3rem 1.8rem;
        }

        .btn-error {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
            color: var(--white);
            font-size: 1.05rem;
            padding: 1.3rem 1.8rem;
        }

        /* Small buttons for warnings */
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.55rem 1rem;
            border-radius: 12px;
        }
        .btn-sm-dark { 
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-hover) 100%);
            color: var(--white); 
        }
        .btn-sm-sun  { 
            background: linear-gradient(135deg, var(--sunflower) 0%, var(--sun-dark) 100%);
            color: var(--white); 
        }
        .btn-sm-red  { 
            background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
            color: var(--white); 
        }
        .btn-sm-ghost { 
            background: var(--white-pure); 
            color: var(--text-soft); 
            border: 2px solid var(--border); 
        }
        .btn-sm-ghost:hover { 
            border-color: var(--text-soft); 
            color: var(--text); 
            background: var(--white-warm);
        }

        /* ═══════════════════════════════
           EMPTY STATE - PURE WHITE
        ═══════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: var(--white-pure);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 2px 12px rgba(61, 50, 38, 0.04);
        }
        .empty-icon {
            width: 100px;
            height: 100px;
            background: var(--white-warm);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
            color: var(--sunflower);
            border: 2px solid var(--border-light);
            box-shadow: 0 2px 8px rgba(232, 168, 37, 0.1);
        }
        .empty-state h3 { 
            font-size: 1.5rem; 
            font-weight: 700; 
            margin-bottom: 0.6rem;
            color: var(--text);
        }
        .empty-state p { 
            font-size: 1rem; 
            color: var(--text-soft); 
            margin-bottom: 2rem; 
        }
        .empty-btns { 
            display: flex; 
            gap: 1rem; 
            justify-content: center; 
            flex-wrap: wrap; 
        }

        /* ═══════════════════════════════
           NOTIFICATIONS - ORIGINAL DESIGN
        ═══════════════════════════════ */
        .notification-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 400px;
        }
        
        .notification {
            padding: 18px 22px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: flex-start;
            gap: 15px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 200px;
            overflow: hidden;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }
        
        .notification.error {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
        }
        
        .notification.info {
            background: linear-gradient(135deg, var(--sunflower), var(--sun-light));
            color: var(--dark);
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: white;
        }
        
        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
            margin-top: 3px;
        }
        
        .notification-content {
            flex-grow: 1;
            min-width: 0;
        }
        
        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .notification-message {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 1.2rem;
            opacity: 0.8;
            transition: opacity 0.3s;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            flex-shrink: 0;
        }
        
        .notification-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Animation for notifications */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .notification-slide-in {
            animation: slideInRight 0.5s ease-out forwards;
        }
        
        .notification-slide-out {
            animation: slideOutRight 0.5s ease-out forwards;
        }

        /* ═══════════════════════════════
           AVAILABILITY MODAL - PURE WHITE
        ═══════════════════════════════ */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            backdrop-filter: blur(8px);
        }
        .modal-overlay.show { display: flex; }

        .modal {
            background: var(--white-pure);
            border-radius: 24px;
            width: 100%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 24px 80px rgba(0,0,0,0.3);
            animation: fadeUp 0.4s ease;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--white-pearl);
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border-radius: 24px 24px 0 0;
        }
        .modal-head h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--red);
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .modal-head h2 i { font-size: 1.1rem; }
        .modal-close {
            background: var(--white-pure);
            border: 2px solid var(--red-light);
            color: var(--red);
            font-size: 1.2rem;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .modal-close:hover { 
            background: var(--red);
            color: var(--white-pure);
            border-color: var(--red);
            transform: rotate(90deg);
        }

        .modal-body { padding: 2rem; }

        .modal-info-box {
            background: var(--blue-light);
            border: 2px solid #c8dff0;
            border-radius: 14px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            margin-bottom: 1.5rem;
        }
        .modal-info-box i { 
            color: var(--blue); 
            font-size: 1rem; 
            margin-top: 2px; 
            flex-shrink: 0; 
        }
        .modal-info-box p { 
            font-size: 0.85rem; 
            color: #1e40af; 
            font-weight: 500; 
            line-height: 1.6; 
        }

        .unavail-item {
            background: var(--white-warm);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
            margin-bottom: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }
        .unavail-item .ui-name { 
            font-size: 0.95rem; 
            font-weight: 700; 
            color: var(--text); 
            margin-bottom: 0.3rem; 
        }
        .unavail-item .ui-reason { 
            font-size: 0.78rem; 
            color: var(--red); 
            font-weight: 600; 
        }
        .unavail-item .ui-badge {
            font-size: 0.68rem; 
            font-weight: 700; 
            text-transform: uppercase;
            padding: 0.3rem 0.65rem; 
            border-radius: 100px;
        }
        .unavail-item .ui-badge.room { background: var(--blue-light); color: var(--blue); }
        .unavail-item .ui-badge.cottage { background: var(--green-light); color: var(--green); }

        .modal-foot {
            display: flex;
            gap: 0.8rem;
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--white-pearl);
            background: var(--white-warm);
            border-radius: 0 0 24px 24px;
        }

        /* ═══════════════════════════════
           RESPONSIVE DESIGN
        ═══════════════════════════════ */
        @media (max-width: 968px) {
            .item-row { 
                flex-direction: column;
            }
            
            .item-img-wrapper {
                width: 100%;
                height: 240px;
            }

            .item-img {
                height: 240px;
            }
        }

        @media (max-width: 768px) {
            .actions-grid { 
                grid-template-columns: 1fr;
            }
            
            .summary-top { 
                flex-direction: column; 
                align-items: flex-start; 
            }
            
            .page-title {
                font-size: 2.8rem;
            }

            .breakdown-content,
            .actions-section {
                padding: 2rem 1.5rem;
            }

            .item-content {
                padding: 1.5rem 4rem 1.5rem 1.5rem;
            }

            .date-pills {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 2rem 1rem 4rem;
            }
            
            .page-title {
                font-size: 2.3rem;
            }

            .summary-card,
            .breakdown-card,
            .item-card {
                border-radius: 18px;
            }

            .breakdown-content,
            .actions-section {
                padding: 1.5rem 1.2rem;
            }

            .item-content {
                padding: 1.2rem 3.5rem 1.2rem 1.2rem;
            }

            .date-pill {
                flex: 1;
            }
            
            .btn-delete {
                top: 0.75rem;
                right: 0.75rem;
                width: 34px;
                height: 34px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    @include('customerFolder.partials.navbar')

    <main class="main-content">
        <div class="cart-container">

            <!-- Title -->
            <div class="page-title-wrap">
                <h1 class="page-title cursive-font">Your Cart</h1>
                <p class="page-subtitle">Review and finalize your booking selections</p>
                <div class="title-line"></div>
            </div>

            <!-- Cart content rendered by JS -->
            <div id="cart-container">
                <div class="loading-wrap">
                    <div class="spinner"></div>
                    <p>Loading your cart...</p>
                </div>
            </div>
        </div>
    </main>

    @include('customerFolder.partials.footer')

    <!-- Notifications -->
    <div id="notification-container" class="notification-container"></div>

    <!-- Availability modal -->
    <div id="availability-modal-container"></div>

<script>
/* JavaScript remains the same - no changes needed */
/* ═══════════════════════════════
   GLOBALS
═══════════════════════════════ */
let entranceFeeAmount = 0;
let hasActiveEntranceFee = false;
let cartUnitType = null;
let cartItemCount = 0;

document.addEventListener('DOMContentLoaded', loadCartItems);

/* ═══════════════════════════════
   LOAD CART
═══════════════════════════════ */
function loadCartItems() {
    const container = document.getElementById('cart-container');
    container.innerHTML = '<div class="loading-wrap"><div class="spinner"></div><p>Loading your cart...</p></div>';

    fetch('/api/cart/items', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
    .then(data => {
        if (data.success && data.cart && data.items && data.items.length > 0) {
            cartItemCount  = data.items.length;
            cartUnitType   = data.cart_type;
            entranceFeeAmount     = parseFloat(data.entrance_fee) || 0;
            hasActiveEntranceFee  = data.has_active_entrance_fee || false;
            
            console.log('Cart loaded:', {
                entranceFee: entranceFeeAmount,
                hasActive: hasActiveEntranceFee,
                cartType: cartUnitType
            });
            
            renderCart(data.cart, data.items, container);
        } else {
            showEmpty(container);
        }
    })
    .catch(err => showError(container, err));
}

/* ═══════════════════════════════
   EMPTY / ERROR
═══════════════════════════════ */
function showEmpty(container) {
    container.innerHTML = `
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
            <h3>Your cart is empty</h3>
            <p>Start exploring our beautiful accommodations!</p>
            <div class="empty-btns">
                <a href="{{ route('roomBooking') }}" class="btn btn-rooms"><i class="fas fa-bed"></i> Browse Rooms</a>
                <a href="{{ route('cottageBooking') }}" class="btn btn-cottages"><i class="fas fa-home"></i> Browse Cottages</a>
            </div>
        </div>`;
}

function showError(container, err) {
    container.innerHTML = `
        <div class="empty-state">
            <div class="empty-icon" style="color:var(--red)"><i class="fas fa-exclamation-triangle"></i></div>
            <h3>Error loading cart</h3>
            <p>${err.message || 'Please login first or add items to your cart.'}</p>
            <button onclick="loadCartItems()" class="btn btn-cottages"><i class="fas fa-redo"></i> Retry</button>
        </div>`;
}

/* ═══════════════════════════════
   RENDER CART - FIXED ENTRANCE FEE CALCULATION
═══════════════════════════════ */
function renderCart(cart, items, container) {
    const days     = cart.daysCount > 0 ? cart.daysCount : 1;
    const guests   = parseInt(cart.numGuests);
    const checkIn  = cart.checkInDate;
    const checkOut = cart.checkOutDate;

    let totalRoom = 0;
    let totalCottage = 0;
    let totalEntranceFee = 0;
    
    const hasCottageInCart = cartUnitType === 'cottage' || cartUnitType === 'mixed';
    const canProceed = !(hasCottageInCart && !hasActiveEntranceFee) && cartUnitType !== 'mixed';

    // ─── Items HTML ───
    const itemsHTML = items.map((item, i) => {
        const unit = item.unit;
        const rate = parseFloat(unit.unitRatePrice);
        const type = unit.unitType;
        let total  = 0;
        let calc   = '';
        let priceColor = 'blue';

        if (type === 'room') {
            // Room calculation: rate × guests × days (minimum 2 guests)
            const guestCount = guests === 1 ? 2 : guests;
            total = rate * guestCount * days;
            totalRoom += total;
            calc = `<strong>Calculation:</strong> ₱${rate.toFixed(2)} × ${guestCount} guest${guestCount > 1 ? 's' : ''} × ${days} day${days > 1 ? 's' : ''} = ₱${total.toFixed(2)}`;
            priceColor = 'blue';
        } else if (type === 'cottage') {
            if (hasActiveEntranceFee) {
                // Cottage WITH entrance fee: (entrance fee × guests) + cottage rate
                const entranceFeeForItem = entranceFeeAmount * guests;
                total = entranceFeeForItem + rate;
                totalEntranceFee += entranceFeeForItem;
                totalCottage += rate; // Only cottage base rate goes to cottage subtotal
                calc = `<strong>Calculation:</strong> (₱${entranceFeeAmount.toFixed(2)} entrance fee × ${guests} guest${guests > 1 ? 's' : ''}) + ₱${rate.toFixed(2)} cottage rate = ₱${total.toFixed(2)}`;
                priceColor = 'green';
            } else {
                // Cottage WITHOUT entrance fee (unavailable)
                total = rate;
                totalCottage += rate;
                calc = `<strong>Cannot proceed:</strong> Active entrance fee required for cottage bookings.`;
                priceColor = 'red';
            }
        }

        const isUnavailable = (type === 'cottage' && !hasActiveEntranceFee);

        return `
        <div class="item-card ${type} ${isUnavailable ? 'unavailable' : ''}" style="animation-delay:${i*0.08}s">
            <!-- Delete button in top-right corner -->
            <button onclick="removeFromCart(${item.cartItemID})" class="btn-delete" title="Remove item">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="item-card-inner">
                <div class="item-row">
                    <div class="item-img-wrapper">
                        <img class="item-img ${isUnavailable ? 'grayscale' : ''}" src="${getUnitImage(unit)}" alt="${unit.unitName}">
                    </div>
                    
                    <div class="item-content">
                        <div class="item-header">
                            <div class="item-title-section">
                                <div class="item-name">${unit.unitName}</div>
                                <span class="item-badge ${type}">
                                    <i class="fas ${type==='room'?'fa-bed':'fa-home'}"></i> 
                                    ${type.charAt(0).toUpperCase()+type.slice(1)}
                                </span>
                            </div>
                            <div class="item-price-section">
                                <div class="item-price ${priceColor}">₱${total.toFixed(2)}</div>
                            </div>
                        </div>
                        
                        <div class="item-tags">
                            <span class="item-tag ${isUnavailable?'red':'blue'}">
                                <i class="fas fa-users"></i> ${guests} guest${guests>1?'s':''}
                            </span>
                            <span class="item-tag ${isUnavailable?'red':'blue'}">
                                <i class="fas ${type==='room'?'fa-calendar-alt':'fa-calendar-day'}"></i> 
                                ${type==='room' ? days+' day(s)' : 'Day use'}
                            </span>
                        </div>
                        
                        ${calc ? `
                        <div class="calc-note ${isUnavailable?'red':''}">
                            <i class="fas ${isUnavailable?'fa-exclamation-triangle':'fa-calculator'}"></i>
                            <p>${calc}</p>
                        </div>` : ''}
                        
                        ${type === 'room' && guests === 1 ? `
                        <div class="calc-note">
                            <i class="fas fa-info-circle"></i>
                            <p><strong>Note:</strong> Single occupancy — minimum charge applies for 2 guests.</p>
                        </div>` : ''}
                        
                        ${isUnavailable ? `
                        <div class="calc-note red">
                            <i class="fas fa-exclamation-circle"></i>
                            <p><strong>Cannot proceed:</strong> No active entrance fee. Contact management or remove this item.</p>
                        </div>` : ''}
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');

    // ─── Warning banners ───
    let warnings = '';
    if (cartUnitType === 'mixed') {
        const roomCount    = items.filter(i => i.unit?.unitType === 'room').length;
        const cottageCount = items.filter(i => i.unit?.unitType === 'cottage').length;
        warnings = `
        <div class="warning-banner yellow">
            <div class="w-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <h4>Mixed Cart Detected</h4>
                <p>Rooms and cottages cannot be booked together in a single reservation. Please remove one type to continue.</p>
                <div class="w-actions">
                    <button onclick="removeAllRooms()" class="btn btn-sm btn-sm-dark">
                        <i class="fas fa-bed"></i> Remove Rooms (${roomCount})
                    </button>
                    <button onclick="removeAllCottages()" class="btn btn-sm btn-sm-sun">
                        <i class="fas fa-home"></i> Remove Cottages (${cottageCount})
                    </button>
                </div>
            </div>
        </div>`;
    } else if (hasCottageInCart && !hasActiveEntranceFee) {
        warnings = `
        <div class="warning-banner red">
            <div class="w-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <h4>Entrance Fee Required</h4>
                <p>Cottage bookings require an active entrance fee. Please contact villa management or remove cottage items to proceed.</p>
                <div class="w-actions">
                    <button onclick="loadCartItems()" class="btn btn-sm btn-sm-ghost">
                        <i class="fas fa-sync-alt"></i> Refresh Cart
                    </button>
                    <button onclick="removeAllCottages()" class="btn btn-sm btn-sm-red">
                        <i class="fas fa-trash-alt"></i> Remove Cottages
                    </button>
                </div>
            </div>
        </div>`;
    }

    // ─── Price breakdown rows WITH ENTRANCE FEE ───
    let breakdownRows = '';
    if (totalRoom > 0) {
        breakdownRows += `
        <div class="breakdown-row">
            <span><i class="fas fa-bed row-icon"></i> Rooms Subtotal</span>
            <span>₱${totalRoom.toFixed(2)}</span>
        </div>`;
    }
    if (totalCottage > 0) {
        breakdownRows += `
        <div class="breakdown-row">
            <span><i class="fas fa-home row-icon"></i> Cottages Base Price</span>
            <span>₱${totalCottage.toFixed(2)}</span>
        </div>`;
    }
    if (totalEntranceFee > 0) {
        breakdownRows += `
        <div class="breakdown-row">
            <span><i class="fas fa-ticket-alt row-icon"></i> Entrance Fees (${guests} guest${guests>1?'s':''})</span>
            <span>₱${totalEntranceFee.toFixed(2)}</span>
        </div>`;
    }
    
    const grandTotal = totalRoom + totalCottage + totalEntranceFee;

    // ─── Checkout button ───
    let checkoutBtn = '';
    if (canProceed) {
        checkoutBtn = `
        <button onclick="proceedToCheckout()" class="btn btn-checkout checkout-button">
            <i class="fas fa-lock"></i> Proceed to Secure Checkout
        </button>`;
    } else if (cartUnitType === 'mixed') {
        checkoutBtn = `
        <button class="btn btn-warning btn-disabled">
            <i class="fas fa-exclamation-circle"></i> Fix Cart to Continue
        </button>`;
    } else {
        checkoutBtn = `
        <button class="btn btn-error btn-disabled">
            <i class="fas fa-ban"></i> Entrance Fee Required
        </button>`;
    }

    // ─── Assemble ───
    container.innerHTML = `
        ${warnings}

        <!-- Summary header -->
        <div class="summary-card">
            <div class="summary-top">
                <div class="summary-left">
                    <h2 class="cursive-font">Booking Summary</h2>
                    ${cartUnitType && cartUnitType !== 'mixed' ? `
                    <span class="type-pill ${cartUnitType}">
                        <i class="fas ${cartUnitType==='room'?'fa-bed':'fa-home'}"></i> 
                        ${cartUnitType==='room'?'Room':'Cottage'} Booking
                    </span>` : ''}
                </div>
                <div class="date-pills">
                    <div class="date-pill">
                        <div class="label">Check-in</div>
                        <div class="value">${checkIn}</div>
                    </div>
                    <div class="date-pill">
                        <div class="label">Check-out</div>
                        <div class="value">${checkOut}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        ${itemsHTML}

        <!-- Breakdown Card with Actions -->
        <div class="breakdown-card">
            <div class="breakdown-content">
                <div class="breakdown-title">
                    <i class="fas fa-receipt"></i> Price Summary
                </div>
                ${breakdownRows}
                <div class="breakdown-row total">
                    <span><i class="fas fa-wallet row-icon"></i> Total Amount</span>
                    <span>₱${grandTotal.toFixed(2)}</span>
                </div>
            </div>
            
            <div class="actions-section">
                <div class="actions-section-title">
                    <i class="fas fa-plus-circle"></i> Add More Items
                </div>
                <div class="actions-grid">
                    <a href="{{ route('roomBooking') }}" class="btn btn-rooms">
                        <i class="fas fa-bed"></i> Add Rooms
                    </a>
                    <a href="{{ route('cottageBooking') }}" class="btn btn-cottages">
                        <i class="fas fa-home"></i> Add Cottages
                    </a>
                    ${checkoutBtn}
                </div>
            </div>
        </div>`;
}

/* ═══════════════════════════════
   CHECKOUT
═══════════════════════════════ */
function proceedToCheckout() {
    const btn = document.querySelector('.checkout-button');
    if (!btn) return;
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner" style="animation:spin 0.7s linear infinite"></i> Validating...';
    btn.disabled = true;
    clearNotifications();

    fetch('/api/cart/items', {
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': csrfToken() }
    })
    .then(r => { if(!r.ok) throw new Error('Network error'); return r.json(); })
    .then(d => {
        if (!d.success || !d.cart) throw new Error('Cart not found');
        return fetch('/api/cart/validate-before-checkout', {
            method:'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken(), 'Accept':'application/json' },
            body: JSON.stringify({})
        });
    })
    .then(r => { if(!r.ok) throw new Error('Server error'); return r.json(); })
    .then(v => {
        if (v.success) {
            showNotification('All items available! Redirecting to checkout…', 'success', 2500);
            setTimeout(() => { window.location.href = "{{ route('booking.page') }}"; }, 1500);
        } else {
            btn.innerHTML = orig;
            btn.disabled  = false;

            if (v.has_availability_issues && v.unavailable_items) {
                showNotification(v.validation_errors?.[0] || 'Some items are unavailable.', 'error');
                setTimeout(() => showUnavailableModal(v.unavailable_items, v.validation_errors), 900);
            } else {
                showNotification(v.message || 'Unable to proceed. Please try again.', 'error');
            }
        }
    })
    .catch(err => {
        btn.innerHTML = orig;
        btn.disabled  = false;
        showNotification('Validation failed. Check your connection.', 'error');
    });
}

/* ═══════════════════════════════
   UNAVAILABLE MODAL
═══════════════════════════════ */
function showUnavailableModal(items, errors) {
    const itemsHTML = items.map(item => `
        <div class="unavail-item">
            <div>
                <div class="ui-name">
                    ${item.unit?.unitName || 'Unknown'} 
                    <span class="ui-badge ${item.unit?.unitType || ''}">${item.unit?.unitType || ''}</span>
                </div>
                <div class="ui-reason">
                    <i class="fas fa-exclamation-circle"></i> ${item.reason || 'Not available'}
                </div>
            </div>
            ${item.cartItemID ? `
            <button onclick="removeUnavailItem(${item.cartItemID})" class="btn btn-sm btn-sm-red">
                <i class="fas fa-trash-alt"></i> Remove
            </button>` : ''}
        </div>`).join('');

    const ids = JSON.stringify(items.filter(i=>i.cartItemID).map(i=>i.cartItemID));

    document.getElementById('availability-modal-container').innerHTML = `
        <div class="modal-overlay show" id="avail-modal">
            <div class="modal">
                <div class="modal-head">
                    <h2><i class="fas fa-exclamation-circle"></i> Booking Issues (${items.length})</h2>
                    <button class="modal-close" onclick="closeAvailModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Some accommodations are no longer available for your selected dates. Please remove them to continue with your booking.</p>
                    </div>
                    ${itemsHTML}
                </div>
                <div class="modal-foot">
                    <button onclick="closeAvailModal()" class="btn btn-sm btn-sm-ghost" style="flex:1">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </button>
                    <button onclick="removeAllUnavail(${ids})" class="btn btn-sm btn-sm-red" style="flex:1">
                        <i class="fas fa-trash-alt"></i> Remove All
                    </button>
                </div>
            </div>
        </div>`;
}

function closeAvailModal() {
    document.getElementById('availability-modal-container').innerHTML = '';
}

/* ═══════════════════════════════
   REMOVE ITEMS
═══════════════════════════════ */
function removeFromCart(id) {
    if (!confirm('Remove this item from your cart?')) return;
    
    showNotification('Removing item...', 'info', 1500);
    
    fetch(`/api/cart/remove/${id}`, {
        method:'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept':'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) { 
            showNotification('Item removed successfully!', 'success'); 
            setTimeout(() => { loadCartItems(); updateBadge(); }, 500); 
        } else {
            showNotification('Failed: ' + d.message, 'error');
        }
    })
    .catch(() => showNotification('Error removing item.', 'error'));
}

function removeUnavailItem(id) {
    closeAvailModal();
    fetch(`/api/cart/remove/${id}`, {
        method:'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept':'application/json' }
    })
    .then(() => { 
        showNotification('Item removed.', 'success'); 
        setTimeout(loadCartItems, 600); 
    })
    .catch(() => showNotification('Error.', 'error'));
}

function removeAllUnavail(ids) {
    if (!confirm('Remove all unavailable items?')) return;
    closeAvailModal();
    Promise.all(ids.map(id => fetch(`/api/cart/remove/${id}`, {
        method:'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept':'application/json' }
    })))
    .then(() => { 
        showNotification('All unavailable items removed.', 'success'); 
        setTimeout(() => { loadCartItems(); updateBadge(); }, 500); 
    })
    .catch(() => showNotification('Some items could not be removed.', 'error'));
}

function removeAllRooms() {
    if (!confirm('Remove all room items from your cart?')) return;
    fetch('/api/cart/items', { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': csrfToken() } })
    .then(r => r.json())
    .then(d => {
        const rooms = (d.items||[]).filter(i => i.unit?.unitType === 'room');
        if (!rooms.length) return showNotification('No room items found.', 'info');
        return Promise.all(rooms.map(i => fetch(`/api/cart/remove/${i.cartItemID}`, {
            method:'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept':'application/json' }
        }))).then(() => { 
            showNotification('All rooms removed.', 'success'); 
            setTimeout(() => { loadCartItems(); updateBadge(); }, 500); 
        });
    })
    .catch(() => showNotification('Error.', 'error'));
}

function removeAllCottages() {
    if (!confirm('Remove all cottage items from your cart?')) return;
    fetch('/api/cart/items', { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': csrfToken() } })
    .then(r => r.json())
    .then(d => {
        const cottages = (d.items||[]).filter(i => i.unit?.unitType === 'cottage');
        if (!cottages.length) return showNotification('No cottage items found.', 'info');
        return Promise.all(cottages.map(i => fetch(`/api/cart/remove/${i.cartItemID}`, {
            method:'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept':'application/json' }
        }))).then(() => { 
            showNotification('All cottages removed.', 'success'); 
            setTimeout(() => { loadCartItems(); updateBadge(); }, 500); 
        });
    })
    .catch(() => showNotification('Error.', 'error'));
}

/* ═══════════════════════════════
   NOTIFICATIONS - ORIGINAL FUNCTIONS
═══════════════════════════════ */
let activeNotifications = new Set();

function showNotification(msg, type='info', duration=3500) {
    const id = 'notification-' + Date.now() + '-' + Math.random().toString(36).substr(2,6);
    const icons = { 
        success:'fa-check-circle', 
        error:'fa-exclamation-circle', 
        warning:'fa-exclamation-triangle', 
        info:'fa-info-circle' 
    };
    const titles = { 
        success:'Success', 
        error:'Error', 
        warning:'Warning', 
        info:'Information' 
    };

    const el = document.createElement('div');
    el.id = id;
    el.className = `notification notification-slide-in ${type}`;
    el.innerHTML = `
        <div class="notification-icon">
            <i class="fas ${icons[type]}"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">${titles[type]}</div>
            <div class="notification-message">${msg}</div>
        </div>
        <button class="notification-close" onclick="removeNotification('${id}')">
            <i class="fas fa-times"></i>
        </button>`;

    document.getElementById('notification-container').appendChild(el);
    activeNotifications.add(id);
    
    requestAnimationFrame(() => {
        el.classList.add('show');
    });
    
    setTimeout(() => removeNotification(id), duration);
    return id;
}

function removeNotification(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('show');
    el.classList.remove('notification-slide-in');
    el.classList.add('notification-slide-out');
    setTimeout(() => el && el.remove(), 500);
    activeNotifications.delete(id);
}

function clearNotifications() {
    document.querySelectorAll('.notification').forEach(n => { 
        n.classList.remove('show');
        n.classList.remove('notification-slide-in'); 
        n.classList.add('notification-slide-out'); 
        setTimeout(() => n.remove(), 500); 
    });
    activeNotifications.clear();
}

// Alias for compatibility
function clearAllNotifications() {
    clearNotifications();
}

/* ═══════════════════════════════
   HELPERS
═══════════════════════════════ */
function csrfToken() { 
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); 
}

function getUnitImage(unit) {
    try {
        if (unit.images) {
            const imgs = typeof unit.images === 'string' ? JSON.parse(unit.images) : unit.images;
            if (Array.isArray(imgs) && imgs[0]) {
                return imgs[0].startsWith('http') ? imgs[0] : `/storage/${imgs[0]}`;
            }
        }
    } catch(e) {}
    return 'https://via.placeholder.com/400x300?text=No+Image';
}

function updateBadge() {
    fetch('/api/cart/count', { headers: { 'Accept':'application/json' } })
    .then(r => r.json())
    .then(d => {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.style.display = (d.success && d.count > 0) ? 'flex' : 'none';
            if (d.count > 0) badge.textContent = d.count;
        }
    }).catch(()=>{});
}
</script>
</body>
</html>