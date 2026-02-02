<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        /* ═══════════════════════════════════════════════════════════
           SCOPED CSS VARIABLES - Enhanced Colors
        ═══════════════════════════════════════════════════════════ */
        .booking-page-wrapper {
            --booking-primary-yellow: #FFD709;
            --booking-secondary-yellow: #FFA500;
            --booking-yellow-dark: #F59E0B;
            --booking-gcash-blue: #007DFF;
            --booking-light-bg: #FFFBF0;
            --booking-card-bg: #FFFFFF;
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
            --booking-yellow-mid: rgba(255, 215, 0, 0.35);
            --booking-blue-light: rgba(0, 125, 255, 0.1);
            --booking-purple: #8B5CF6;
            --booking-purple-light: rgba(139, 92, 246, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFFBF0 0%, #FFF8E1 100%);
            color: #1F2937;
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }

        /* ─── MAIN CONTENT WRAPPER ─── */
        .booking-page-wrapper {
            background: transparent;
        }

        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 300px);
            padding: 2.5rem 1rem 3rem;
        }

        /* ─── PAGE TITLE WITH ENHANCED STYLING ─── */
        .page-title-wrap {
            text-align: center;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.6s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #F59E0B, #D97706, #B45309);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            text-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
            letter-spacing: -0.5px;
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

        @keyframes expandLine {
            from {
                width: 0;
                opacity: 0;
            }
            to {
                width: 100px;
                opacity: 1;
            }
        }

        /* ─── ENHANCED STEP PROGRESS BAR ─── */
        .stepper {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            max-width: 600px;
            margin: 2rem auto 3rem;
            position: relative;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .step-head {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: center;
        }

        .step-line {
            flex: 1;
            height: 4px;
            background: linear-gradient(90deg, #E5E7EB, #D1D5DB);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 120px;
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }

        .step-line::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, var(--booking-primary-yellow), var(--booking-secondary-yellow));
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .step-line.active::before {
            left: 0;
        }

        .step-line.left { order: -1; }
        .step-line.right { order: 1; }
        .step-item:first-child .step-line.left { visibility: hidden; }
        .step-item:last-child .step-line.right { visibility: hidden; }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 4px solid #E5E7EB;
            background: linear-gradient(135deg, #FFFFFF, #F9FAFB);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: var(--booking-text-light);
            position: relative;
            z-index: 2;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            order: 0;
        }

        .step-circle::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow));
            opacity: 0;
            transition: opacity 0.4s;
            z-index: -1;
        }

        .step-circle.active,
        .step-circle.completed {
            border-color: transparent;
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow));
            color: #fff;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
            transform: scale(1.15);
        }

        .step-circle.active::before,
        .step-circle.completed::before {
            opacity: 1;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.6;
            }
            50% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        .step-circle i {
            font-size: 1.1rem;
        }

        .step-label {
            margin-top: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--booking-text-light);
            transition: all 0.4s;
            text-align: center;
        }

        .step-item.active .step-label {
            color: var(--booking-secondary-yellow);
            transform: scale(1.05);
        }

        /* ─── ENHANCED STEP PANELS WITH SWIPE ANIMATION ─── */
        .panels-container {
            position: relative;
            overflow: hidden;
            max-width: 700px;
            margin: 0 auto;
        }

        .step-panel {
            display: none;
            opacity: 0;
            transform: translateX(100%);
            transition: none;
        }

        .step-panel.active {
            display: block;
            animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .step-panel.slide-out-left {
            animation: slideOutLeft 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .step-panel.slide-out-right {
            animation: slideOutRight 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes slideOutLeft {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateX(-100%) scale(0.95);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateX(100%) scale(0.95);
            }
        }

        /* ─── VIBRANT BOOKING CARD ─── */
        .booking-card {
            background: linear-gradient(135deg, #FFFFFF, #FEFEFE);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            margin-bottom: 1.5rem;
            position: relative;
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

        .card-header {
            padding: 2rem 2.5rem 0.5rem;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.05), transparent);
        }

        .card-header h2 {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--booking-text-dark), var(--booking-text-medium));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.3rem;
            letter-spacing: -0.3px;
        }

        .card-header p {
            font-size: 0.85rem;
            color: var(--booking-text-medium);
            font-weight: 500;
        }

        .card-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        /* ─── ENHANCED FORM ELEMENTS ─── */
        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.5s ease-out both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            margin-bottom: 0.7rem;
            color: var(--booking-text-dark);
            font-size: 0.85rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .form-label i {
            color: var(--booking-primary-yellow);
            font-size: 1.1rem;
            filter: drop-shadow(0 2px 4px rgba(255, 215, 0, 0.3));
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #FFFFFF, #FAFAFA);
            color: var(--booking-text-dark);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--booking-primary-yellow);
            box-shadow: 0 0 0 4px var(--booking-yellow-light), 0 4px 12px rgba(255, 215, 0, 0.2);
            transform: translateY(-2px);
            background: #FFFFFF;
        }

        .form-input:disabled {
            background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
            color: var(--booking-text-light);
            cursor: not-allowed;
            border-color: #D1D5DB;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        /* ─── VIBRANT PAYMENT METHOD CARDS ─── */
        .pay-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
            margin-top: 0.5rem;
        }

        .pay-card {
            border: 3px solid var(--booking-border-color);
            border-radius: 18px;
            padding: 1.8rem 1.2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            background: linear-gradient(135deg, #FFFFFF, #FAFAFA);
            position: relative;
            overflow: hidden;
        }

        .pay-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--booking-yellow-light), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .pay-card:hover {
            border-color: var(--booking-primary-yellow);
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 28px rgba(255, 215, 0, 0.25);
        }

        .pay-card:hover::before {
            opacity: 1;
        }

        .pay-card.selected {
            border-color: var(--booking-primary-yellow);
            background: linear-gradient(135deg, var(--booking-yellow-light), rgba(255, 215, 0, 0.05));
            box-shadow: 0 0 0 4px var(--booking-yellow-light), 0 8px 24px rgba(255, 215, 0, 0.3);
            transform: scale(1.05);
        }

        .pay-card.selected::before {
            opacity: 1;
        }

        .pay-card.selected .pay-check {
            opacity: 1;
            transform: scale(1) rotate(360deg);
        }

        .pay-check {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            opacity: 0;
            transform: scale(0) rotate(0deg);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 1;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .pay-card .pay-icon {
            position: relative;
            z-index: 1;
            font-size: 2.5rem;
            margin-bottom: 0.8rem;
            transition: all 0.4s;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .pay-card .pay-icon i {
            background: linear-gradient(135deg, var(--booking-text-medium), var(--booking-text-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .pay-card.selected .pay-icon,
        .pay-card:hover .pay-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .pay-card.selected .pay-icon i {
            background: linear-gradient(135deg, var(--booking-secondary-yellow), var(--booking-yellow-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .pay-card .pay-title {
            position: relative;
            z-index: 1;
            font-size: 1rem;
            font-weight: 700;
            color: var(--booking-text-dark);
            margin-bottom: 0.3rem;
            letter-spacing: 0.3px;
        }

        .pay-card .pay-sub {
            position: relative;
            z-index: 1;
            font-size: 0.75rem;
            color: var(--booking-text-medium);
            font-weight: 500;
        }

        /* ─── VIBRANT AMOUNT CHIPS ─── */
        .amount-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
            margin-top: 0.5rem;
        }

        .amount-chip {
            border: 3px solid var(--booking-border-color);
            border-radius: 18px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            background: linear-gradient(135deg, #FFFFFF, #FAFAFA);
            overflow: hidden;
        }

        .amount-chip::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--booking-purple-light), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .amount-chip:hover {
            border-color: var(--booking-purple);
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 28px rgba(139, 92, 246, 0.25);
        }

        .amount-chip:hover::before {
            opacity: 1;
        }

        .amount-chip.selected {
            border-color: var(--booking-purple);
            background: linear-gradient(135deg, var(--booking-purple-light), rgba(139, 92, 246, 0.05));
            box-shadow: 0 0 0 4px var(--booking-purple-light), 0 8px 24px rgba(139, 92, 246, 0.3);
            transform: scale(1.05);
        }

        .amount-chip.selected::before {
            opacity: 1;
        }

        .amount-chip.selected .chip-check {
            opacity: 1;
            transform: scale(1) rotate(360deg);
        }

        .chip-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            opacity: 0;
            transform: scale(0) rotate(0deg);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .chip-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--booking-text-medium);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.5rem;
        }

        .chip-amount {
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--booking-purple), #7C3AED);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.3rem;
        }

        .chip-note {
            font-size: 0.7rem;
            color: var(--booking-text-light);
            font-weight: 500;
        }

        /* ─── SUMMARY SECTIONS ─── */
        .section-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--booking-text-medium);
            margin-bottom: 1rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid var(--booking-border-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-label::before {
            content: '';
            width: 4px;
            height: 16px;
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow));
            border-radius: 2px;
        }

        .summary-block {
            margin-bottom: 2rem;
        }

        .s-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 0;
            font-size: 0.9rem;
            border-bottom: 1px solid #F3F4F6;
        }

        .s-row:last-child {
            border-bottom: none;
        }

        .s-row .s-label {
            color: var(--booking-text-medium);
            font-weight: 500;
        }

        .s-row .s-value {
            font-weight: 700;
            color: var(--booking-text-dark);
        }

        /* Accommodation item */
        .accom-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 2px solid #F3F4F6;
        }

        .accom-item:last-child {
            border-bottom: none;
        }

        .accom-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--booking-text-dark);
            margin-bottom: 0.4rem;
        }

        .accom-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .accom-badge.room {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(29, 78, 216, 0.1));
            color: #1e40af;
        }

        .accom-badge.cottage {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            color: #065f46;
        }

        .accom-calc {
            font-size: 0.75rem;
            color: var(--booking-text-light);
            margin-top: 0.3rem;
            font-weight: 500;
        }

        .accom-price {
            font-size: 1.1rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            white-space: nowrap;
        }

        /* Total box */
        .total-price-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.08));
            border: 3px solid var(--booking-green);
            border-radius: 18px;
            padding: 1.5rem 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2);
        }

        .total-price-box .t-label {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--booking-green-dark);
        }

        .total-price-box .t-amount {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ─── RULES CARDS ─── */
        .rules-card {
            margin-bottom: 1.2rem;
        }

        .rules-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 2rem 2.5rem 1rem;
        }

        .rules-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .rules-icon.room-icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(29, 78, 216, 0.1));
            color: #2563eb;
        }

        .rules-icon.cottage-icon {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            color: #10b981;
        }

        .rules-icon.general-icon {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 165, 0, 0.15));
            color: var(--booking-secondary-yellow);
        }

        .rules-header h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--booking-text-dark);
        }

        .rules-header p {
            font-size: 0.75rem;
            color: var(--booking-text-medium);
            margin-top: 0.2rem;
            font-weight: 500;
        }

        .rules-list {
            list-style: none;
            padding: 0 2.5rem 2rem;
        }

        .rules-list li {
            display: flex;
            gap: 0.8rem;
            padding: 0.7rem 0;
            font-size: 0.85rem;
            color: var(--booking-text-medium);
            border-bottom: 1px solid #F3F4F6;
            align-items: flex-start;
            line-height: 1.6;
            font-weight: 500;
        }

        .rules-list li:last-child {
            border-bottom: none;
        }

        .rules-list .ri {
            font-size: 0.5rem;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .rules-list .ri.y {
            color: var(--booking-secondary-yellow);
        }

        .rules-list .ri.g {
            color: var(--booking-green);
        }

        .rules-list .ri.r {
            color: var(--booking-red);
        }

        /* Cancel notice */
        .cancel-notice {
            background: linear-gradient(135deg, var(--booking-red-light), rgba(239, 68, 68, 0.05));
            border: 2px solid var(--booking-red);
            border-radius: 14px;
            padding: 1.2rem 1.3rem;
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
        }

        .cancel-notice i {
            color: var(--booking-red);
            flex-shrink: 0;
            margin-top: 2px;
            font-size: 1.1rem;
        }

        .cancel-notice p {
            font-size: 0.8rem;
            color: var(--booking-red-dark);
            line-height: 1.6;
            font-weight: 500;
        }

        /* Contact strip */
        .contact-strip {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
            padding: 1.2rem 1.3rem;
            background: linear-gradient(135deg, #F9FAFB, #F3F4F6);
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
            color: var(--booking-text-medium);
            font-weight: 600;
        }

        .contact-item i {
            color: var(--booking-primary-yellow);
            font-size: 1rem;
            filter: drop-shadow(0 2px 4px rgba(255, 215, 0, 0.3));
        }

        /* ─── ENHANCED BUTTONS ─── */
        .btn-row {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.3px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
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

        .action-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .action-btn:active {
            transform: scale(0.95);
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .action-btn:disabled::before {
            display: none;
        }

        .action-btn i,
        .action-btn span {
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--booking-primary-yellow), var(--booking-secondary-yellow), var(--booking-yellow-dark));
            color: #fff;
            flex: 1;
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover:not(:disabled) {
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.5);
            transform: translateY(-3px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #FFFFFF, #F9FAFB);
            color: var(--booking-text-medium);
            border: 2px solid var(--booking-border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #F9FAFB, #F3F4F6);
            border-color: var(--booking-text-medium);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            color: #fff;
            flex: 1;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--booking-green-dark), #047857);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.5);
            transform: translateY(-3px);
        }

        .btn-gcash {
            background: linear-gradient(135deg, var(--booking-gcash-blue), #0062CC);
            color: #fff;
            flex: 1;
            box-shadow: 0 6px 20px rgba(0, 125, 255, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-gcash:hover:not(:disabled) {
            background: linear-gradient(135deg, #0062CC, #004C99);
            box-shadow: 0 10px 30px rgba(0, 125, 255, 0.5);
            transform: translateY(-3px);
        }

        .btn-danger-ghost {
            background: transparent;
            color: var(--booking-red);
            border: 2px solid var(--booking-red);
            width: 100%;
            margin-top: 1rem;
            justify-content: center;
        }

        .btn-danger-ghost:hover {
            background: var(--booking-red-light);
            border-color: var(--booking-red-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        /* ─── SUCCESS STATE ─── */
        .success-wrap {
            text-align: center;
            padding: 3rem 1.5rem 2rem;
        }

        .success-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: var(--booking-green);
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-wrap h3 {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .success-wrap > p {
            font-size: 0.9rem;
            color: var(--booking-text-medium);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .ref-box {
            background: linear-gradient(135deg, #F9FAFB, #F3F4F6);
            border: 2px solid var(--booking-border-color);
            border-radius: 14px;
            padding: 1.3rem 1.5rem;
            text-align: left;
            display: inline-block;
            min-width: 300px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .ref-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            padding: 0.4rem 0;
            color: var(--booking-text-medium);
            font-weight: 600;
        }

        .ref-row span:last-child {
            font-weight: 700;
            color: var(--booking-text-dark);
        }

        .success-btns {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        /* ─── TOAST ─── */
        .toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--booking-text-dark);
            color: #fff;
            padding: 1rem 1.3rem;
            border-radius: 14px;
            font-size: 0.85rem;
            max-width: 350px;
            display: flex;
            gap: 0.7rem;
            align-items: flex-start;
            z-index: 999;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: toastSlide 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .toast.error {
            background: linear-gradient(135deg, var(--booking-red), var(--booking-red-dark));
        }

        .toast.success {
            background: linear-gradient(135deg, var(--booking-green), var(--booking-green-dark));
        }

        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast i {
            flex-shrink: 0;
            margin-top: 2px;
            font-size: 1.1rem;
        }

        /* ─── SPINNER ─── */
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 215, 0, 0.2);
            border-top-color: var(--booking-primary-yellow);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 3rem auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 640px) {
            .main-content {
                margin-top: 70px;
                padding-top: 2rem;
            }
            .row-2 {
                grid-template-columns: 1fr;
            }
            .pay-grid,
            .amount-row {
                grid-template-columns: 1fr;
            }
            .card-body,
            .card-header {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .rules-list {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .rules-header {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .contact-strip {
                flex-direction: column;
                gap: 0.8rem;
            }
            .page-title {
                font-size: 2rem;
            }
            .step-circle {
                width: 44px;
                height: 44px;
                font-size: 0.9rem;
            }
            .btn-row {
                flex-direction: column;
            }
            .action-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @include('customerFolder.partials.navbar')

    <!-- WRAP EVERYTHING IN A SCOPED CONTAINER -->
    <div class="booking-page-wrapper">
        <div class="main-content">

            <!-- Page Title -->
            <div class="page-title-wrap">
                <h1 class="page-title">Complete Your Booking</h1>
                <div class="page-title-line"></div>
            </div>

            <!-- Step Progress -->
            <div class="stepper">
                <div class="step-item active" id="step-nav-1">
                    <div class="step-head">
                        <div class="step-line left"></div>
                        <div class="step-circle active"><i class="fas fa-user"></i></div>
                        <div class="step-line right" id="line-1"></div>
                    </div>
                    <div class="step-label">Details</div>
                </div>
                <div class="step-item" id="step-nav-2">
                    <div class="step-head">
                        <div class="step-line left" id="line-1b"></div>
                        <div class="step-circle"><i class="fas fa-file-invoice"></i></div>
                        <div class="step-line right" id="line-2"></div>
                    </div>
                    <div class="step-label">Summary</div>
                </div>
                <div class="step-item" id="step-nav-3">
                    <div class="step-head">
                        <div class="step-line left" id="line-2b"></div>
                        <div class="step-circle"><i class="fas fa-check-circle"></i></div>
                        <div class="step-line right"></div>
                    </div>
                    <div class="step-label">Confirm</div>
                </div>
            </div>

            <!-- Panels Container -->
            <div class="panels-container">
                <!-- ══════════════════════════════════════
                     STEP 1 – Guest Info + Payment
                ══════════════════════════════════════ -->
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
                                    <label class="form-label">
                                        <i class="fas fa-user"></i>
                                        <span>Full Name</span>
                                    </label>
                                    <input type="text" name="full_name" class="form-input" readonly>
                                </div>

                                <div class="row-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-envelope"></i>
                                            <span>Email</span>
                                        </label>
                                        <input type="email" name="email" class="form-input" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-phone"></i>
                                            <span>Phone</span>
                                        </label>
                                        <input type="tel" name="phone" class="form-input" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-comment-dots"></i>
                                        <span>Special Requirements</span>
                                    </label>
                                    <textarea name="special_requirements" class="form-textarea" placeholder="Any special requests or requirements…"></textarea>
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Payment Method</span>
                                    </label>
                                    <div class="pay-grid">
                                        <div class="pay-card" id="pay-cash" onclick="selectPayMethod('cash')">
                                            <div class="pay-check"><i class="fas fa-check"></i></div>
                                            <div class="pay-icon"><i class="fas fa-money-bill-wave"></i></div>
                                            <div class="pay-title">Cash</div>
                                            <div class="pay-sub">Pay on arrival</div>
                                        </div>
                                        <div class="pay-card" id="pay-gcash" onclick="selectPayMethod('gcash')">
                                            <div class="pay-check"><i class="fas fa-check"></i></div>
                                            <div class="pay-icon"><i class="fas fa-mobile-alt"></i></div>
                                            <div class="pay-title">GCash</div>
                                            <div class="pay-sub">Pay online now</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount Selection (appears after method) -->
                                <div id="amount-section" style="display:none;">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span>Payment Amount</span>
                                        </label>
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
                                    </div>
                                </div>

                                <!-- Hidden fields -->
                                <input type="hidden" name="payment_method" id="h-payment-method">
                                <input type="hidden" name="payment_amount" id="h-payment-amount">
                                <input type="hidden" name="booking_type" id="h-booking-type">
                                <input type="hidden" name="event_type" value="normal-booking">
                            </form>

                            <div class="btn-row">
                                <button class="action-btn btn-primary" id="btn-next1" onclick="goToStep2()" disabled>
                                    <span>Next Step</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     STEP 2 – Booking Summary
                ══════════════════════════════════════ -->
                <div class="step-panel" id="panel-2">
                    <div class="booking-card">
                        <div class="card-header">
                            <h2>Booking Summary</h2>
                            <p>Review your stay details and pricing before we proceed.</p>
                        </div>
                        <div class="card-body" id="summary-body">
                            <div class="spinner"></div>
                        </div>
                    </div>

                    <div class="btn-row">
                        <button class="action-btn btn-secondary" onclick="goToStep(1)">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button class="action-btn btn-primary" onclick="goToStep3()">
                            <span>Next Step</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     STEP 3 – Rules + Confirm
                ══════════════════════════════════════ -->
                <div class="step-panel" id="panel-3">

                    <!-- Room Rules -->
                    <div class="booking-card rules-card" id="room-rules-card" style="display:none;">
                        <div class="rules-header">
                            <div class="rules-icon room-icon"><i class="fas fa-bed"></i></div>
                            <div>
                                <h3>Room Guidelines</h3>
                                <p>Rules that apply to your room booking.</p>
                            </div>
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

                    <!-- Cottage Rules -->
                    <div class="booking-card rules-card" id="cottage-rules-card" style="display:none;">
                        <div class="rules-header">
                            <div class="rules-icon cottage-icon"><i class="fas fa-home"></i></div>
                            <div>
                                <h3>Cottage Guidelines</h3>
                                <p>Rules that apply to your cottage booking.</p>
                            </div>
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

                    <!-- Cancellation + Contact -->
                    <div class="booking-card rules-card">
                        <div class="rules-header">
                            <div class="rules-icon general-icon"><i class="fas fa-info-circle"></i></div>
                            <div>
                                <h3>Cancellation & Contact</h3>
                                <p>Important details before you confirm.</p>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:0;">
                            <div class="cancel-notice">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>Cancellations made <strong>less than 48 hours</strong> before check-in are non-refundable. Cancellations made 48 hours or more in advance will receive a full downpayment refund.</p>
                            </div>

                            <div style="margin-top:1rem;">
                                <div class="section-label" style="margin-top:0;">Reach Us</div>
                                <div class="contact-strip">
                                    <div class="contact-item"><i class="fas fa-phone"></i> +63 (XXX) XXX-XXXX</div>
                                    <div class="contact-item"><i class="fas fa-envelope"></i> villa.elena@email.com</div>
                                    <div class="contact-item"><i class="fas fa-map-marker-alt"></i> Villa Elena, Zambales</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Buttons -->
                    <div class="btn-row">
                        <button class="action-btn btn-secondary" onclick="goToStep(2)">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button class="action-btn btn-success" id="btn-confirm-cash" style="display:none;" onclick="submitCashBooking()">
                            <i class="fas fa-check"></i>
                            <span>Confirm Booking</span>
                        </button>
                        <button class="action-btn btn-gcash" id="btn-confirm-gcash" style="display:none;" onclick="submitGCashBooking()">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Pay with GCash</span>
                        </button>
                    </div>

                    <button class="action-btn btn-danger-ghost" onclick="cancelBooking()">
                        <i class="fas fa-times-circle"></i>
                        <span>Cancel Booking</span>
                    </button>
                </div>

                <!-- ══════════════════════════════════════
                     SUCCESS STATE
                ══════════════════════════════════════ -->
                <div class="step-panel" id="panel-success">
                    <div class="booking-card">
                        <div class="card-body">
                            <div class="success-wrap">
                                <div class="success-icon"><i class="fas fa-check"></i></div>
                                <h3>Booking Confirmed!</h3>
                                <p>Your reservation at Villa Elena is all set.</p>
                                <div class="ref-box" id="ref-box"></div>
                                <div class="success-btns">
                                    <a href="/" class="action-btn btn-secondary">
                                        <i class="fas fa-home"></i>
                                        <span>Home</span>
                                    </a>
                                    <a href="/my-bookings" class="action-btn btn-primary">
                                        <i class="fas fa-calendar-check"></i>
                                        <span>My Bookings</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /panels-container -->

        </div><!-- /main-content -->
    </div><!-- /booking-page-wrapper -->

    @include('customerFolder.partials.footer')

<script>
// ─── STATE ───
let bookingTotal = 0;
let selectedPayMethod = null;
let selectedAmountType = null;
let selectedAmount = 0;
let daysCount = 1;
let numGuests = 1;
let entranceFeeAmount = 0;
let totalEntranceFees = 0;
let hasActiveEntranceFee = false;
let hasRoom = false;
let hasCottage = false;
let cartItems = [];
let cartData = null;
let currentStep = 1;

// ─── INIT ───
document.addEventListener('DOMContentLoaded', function () {
    Promise.all([loadEntranceFee(), loadBookingSummary()]).then(() => {
        prefillUserInfo();
    });
});

// ─── LOAD ENTRANCE FEE ───
async function loadEntranceFee() {
    try {
        const r = await fetch('/api/entrance-fee', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        if (r.ok) {
            const d = await r.json();
            if (d.success && d.entrance_fee) {
                entranceFeeAmount = parseFloat(d.entrance_fee.amount);
                hasActiveEntranceFee = true;
                console.log('Entrance fee loaded:', entranceFeeAmount);
            }
        }
    } catch (e) { console.error('Entrance fee error:', e); }
}

// ─── LOAD CART ───
function loadBookingSummary() {
    return fetch('/api/cart/items')
        .then(r => r.json())
        .then(data => {
            if (data.success && data.cart && data.items.length > 0) {
                cartData = data.cart;
                cartItems = data.items;
                computeTotals();
            } else {
                document.getElementById('summary-body').innerHTML =
                    '<p style="color:#dc2626;font-size:.82rem;text-align:center;padding:1rem 0;">No items in cart. Please add accommodations first.</p>';
            }
        })
        .catch(e => console.error('Cart error:', e));
}

// ─── COMPUTE TOTALS (REMOVED TAX & SERVICE CHARGE) ───
function computeTotals() {
    daysCount = parseInt(cartData.daysCount) || 1;
    numGuests = parseInt(cartData.numGuests) || 1;

    const checkIn  = new Date(cartData.checkInDate);
    const checkOut = new Date(cartData.checkOutDate);
    const isSameDay = checkIn.toDateString() === checkOut.toDateString();
    document.getElementById('h-booking-type').value = isSameDay ? 'day-use' : 'overnight';

    let roomSubtotal = 0;
    let cottageSubtotal = 0;
    totalEntranceFees = 0;
    hasRoom = false;
    hasCottage = false;

    cartItems.forEach(item => {
        const unit  = item.unit;
        const price = parseFloat(unit.unitRatePrice);

        if (unit.unitType === 'room') {
            hasRoom = true;
            const mult = numGuests === 1 ? 2 : numGuests;
            const roomTotal = price * mult * daysCount;
            roomSubtotal += roomTotal;
            item._total = roomTotal;
            item._calc  = `₱${price.toFixed(2)} × ${mult} guest${mult > 1 ? 's' : ''} × ${daysCount} day${daysCount > 1 ? 's' : ''}`;
        } else if (unit.unitType === 'cottage') {
            hasCottage = true;
            cottageSubtotal += price;
            
            if (hasActiveEntranceFee) {
                const entranceFeeForThisCottage = entranceFeeAmount * numGuests;
                totalEntranceFees += entranceFeeForThisCottage;
                item._total = price + entranceFeeForThisCottage;
                item._calc  = `Cottage: ₱${price.toFixed(2)} + Entrance fees: ₱${entranceFeeForThisCottage.toFixed(2)} (₱${entranceFeeAmount.toFixed(2)} × ${numGuests})`;
            } else {
                item._total = price;
                item._calc  = 'Cottage price only';
            }
        }
    });

    // NO TAX OR SERVICE CHARGE - Direct total
    bookingTotal = roomSubtotal + cottageSubtotal + totalEntranceFees;

    cartData._roomSubtotal = roomSubtotal;
    cartData._cottageSubtotal = cottageSubtotal;
    cartData._entranceFees = totalEntranceFees;
    cartData._subtotal = roomSubtotal + cottageSubtotal + totalEntranceFees;

    console.log('Totals computed:', {
        room: roomSubtotal,
        cottage: cottageSubtotal,
        entrance: totalEntranceFees,
        total: bookingTotal
    });

    updateAmountChips();
}

function updateAmountChips() {
    const down = Math.round(bookingTotal * 0.5 * 100) / 100;
    document.getElementById('chip-down-amt').textContent = '₱' + down.toFixed(2);
    document.getElementById('chip-full-amt').textContent = '₱' + bookingTotal.toFixed(2);
}

// ─── PREFILL USER ───
function prefillUserInfo() {
    const n = '{{ Auth::user()->name ?? "" }}';
    const e = '{{ Auth::user()->email ?? "" }}';
    const p = '{{ Auth::user()->phoneNumber ?? "" }}';
    if (n) document.querySelector('input[name="full_name"]').value = n.trim();
    if (e) document.querySelector('input[name="email"]').value = e;
    if (p) document.querySelector('input[name="phone"]').value = p;
}

// ─── PAYMENT METHOD ───
function selectPayMethod(method) {
    selectedPayMethod = method;
    document.getElementById('pay-cash').classList.toggle('selected', method === 'cash');
    document.getElementById('pay-gcash').classList.toggle('selected', method === 'gcash');
    document.getElementById('h-payment-method').value = method;
    document.getElementById('amount-section').style.display = 'block';
    updateNextBtn();
}

// ─── AMOUNT SELECTION ───
function selectAmount(type) {
    selectedAmountType = type;
    document.getElementById('chip-down').classList.toggle('selected', type === 'downpayment');
    document.getElementById('chip-full').classList.toggle('selected', type === 'full');
    selectedAmount = type === 'downpayment'
        ? Math.round(bookingTotal * 0.5 * 100) / 100
        : bookingTotal;
    document.getElementById('h-payment-amount').value = selectedAmount.toFixed(2);
    updateNextBtn();
}

function updateNextBtn() {
    document.getElementById('btn-next1').disabled = !(selectedPayMethod && selectedAmountType);
}

// ─── ENHANCED STEP NAVIGATION WITH SWIPE ANIMATION ───
function goToStep(n) {
    const oldStep = currentStep;
    const oldPanel = document.getElementById('panel-' + oldStep);
    const newPanel = document.getElementById('panel-' + n);
    
    // Determine direction
    const isForward = n > oldStep;
    
    // Add exit animation to current panel
    if (oldPanel && oldPanel !== newPanel) {
        oldPanel.classList.add(isForward ? 'slide-out-left' : 'slide-out-right');
        
        setTimeout(() => {
            oldPanel.classList.remove('active', 'slide-out-left', 'slide-out-right');
        }, 600);
    }
    
    // Add enter animation to new panel
    setTimeout(() => {
        if (newPanel) {
            newPanel.style.transform = isForward ? 'translateX(100%)' : 'translateX(-100%)';
            newPanel.classList.add('active');
        }
    }, isForward ? 100 : 0);
    
    currentStep = n;

    // Update step indicators
    for (let i = 1; i <= 3; i++) {
        const nav    = document.getElementById('step-nav-' + i);
        const circle = nav.querySelector('.step-circle');
        const icon   = circle.querySelector('i');
        
        nav.classList.toggle('active', i === n);
        circle.classList.toggle('active', i === n);
        circle.classList.toggle('completed', i < n);
        
        // Change icons for completed steps
        if (i < n) {
            icon.className = 'fas fa-check';
        } else if (i === 1) {
            icon.className = 'fas fa-user';
        } else if (i === 2) {
            icon.className = 'fas fa-file-invoice';
        } else if (i === 3) {
            icon.className = 'fas fa-check-circle';
        }
    }

    // Activate connector lines between completed/active steps
    const activateLines = (ids, cond) => ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('active', cond);
    });
    activateLines(['line-1', 'line-1b'], n >= 2);
    activateLines(['line-2', 'line-2b'], n >= 3);
}

function goToStep2() {
    if (!selectedPayMethod || !selectedAmountType) return;
    renderSummary();
    goToStep(2);
}

function goToStep3() {
    document.getElementById('room-rules-card').style.display    = hasRoom    ? 'block' : 'none';
    document.getElementById('cottage-rules-card').style.display = hasCottage ? 'block' : 'none';
    document.getElementById('btn-confirm-cash').style.display   = selectedPayMethod === 'cash'   ? 'flex' : 'none';
    document.getElementById('btn-confirm-gcash').style.display  = selectedPayMethod === 'gcash'  ? 'flex' : 'none';
    goToStep(3);
}

// ─── RENDER SUMMARY (WITH ENTRANCE FEE BREAKDOWN, NO TAX/SERVICE) ───
function renderSummary() {
    const d = cartData;
    const checkIn  = new Date(d.checkInDate);
    const checkOut = new Date(d.checkOutDate);
    const isSameDay = checkIn.toDateString() === checkOut.toDateString();
    const bookingType = isSameDay ? 'Day Use' : 'Overnight';

    const itemsHTML = cartItems.map(item => {
        const u = item.unit;
        return `
        <div class="accom-item">
            <div>
                <div class="accom-name">${u.unitName}</div>
                <span class="accom-badge ${u.unitType}">
                    <i class="fas fa-${u.unitType === 'room' ? 'bed' : 'home'}"></i> ${u.unitType}
                </span>
                <div class="accom-calc">${item._calc}</div>
            </div>
            <div class="accom-price">₱${item._total.toFixed(2)}</div>
        </div>`;
    }).join('');

    const payLabel = selectedAmountType === 'downpayment' ? 'Downpayment (50%)' : 'Full Payment';

    // Build pricing rows WITH entrance fee breakdown
    let pricingRows = '';
    
    if (d._roomSubtotal > 0) {
        pricingRows += `<div class="s-row"><span class="s-label">Rooms Subtotal</span><span class="s-value">₱${d._roomSubtotal.toFixed(2)}</span></div>`;
    }
    
    if (d._cottageSubtotal > 0) {
        pricingRows += `<div class="s-row"><span class="s-label">Cottages Subtotal</span><span class="s-value">₱${d._cottageSubtotal.toFixed(2)}</span></div>`;
    }
    
    if (d._entranceFees > 0) {
        pricingRows += `<div class="s-row"><span class="s-label">Entrance Fees (${numGuests} guest${numGuests > 1 ? 's' : ''})</span><span class="s-value">₱${d._entranceFees.toFixed(2)}</span></div>`;
    }

    document.getElementById('summary-body').innerHTML = `
        <div class="summary-block">
            <div class="section-label">Stay Details</div>
            <div class="s-row"><span class="s-label">Check-in</span><span class="s-value">${d.checkInDate}</span></div>
            <div class="s-row"><span class="s-label">Check-out</span><span class="s-value">${d.checkOutDate}</span></div>
            <div class="s-row"><span class="s-label">Duration</span><span class="s-value">${daysCount} day(s)</span></div>
            <div class="s-row"><span class="s-label">Booking Type</span><span class="s-value">${bookingType}</span></div>
            <div class="s-row"><span class="s-label">Guests</span><span class="s-value">${numGuests}</span></div>
            <div class="s-row"><span class="s-label">Event Type</span><span class="s-value">Normal Booking</span></div>
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
            <div class="s-row"><span class="s-label">Method</span><span class="s-value" style="text-transform:capitalize;">${selectedPayMethod}</span></div>
            <div class="s-row"><span class="s-label">${payLabel}</span><span class="s-value" style="color:#10b981;">₱${selectedAmount.toFixed(2)}</span></div>
        </div>
    `;
}

// ─── TOAST ───
function showToast(type, msg) {
    const t = document.createElement('div');
    t.className = 'toast ' + type;
    t.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 4500);
}

// ─── PRE-VALIDATE ───
async function validateCartBeforeSubmit() {
    try {
        const r = await fetch('/api/cart/pre-validate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const d = await r.json();
        if (!d.success) {
            showToast('error', d.validation_errors ? d.validation_errors.join(' ') : (d.message || 'Validation failed.'));
            return false;
        }
        return true;
    } catch (e) { showToast('error', 'Validation error. Please try again.'); return false; }
}

// ─── SUBMIT CASH ───
async function submitCashBooking() {
    const btn = document.getElementById('btn-confirm-cash');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing…</span>';

    if (!(await validateCartBeforeSubmit())) { resetBtn(btn, 'cash'); return; }

    const fd = new FormData(document.getElementById('bookingForm'));
    fd.set('payment_amount', selectedAmount.toFixed(2));
    fd.set('payment_method', 'cash');
    fd.set('booking_type', document.getElementById('h-booking-type').value);
    fd.append('event_type', 'normal-booking');

    try {
        const r = await fetch('/api/customer-bookings', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: fd
        });
        const d = await r.json();
        if (d.success) { showSuccess(d); }
        else { throw new Error(d.message || 'Booking failed'); }
    } catch (e) { showToast('error', e.message); resetBtn(btn, 'cash'); }
}

// ─── SUBMIT GCASH (TWO-PHASE) ───
async function submitGCashBooking() {
    const btn = document.getElementById('btn-confirm-gcash');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Validating…</span>';

    if (!(await validateCartBeforeSubmit())) { resetBtn(btn, 'gcash'); return; }

    const fd = new FormData(document.getElementById('bookingForm'));
    fd.set('payment_amount', selectedAmount.toFixed(2));
    fd.set('payment_method', 'gcash');
    fd.set('booking_type', document.getElementById('h-booking-type').value);
    fd.append('event_type', 'normal-booking');

    try {
        // PHASE 1 – Validate
        const vr = await fetch('/api/customer-bookings', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: fd
        });
        const vd = await vr.json();
        if (!vd.success || !vd.requires_payment_first) throw new Error(vd.message || 'Validation failed');

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing payment…</span>';

        // PHASE 2 – Pay
        const pr = await fetch('/gcash/process-payment', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ booking_data: vd.booking_data, payment_data: vd.payment_data })
        });
        const pd = await pr.json();
        if (pd.success && pd.checkout_url) { window.location.href = pd.checkout_url; }
        else { throw new Error(pd.message || 'GCash payment failed'); }
    } catch (e) { showToast('error', e.message); resetBtn(btn, 'gcash'); }
}

function resetBtn(btn, type) {
    btn.disabled = false;
    btn.innerHTML = type === 'cash'
        ? '<i class="fas fa-check"></i><span>Confirm Booking</span>'
        : '<i class="fas fa-mobile-alt"></i><span>Pay with GCash</span>';
}

// ─── SUCCESS ───
function showSuccess(data) {
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-success').classList.add('active');
    document.querySelector('.stepper').style.display = 'none';
    document.querySelector('.page-title-wrap').style.display = 'none';

    document.getElementById('ref-box').innerHTML = `
        <div class="ref-row"><span>Reference</span><span>${data.booking_reference}</span></div>
        <div class="ref-row"><span>Booking ID</span><span>${data.booking_id}</span></div>
        <div class="ref-row"><span>Status</span><span>${data.booking_status}</span></div>
    `;
}

// ─── CANCEL ───
function cancelBooking() {
    if (!confirm('Are you sure you want to cancel this booking?')) return;
    fetch('/api/cart/clear', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    }).then(r => r.json()).then(d => { if (d.success) window.location.href = "{{ route('home') }}"; });
}
</script>
</body>
</html>