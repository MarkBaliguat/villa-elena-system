<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        /* ═══════════════════════════════
           PROFESSIONAL COLOR PALETTE - BRIGHT & VIBRANT
        ═══════════════════════════════ */
        :root {
            --white-pure:     #FFFFFF;
            --white-soft:     #FEFEFE;
            --white-warm:     #FDFCFB;
            --white-pearl:    #F9F9F9;
            --white-ivory:    #F7F7F7;
            --cream:        #FFF9F0;
            --cream-dark:   #FFF3E0;
            --sunflower:    #FFB84D;
            --sun-light:    #FFC870;
            --sun-dark:     #FF9F1C;
            --text:         #2C3E50;
            --text-soft:    #5D6D7E;
            --text-faint:   #95A5A6;
            --border:       #E8E8E8;
            --border-light: #F0F0F0;
            --red:          #FF6B6B;
            --red-light:    #FFE5E5;
            --red-dark:     #EE5A52;
            --green:        #51CF66;
            --green-light:  #E7F5E9;
            --green-dark:   #40C057;
            --blue:         #4DABF7;
            --blue-light:   #E7F5FF;
            --blue-dark:    #339AF0;
            --dark:         #2C3E50;
            --dark-hover:   #34495E;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
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
        @keyframes zoomIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ═══════════════════════════════
           LAYOUT
        ═══════════════════════════════ */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 3rem 1.5rem 5rem;
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
        }

        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        .left-sidebar {
            display: flex;
            flex-direction: column;
        }

        .main-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ═══════════════════════════════
           PAGE TITLE
        ═══════════════════════════════ */
        .page-title-wrap {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeUp 0.6s ease both;
        }

        .page-title {
            font-size: 2.8rem;
            font-weight: 800;
            background: #1F2937;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 1rem;
            color: var(--text-soft);
            font-weight: 400;
            letter-spacing: 0.2px;
            margin-bottom: 0.8rem;
        }

        .title-line {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--sunflower), var(--sun-dark));
            border-radius: 3px;
            margin: 0 auto;
        }

        /* ═══════════════════════════════
           LEFT SIDEBAR CARD
        ═══════════════════════════════ */
        .sidebar-card {
            background: var(--white-pure);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .summary-header {
            padding: 1.5rem;
            text-align: center;
        }

        .summary-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1F2937;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .summary-header i {
            font-size: 1rem;
        }

        .summary-content {
            padding: 1.5rem;
            padding-top: 0;
            background: var(--white-pure);
        }

        .summary-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 1rem;
            border-radius: 12px;
            background: var(--white-warm);
            margin-bottom: 0.8rem;
            transition: all 0.3s ease;
        }

        .summary-item:hover {
            background: var(--cream);
            transform: translateX(4px);
        }

        .summary-item i {
            color: var(--sunflower);
            font-size: 1.1rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .summary-item-content {
            flex: 1;
        }

        .summary-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.3rem;
        }

        .summary-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text);
        }

        /* ✅ NEW: Guest varies badge */
        .guest-varies-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.25rem 0.6rem;
            border-radius: 100px;
            background: var(--blue-light);
            color: var(--blue-dark);
            margin-top: 0.3rem;
        }

        .price-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, var(--sunflower), transparent);
            margin: 0.5rem 1.5rem;
        }

        .price-section-title {
            padding: 1.2rem 1.5rem 0.8rem;
            text-align: center;
        }

        .price-section-title h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .price-section-title i {
            color: var(--green);
            font-size: 0.95rem;
        }

        .price-content {
            padding: 0 1.5rem 1.5rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem;
            font-size: 0.9rem;
            color: var(--text-soft);
            font-weight: 500;
            background: var(--white-warm);
            border-radius: 10px;
            margin-bottom: 0.6rem;
        }

        .price-row span:first-child {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .price-row .row-icon {
            color: var(--sunflower);
            font-size: 0.9rem;
        }

        .price-row span:last-child {
            font-weight: 700;
            color: var(--text);
            font-size: 1rem;
        }

        .price-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
            border-radius: 12px;
            margin-top: 0.5rem;
        }

        .price-total span:first-child {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .price-total span:last-child {
            color: var(--green-dark);
            font-size: 1.9rem;
            font-weight: 800;
        }

        /* ═══════════════════════════════
           LOADING STATE
        ═══════════════════════════════ */
        .loading-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 6rem 1rem;
            background: var(--white-pure);
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
           WARNING BANNERS
        ═══════════════════════════════ */
        .warning-banner {
            border-radius: 16px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
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
            background: linear-gradient(135deg, #FFF9E6 0%, #FFF3CC 100%);
            border: 2px solid #FFE082;
        }
        .warning-banner.yellow .w-icon { background: var(--white-pure); color: var(--sunflower); }
        .warning-banner.yellow h4 { color: #F57C00; }
        .warning-banner.yellow p { color: #E65100; }

        .warning-banner.red {
            background: linear-gradient(135deg, #FFF0F0 0%, #FFE0E0 100%);
            border: 2px solid #FFB3B3;
        }
        .warning-banner.red .w-icon { background: var(--white-pure); color: var(--red); }
        .warning-banner.red h4 { color: #D32F2F; }
        .warning-banner.red p { color: #C62828; }

        /* ═══════════════════════════════
           CART ITEM CARD
        ═══════════════════════════════ */
        .items-section {
            background: var(--white-pure);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .items-header {
            background: #FFFFFF;
            padding: 1.2rem 1.8rem;
            border-bottom: 2px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .items-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-header i {
            color: var(--sunflower);
            font-size: 0.95rem;
        }

        .items-count {
            background: linear-gradient(135deg, var(--sunflower) 0%, var(--sun-dark) 100%);
            color: var(--white-pure);
            padding: 0.3rem 0.8rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(255, 184, 77, 0.3);
        }

        .items-container {
            padding: 0;
        }

        .item-card {
            background: var(--white-pure);
            border-bottom: 1px solid var(--border-light);
            padding: 0;
            margin: 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .item-card:last-child {
            border-bottom: none;
        }

        .item-card:hover {
            box-shadow: inset 0 0 0 2px var(--border);
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
           IMAGE CONTAINER
        ═══════════════════════════════ */
        .item-img-wrapper {
            position: relative;
            width: 200px;
            flex-shrink: 0;
            overflow: hidden;
            background: var(--white-warm);
            cursor: pointer;
        }

        .item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
            position: absolute;
            inset: 0;
            object-fit: cover;
        }

        .item-card:hover .item-img {
            transform: scale(1.05);
        }

        .item-img.grayscale { 
            filter: grayscale(1) opacity(0.5); 
        }

        .img-count-badge {
            position: absolute;
            bottom: 0.8rem;
            left: 0.8rem;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            z-index: 5;
        }

        .img-view-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 6;
        }

        .item-img-wrapper:hover .img-view-overlay {
            background: rgba(0, 0, 0, 0.6);
            opacity: 1;
        }

        .img-view-overlay i {
            color: white;
            font-size: 2rem;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .item-img-wrapper:hover .img-view-overlay i {
            transform: scale(1);
        }

        /* ═══════════════════════════════
           IMAGE GALLERY MODAL
        ═══════════════════════════════ */
        .gallery-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            backdrop-filter: blur(10px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .gallery-modal-overlay.show { 
            display: flex; 
        }

        .gallery-modal {
            max-width: 1200px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            animation: zoomIn 0.4s ease;
        }

        .gallery-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .gallery-title {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .gallery-close {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .gallery-close:hover {
            background: rgba(255, 107, 107, 0.9);
            border-color: rgba(255, 107, 107, 1);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.5);
        }

        .gallery-main {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 16px;
            overflow: hidden;
        }

        .gallery-main-img {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease;
        }

        .gallery-main-img.fade-out {
            opacity: 0;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.5rem;
            color: var(--text);
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        .gallery-nav:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .gallery-nav.prev { left: 1.5rem; }
        .gallery-nav.next { right: 1.5rem; }

        .gallery-counter {
            position: absolute;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 100px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .gallery-thumbnails {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding: 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
            justify-content: center;
        }

        .gallery-thumbnails::-webkit-scrollbar { height: 6px; }
        .gallery-thumbnails::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 3px; }
        .gallery-thumbnails::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 3px; }

        .gallery-thumb {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 3px solid transparent;
            flex-shrink: 0;
        }

        .gallery-thumb:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .gallery-thumb.active {
            border-color: var(--sunflower);
            box-shadow: 0 0 0 2px rgba(255, 184, 77, 0.5);
        }

        /* ═══════════════════════════════
           DELETE BUTTON
        ═══════════════════════════════ */
        .btn-delete {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--red);
            border: 2px solid var(--white-pure);
            color: var(--white-pure);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(255, 107, 107, 0.4);
            font-size: 0.9rem;
            z-index: 10;
        }

        .btn-delete:hover {
            background: var(--red-dark);
            transform: scale(1.15) rotate(90deg);
            box-shadow: 0 6px 24px rgba(255, 107, 107, 0.6);
        }

        /* ═══════════════════════════════
           ITEM CONTENT
        ═══════════════════════════════ */
        .item-content { 
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            padding: 1.5rem 4rem 1.5rem 1.5rem;
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
            font-size: 1.2rem;
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
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 0.3rem 0.75rem;
            border-radius: 100px;
        }
        .item-badge.room    { background: var(--blue-light);  color: var(--blue-dark); }
        .item-badge.cottage { background: var(--green-light); color: var(--green-dark); }

        .item-price-section {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.3rem;
        }

        .item-price {
            font-size: 1.6rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: -0.5px;
        }
        .item-price.blue  { color: var(--blue-dark); }
        .item-price.green { color: var(--green-dark); }
        .item-price.red   { color: var(--red); }

        .item-tags {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .item-tag {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.4rem 0.85rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .item-tag.blue  { background: var(--blue-light);  color: var(--blue-dark); }
        .item-tag.green { background: var(--green-light); color: var(--green-dark); }
        .item-tag.red   { background: var(--red-light);   color: var(--red); }
        .item-tag i { font-size: 0.72rem; }

        .calc-note {
            background: var(--white-warm);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }
        .calc-note i { 
            color: var(--sunflower); 
            font-size: 0.85rem; 
            margin-top: 2px; 
            flex-shrink: 0; 
        }
        .calc-note p { 
            font-size: 0.78rem; 
            color: var(--text-soft); 
            line-height: 1.6; 
            font-weight: 500; 
        }
        .calc-note p strong { color: var(--text); font-weight: 700; }

        .calc-note.red { 
            border-color: var(--red-light); 
            background: linear-gradient(135deg, #FFF0F0 0%, #FFE5E5 100%); 
        }
        .calc-note.red i { color: var(--red); }
        .calc-note.red p { color: #D32F2F; }

        /* ═══════════════════════════════
           ACTION BUTTONS SECTION
        ═══════════════════════════════ */
        .actions-card {
            background: var(--white-pure);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            animation: fadeUp 0.5s ease .2s both;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .actions-header {
            background: #FFFFFF;
            padding: 1.2rem 1.8rem;
            border-bottom: 2px solid var(--border);
        }

        .actions-header h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .actions-header i {
            color: var(--sunflower);
            font-size: 0.85rem;
        }

        .actions-content {
            padding: 1.8rem;
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
            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
            letter-spacing: 0.3px;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(0,0,0,0.2);
        }
        .btn:active { transform: translateY(-1px); }
        .btn i { font-size: 0.85rem; }

        .btn-rooms {
            background: #1F2937;
            color: var(--white-pure);
        }
        .btn-rooms:hover { 
            background: linear-gradient(135deg, #1A2533 0%, var(--dark) 100%);
        }

        .btn-cottages {
            background: linear-gradient(135deg, var(--sunflower) 0%, var(--sun-dark) 100%);
            color: var(--white-pure);
        }
        .btn-cottages:hover { 
            background: linear-gradient(135deg, var(--sun-dark) 0%, #FF8C00 100%);
        }

        .btn-checkout {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
            color: var(--white-pure);
            font-size: 1.05rem;
            padding: 1.3rem 1.8rem;
            box-shadow: 0 4px 16px rgba(81, 207, 102, 0.4);
        }
        .btn-checkout:hover { 
            background: linear-gradient(135deg, var(--green-dark) 0%, #2B8A3E 100%);
            box-shadow: 0 6px 28px rgba(81, 207, 102, 0.5);
        }

        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(0.3);
        }

        .btn-error {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
            color: var(--white-pure);
            font-size: 1.05rem;
            padding: 1.3rem 1.8rem;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.55rem 1rem;
            border-radius: 12px;
        }
        .btn-sm-dark  { background: linear-gradient(135deg, var(--dark) 0%, var(--dark-hover) 100%); color: var(--white-pure); }
        .btn-sm-sun   { background: linear-gradient(135deg, var(--sunflower) 0%, var(--sun-dark) 100%); color: var(--white-pure); }
        .btn-sm-red   { background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%); color: var(--white-pure); }
        .btn-sm-ghost { background: var(--white-pure); color: var(--text-soft); border: 2px solid var(--border); }
        .btn-sm-ghost:hover { border-color: var(--text-soft); color: var(--text); background: var(--white-warm); }

        /* ═══════════════════════════════
           EMPTY STATE
        ═══════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: var(--white-pure);
            border: 1px solid var(--border);
            border-radius: 24px;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            grid-column: 1 / -1;
        }
        .empty-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--cream) 0%, var(--cream-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
            color: var(--sunflower);
            border: 2px solid var(--border);
            box-shadow: 0 4px 16px rgba(255, 184, 77, 0.2);
        }
        .empty-state h3 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.6rem; color: var(--text); }
        .empty-state p  { font-size: 1rem; color: var(--text-soft); margin-bottom: 2rem; }
        .empty-btns     { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

        /* ═══════════════════════════════
           NOTIFICATIONS
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
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: flex-start;
            gap: 15px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .notification.show { transform: translateX(0); }
        .notification.success { background: linear-gradient(135deg, #51CF66, #40C057); color: white; }
        .notification.error   { background: linear-gradient(135deg, #FF6B6B, #EE5A52); color: white; }
        .notification.info    { background: linear-gradient(135deg, var(--sunflower), var(--sun-light)); color: var(--dark); }
        .notification.warning { background: linear-gradient(135deg, #FFB84D, #FF9F1C); color: white; }
        .notification-icon    { font-size: 1.5rem; flex-shrink: 0; }
        .notification-content { flex-grow: 1; }
        .notification-title   { font-weight: 600; margin-bottom: 5px; font-size: 1rem; }
        .notification-message { font-size: 0.95rem; opacity: 0.95; line-height: 1.5; }
        .notification-close {
            background: none; border: none; color: inherit; cursor: pointer;
            font-size: 1.2rem; opacity: 0.8; transition: opacity 0.3s; padding: 0;
            width: 24px; height: 24px; display: flex; align-items: center;
            justify-content: center; border-radius: 6px;
        }
        .notification-close:hover { opacity: 1; background: rgba(255, 255, 255, 0.1); }

        /* SweetAlert */
        .swal-custom-popup { border-radius: 20px !important; font-family: 'Poppins', sans-serif !important; box-shadow: 0 20px 60px rgba(0,0,0,0.2) !important; }
        .swal-confirm-btn  { border-radius: 12px !important; font-weight: 700 !important; padding: 0.7rem 1.5rem !important; }
        .swal-cancel-btn   { border-radius: 12px !important; font-weight: 700 !important; padding: 0.7rem 1.5rem !important; }

        /* ═══════════════════════════════
           AVAILABILITY MODAL
        ═══════════════════════════════ */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
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
            box-shadow: 0 24px 80px rgba(0,0,0,0.4);
            animation: fadeUp 0.4s ease;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--white-pearl);
            background: linear-gradient(135deg, #FFF0F0 0%, #FFE0E0 100%);
            border-radius: 24px 24px 0 0;
        }
        .modal-head h2 { font-size: 1.2rem; font-weight: 700; color: var(--red); display: flex; align-items: center; gap: 0.7rem; }
        .modal-close {
            background: var(--white-pure); border: 2px solid var(--red-light); color: var(--red);
            font-size: 1.2rem; cursor: pointer; width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: all 0.3s;
        }
        .modal-close:hover { background: var(--red); color: var(--white-pure); transform: rotate(90deg); }

        .modal-body { padding: 2rem; }

        .modal-info-box {
            background: var(--blue-light); border: 2px solid #a3d5ff; border-radius: 14px;
            padding: 1rem 1.2rem; display: flex; gap: 0.7rem; margin-bottom: 1.5rem;
        }
        .modal-info-box i { color: var(--blue-dark); font-size: 1rem; }
        .modal-info-box p { font-size: 0.85rem; color: #1565C0; font-weight: 500; line-height: 1.6; }

        .unavail-item {
            background: var(--white-warm); border: 1px solid var(--border); border-radius: 14px;
            padding: 1.1rem 1.3rem; margin-bottom: 0.8rem; display: flex;
            justify-content: space-between; align-items: flex-start; gap: 1rem;
        }
        .unavail-item .ui-name   { font-size: 0.95rem; font-weight: 700; color: var(--text); }
        .unavail-item .ui-reason { font-size: 0.78rem; color: var(--red); font-weight: 600; }

        .modal-foot {
            display: flex; gap: 0.8rem; padding: 1.5rem 2rem;
            border-top: 1px solid var(--white-pearl); background: var(--white-warm);
            border-radius: 0 0 24px 24px;
        }

        /* ═══════════════════════════════
           RESPONSIVE
        ═══════════════════════════════ */
        @media (max-width: 1200px) {
            .cart-grid { grid-template-columns: 1fr; }
            .left-sidebar { order: 2; }
            .main-column  { order: 1; }
        }

        @media (max-width: 768px) {
            .actions-grid { grid-template-columns: 1fr; }
            .page-title   { font-size: 2.2rem; }
            .item-row     { flex-direction: column; }
            .item-img-wrapper { width: 100%; height: 200px; }
            .item-img         { height: 200px; }
            .item-content     { padding: 1.2rem 3.5rem 1.2rem 1.2rem; }
            .gallery-nav      { width: 44px; height: 44px; font-size: 1.2rem; }
            .gallery-nav.prev { left: 0.8rem; }
            .gallery-nav.next { right: 0.8rem; }
        }

        @media (max-width: 480px) {
            .main-content { padding: 2rem 1rem 4rem; }
            .page-title   { font-size: 1.8rem; }
            .cart-grid    { gap: 1.5rem; }
        }
    </style>
</head>

<body>
    @include('customerFolder.partials.navbar')

    <main class="main-content">
        <div class="cart-container">

            <!-- Title -->
            <div class="page-title-wrap">
                <h1 class="page-title">Your Cart</h1>
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

    <div id="notification-container" class="notification-container"></div>
    <div id="availability-modal-container"></div>
    <div id="gallery-modal-container"></div>

<script>
/* ═══════════════════════════════
   GLOBALS
═══════════════════════════════ */
let entranceFeeAmount    = 0;
let hasActiveEntranceFee = false;
let cartUnitType         = null;
let cartItemCount        = 0;

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
            cartItemCount        = data.items.length;
            cartUnitType         = data.cart_type;
            entranceFeeAmount    = parseFloat(data.entrance_fee) || 0;
            hasActiveEntranceFee = data.has_active_entrance_fee || false;
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
            <p>Please verify your email first.</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-cottages"><i class="fas fa-user"></i> Profile</a>
        </div>`;
}

/* ═══════════════════════════════
   IMAGE GALLERY
═══════════════════════════════ */
let currentGallery = { images: [], currentIndex: 0, unitName: '' };

function openGallery(images, unitName, startIndex = 0) {
    currentGallery = { images, currentIndex: startIndex, unitName };
    renderGalleryModal();
}

function renderGalleryModal() {
    const { images, currentIndex, unitName } = currentGallery;
    
    const thumbnailsHTML = images.map((img, idx) => 
        `<img src="${img}" class="gallery-thumb ${idx === currentIndex ? 'active' : ''}" 
              onclick="changeGalleryImage(${idx})" alt="Thumbnail ${idx + 1}">`
    ).join('');
    
    const modalExists = document.getElementById('gallery-modal');
    
    if (modalExists) {
        const mainImg = document.querySelector('.gallery-main-img');
        const counter = document.querySelector('.gallery-counter');
        const thumbs  = document.querySelectorAll('.gallery-thumb');
        
        if (mainImg) {
            mainImg.classList.add('fade-out');
            setTimeout(() => { mainImg.src = images[currentIndex]; mainImg.classList.remove('fade-out'); }, 150);
        }
        if (counter && images.length > 1) counter.textContent = `${currentIndex + 1} / ${images.length}`;
        thumbs.forEach((t, i) => t.classList.toggle('active', i === currentIndex));
        return;
    }
    
    document.getElementById('gallery-modal-container').innerHTML = `
        <div class="gallery-modal-overlay show" id="gallery-modal" onclick="closeGalleryOnBackdrop(event)">
            <div class="gallery-modal">
                <div class="gallery-header">
                    <div class="gallery-title"><i class="fas fa-images"></i> ${unitName}</div>
                    <button class="gallery-close" onclick="closeGallery()"><i class="fas fa-times"></i></button>
                </div>
                <div class="gallery-main">
                    <img src="${images[currentIndex]}" class="gallery-main-img" alt="${unitName}">
                    ${images.length > 1 ? `
                        <button class="gallery-nav prev" onclick="prevGalleryImage()"><i class="fas fa-chevron-left"></i></button>
                        <button class="gallery-nav next" onclick="nextGalleryImage()"><i class="fas fa-chevron-right"></i></button>
                        <div class="gallery-counter">${currentIndex + 1} / ${images.length}</div>
                    ` : ''}
                </div>
                ${images.length > 1 ? `<div class="gallery-thumbnails">${thumbnailsHTML}</div>` : ''}
            </div>
        </div>`;
}

function changeGalleryImage(index) { currentGallery.currentIndex = index; renderGalleryModal(); }
function nextGalleryImage() { currentGallery.currentIndex = (currentGallery.currentIndex + 1) % currentGallery.images.length; renderGalleryModal(); }
function prevGalleryImage() { currentGallery.currentIndex = (currentGallery.currentIndex - 1 + currentGallery.images.length) % currentGallery.images.length; renderGalleryModal(); }
function closeGallery() { document.getElementById('gallery-modal-container').innerHTML = ''; }
function closeGalleryOnBackdrop(event) { if (event.target.id === 'gallery-modal') closeGallery(); }

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('gallery-modal')) return;
    if (e.key === 'Escape')      closeGallery();
    if (e.key === 'ArrowRight')  nextGalleryImage();
    if (e.key === 'ArrowLeft')   prevGalleryImage();
});

/* ═══════════════════════════════
   RENDER CART
═══════════════════════════════ */
function renderCart(cart, items, container) {
    const days     = cart.daysCount > 0 ? cart.daysCount : 1;
    const checkIn  = cart.checkInDate;
    const checkOut = cart.checkOutDate;

    // ✅ numGuests is now per item — no more cart-level guests
    const hasCottageInCart = cartUnitType === 'cottage' || cartUnitType === 'mixed';
    const canProceed = !(hasCottageInCart && !hasActiveEntranceFee);

    let totalRoom        = 0;
    let totalCottage     = 0;
    let totalEntranceFee = 0;

    // ─── Items HTML ───
    const itemsHTML = items.map((item) => {
        const unit = item.unit;
        const rate = parseFloat(unit.unitRatePrice);
        const type = unit.unitType;

        // ✅ READ numGuests from each item, NOT from cart
        const guests = parseInt(item.numGuests) || 1;

        let total      = 0;
        let calc       = '';
        let priceColor = 'blue';

        const images     = getUnitImages(unit);
        const imageCount = images.length;

        if (type === 'room') {
            const effectiveGuests = guests === 1 ? 2 : guests;
            total      = rate * effectiveGuests * days;
            totalRoom += total;
            calc       = `<strong>Calculation:</strong> ₱${rate.toFixed(2)} × ${effectiveGuests} guest${effectiveGuests > 1 ? 's' : ''} × ${days} day${days > 1 ? 's' : ''} = ₱${total.toFixed(2)}`;
            priceColor = 'blue';
        } else if (type === 'cottage') {
            if (hasActiveEntranceFee) {
                const entranceForItem = entranceFeeAmount * guests;
                total             = entranceForItem + rate;
                totalEntranceFee += entranceForItem;
                totalCottage     += rate;
                calc              = `<strong>Calculation:</strong> (₱${entranceFeeAmount.toFixed(2)} entrance fee × ${guests} guest${guests > 1 ? 's' : ''}) + ₱${rate.toFixed(2)} cottage rate = ₱${total.toFixed(2)}`;
                priceColor        = 'green';
            } else {
                total         = rate;
                totalCottage += rate;
                calc          = `<strong>Cannot proceed:</strong> Active entrance fee required for cottage bookings.`;
                priceColor    = 'red';
            }
        }

        const isUnavailable = (type === 'cottage' && !hasActiveEntranceFee);

        return `
        <div class="item-card ${type} ${isUnavailable ? 'unavailable' : ''}">
            <button onclick="removeFromCart(${item.cartItemID})" class="btn-delete" title="Remove item">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="item-card-inner">
                <div class="item-row">
                    <div class="item-img-wrapper" onclick='openGallery(${JSON.stringify(images)}, "${unit.unitName.replace(/'/g, "\\'")}", 0)'>
                        <img class="item-img ${isUnavailable ? 'grayscale' : ''}" src="${images[0]}" alt="${unit.unitName}">
                        ${imageCount > 1 ? `
                        <div class="img-count-badge">
                            <i class="fas fa-images"></i> ${imageCount}
                        </div>` : ''}
                        <div class="img-view-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    
                    <div class="item-content">
                        <div class="item-header">
                            <div class="item-title-section">
                                <div class="item-name">${unit.unitName}</div>
                                <span class="item-badge ${type}">
                                    <i class="fas ${type === 'room' ? 'fa-bed' : 'fa-home'}"></i> 
                                    ${type.charAt(0).toUpperCase() + type.slice(1)}
                                </span>
                            </div>
                            <div class="item-price-section">
                                <div class="item-price ${priceColor}">₱${total.toFixed(2)}</div>
                            </div>
                        </div>
                        
                        <div class="item-tags">
                            {{-- ✅ numGuests shown per item from cart_items --}}
                            <span class="item-tag ${isUnavailable ? 'red' : 'blue'}">
                                <i class="fas fa-users"></i> ${guests} guest${guests > 1 ? 's' : ''}
                            </span>
                            <span class="item-tag ${isUnavailable ? 'red' : 'blue'}">
                                <i class="fas ${type === 'room' ? 'fa-calendar-alt' : 'fa-calendar-day'}"></i> 
                                ${type === 'room' ? days + ' day(s)' : 'Day use'}
                            </span>
                        </div>
                        
                        ${calc ? `
                        <div class="calc-note ${isUnavailable ? 'red' : ''}">
                            <i class="fas ${isUnavailable ? 'fa-exclamation-triangle' : 'fa-calculator'}"></i>
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

    // ─── Warning banner ───
    let warnings = '';
    if (hasCottageInCart && !hasActiveEntranceFee) {
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

    // ─── Price breakdown ───
    let breakdownRows = '';
    if (totalRoom > 0) {
        breakdownRows += `
        <div class="price-row">
            <span><i class="fas fa-bed row-icon"></i> Rooms Subtotal</span>
            <span>₱${totalRoom.toFixed(2)}</span>
        </div>`;
    }
    if (totalCottage > 0) {
        breakdownRows += `
        <div class="price-row">
            <span><i class="fas fa-home row-icon"></i> Cottages Base Price</span>
            <span>₱${totalCottage.toFixed(2)}</span>
        </div>`;
    }
    if (totalEntranceFee > 0) {
        breakdownRows += `
        <div class="price-row">
            <span><i class="fas fa-ticket-alt row-icon"></i> Entrance Fees</span>
            <span>₱${totalEntranceFee.toFixed(2)}</span>
        </div>`;
    }

    const grandTotal = totalRoom + totalCottage + totalEntranceFee;

    // ─── Checkout button ───
    const checkoutBtn = canProceed
        ? `<button onclick="proceedToCheckout()" class="btn btn-checkout checkout-button">
               <i class="fas fa-lock"></i> Proceed to Secure Checkout
           </button>`
        : `<button class="btn btn-error btn-disabled">
               <i class="fas fa-ban"></i> Entrance Fee Required for Cottages
           </button>`;

    // ✅ Booking Summary guest display — guestSummary & guestVaries from API
    const guestSummary = cart.guestSummary;
    const guestVaries  = cart.guestVaries;

    const guestDisplay = guestVaries
        ? `${guestSummary} guests
           <br><span class="guest-varies-badge">
               <i class="fas fa-info-circle"></i> Varies per unit
           </span>`
        : `${guestSummary} Guest${parseInt(guestSummary) > 1 ? 's' : ''}`;

    container.innerHTML = `
        <div class="cart-grid">
            <!-- LEFT SIDEBAR -->
            <div class="left-sidebar">
                <div class="sidebar-card">
                    <div class="summary-header">
                        <h3>Booking Summary</h3>
                    </div>
                    <div class="summary-content">
                        <div class="summary-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="summary-item-content">
                                <div class="summary-label">Check-in</div>
                                <div class="summary-value">${checkIn}</div>
                            </div>
                        </div>
                        <div class="summary-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="summary-item-content">
                                <div class="summary-label">Check-out</div>
                                <div class="summary-value">${checkOut}</div>
                            </div>
                        </div>
                        <div class="summary-item">
                            <i class="fas fa-users"></i>
                            <div class="summary-item-content">
                                {{-- ✅ numGuests now per-item, show aggregate in summary --}}
                                <div class="summary-label">Guests</div>
                                <div class="summary-value">${guestDisplay}</div>
                            </div>
                        </div>
                        <div class="summary-item">
                            <i class="fas ${cartUnitType === 'room' ? 'fa-bed' : cartUnitType === 'cottage' ? 'fa-home' : 'fa-hotel'}"></i>
                            <div class="summary-item-content">
                                <div class="summary-label">Booking Type</div>
                                <div class="summary-value">
                                    ${cartUnitType === 'room'    ? 'Rooms Only'   :
                                      cartUnitType === 'cottage' ? 'Cottages Only' :
                                      'Mixed (Rooms + Cottages)'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="price-divider"></div>

                    <div class="price-section-title">
                        <h4><i class="fas fa-receipt"></i> Price Summary</h4>
                    </div>
                    <div class="price-content">
                        ${breakdownRows}
                        <div class="price-total">
                            <span>Total Amount</span>
                            <span>₱${grandTotal.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN COLUMN -->
            <div class="main-column">
                ${warnings}

                <div class="items-section">
                    <div class="items-header">
                        <h3><i class="fas fa-shopping-bag"></i> Cart Items</h3>
                        <span class="items-count">${items.length}</span>
                    </div>
                    <div class="items-container">
                        ${itemsHTML}
                    </div>
                </div>

                <div class="actions-card">
                    <div class="actions-header">
                        <h3><i class="fas fa-plus-circle"></i> Quick Actions</h3>
                    </div>
                    <div class="actions-content">
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
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() }
    })
    .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
    .then(d => {
        if (!d.success || !d.cart) throw new Error('Cart not found');
        return fetch('/api/cart/validate-before-checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
            body: JSON.stringify({})
        });
    })
    .then(r => { if (!r.ok) throw new Error('Server error'); return r.json(); })
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
    .catch(() => {
        btn.innerHTML = orig;
        btn.disabled  = false;
        showNotification('Validation failed. Try to remove the item and book again.', 'error');
    });
}

/* ═══════════════════════════════
   UNAVAILABLE MODAL
═══════════════════════════════ */
function showUnavailableModal(items, errors) {
    const itemsHTML = items.map(item => `
        <div class="unavail-item">
            <div>
                <div class="ui-name">${item.unit?.unitName || 'Unknown'}</div>
                <div class="ui-reason"><i class="fas fa-exclamation-circle"></i> ${item.reason || 'Not available'}</div>
            </div>
            ${item.cartItemID ? `
            <button onclick="removeUnavailItem(${item.cartItemID})" class="btn btn-sm btn-sm-red">
                <i class="fas fa-trash-alt"></i> Remove
            </button>` : ''}
        </div>`).join('');

    const ids = JSON.stringify(items.filter(i => i.cartItemID).map(i => i.cartItemID));

    document.getElementById('availability-modal-container').innerHTML = `
        <div class="modal-overlay show" id="avail-modal">
            <div class="modal">
                <div class="modal-head">
                    <h2><i class="fas fa-exclamation-circle"></i> Booking Issues (${items.length})</h2>
                    <button class="modal-close" onclick="closeAvailModal()"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="modal-info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Some accommodations are no longer available for your selected dates. Please remove them to continue.</p>
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
    Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF6B6B',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-trash-alt"></i> Yes, Remove',
        cancelButtonText: '<i class="fas fa-times"></i> Cancel',
        customClass: { popup: 'swal-custom-popup', confirmButton: 'swal-confirm-btn', cancelButton: 'swal-cancel-btn' }
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch(`/api/cart/remove/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Swal.fire({
                    title: 'Removed!',
                    text: 'Item has been removed from your cart.',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonColor: '#51CF66',
                    confirmButtonText: 'OK',
                    customClass: { popup: 'swal-custom-popup' }
                }).then(() => { loadCartItems(); updateBadge(); });
            } else {
                Swal.fire({ title: 'Failed!', text: d.message || 'Could not remove item.', icon: 'error', confirmButtonColor: '#FF6B6B' });
            }
        })
        .catch(() => {
            Swal.fire({ title: 'Error', text: 'Something went wrong. Please try again.', icon: 'error', confirmButtonColor: '#FF6B6B' });
        });
    });
}

function removeUnavailItem(id) {
    closeAvailModal();
    fetch(`/api/cart/remove/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
    })
    .then(() => { showNotification('Item removed.', 'success'); setTimeout(loadCartItems, 600); })
    .catch(() => showNotification('Error.', 'error'));
}

function removeAllUnavail(ids) {
    if (!confirm('Remove all unavailable items?')) return;
    closeAvailModal();
    Promise.all(ids.map(id => fetch(`/api/cart/remove/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
    })))
    .then(() => { showNotification('All unavailable items removed.', 'success'); setTimeout(() => { loadCartItems(); updateBadge(); }, 500); })
    .catch(() => showNotification('Some items could not be removed.', 'error'));
}

function removeAllRooms() {
    if (!confirm('Remove all room items from your cart?')) return;
    fetch('/api/cart/items', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() } })
    .then(r => r.json())
    .then(d => {
        const rooms = (d.items || []).filter(i => i.unit?.unitType === 'room');
        if (!rooms.length) return showNotification('No room items found.', 'info');
        return Promise.all(rooms.map(i => fetch(`/api/cart/remove/${i.cartItemID}`, {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
        }))).then(() => { showNotification('All rooms removed.', 'success'); setTimeout(() => { loadCartItems(); updateBadge(); }, 500); });
    })
    .catch(() => showNotification('Error.', 'error'));
}

function removeAllCottages() {
    if (!confirm('Remove all cottage items from your cart?')) return;
    fetch('/api/cart/items', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() } })
    .then(r => r.json())
    .then(d => {
        const cottages = (d.items || []).filter(i => i.unit?.unitType === 'cottage');
        if (!cottages.length) return showNotification('No cottage items found.', 'info');
        return Promise.all(cottages.map(i => fetch(`/api/cart/remove/${i.cartItemID}`, {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
        }))).then(() => { showNotification('All cottages removed.', 'success'); setTimeout(() => { loadCartItems(); updateBadge(); }, 500); });
    })
    .catch(() => showNotification('Error.', 'error'));
}

/* ═══════════════════════════════
   NOTIFICATIONS
═══════════════════════════════ */
let activeNotifications = new Set();

function showNotification(msg, type = 'info', duration = 3500) {
    const id = 'notification-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6);
    const icons  = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    const titles = { success: 'Success', error: 'Error', warning: 'Warning', info: 'Information' };

    const el = document.createElement('div');
    el.id = id;
    el.className = `notification ${type}`;
    el.innerHTML = `
        <div class="notification-icon"><i class="fas ${icons[type]}"></i></div>
        <div class="notification-content">
            <div class="notification-title">${titles[type]}</div>
            <div class="notification-message">${msg}</div>
        </div>
        <button class="notification-close" onclick="removeNotification('${id}')">
            <i class="fas fa-times"></i>
        </button>`;

    document.getElementById('notification-container').appendChild(el);
    activeNotifications.add(id);
    requestAnimationFrame(() => el.classList.add('show'));
    setTimeout(() => removeNotification(id), duration);
    return id;
}

function removeNotification(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('show');
    setTimeout(() => el && el.remove(), 500);
    activeNotifications.delete(id);
}

function clearNotifications() {
    document.querySelectorAll('.notification').forEach(n => { n.classList.remove('show'); setTimeout(() => n.remove(), 500); });
    activeNotifications.clear();
}

/* ═══════════════════════════════
   HELPERS
═══════════════════════════════ */
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function getUnitImages(unit) {
    try {
        if (unit.images) {
            const imgs = typeof unit.images === 'string' ? JSON.parse(unit.images) : unit.images;
            if (Array.isArray(imgs) && imgs.length > 0) {
                return imgs.map(img => img.startsWith('http') ? img : `/storage/${img}`);
            }
        }
    } catch(e) {
        console.error('Error parsing images:', e);
    }
    return ['https://via.placeholder.com/400x300?text=No+Image'];
}

function updateBadge() {
    fetch('/api/cart/count', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(d => {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.style.display = (d.success && d.count > 0) ? 'flex' : 'none';
            if (d.count > 0) badge.textContent = d.count;
        }
    }).catch(() => {});
}
</script>
</body>
</html>