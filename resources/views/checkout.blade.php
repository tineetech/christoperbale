@extends('layouts.app')

@section('title', 'Checkout — CHRISBALE')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        .select2-container { width: 100% !important; font-family: 'Inter', sans-serif; }
        .select2-container .select2-selection--single {
            height: auto;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            background: #fff;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 13px;
            color: var(--ink);
            padding: 9px 12px;
            line-height: 1.4;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; }
        .select2-container--default.select2-container--focus .select2-selection--single { border-color: var(--accent); }
        .select2-container--default.select2-container--disabled .select2-selection--single { background: #f3f4f6; color: #9ca3af; }
        .select2-dropdown { border: 1.5px solid var(--line); border-radius: 10px; overflow: hidden; }
        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--line);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }
        .select2-results__option { font-size: 13px; padding: 8px 12px; }
    </style>
    <style>
        .checkout-wrap * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .checkout-wrap {
            width: 100%;
            max-width: 1100px;
            margin: 20px auto;
            padding: 0 20px 80px;
            overflow-x: hidden;
        }

        .checkout-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin: 0 0 20px;
            padding-top: 16px;
        }

        .checkout-head h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.01em;
        }

        .checkout-head .subtitle {
            color: var(--ink-muted);
            font-size: 14px;
        }

        /* Progress steps */
        .checkout-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 32px;
            list-style: none;
            padding: 0;
        }

        .checkout-steps li {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
        }

        .checkout-steps li:not(:last-child)::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
            margin: 0 12px;
        }

        .checkout-steps .step-dot {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .checkout-steps .step-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
        }

        .checkout-steps li.is-pending .step-dot {
            background: var(--bg-card);
            border: 1.5px solid var(--line);
            color: var(--ink-muted);
        }

        .checkout-steps li.is-pending .step-label {
            color: var(--ink-muted);
        }

        .checkout-back {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: var(--ink-muted);
            text-decoration: none;
            margin-bottom: 16px;
        }

        .checkout-back:hover {
            color: var(--accent);
        }

        /* Grid layout: main + sticky summary */
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
            width: 100%;
            min-width: 0;
        }

        .checkout-main {
            min-width: 0;
        }

        @media (max-width:860px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }

        .checkout-section {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 16px;
            transition: border-color .2s;
        }

        .checkout-section h2 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .checkout-section h2 .h2-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkout-section h2 svg {
            width: 18px;
            height: 18px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            flex-shrink: 0;
        }

        .h2-action {
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
            cursor: pointer;
            text-decoration: none;
        }

        .h2-action:hover {
            text-decoration: underline;
        }

        /* Address — selectable card */
        .address-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .address-card:last-child {
            margin-bottom: 0;
        }

        .address-card:hover {
            border-color: color-mix(in srgb, var(--accent) 45%, var(--line));
        }

        .address-card:has(input:checked) {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 6%, var(--bg-card));
        }

        .address-card input[type=radio] {
            accent-color: var(--accent);
            margin-top: 3px;
            flex-shrink: 0;
        }

        .address-card .addr-label {
            font-weight: 600;
            font-size: 13px;
        }

        .address-card .addr-text {
            font-size: 12px;
            color: var(--ink-muted);
            margin-top: 2px;
            line-height: 1.5;
        }

        .address-card .addr-tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: var(--accent);
            color: #fff;
            padding: 2px 8px;
            border-radius: 999px;
            margin-top: 6px;
        }

        .addr-empty {
            font-size: 13px;
            color: var(--ink-muted);
            text-align: center;
            padding: 20px 0;
        }

        .addr-empty a {
            color: var(--accent);
            font-weight: 600;
        }

        .address-card.is-selected {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 6%, var(--bg-card));
            cursor: default;
        }

        /* Address modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: var(--radius);
            width: min(480px, 94vw);
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
        }

        .modal-head h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--ink-muted);
            padding: 0;
            line-height: 1;
        }

        .modal-close:hover {
            color: var(--ink);
        }

        .modal-body {
            overflow-y: auto;
            padding: 16px 24px 24px;
        }

        .pform-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .na-form-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .na-yn-group {
            display: flex;
            gap: 8px;
        }

        .na-yn-btn {
            flex: 1;
            padding: 10px 12px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            background: #fff;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-muted);
            cursor: pointer;
            transition: all .15s;
            font-family: 'Inter', sans-serif;
        }

        .na-yn-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .na-yn-btn.is-active {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(184, 134, 11, 0.08);
        }

        @media (max-width: 600px) {
            #newAddressModal {
                padding: 12px;
                align-items: flex-end;
            }

            #newAddressModal .modal-content {
                width: 100%;
                max-height: 94vh;
                border-radius: 16px 16px 0 0;
            }

            #newAddressModal .modal-head {
                padding: 16px;
            }

            #newAddressModal .modal-head h3 {
                font-size: 14px;
            }

            #newAddressModal .modal-body {
                padding: 14px 16px 18px;
            }

            #newAddressModal .pform-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            #newAddressModal #naMap {
                height: 180px;
            }

            #newAddressModal .na-form-actions {
                flex-direction: column;
                gap: 10px;
            }

            #newAddressModal .na-form-actions button {
                width: 100%;
            }
        }

        .modal-address-item {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .modal-address-item.is-selected {
            border-color: var(--accent);
        }

        .modal-add-addr {
            display: block;
            text-align: center;
            padding: 14px;
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            border: 1.5px dashed var(--line);
            border-radius: 10px;
            margin-top: 12px;
            transition: border-color .2s;
        }

        .modal-add-addr:hover {
            border-color: var(--accent);
        }

        /* New Address Modal Styles */
        #newAddressModal .form-group {
            display: flex;
            flex-direction: column;
        }

        #newAddressModal .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }

        #newAddressModal .form-group input,
        #newAddressModal .form-group select,
        #newAddressModal .form-group textarea {
            width: 100%;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: #fff;
            transition: border-color .15s;
            outline: none;
            box-sizing: border-box;
        }

        #newAddressModal .form-group input:focus,
        #newAddressModal .form-group select:focus,
        #newAddressModal .form-group textarea:focus {
            border-color: var(--accent);
        }

        /* Toast animation */
        @keyframes toastSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toast-animate {
            animation: toastSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Voucher selector */
        .voucher-selector {
            margin-bottom: 16px;
        }

        .voucher-current {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .voucher-current:hover {
            border-color: color-mix(in srgb, var(--accent) 45%, var(--line));
        }

        .voucher-current.is-active {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 8%, var(--bg-card));
        }

        .voucher-current-left {
            flex-shrink: 0;
        }

        .voucher-amount {
            background: linear-gradient(135deg, var(--accent-dark), var(--accent-light));
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 8px;
            white-space: nowrap;
        }

        .voucher-current-body {
            flex: 1;
            min-width: 0;
        }

        .voucher-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .voucher-desc {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Voucher modal list */
        .voucher-option {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .voucher-option:last-child {
            margin-bottom: 0;
        }

        .voucher-option:hover {
            border-color: color-mix(in srgb, var(--accent) 45%, var(--line));
        }

        .voucher-option.is-selected {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 6%, var(--bg-card));
        }

        .voucher-option-amount {
            background: linear-gradient(135deg, var(--accent-dark), var(--accent-light));
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 8px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .voucher-option-body {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .voucher-option-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .voucher-option-desc {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 2px;
        }

        .voucher-option-exp {
            font-size: 10px;
            color: var(--ink-muted);
            margin-top: 4px;
        }

        /* Products */
        .checkout-product {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--line);
        }

        .checkout-product:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .checkout-product img {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f0f0f0;
        }

        .checkout-product .cp-info {
            flex: 1;
            min-width: 0;
        }

        .checkout-product .cp-info .cp-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .checkout-product .cp-info .cp-variant {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 2px;
        }

        .checkout-product .cp-right {
            text-align: right;
            flex-shrink: 0;
        }

        .checkout-product .cp-price {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent);
            white-space: nowrap;
            display: block;
        }

        .checkout-product .cp-qty {
            font-size: 11px;
            color: var(--ink-muted);
            white-space: nowrap;
        }

        .checkout-items-count {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-muted);
            background: var(--line);
            padding: 2px 10px;
            border-radius: 999px;
        }

        /* Shipping / Payment — selectable option rows */
        .option-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .option-card:last-child {
            margin-bottom: 0;
        }

        .option-card:hover {
            border-color: color-mix(in srgb, var(--accent) 45%, var(--line));
        }

        .option-card:has(input:checked) {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 6%, var(--bg-card));
        }

        .option-card input[type=radio] {
            accent-color: var(--accent);
            flex-shrink: 0;
        }

        .option-card .opt-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: color-mix(in srgb, var(--accent) 12%, transparent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .option-card .opt-icon svg {
            width: 17px;
            height: 17px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .option-card .opt-body {
            flex: 1;
            min-width: 0;
        }

        .option-card .opt-name {
            font-size: 13px;
            font-weight: 600;
        }

        .option-card .opt-desc {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 1px;
        }

        .option-card .opt-price {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            margin-left: auto;
            white-space: nowrap;
        }

        .option-card .opt-badge {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--accent);
            background: color-mix(in srgb, var(--accent) 14%, transparent);
            padding: 2px 6px;
            border-radius: 5px;
            margin-left: 6px;
        }

        .option-card .opt-logos {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .option-card .opt-logos img {
            height: 20px;
            width: auto;
            display: block;
        }

        .option-card .opt-courier-logo {
            width: 46px;
            height: auto;
            max-height: 34px;
            object-fit: contain;
            flex-shrink: 0;
            display: block;
        }

        @media (max-width: 480px) {
            .option-card .opt-logos img {
                height: 16px;
            }

            .option-card .opt-logos {
                gap: 4px;
            }
        }

        /* Notes */
        .checkout-notes textarea {
            width: 100%;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            resize: vertical;
            min-height: 70px;
            transition: border-color .15s;
        }

        .checkout-notes textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .checkout-notes .char-count {
            font-size: 11px;
            color: var(--ink-muted);
            text-align: right;
            margin-top: 4px;
        }

        /* Sticky summary sidebar */
        .order-summary {
            position: sticky;
            top: 20px;
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 24px;
        }

        .order-summary h2 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .promo-row {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .promo-row input {
            flex: 1;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            transition: border-color .15s;
        }

        .promo-row input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .promo-row button {
            border: 1.5px solid var(--accent);
            background: transparent;
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            padding: 0 16px;
            border-radius: 8px;
            cursor: pointer;
            white-space: nowrap;
            transition: background .15s, color .15s;
        }

        .promo-row button:hover {
            background: var(--accent);
            color: #fff;
        }

        .promo-msg {
            font-size: 11px;
            margin: -14px 0 16px;
            display: none;
        }

        .promo-msg.show {
            display: block;
        }

        .promo-msg.ok {
            color: #2a8a4d;
        }

        .promo-msg.err {
            color: #c0392b;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--ink-muted);
            padding: 7px 0;
        }

        .summary-line.discount span:last-child {
            color: #2a8a4d;
            font-weight: 600;
        }

        .summary-line.total {
            border-top: 1px solid var(--line);
            margin-top: 8px;
            padding-top: 14px;
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
        }

        .summary-line.total span:last-child {
            color: var(--accent);
            font-size: 19px;
        }

        .summary-line.total .total-old {
            color: var(--ink-muted);
            font-size: 13px;
            font-weight: 500;
            text-decoration: line-through;
            margin-right: 8px;
        }

        .btn-buy {
            position: relative;
            overflow: hidden;

            width: 100%;
            margin-top: 18px;
            padding: 15px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: background .2s, transform .1s;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
        }

        /* Efek kilap yang bergerak */
        .btn-buy::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;

            background: linear-gradient(
                120deg,
                transparent,
                rgba(255, 255, 255, 0.45),
                transparent
            );

            transform: skewX(-20deg);
            animation: shine 2.5s infinite;
        }

        /* Animasi kilatan */
        @keyframes shine {
            0% {
                left: -100%;
            }

            45% {
                left: 120%;
            }

            100% {
                left: 120%;
            }
        }

        .btn-buy:hover {
            background: color-mix(in srgb, var(--accent) 82%, black);
        }

        .btn-buy:active {
            transform: scale(0.98);
        }
        .trust-badges {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid var(--line);
        }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11.5px;
            color: var(--ink-muted);
        }

        .trust-badge svg {
            width: 16px;
            height: 16px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            flex-shrink: 0;
        }

        @media (max-width:860px) {
            .order-summary {
                position: static;
                margin-top: 8px;
            }
        }

        /* Mobile */
        @media (max-width:600px) {
            .checkout-wrap {
                padding: 0 14px 60px;
                margin-top: -20px;
            }

            .checkout-head {
                padding-top: 12px;
                margin-bottom: 16px;
            }

            .checkout-head h1 {
                font-size: 21px;
            }

            .checkout-head .subtitle {
                font-size: 13px;
            }

            .checkout-steps {
                margin-bottom: 22px;
                gap: 0;
            }

            .checkout-steps li:not(:last-child)::after {
                margin: 0 6px;
            }

            .checkout-steps .step-dot {
                width: 22px;
                height: 22px;
                font-size: 11px;
            }

            .checkout-steps .step-label {
                display: none;
            }

            .checkout-grid {
                gap: 14px;
            }

            .checkout-section {
                padding: 16px;
                margin-bottom: 12px;
                border-radius: 12px;
            }

            .checkout-section h2 {
                font-size: 14px;
                margin-bottom: 12px;
                flex-wrap: wrap;
            }

            .address-card,
            .option-card {
                padding: 12px;
                gap: 10px;
            }

            .option-card .opt-icon {
                width: 30px;
                height: 30px;
            }

            .option-card .opt-price {
                white-space: nowrap;
                font-size: 12px;
            }

            .checkout-product {
                gap: 10px;
            }

            .checkout-product img,
            .img-placeholder {
                width: 48px;
                height: 48px;
            }

            .checkout-product .cp-name {
                font-size: 12.5px;
            }

            .order-summary {
                padding: 18px;
            }

            .promo-row {
                flex-wrap: wrap;
            }

            .promo-row input {
                min-width: 0;
                flex: 1 1 100%;
            }

            .promo-row button {
                flex: 1;
                padding: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="wrap">
        <div class="checkout-wrap">
            <div class="checkout-head">
                <div>
                    <h1>Checkout</h1>
                    <p class="subtitle">Konfirmasi pesanan Anda sebelum membeli.</p>
                </div>
            </div>

            <ul class="checkout-steps">
                <li><span class="step-dot">1</span><span class="step-label">Keranjang</span></li>
                <li><span class="step-dot">2</span><span class="step-label">Checkout</span></li>
                <li class="is-pending"><span class="step-dot">3</span><span class="step-label">Pembayaran</span></li>
                <li class="is-pending"><span class="step-dot">4</span><span class="step-label">Selesai</span></li>
            </ul>

            <div class="checkout-grid">
                <div class="checkout-main">

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('dashboard.keranjang') }}" class="checkout-back">&larr; Kembali ke Keranjang</a>

                    {{-- Alamat Pengiriman --}}
                    <div class="checkout-section">
                        <h2>
                            <span class="h2-left"><svg viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg> Alamat Pengiriman</span>
                            @if ($defaultAddress)
                                <button type="button" class="h2-action" onclick="openAddressModal()">Pilih alamat lain</button>
                            @else
                                <button type="button" class="h2-action" onclick="openNewAddressFormModal()">+ Tambah alamat baru</button>
                            @endif
                        </h2>
                        @if ($defaultAddress)
                            <div class="address-card is-selected">
                                <div>
                                    <div class="addr-label">{{ $defaultAddress->label ?? $defaultAddress->receiver_name }} —
                                        {{ $defaultAddress->phone }}</div>
                                    <div class="addr-text">{{ $defaultAddress->address }}, {{ $defaultAddress->city }},
                                        {{ $defaultAddress->province }} {{ $defaultAddress->postal_code }}</div>
                                    @if ($defaultAddress->is_default)
                                        <span class="addr-tag">Utama</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="addr-empty">Belum ada alamat pengiriman. </p>
                        @endif
                    </div>

                    {{-- Produk Dipilih --}}
                    <div class="checkout-section">
                        <h2>
                            <span class="h2-left"><svg viewBox="0 0 24 24">
                                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                    <line x1="3" y1="6" x2="21" y2="6" />
                                </svg> Produk</span>
                            <span class="checkout-items-count">{{ $items->sum('qty') }} item</span>
                        </h2>
                        @foreach ($items as $item)
                            <div class="checkout-product">
                                @if ($item->barang && $item->barang->produk && $item->barang->produk->fotoUtama)
                                    <img src="{{ env('BE_URL') . '/storage/' . $item->barang->produk->fotoUtama->foto }}"
                                        alt="">
                                @else
                                    <div class="img-placeholder"
                                        style="width:56px;height:56px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#111;color:rgba(255,255,255,0.12);font-size:10px;font-weight:700;text-transform:uppercase;flex-shrink:0;">
                                        {{ $item->barang->produk->brand->nama_brand ?? 'N/A' }}</div>
                                @endif
                                <div class="cp-info">
                                    <div class="cp-name">{{ $item->barang->produk->nama_produk }}</div>
                                    <div class="cp-variant">{{ $item->barang->nama_barang ?? '' }}</div>
                                </div>
                                <div class="cp-right">
                                    <span class="cp-price">
                                        @if ($item->hasDiscount)
                                            <span
                                                style="text-decoration:line-through;color:var(--ink-muted);font-size:12px;font-weight:400;margin-right:4px;">Rp{{ number_format($item->normalPrice, 0, ',', '.') }}</span>
                                            Rp{{ number_format($item->finalPrice, 0, ',', '.') }}
                                            <span
                                                style="display:inline-block;font-size:10px;font-weight:600;color:#fff;background:var(--accent);padding:1px 6px;border-radius:999px;margin-left:4px;vertical-align:middle;">-{{ $item->discountPercent }}%</span>
                                        @else
                                            Rp{{ number_format($item->finalPrice, 0, ',', '.') }}
                                        @endif
                                    </span>
                                    <span class="cp-qty">{{ $item->qty }}x</span>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    {{-- Opsi Pengiriman --}}
                    <div class="checkout-section">
                        <h2><span class="h2-left"><svg viewBox="0 0 24 24">
                                    <rect x="1" y="3" width="15" height="13" />
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                    <circle cx="5.5" cy="18.5" r="2.5" />
                                    <circle cx="18.5" cy="18.5" r="2.5" />
                                </svg> Opsi Pengiriman</span></h2>

                        @forelse($shippingOptions as $i => $opt)
                            @php
                                $val = $opt['courier_code'] . '-' . $opt['courier_service_code'];
                                $durationText = str_replace(['days', 'day'], ['hari', 'hari'], $opt['duration']);
                                $courierLogoPath = 'images/shipping/' . $opt['courier_code'] . '.png';
                                $hasCourierLogo = file_exists(public_path($courierLogoPath));
                                $estimationDays = 0;
                                if (preg_match_all('/\d+/', $opt['duration'] ?? '', $m)) $estimationDays = (int) max($m[0]);
                                if (!$estimationDays && !empty($opt['shipment_duration_range']) && preg_match_all('/\d+/', $opt['shipment_duration_range'], $m2)) $estimationDays = (int) max($m2[0]);
                            @endphp
                            <label class="option-card">
                                <input type="radio" name="shipping" value="{{ $val }}"
                                    data-price="{{ $opt['price'] }}" data-estimation="{{ $estimationDays }}" {{ $i === 0 ? 'checked' : '' }}
                                    onchange="updateSummary()">

                                @if ($hasCourierLogo)
                                    <img class="opt-courier-logo" src="{{ asset($courierLogoPath) }}"
                                        alt="{{ $opt['courier_name'] }}" title="{{ $opt['courier_name'] }}">
                                @else
                                    <span class="opt-icon">
                                        <span
                                            class="opt-courier-code">{{ strtoupper(substr($opt['courier_code'], 0, 2)) }}</span>
                                    </span>
                                @endif

                                <span class="opt-body">
                                    <span class="opt-name">
                                        {{ $opt['courier_name'] }} · {{ $opt['courier_service_name'] }}
                                        @if ($opt['shipping_type'] === 'freight')
                                            <span class="opt-badge">Kargo</span>
                                        @endif
                                        @if (!empty($opt['available_for_cash_on_delivery']))
                                            <span class="opt-badge opt-badge-cod">COD</span>
                                            @endif
                                    </span>
                                    <span class="opt-badge opt-badge-cod">{{ $durationText }} </span>
                                    {{-- <span class="opt-desc"> Estimasi {{ $durationText }} kerja</span> --}}
                                </span>

                                <span class="opt-price">Rp{{ number_format($opt['price'], 0, ',', '.') }}</span>
                            </label>
                        @empty
                            <p class="addr-empty">Ongkos kirim tidak tersedia untuk rute ini. Coba pilih alamat lain.</p>
                        @endforelse
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="checkout-section">
                        <h2><span class="h2-left"><svg viewBox="0 0 24 24">
                                    <rect x="1" y="4" width="22" height="16" rx="2" />
                                    <line x1="1" y1="10" x2="23" y2="10" />
                                </svg> Metode Pembayaran</span></h2>
                        <label class="option-card">
                            <input type="radio" name="payment" value="ewallet" checked>
                            <span class="opt-icon"><svg viewBox="0 0 24 24">
                                    <rect x="1" y="4" width="22" height="16" rx="2" />
                                    <line x1="1" y1="10" x2="23" y2="10" />
                                </svg></span>
                            <span class="opt-body">
                                <span class="opt-name">E-Wallet</span>
                                <span class="opt-desc">GoPay, DANA, QRIS, dan lainnya</span>
                            </span>
                            <span class="opt-logos">
                                <img src="{{ asset('images/payment/qris.png') }}" alt="QRIS" title="QRIS">
                                <img src="{{ asset('images/payment/gopay.png') }}" alt="GoPay" title="GoPay">
                                <img src="{{ asset('images/payment/dana.png') }}" alt="DANA" title="DANA">
                            </span>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="payment" value="bca">
                            <span class="opt-icon"><svg viewBox="0 0 24 24">
                                    <rect x="1" y="4" width="22" height="16" rx="2" />
                                    <line x1="1" y1="10" x2="23" y2="10" />
                                </svg></span>
                            <span class="opt-body">
                                <span class="opt-name">Transfer Bank BCA</span>
                                <span class="opt-desc">Konfirmasi manual pembayaran ke admin WA</span>
                            </span>
                            <span class="opt-logos">
                                <img src="{{ asset('images/payment/bca.png') }}" alt="BCA" title="BCA">
                            </span>
                        </label>
                    </div>

                    {{-- Catatan --}}
                    <div class="checkout-section checkout-notes">
                        <h2><span class="h2-left"><svg viewBox="0 0 24 24">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z" />
                                </svg> Catatan untuk Penjual</span></h2>
                        <textarea name="catatan" maxlength="200" placeholder="Contoh: tolong dibungkus rapi, ukuran sepatu pas ya (opsional)"
                            oninput="document.getElementById('charCount').textContent = this.value.length"></textarea>
                        <div class="char-count"><span id="charCount">0</span>/200</div>
                    </div>
                </div>

                {{-- Ringkasan Pesanan --}}
                <aside class="order-summary">
                    <h2 style="display: flex;justify-content: space-between">
                        <span>Ringkasan Pesanan</span>
                        <button type="button" class="h2-action" onclick="openVoucherModal()">Pilih voucher lain</button>
                    </h2>

                    <div class="voucher-selector">
                        <div class="voucher-current {{ $activeVoucher ? 'is-active' : '' }}" id="voucherCurrent" onclick="openVoucherModal()">
                            @if ($activeVoucher)
                                @php $av = $activeVoucher->voucher; @endphp
                                <div class="voucher-current-left" id="voucherAmountWrap">
                                    <div class="voucher-amount" id="voucherSelectedAmount">
                                        @if ($av->type === 'percent')
                                            {{ (int) $av->value }}%
                                        @elseif ($av->type === 'fixed')
                                            Rp{{ number_format($av->value, 0, ',', '.') }}
                                        @else
                                            Gratis Ongkir
                                        @endif
                                    </div>
                                </div>
                                <div class="voucher-current-body">
                                    <div class="voucher-name" id="voucherSelectedLabel">{{ $av->name ?? $av->code }}</div>
                                    <div class="voucher-desc" id="voucherSelectedDesc">{{ $av->description ?? $av->code }}</div>
                                </div>
                            @else
                                <div class="voucher-current-body" style="padding:0;">
                                    <div class="voucher-name" id="voucherSelectedLabel">Tidak ada voucher</div>
                                    <div class="voucher-desc" id="voucherSelectedDesc">Klik untuk memilih voucher</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="summary-line">
                        <span>Subtotal ({{ $items->sum('qty') }} produk)</span>
                        <span id="lineSubtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-line">
                        <span>Biaya Pengiriman</span>
                        <span id="lineShipping">Rp10.000</span>
                    </div>
                    <div class="summary-line discount" id="lineDiscountRow" style="display:none;">
                        <span>Diskon</span>
                        <span id="lineDiscount">-Rp0</span>
                    </div>
                    <div class="summary-line total">
                        <span>Total</span>
                        <span id="checkoutTotalWrap">
                            <span id="checkoutTotalOld" class="total-old" style="display:none;"></span>
                            <span id="checkoutTotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </span>
                    </div>

                    <button class="btn-buy" id="btnBeliSekarang" onclick="submitCheckout(this)">Beli Sekarang</button>

                    <div class="trust-badges">
                        <div class="trust-badge"><svg viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg> Pembayaran aman &amp; terenkripsi</div>
                        <div class="trust-badge"><svg viewBox="0 0 24 24">
                                <path d="M20 6L9 17l-5-5" />
                            </svg> Garansi 100% produk original</div>
                        <div class="trust-badge"><svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg> Estimasi pengiriman transparan</div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

{{-- Modal Pilih Alamat --}}
<div class="modal-overlay" id="addressModal">
    <div class="modal-content">
        <div class="modal-head">
            <h3>Pilih Alamat Pengiriman</h3>
            <button type="button" class="modal-close" onclick="closeAddressModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="addressListContainer">
                @forelse($allAddresses as $addr)
                    <a href="{{ route('checkout', array_merge(request()->query(), ['address_id' => $addr->id])) }}"
                        class="address-card modal-address-item {{ $addr->id === $defaultAddress?->id ? 'is-selected' : '' }}">
                        <div>
                            <div class="addr-label">{{ $addr->label ?? $addr->receiver_name }} — {{ $addr->phone }}
                            </div>
                            <div class="addr-text">{{ $addr->address }}, {{ $addr->city }}, {{ $addr->province }}
                                {{ $addr->postal_code }}</div>
                            @if ($addr->is_default)
                                <span class="addr-tag">Utama</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="addr-empty" id="noAddressText">Belum ada alamat.</p>
                @endforelse
            </div>
            <button type="button" class="modal-add-addr" onclick="openNewAddressFormModal()">+ Tambah alamat baru</button>
        </div>
    </div>
</div>

{{-- Modal Form Tambah Alamat Baru (Checkout) --}}
<div class="modal-overlay" id="newAddressModal" style="z-index:10000;">
    <div class="modal-content" style="width:min(560px,96vw);max-height:92vh;">
        <div class="modal-head">
            <h3>Tambah Alamat Baru</h3>
            <button type="button" class="modal-close" onclick="closeNewAddressModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 24px 24px;">
            <div id="newAddrError" style="display:none;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:8px;padding:10px 14px;font-size:13px;margin-bottom:16px;"></div>
            <div class="pform-grid">
                <div class="form-group">
                    <label for="na_label">Label Alamat <span style="color:var(--red);">*</span></label>
                    <input type="text" id="na_label" placeholder="Rumah / Kantor / Lainnya">
                </div>
                <div class="form-group">
                    <label for="na_receiver_name">Nama Penerima <span style="color:var(--red);">*</span></label>
                    <input type="text" id="na_receiver_name" placeholder="Nama lengkap">
                </div>
                <div class="form-group">
                    <label for="na_phone">Nomor Telepon <span style="color:var(--red);">*</span></label>
                    <input type="tel" id="na_phone" placeholder="08xx-xxxx-xxxx">
                </div>
                <div class="form-group">
                    <label for="na_province">Provinsi <span style="color:var(--red);">*</span></label>
                    <select id="na_province">
                        <option value="">Pilih Provinsi</option>
                        <option value="Aceh">Aceh</option>
                        <option value="Bali">Bali</option>
                        <option value="Banten">Banten</option>
                        <option value="Bengkulu">Bengkulu</option>
                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                        <option value="DKI Jakarta">DKI Jakarta</option>
                        <option value="Gorontalo">Gorontalo</option>
                        <option value="Jambi">Jambi</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="Jawa Timur">Jawa Timur</option>
                        <option value="Kalimantan Barat">Kalimantan Barat</option>
                        <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                        <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                        <option value="Kalimantan Timur">Kalimantan Timur</option>
                        <option value="Kalimantan Utara">Kalimantan Utara</option>
                        <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                        <option value="Kepulauan Riau">Kepulauan Riau</option>
                        <option value="Lampung">Lampung</option>
                        <option value="Maluku">Maluku</option>
                        <option value="Maluku Utara">Maluku Utara</option>
                        <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                        <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                        <option value="Papua">Papua</option>
                        <option value="Papua Barat">Papua Barat</option>
                        <option value="Papua Barat Daya">Papua Barat Daya</option>
                        <option value="Papua Pegunungan">Papua Pegunungan</option>
                        <option value="Papua Selatan">Papua Selatan</option>
                        <option value="Papua Tengah">Papua Tengah</option>
                        <option value="Riau">Riau</option>
                        <option value="Sulawesi Barat">Sulawesi Barat</option>
                        <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                        <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                        <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                        <option value="Sulawesi Utara">Sulawesi Utara</option>
                        <option value="Sumatera Barat">Sumatera Barat</option>
                        <option value="Sumatera Selatan">Sumatera Selatan</option>
                        <option value="Sumatera Utara">Sumatera Utara</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="na_city">Kota/Kabupaten <span style="color:var(--red);">*</span></label>
                    <select id="na_city">
                        <option value="">Pilih/ketik kota</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="na_district">Kecamatan <span style="color:var(--red);">*</span></label>
                    <select id="na_district">
                        <option value="">Pilih/ketik kecamatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="na_postal_code">Kode Pos <span style="color:var(--red);">*</span></label>
                    <input type="text" id="na_postal_code" placeholder="Contoh: 12190">
                </div>
                <div class="form-group">
                    <label>Jadikan alamat utama?</label>
                    <div class="na-yn-group" id="naDefaultGroup">
                        <button type="button" class="na-yn-btn is-active" data-value="0" onclick="setNaDefault(0)">Tidak</button>
                        <button type="button" class="na-yn-btn" data-value="1" onclick="setNaDefault(1)">Ya</button>
                    </div>
                    <input type="hidden" id="na_is_default" value="0">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label for="na_catatan">Catatan <span style="color:var(--ink-muted);font-weight:400;">(opsional)</span></label>
                <textarea id="na_catatan" style="width:100%;height:54px;border:1.5px solid var(--line);border-radius:10px;padding:10px 12px;font-size:13px;font-family:'Inter',sans-serif;resize:vertical;" placeholder="Contoh: dekat warung Bu Indah, pagar hijau..."></textarea>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label for="na_address">Alamat Lengkap <span style="color:var(--red);">*</span></label>
                <textarea id="na_address" style="width:100%;height:80px;border:1.5px solid var(--line);border-radius:10px;padding:10px 12px;font-size:13px;font-family:'Inter',sans-serif;resize:vertical;" placeholder="Jl. Nama Jalan No.xx, RT/RW, Kelurahan, dsb."></textarea>
            </div>
            <div style="font-size:11px;color:var(--ink-muted);margin-top:14px;line-height:1.6;">Isi wilayah secara bertahap: provinsi &rarr; kota &rarr; kecamatan &rarr; kode pos. Pastikan titik di peta <strong>sama persis</strong> dengan alamat yang Anda ketik.</div>
            <div class="form-group" style="margin-top:10px;">
                <label>Peta Lokasi <span style="color:var(--red);">*</span></label>
                <div style="border:1px solid var(--line);border-radius:10px;overflow:hidden;">
                    <div id="naMap" style="height:220px;width:100%;z-index:0;"></div>
                </div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:6px;" id="naGeoHint">Klik peta atau geser marker untuk menentukan titik lokasi. Wajib diisi. Pastikan titiknya sama persis dengan alamat yang Anda ketik di form.</div>
            </div>
            <input type="hidden" id="na_area_id">
            <input type="hidden" id="na_lat">
            <input type="hidden" id="na_lng">
            <div class="na-form-actions">
                <button type="button" id="naSaveBtn" onclick="submitNewAddress()" style="flex:1;padding:13px;background:var(--accent);color:#fff;border:none;border-radius:var(--radius-sm);font-size:14px;font-weight:700;cursor:pointer;transition:background .2s;">Simpan Alamat</button>
                <button type="button" onclick="closeNewAddressModal()" style="padding:13px 20px;background:none;border:1.5px solid var(--line);border-radius:var(--radius-sm);font-size:13px;cursor:pointer;">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="checkoutToast" style="position:fixed;bottom:28px;right:28px;z-index:99999;display:none;min-width:280px;max-width:380px;">
    <div style="background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.18);padding:16px 20px;display:flex;align-items:center;gap:14px;border-left:4px solid var(--accent);">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" style="flex-shrink:0;"><path d="M20 6L9 17l-5-5"/></svg>
        <div>
            <div style="font-size:14px;font-weight:600;color:#111;" id="toastTitle">Berhasil</div>
            <div style="font-size:12px;color:#6b7280;margin-top:2px;" id="toastMsg"></div>
        </div>
    </div>
</div>

{{-- Modal Pilih Voucher --}}
<div class="modal-overlay" id="voucherModal">
    <div class="modal-content">
        <div class="modal-head">
            <h3>Pilih Voucher</h3>
            <button type="button" class="modal-close" onclick="closeVoucherModal()">&times;</button>
        </div>
        <div class="modal-body">
            @forelse($vouchers as $uv)
                @php $v = $uv->voucher; @endphp
                <div class="voucher-option {{ $uv->id === $activeVoucher?->id ? 'is-selected' : '' }}"
                    data-uv-id="{{ $uv->id }}"
                    onclick="selectVoucher({{ $uv->id }}, '{{ $v->type }}', {{ $v->value }}, {{ $v->maximum_discount ?? 0 }})">
                    <span class="voucher-option-amount"
                        id="voucherName_{{ $uv->id }}"
                        data-name="{{ $v->name ?? $v->code }}"
                        data-desc="{{ $v->description ?? $v->code }}">
                        @if ($v->type === 'percent')
                            {{ (int) $v->value }}%
                        @elseif ($v->type === 'fixed')
                            Rp{{ number_format($v->value, 0, ',', '.') }}
                        @else
                            Gratis Ongkir
                        @endif
                    </span>
                    <span class="voucher-option-body">
                        <span class="voucher-option-name">{{ $v->name ?? $v->code }}</span>
                        <span class="voucher-option-desc">{{ $v->description ?? $v->code }}</span>
                        <span class="voucher-option-exp">Berlaku hingga {{ $v->end_at ? \Carbon\Carbon::parse($v->end_at)->format('d M Y') : '-' }}</span>
                    </span>
                </div>
            @empty
                <p class="addr-empty">Belum ada voucher tersedia.</p>
            @endforelse
            <a href="{{ route('dashboard.voucher') }}" class="modal-add-addr">+ Cari voucher lain</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const SUBTOTAL = {{ (float) $subtotal }};
        const CHECKOUT_SOURCE = @json($checkoutSource);
        let CHECKOUT_ADDRESS_ID = {{ $defaultAddress?->id ?? 'null' }};
        let discount = 0;

        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        async function submitCheckout(btn) {
            const addressId = CHECKOUT_ADDRESS_ID;
            if (!addressId) {
                Swal.fire({ icon: 'warning', title: 'Alamat belum dipilih', text: 'Silakan tambahkan alamat pengiriman terlebih dahulu.' });
                return;
            }

            const shippingInput = document.querySelector('input[name="shipping"]:checked');
            if (!shippingInput) {
                Swal.fire({ icon: 'warning', title: 'Ongkir belum tersedia', text: 'Silakan pilih opsi pengiriman yang tersedia.' });
                return;
            }

            const paymentInput = document.querySelector('input[name="payment"]:checked');
            const catatan = document.querySelector('textarea[name="catatan"]')?.value || '';

            const selectedVoucher = document.querySelector('.voucher-option.is-selected');
            const voucherId = selectedVoucher ? selectedVoucher.dataset.uvId : null;

            const payload = {
                source: CHECKOUT_SOURCE,
                address_id: addressId,
                shipping_code: shippingInput.value,
                shipping_price: parseFloat(shippingInput.dataset.price),
                estimation_days: parseInt(shippingInput.dataset.estimation || 0) || null,
                payment_method: paymentInput ? paymentInput.value : 'ewallet',
                catatan: catatan,
                voucher_id: voucherId,
            };

            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const res = await fetch('{{ route('checkout.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (res.ok && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                btn.disabled = false;
                btn.textContent = 'Beli Sekarang';

                if (data.pending_payments && data.pending_payments.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selesaikan Pembayaran Dahulu',
                        html: '<p style="font-size:14px;color:#374151;margin:0;">Anda masih memiliki ' + data.pending_payments.length + ' pembayaran yang belum selesai. Selesaikan terlebih dahulu sebelum membuat pesanan baru.</p>',
                        showCancelButton: true,
                        confirmButtonText: 'Bayar Sekarang',
                        cancelButtonText: 'Nanti'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('dashboard.pembayaran') }}';
                        }
                    });
                    return;
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Checkout gagal',
                    text: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            } catch (err) {
                btn.disabled = false;
                btn.textContent = 'Beli Sekarang';
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Gagal terhubung ke server.',
                    confirmButtonText: 'OK'
                });
            }
        }

        function formatRupiah(n) {
            return 'Rp' + Math.round(n).toLocaleString('id-ID');
        }

        function updateSummary() {
            const shippingInput = document.querySelector('input[name="shipping"]:checked');
            const shippingCost = shippingInput ? parseFloat(shippingInput.dataset.price) : 0;

            document.getElementById('lineShipping').textContent = formatRupiah(shippingCost);
            document.getElementById('lineSubtotal').textContent = formatRupiah(SUBTOTAL);

            const rawTotal = SUBTOTAL + shippingCost;
            const total = Math.max(rawTotal - discount, 0);

            const oldEl = document.getElementById('checkoutTotalOld');
            if (discount > 0 && total < rawTotal) {
                oldEl.style.display = 'inline';
                oldEl.textContent = formatRupiah(rawTotal);
            } else {
                oldEl.style.display = 'none';
            }
            document.getElementById('checkoutTotal').textContent = formatRupiah(total);
        }

        function selectVoucher(uvId, type, value, maxDiscount) {
            document.querySelectorAll('.voucher-option').forEach(function(opt) {
                opt.classList.toggle('is-selected', opt.dataset.uvId == uvId);
            });
            const voucherCurrent = document.getElementById('voucherCurrent');

            if (!uvId) {
                discount = 0;
                document.getElementById('lineDiscountRow').style.display = 'none';
                document.getElementById('voucherSelectedLabel').textContent = 'Tidak ada voucher';
                document.getElementById('voucherSelectedDesc').textContent = 'Klik untuk memilih voucher';
                const amtWrap = document.getElementById('voucherAmountWrap');
                if (amtWrap) amtWrap.style.display = 'none';
                if (voucherCurrent) voucherCurrent.classList.remove('is-active');
            } else {
                const shippingInput = document.querySelector('input[name="shipping"]:checked');
                const shippingCost = shippingInput ? parseFloat(shippingInput.dataset.price) : 0;
                value = parseFloat(value);
                maxDiscount = maxDiscount ? parseFloat(maxDiscount) : 0;

                if (type === 'percent') {
                    discount = SUBTOTAL * value / 100;
                } else if (type === 'fixed') {
                    discount = value;
                } else {
                    discount = shippingCost;
                }
                if (maxDiscount > 0) discount = Math.min(discount, maxDiscount);
                discount = Math.min(discount, SUBTOTAL + shippingCost);

                document.getElementById('lineDiscountRow').style.display = 'flex';
                document.getElementById('lineDiscount').textContent = '-' + formatRupiah(discount);
                const nameEl = document.getElementById('voucherName_' + uvId);
                document.getElementById('voucherSelectedLabel').textContent = nameEl.dataset.name;
                document.getElementById('voucherSelectedDesc').textContent = nameEl.dataset.desc;
                const amtWrap = document.getElementById('voucherAmountWrap');
                if (amtWrap) amtWrap.style.display = 'flex';
                document.getElementById('voucherSelectedAmount').textContent = type === 'percent'
                    ? Math.round(value) + '%'
                    : (type === 'fixed' ? 'Rp' + Math.round(value).toLocaleString('id-ID') : 'Gratis Ongkir');
                if (voucherCurrent) voucherCurrent.classList.add('is-active');
            }
            updateSummary();
            closeVoucherModal();
        }

        function openVoucherModal() { document.getElementById('voucherModal').classList.add('show'); }
        function closeVoucherModal() { document.getElementById('voucherModal').classList.remove('show'); }
        document.getElementById('voucherModal')?.addEventListener('click', function(e) { if (e.target === this) closeVoucherModal(); });

        @if ($activeVoucher)
            selectVoucher({{ $activeVoucher->id }}, '{{ $activeVoucher->voucher->type }}', {{ $activeVoucher->voucher->value }}, {{ $activeVoucher->voucher->maximum_discount ?? 0 }});
        @else
            selectVoucher(0);
        @endif

        updateSummary();

        function openAddressModal() {
            document.getElementById('addressModal').classList.add('show');
        }

        function closeAddressModal() {
            document.getElementById('addressModal').classList.remove('show');
        }
        document.getElementById('addressModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeAddressModal();
        });

        // =============================================
        // NEW ADDRESS FORM MODAL (from checkout)
        // =============================================
        var naMap = null, naMarker = null;
        var naStoreUrl = '{{ route("dashboard.alamat.store.ajax") }}';
        var naAreaUrl = '{{ route("dashboard.alamat.area") }}';
        var naWilayahProvincesUrl = '{{ route("dashboard.wilayah.provinces") }}';
        var naWilayahRegenciesBaseUrl = '{{ url("/dashboard/wilayah/regencies") }}';
        var naWilayahDistrictsBaseUrl = '{{ url("/dashboard/wilayah/districts") }}';
        var naProvinceCodeByName = {};
        var naCityCodeByName = {};
        var naCitiesLoadedFor = '';
        var naDistrictsLoadedFor = '';

        function openNewAddressFormModal() {
            closeAddressModal();
            // Reset form
            ['na_label','na_receiver_name','na_phone','na_catatan','na_address'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.value = '';
            });
            setNaDefault(0);
            document.getElementById('na_area_id').value = '';
            document.getElementById('na_lat').value = '';
            document.getElementById('na_lng').value = '';
            naResetRegionSelects();
            document.getElementById('naGeoHint').textContent = 'Klik peta atau geser marker untuk menentukan titik lokasi. Wajib diisi. Pastikan titiknya sama persis dengan alamat yang Anda ketik di form.';
            var errEl = document.getElementById('newAddrError');
            if (errEl) { errEl.style.display = 'none'; errEl.textContent = ''; }
            if (naMarker && naMap) { naMap.removeLayer(naMarker); naMarker = null; }

            document.getElementById('newAddressModal').classList.add('show');
            setTimeout(function() {
                if (!naMap) {
                    naMap = L.map('naMap', { scrollWheelZoom: false }).setView([-6.5950, 106.8166], 13);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(naMap);
                    naMap.on('click', function(e) {
                        // Pin hanya mengisi koordinat; isian form TIDAK ditimpa.
                        naSetMarker(e.latlng.lat, e.latlng.lng);
                    });
                }
                naMap.invalidateSize();
            }, 320);
        }

        function closeNewAddressModal() {
            document.getElementById('newAddressModal').classList.remove('show');
        }

        function setNaDefault(val) {
            var v = val ? 1 : 0;
            document.getElementById('na_is_default').value = v;
            document.querySelectorAll('#naDefaultGroup .na-yn-btn').forEach(function(b) {
                b.classList.toggle('is-active', parseInt(b.dataset.value, 10) === v);
            });
        }

        document.getElementById('newAddressModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeNewAddressModal();
        });

        function naSetMarker(lat, lng) {
            if (!naMap) return;
            if (naMarker) {
                naMarker.setLatLng([lat, lng]);
            } else {
                naMarker = L.marker([lat, lng], { draggable: true }).addTo(naMap);
                naMarker.on('dragend', function() {
                    var p = naMarker.getLatLng();
                    naSetMarker(p.lat, p.lng);
                });
            }
            naMap.setView([lat, lng], 16);
            document.getElementById('na_lat').value = lat;
            document.getElementById('na_lng').value = lng;
            document.getElementById('naGeoHint').textContent = 'Titik lokasi sudah ditentukan (' + Number(lat).toFixed(5) + ', ' + Number(lng).toFixed(5) + '). Pastikan posisinya sama persis dengan alamat yang Anda ketik di form. Geser marker untuk mengubah.';
        }

        // =============================================
        // Wilayah bertahap via proxy wilayah.id (mirror form dashboard)
        // =============================================
        function naHasSelect2() {
            return (typeof window.jQuery !== 'undefined') && !!window.jQuery.fn.select2;
        }

        function naSortAbjad(a, b) {
            return String(a).localeCompare(String(b), 'id');
        }

        function naModalContent() {
            return window.jQuery('#newAddressModal .modal-content');
        }

        // Tambah opsi tanpa menghapus yang sudah ada (termasuk ketikan user).
        function naAppendOptions(selId, names) {
            var sel = document.getElementById(selId);
            var existing = {};
            for (var i = 0; i < sel.options.length; i++) {
                existing[sel.options[i].value] = true;
            }
            names.slice().sort(naSortAbjad).forEach(function(name) {
                if (!existing[name]) {
                    var opt = document.createElement('option');
                    opt.value = name;
                    opt.textContent = name;
                    sel.appendChild(opt);
                }
            });
            naRefreshOpenDropdown(selId);
        }

        // Refresh dropdown yang sedang terbuka agar opsi hasil fetch langsung tampil.
        function naRefreshOpenDropdown(selId) {
            if (!naHasSelect2()) return;
            var $s = window.jQuery('#' + selId);
            var inst = $s.data('select2');
            if (inst && inst.isOpen()) {
                $s.select2('close');
                $s.select2('open');
            }
        }

        function naCitySelect2Opts() {
            return {
                placeholder: 'Pilih/ketik kota',
                tags: true,
                width: '100%',
                dropdownParent: naModalContent(),
                language: {
                    noResults: function() { return 'Tidak ditemukan — tekan Enter untuk memakai ketikan Anda'; },
                    searching: function() { return 'Mencari...'; }
                }
            };
        }

        function naDistrictSelect2Opts() {
            return {
                placeholder: 'Pilih/ketik kecamatan',
                tags: true,
                width: '100%',
                dropdownParent: naModalContent(),
                language: {
                    noResults: function() { return 'Tidak ditemukan — tekan Enter untuk memakai ketikan Anda'; },
                    searching: function() { return 'Mencari...'; }
                }
            };
        }

        var naSelect2Ready = {};

        function naInitSelect2() {
            if (!naHasSelect2() || naInitSelect2.done) return;
            naInitSelect2.done = true;
            window.jQuery('#na_province').select2({
                placeholder: 'Pilih Provinsi',
                width: '100%',
                dropdownParent: naModalContent()
            });
            naSelect2Ready['na_province'] = true;
            window.jQuery('#na_city').select2(naCitySelect2Opts());
            naSelect2Ready['na_city'] = true;
            window.jQuery('#na_district').select2(naDistrictSelect2Opts());
            naSelect2Ready['na_district'] = true;
        }

        function naQuietSyncSelect(selId, optsFn) {
            if (!naHasSelect2() || !naSelect2Ready[selId]) return;
            var $s = window.jQuery('#' + selId);
            $s.select2('destroy');
            $s.select2(optsFn());
        }

        // Reset form tiap modal dibuka (belum ada interaksi user, aman me-reset).
        function naResetRegionSelects() {
            var prov = document.getElementById('na_province');
            var city = document.getElementById('na_city');
            var dist = document.getElementById('na_district');
            prov.value = '';
            city.innerHTML = '<option value="">Pilih/ketik kota</option>';
            dist.innerHTML = '<option value="">Pilih/ketik kecamatan</option>';
            document.getElementById('na_postal_code').value = '';
            naCitiesLoadedFor = '';
            naDistrictsLoadedFor = '';
            naQuietSyncSelect('na_province', function() { return { placeholder: 'Pilih Provinsi', width: '100%', dropdownParent: naModalContent() }; });
            naQuietSyncSelect('na_city', naCitySelect2Opts);
            naQuietSyncSelect('na_district', naDistrictSelect2Opts);
        }

        function naResetAndResolveArea() {
            document.getElementById('na_area_id').value = '';
            naResolveArea();
        }

        var naResolveTimer = null;
        function naResolveAreaDebounced() {
            document.getElementById('na_area_id').value = '';
            if (naResolveTimer) clearTimeout(naResolveTimer);
            naResolveTimer = setTimeout(naResolveArea, 600);
        }

        function naResolveArea() {
            var district = document.getElementById('na_district').value;
            var city = document.getElementById('na_city').value;
            var province = document.getElementById('na_province').value;
            var postal = document.getElementById('na_postal_code').value;
            if (!city && !district && !province) return;
            fetch(naAreaUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ district: district, city: city, province: province, postal_code: postal })
            }).then(function(r) { return r.json(); }).then(function(res) {
                document.getElementById('na_area_id').value = res.area_id || '';
            }).catch(function() {
                document.getElementById('na_area_id').value = '';
            });
        }

        // Flag agar sinkronisasi tampilan Select2 tidak memicu handler change (anti rekursi).
        var naSuppressRegionChange = false;

        // Set value select + sinkronkan tampilan Select2 TANPA menjalankan handler.
        function naSetSelectValueQuiet(selId, value) {
            var sel = document.getElementById(selId);
            if (!sel) return;
            sel.value = value;
            if (naHasSelect2()) {
                naSuppressRegionChange = true;
                try {
                    window.jQuery('#' + selId).trigger('change');
                } finally {
                    naSuppressRegionChange = false;
                }
            }
        }

        // Aturan reset (hanya saat value DIUBAH user, bukan saat diklik/dibuka):
        // - Provinsi diubah  -> kota, kecamatan, kode pos dihapus nilainya.
        // - Kota diubah      -> provinsi stay, kecamatan + kode pos dihapus.
        // - Kecamatan diubah -> provinsi + kota stay, kode pos dihapus.
        // - Kode pos diubah  -> semua induk stay.
        function naHandleProvinceChange(value) {
            if (naSuppressRegionChange) return;
            var hasProvince = !!value;
            document.getElementById('na_area_id').value = '';
            naCitiesLoadedFor = '';
            naDistrictsLoadedFor = '';
            naCityCodeByName = {};
            if (hasProvince) {
                naSetSelectValueQuiet('na_city', '');
                naSetSelectValueQuiet('na_district', '');
                document.getElementById('na_postal_code').value = '';
                naEnsureCities();
            } else {
                naSetSelectValueQuiet('na_city', '');
                naSetSelectValueQuiet('na_district', '');
                document.getElementById('na_postal_code').value = '';
            }
            naResolveArea();
        }

        function naHandleCityChange(value) {
            if (naSuppressRegionChange) return;
            var hasCity = !!value;
            document.getElementById('na_area_id').value = '';
            naDistrictsLoadedFor = '';
            if (hasCity) {
                naSetSelectValueQuiet('na_district', '');
                document.getElementById('na_postal_code').value = '';
                naEnsureDistricts();
            } else {
                naSetSelectValueQuiet('na_district', '');
                document.getElementById('na_postal_code').value = '';
            }
            naResolveArea();
        }

        function naHandleDistrictChange() {
            if (naSuppressRegionChange) return;
            document.getElementById('na_area_id').value = '';
            document.getElementById('na_postal_code').value = '';
            naResolveArea();
        }

        function naBindRegionField(fid, isText) {
            var el = document.getElementById(fid);
            if (!el) return;
            if (window.jQuery) {
                window.jQuery(el).on('change', function() {
                    if (fid === 'na_province') naHandleProvinceChange(this.value);
                    else if (fid === 'na_city') naHandleCityChange(this.value);
                    else if (fid === 'na_district') naHandleDistrictChange(this.value);
                    else naResetAndResolveArea();
                });
                if (isText) {
                    window.jQuery(el).on('input', function() { naResolveAreaDebounced(); });
                }
            } else {
                el.addEventListener('change', function() {
                    if (fid === 'na_province') naHandleProvinceChange(this.value);
                    else if (fid === 'na_city') naHandleCityChange(this.value);
                    else if (fid === 'na_district') naHandleDistrictChange(this.value);
                    else naResetAndResolveArea();
                });
                if (isText) el.addEventListener('input', naResolveAreaDebounced);
            }
        }

        // Bangun peta nama provinsi -> kode (untuk fetch kota).
        // Tidak menyentuh opsi/nilai select sama sekali.
        function naEnsureProvinceCodes(cb) {
            if (Object.keys(naProvinceCodeByName).length) { if (cb) cb(); return; }
            fetch(naWilayahProvincesUrl)
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    ((res && res.data) || []).forEach(function(row) {
                        naProvinceCodeByName[row.name] = row.code;
                    });
                    if (cb) cb();
                })
                .catch(function() { if (cb) cb(); });
        }

        function naEnsureCities() {
            var prov = document.getElementById('na_province').value;
            if (!prov || naCitiesLoadedFor === prov) return;
            naEnsureProvinceCodes(function() {
                var code = naProvinceCodeByName[prov];
                if (!code) return;
                fetch(naWilayahRegenciesBaseUrl + '/' + encodeURIComponent(code))
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        var rows = (res && res.data) || [];
                        naCityCodeByName = {};
                        var names = rows.map(function(row) {
                            naCityCodeByName[row.name] = row.code;
                            return row.name;
                        });
                        naAppendOptions('na_city', names);
                        naCitiesLoadedFor = prov;
                    })
                    .catch(function() {});
            });
        }

        function naEnsureDistricts() {
            var city = document.getElementById('na_city').value;
            if (!city || naDistrictsLoadedFor === city) return;
            var code = naCityCodeByName[city];
            if (!code) return;
            fetch(naWilayahDistrictsBaseUrl + '/' + encodeURIComponent(code))
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    var rows = (res && res.data) || [];
                    naAppendOptions('na_district', rows.map(function(row) { return row.name; }));
                    naDistrictsLoadedFor = city;
                })
                .catch(function() {});
        }

        naBindRegionField('na_province', false);
        naBindRegionField('na_city', false);
        naBindRegionField('na_district', false);
        naBindRegionField('na_postal_code', true);
        naBindRegionField('na_address', true);

        if (naHasSelect2()) {
            window.jQuery('#na_city').on('select2:open', function() { naEnsureCities(); });
            window.jQuery('#na_district').on('select2:open', function() { naEnsureDistricts(); });
        } else {
            document.getElementById('na_city').addEventListener('focus', function() { naEnsureCities(); });
            document.getElementById('na_district').addEventListener('focus', function() { naEnsureDistricts(); });
        }

        naInitSelect2();

        async function submitNewAddress() {
            var lat = document.getElementById('na_lat').value;
            var lng = document.getElementById('na_lng').value;

            if (!lat || !lng) {
                Swal.fire({ icon: 'warning', title: 'Pilih Titik Lokasi', text: 'Silakan klik peta untuk menentukan titik lokasi sebelum menyimpan.' });
                return;
            }

            var payload = {
                label: document.getElementById('na_label').value.trim(),
                receiver_name: document.getElementById('na_receiver_name').value.trim(),
                phone: document.getElementById('na_phone').value.trim(),
                province: document.getElementById('na_province').value,
                city: document.getElementById('na_city').value.trim(),
                district: document.getElementById('na_district').value.trim(),
                postal_code: document.getElementById('na_postal_code').value.trim(),
                address: document.getElementById('na_address').value.trim(),
                latitude: parseFloat(lat),
                longitude: parseFloat(lng),
                catatan: document.getElementById('na_catatan').value.trim(),
                area_id: document.getElementById('na_area_id').value,
                is_default: parseInt(document.getElementById('na_is_default').value || 0, 10) ? 1 : 0,
            };

            var errEl = document.getElementById('newAddrError');
            errEl.style.display = 'none';

            var saveBtn = document.getElementById('naSaveBtn');
            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';

            try {
                var res = await fetch(naStoreUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });
                var data = await res.json();

                saveBtn.disabled = false;
                saveBtn.textContent = 'Simpan Alamat';

                if (!res.ok || !data.success) {
                    errEl.textContent = data.message || 'Terjadi kesalahan. Periksa kembali isian form.';
                    errEl.style.display = 'block';
                    return;
                }

                // Sukses — simpan data toast & redirect ke checkout dengan address_id baru
                var savedAddr = data.address || {};
                sessionStorage.setItem('checkout_address_toast', JSON.stringify({
                    title: 'Alamat Berhasil Ditambahkan',
                    msg: (savedAddr.label || savedAddr.receiver_name || payload.receiver_name) + ' (' + (savedAddr.city || payload.city) + ') telah dipilih.'
                }));

                if (!savedAddr.id) {
                    errEl.textContent = 'Alamat tersimpan, tetapi ID alamat tidak kembali dari server.';
                    errEl.style.display = 'block';
                    return;
                }

                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('address_id', savedAddr.id);
                window.location.href = currentUrl.toString();

            } catch (err) {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Simpan Alamat';
                errEl.textContent = 'Gagal terhubung ke server. Coba lagi.';
                errEl.style.display = 'block';
            }
        }

        function showCheckoutToast(title, msg) {
            var toast = document.getElementById('checkoutToast');
            if (!toast) return;
            document.getElementById('toastTitle').textContent = title;
            document.getElementById('toastMsg').textContent = msg;
            toast.style.display = 'block';
            toast.classList.remove('toast-animate');
            void toast.offsetWidth; // trigger reflow
            toast.classList.add('toast-animate');
            clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(function() {
                toast.style.display = 'none';
            }, 4500);
        }

        // Cek apakah ada notifikasi toast alamat baru dari sessionStorage
        try {
            var savedToast = sessionStorage.getItem('checkout_address_toast');
            if (savedToast) {
                sessionStorage.removeItem('checkout_address_toast');
                var tData = JSON.parse(savedToast);
                setTimeout(function() {
                    showCheckoutToast(tData.title, tData.msg);
                }, 200);
            }
        } catch(e) {}
    </script>
@endpush
