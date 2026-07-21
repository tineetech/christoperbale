@extends('layouts.app')

@section('title', $title ?? 'Dashboard — CHRISBALE')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* ============================================
       DASHBOARD STYLES — CHRISBALE
       ============================================ */

        /* Layout */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 28px;
            max-width: 100%;
        }

        .dashboard-main,
        .dash-panel,
        .dash-panel.active {
            max-width: 100%;
        }

        .dashboard-layout * {
            font-family: 'Inter', sans-serif;
        }

        /* ---- SIDEBAR ---- */
        .dashboard-sidebar {
            position: sticky;
            top: 168px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Profile Card */
        .dash-profile-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 24px 20px;
            text-align: center;
        }

        .dash-avatar {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 14px;
        }

        .dash-avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--accent);
        }

        .dash-avatar-initials {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            border: 2.5px solid var(--accent);
        }

        .dash-avatar-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-card);
        }

        .dash-profile-info h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
            font-family: 'Inter', sans-serif;
        }

        .dash-profile-info p {
            font-size: 12px;
            color: var(--ink-muted);
            margin: 4px 0 0;
        }

        .dash-member-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(184, 134, 11, 0.1);
            border: 1px solid rgba(184, 134, 11, 0.25);
            color: var(--accent);
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.04em;
            margin-top: 6px;
        }

        /* Sidebar Nav */
        .dash-nav {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .dash-nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 13px 18px;
            font-size: 13.5px;
            font-weight: 400;
            color: var(--ink-soft);
            border-bottom: 1px solid var(--line-soft);
            transition: background 0.18s, color 0.18s;
            cursor: pointer;
            position: relative;
            text-decoration: none;
        }

        .dash-nav-item:last-child {
            border-bottom: none;
        }

        .dash-nav-item svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            flex-shrink: 0;
        }

        .dash-nav-item:hover {
            background: var(--bg);
            color: var(--ink);
        }

        .dash-nav-item.active {
            background: rgba(184, 134, 11, 0.07);
            color: var(--accent);
            font-weight: 500;
        }

        .dash-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        .dash-nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: #fff;
            font-size: 9.5px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        .dash-nav-divider {
            height: 1px;
            background: var(--line);
            margin: 4px 0;
        }

        .dash-nav-toggle {
            display: none;
        }

        .dash-nav-item--danger {
            color: var(--red);
        }

        .dash-nav-item--danger:hover {
            background: rgba(192, 57, 43, 0.05);
            color: var(--red);
        }

        /* ---- MAIN CONTENT ---- */
        .dashboard-main {
            min-width: 0;
        }

        /* Panels */
        .dash-panel {
            animation: fadeInUp 0.35s ease;
        }

        .dash-panel.active {}

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section Heads */
        .dash-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .dash-section-title {
            font-size: clamp(16px, 1.8vw, 20px);
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: 0.01em;
            margin: 0;
        }

        .dash-section-link {
            font-size: 11.5px;
            font-weight: 400;
            color: var(--ink-muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--line);
            padding-bottom: 1px;
            transition: color 0.2s, border-color 0.2s;
            cursor: pointer;
            text-decoration: none;
        }

        .dash-section-link:hover {
            color: var(--accent);
            border-color: var(--accent);
        }

        /* Stats Row */
        .dash-stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .dash-stat-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .dash-stat-card:hover {
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }

        .dash-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dash-stat-icon svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke-width: 1.8;
        }

        .dash-stat-icon--gold {
            background: rgba(212, 148, 62, 0.15);
        }

        .dash-stat-icon--gold svg {
            stroke: #D4943E;
        }

        .dash-stat-icon--green {
            background: rgba(46, 125, 50, 0.15);
        }

        .dash-stat-icon--green svg {
            stroke: #2E7D32;
        }

        .dash-stat-icon--blue {
            background: rgba(25, 118, 210, 0.15);
        }

        .dash-stat-icon--blue svg {
            stroke: #1976D2;
        }

        .dash-stat-icon--red {
            background: rgba(192, 57, 43, 0.15);
        }

        .dash-stat-icon--red svg {
            stroke: #C0392B;
        }

        .dash-stat-info {
            display: flex;
            flex-direction: column;
        }

        .dash-stat-num {
            font-size: 26px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .dash-stat-label {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 4px;
            letter-spacing: 0.04em;
        }

        /* Alert Bar */
        .dash-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(184, 134, 11, 0.07);
            border: 1px solid rgba(184, 134, 11, 0.25);
            border-radius: var(--radius-sm);
            padding: 13px 18px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .dash-alert-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            flex-shrink: 0;
            animation: blink 1.2s ease-in-out infinite;
        }

        .dash-alert-content {
            flex: 1;
            font-size: 13px;
            color: var(--ink-soft);
            min-width: 0;
        }

        .dash-alert-content strong {
            color: var(--ink);
        }

        .dash-alert-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--accent);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            flex-shrink: 0;
            border-bottom: 1px solid var(--accent);
            padding-bottom: 1px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .dash-alert-link:hover {
            color: var(--accent-dark);
        }

        /* Orders Table */
        .dash-orders-table {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .dot-table-header {
            display: grid;
            grid-template-columns: 2.2fr 1.1fr 1fr 1fr 1fr 80px;
            gap: 12px;
            padding: 12px 20px;
            background: var(--bg);
            border-bottom: 1px solid var(--line);
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink-muted);
        }

        .dot-row {
            display: grid;
            grid-template-columns: 2.2fr 1.1fr 1fr 1fr 1fr 80px;
            gap: 12px;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--line-soft);
            transition: background 0.18s;
        }

        .dot-row:last-child {
            border-bottom: none;
        }

        .dot-row:hover {
            background: var(--bg);
        }

        .dot-product {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .dot-prod-img {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            flex-shrink: 0;
            background: var(--bg);
        }

        .dot-prod-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dot-prod-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .dot-prod-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dot-prod-meta {
            font-size: 11.5px;
            color: var(--ink-muted);
            margin-top: 3px;
        }

        .dot-id {
            font-size: 12px;
            color: var(--ink-muted);
            font-family: monospace;
            letter-spacing: 0.03em;
        }

        .dot-date {
            font-size: 12.5px;
            color: var(--ink-soft);
        }

        .dot-total {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .dot-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            width: fit-content;
        }

        .dot-status--shipping {
            background: rgba(21, 101, 192, 0.1);
            color: #1565C0;
        }

        .dot-status--done {
            background: rgba(46, 125, 50, 0.1);
            color: var(--green);
        }

        .dot-status--process {
            background: rgba(184, 134, 11, 0.1);
            color: var(--accent);
        }

        .dot-status--cancelled {
            background: rgba(192, 57, 43, 0.1);
            color: var(--red);
        }

        .dot-action-btn {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: var(--radius-sm);
            padding: 6px 14px;
            background: none;
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap;
        }

        .dot-action-btn:hover {
            background: var(--accent);
            color: #fff;
        }

        /* Two-col layout inside overview */
        .dash-two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            margin-top: 8px;
        }

        /* Reco List */
        .dash-reco-list {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .dash-reco-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line-soft);
            transition: background 0.18s;
        }

        .dash-reco-item:last-child {
            border-bottom: none;
        }

        .dash-reco-item:hover {
            background: var(--bg);
        }

        .dash-reco-img {
            width: 54px;
            height: 54px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            flex-shrink: 0;
            background: var(--bg);
        }

        .dash-reco-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dash-reco-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .dash-reco-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dash-reco-price {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--accent);
            margin-top: 3px;
        }

        .dash-reco-btn {
            font-size: 10.5px;
            font-weight: 500;
            color: var(--ink-soft);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 6px 14px;
            background: none;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.18s;
        }

        .dash-reco-btn:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* Vouchers */
        .dash-vouchers {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .vouchers-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .dash-voucher-card {
            display: flex;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            background: var(--bg-card);
            transition: box-shadow 0.2s, border-color 0.2s;
            position: relative;
        }

        .dash-voucher-card:hover {
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }

        .dash-voucher-card--expired {
            opacity: 0.55;
            pointer-events: none;
        }

        .dash-voucher-left {
            background: linear-gradient(135deg, var(--accent-dark), var(--accent-light));
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 120px;
            flex-shrink: 0;
            text-align: center;
            gap: 6px;
            position: relative;
        }

        .dash-voucher-amount {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .dash-voucher-code {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
            background: rgba(0, 0, 0, 0.15);
            padding: 3px 8px;
            border-radius: 4px;
        }

        .dash-voucher-divider {
            width: 1px;
            background: repeating-linear-gradient(to bottom,
                    var(--line) 0px,
                    var(--line) 6px,
                    transparent 6px,
                    transparent 12px);
            flex-shrink: 0;
            margin: 12px 0;
        }

        .dash-voucher-right {
            flex: 1;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .dash-voucher-right p {
            font-size: 13px;
            color: var(--ink);
            font-weight: 500;
            margin: 0;
            line-height: 1.4;
        }

        .dash-voucher-right span {
            font-size: 11.5px;
            color: var(--ink-muted);
        }

        .dash-copy-btn {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 999px;
            padding: 5px 14px;
            background: none;
            cursor: pointer;
            width: fit-content;
            transition: all 0.18s;
        }

        .dash-copy-btn:hover {
            background: var(--accent);
            color: #fff;
        }

        /* ---- ORDERS FULL LIST ---- */
        .order-filter-tabs {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        /* Order Card — collapsible */
        .order-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .order-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .order-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: var(--bg);
            gap: 12px;
            flex-wrap: wrap;
            cursor: pointer;
            user-select: none;
            transition: background 0.18s;
        }

        .order-card-header:hover {
            background: var(--bg-soft);
        }

        .order-card.open .order-card-header {
            border-bottom: 1px solid var(--line-soft);
        }

        .order-chevron {
            transition: transform 0.25s ease;
            flex-shrink: 0;
            color: var(--ink-muted);
        }

        .order-card.open .order-chevron {
            transform: rotate(180deg);
        }

        .order-card-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        .order-card.open .order-card-body {
            max-height: 600px;
        }

        .ofilter {
            padding: 7px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            color: var(--ink-muted);
            border: 1px solid var(--line);
            background: var(--bg-card);
            cursor: pointer;
            transition: all 0.18s;
        }

        .ofilter:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .ofilter.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .orders-full-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .order-full-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .order-full-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .ofc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--line-soft);
            background: var(--bg);
            gap: 12px;
            flex-wrap: wrap;
        }

        .ofc-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ofc-id {
            font-size: 13px;
            font-family: monospace;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0.04em;
        }

        .ofc-date {
            font-size: 12.5px;
            color: var(--ink-muted);
        }

        /* Tracking Bar */
        .order-track-bar {
            display: flex;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid var(--line-soft);
            gap: 0;
            background: rgba(184, 134, 11, 0.02);
            overflow-x: auto;
        }

        .otb-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            position: relative;
        }

        .otb-step span {
            font-size: 10.5px;
            color: var(--ink-muted);
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .otb-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--line);
            background: var(--bg-card);
            transition: all 0.3s;
        }

        .otb-step.done .otb-dot {
            background: var(--accent);
            border-color: var(--accent);
        }

        .otb-step.done span {
            color: var(--ink);
            font-weight: 500;
        }

        .otb-step.active .otb-dot {
            background: #fff;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.2);
            animation: pulseStep 1.5s ease-in-out infinite;
        }

        .otb-step.active span {
            color: var(--accent);
            font-weight: 600;
        }

        @keyframes pulseStep {

            0%,
            100% {
                box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.2);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(184, 134, 11, 0.08);
            }
        }

        .otb-line {
            flex: 1;
            height: 2px;
            background: var(--line);
            min-width: 40px;
            margin: 0 6px;
            margin-top: -16px;
            transition: background 0.3s;
        }

        .otb-line.done {
            background: var(--accent);
        }

        .ofc-products {
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            border-bottom: 1px solid var(--line-soft);
        }

        .ofc-prod-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .ofc-prod-row img {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            background: var(--bg);
            flex-shrink: 0;
        }

        .ofc-prod-detail {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .ofc-prod-detail strong {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink);
        }

        .ofc-prod-detail span {
            font-size: 12px;
            color: var(--ink-muted);
        }

        .ofc-footer {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 20px;
            flex-wrap: wrap;
        }

        .ofc-footer-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .ofc-footer-info>div {
            display: flex;
            gap: 10px;
            align-items: baseline;
            flex-wrap: wrap;
        }

        .ofc-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-muted);
            flex-shrink: 0;
        }

        .ofc-footer-info span {
            font-size: 12.5px;
            color: var(--ink-soft);
        }

        .ofc-total-row {
            margin-top: 4px;
        }

        .ofc-total-row strong {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
        }

        .ofc-footer-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            flex-shrink: 0;
        }

        .ofc-btn {
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: all 0.18s;
            white-space: nowrap;
        }

        .ofc-btn--primary {
            background: var(--accent);
            color: #fff;
            border: 1px solid var(--accent);
        }

        .ofc-btn--primary:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
        }

        .ofc-btn--outline {
            background: none;
            color: var(--ink-soft);
            border: 1px solid var(--line);
        }

        .ofc-btn--outline:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        /* Wishlist Remove Button */
        .wish-remove-btn {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 4;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(17, 16, 14, 0.55);
            color: #fff;
            font-size: 11px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
            transition: background 0.18s;
        }

        .wish-remove-btn:hover {
            background: var(--red);
        }

        /* Profile Form */
        .profile-form-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 32px;
        }

        .profile-avatar-row {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--line-soft);
        }

        .profile-avatar-big {
            position: relative;
            width: 88px;
            height: 88px;
            flex-shrink: 0;
        }

        .profile-avatar-big img {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--accent);
        }

        .profile-avatar-initials {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            border: 2.5px solid var(--accent);
        }

        .profile-avatar-change {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            border: 2px solid var(--bg-card);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.18s;
        }

        .profile-avatar-change:hover {
            background: var(--accent-dark);
        }

        .pform-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .pform-divider {
            height: 1px;
            background: var(--line-soft);
            margin: 24px 0;
        }

        /* Address */
        .address-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .address-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 22px 24px;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .address-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .address-card--main {
            border-color: var(--accent);
            background: rgba(184, 134, 11, 0.03);
        }

        .address-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .address-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .address-default-badge {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(184, 134, 11, 0.12);
            color: var(--accent);
            border: 1px solid rgba(184, 134, 11, 0.3);
        }

        .address-set-main {
            font-size: 11px;
            font-weight: 500;
            color: var(--ink-muted);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 4px 12px;
            background: none;
            cursor: pointer;
            transition: all 0.18s;
        }

        .address-set-main:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .address-name {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink);
            margin: 0 0 6px;
        }

        .address-detail {
            font-size: 13px;
            color: var(--ink-soft);
            line-height: 1.7;
            margin: 0 0 14px;
        }

        .address-actions {
            display: flex;
            gap: 8px;
        }

        .addr-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 13px;
            color: var(--ink-soft);
            padding: 6px 0;
        }

        .addr-checkbox-label input[type="checkbox"] {
            display: none;
        }

        .addr-checkbox-mark {
            width: 18px;
            height: 18px;
            border: 2px solid var(--line);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s;
            flex-shrink: 0;
            background: var(--bg-card);
        }

        .addr-checkbox-label input:checked + .addr-checkbox-mark {
            background: var(--accent);
            border-color: var(--accent);
        }

        .addr-checkbox-label input:checked + .addr-checkbox-mark::after {
            content: '';
            width: 5px;
            height: 9px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            margin-top: -2px;
        }

        .addr-checkbox-label:hover .addr-checkbox-mark {
            border-color: var(--accent);
        }

        .address-btn {
            font-size: 12px;
            font-weight: 500;
            color: var(--ink-soft);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            padding: 7px 18px;
            background: none;
            cursor: pointer;
            transition: all 0.18s;
        }

        .address-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .address-btn--danger:hover {
            border-color: var(--red);
            color: var(--red);
        }

        /* Voucher Input */
        .voucher-input-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 8px;
        }

        .voucher-input-inner {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .voucher-input-inner svg {
            flex-shrink: 0;
        }

        .voucher-input-inner input {
            flex: 1;
            padding: 12px 16px;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: var(--bg);
            outline: none;
            transition: border-color 0.2s;
            min-width: 0;
        }

        .voucher-input-inner input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
        }

        /* prod-card price row overrides */
        .prod-card .price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }

        .prod-card .price-row .current {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent);
        }

        .prod-card .price-row .old {
            font-size: 11.5px;
            color: var(--ink-muted);
            text-decoration: line-through;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 16, 14, 0.6);
            z-index: 500;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(3px);
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: var(--bg-card);
            border-radius: var(--radius);
            width: 100%;
            max-width: 540px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 24px 60px rgba(17, 16, 14, 0.25);
            animation: fadeInUp 0.28s ease;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--line-soft);
        }

        .modal-header h3 {
            font-size: 16px;
            font-family: 'Inter', sans-serif;
        }

        .modal-close {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--bg);
            border: 1px solid var(--line);
            font-size: 14px;
            color: var(--ink-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s;
        }

        .modal-close:hover {
            background: var(--red);
            color: #fff;
            border-color: var(--red);
        }

        .modal-body {
            padding: 24px;
        }

        /* ---- RESPONSIVE ---- */
        @media (max-width: 1024px) {
            .dashboard-layout {
                grid-template-columns: 240px 1fr;
                gap: 20px;
            }

            .dash-stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .dot-table-header,
            .dot-row {
                grid-template-columns: 2fr 1fr 1fr 90px;
            }

            .dot-table-header span:nth-child(2),
            .dot-row .dot-id,
            .dot-table-header span:nth-child(3),
            .dot-row .dot-date {
                display: none;
            }

            .dash-two-col {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
                max-width: 100%;
                box-sizing: border-box;
            }

            .dashboard-sidebar {
                position: static;
            }

            .dash-profile-card {
                display: flex;
                align-items: center;
                gap: 16px;
                text-align: left;
                padding: 14px 16px;
            }

            .dash-avatar {
                width: 52px;
                height: 52px;
                margin: 0;
                flex-shrink: 0;
            }

            .dash-avatar img {
                width: 52px;
                height: 52px;
            }

            .dash-avatar-badge {
                width: 16px;
                height: 16px;
                font-size: 8px;
                bottom: 1px;
                right: 1px;
            }

            .dash-profile-info h3 {
                font-size: 14px;
                margin-bottom: 2px;
            }

            .dash-profile-info p {
                font-size: 11px;
                margin-top: 2px;
            }

            .dash-member-tag {
                font-size: 10px;
                padding: 2px 8px;
                margin-top: 4px;
            }

            .dash-nav-toggle {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                padding: 12px 16px;
                background: var(--bg-card);
                border: 1px solid var(--line);
                border-radius: var(--radius);
                font-size: 13px;
                font-weight: 500;
                color: var(--ink-soft);
                cursor: pointer;
                transition: color 0.2s;
                box-sizing: border-box;
            }

            .dash-nav-toggle:hover {
                color: var(--ink);
            }

            .dash-nav-toggle svg {
                width: 16px;
                height: 16px;
                transition: transform 0.25s;
            }

            .dash-nav-toggle.open svg {
                transform: rotate(180deg);
            }

            .dash-nav {
                display: flex;
                flex-direction: column;
                border-radius: var(--radius);
            }

            .dash-nav.collapsed {
                display: none;
            }

            .dash-nav-item {
                padding: 14px 16px;
                font-size: 13px;
                border-bottom: 1px solid var(--line-soft);
                border-right: none;
                text-align: left;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .dash-nav-item:last-child {
                border-bottom: none;
            }

            .dash-nav-item.active::before {
                top: 0;
                bottom: 0;
                left: 0;
                right: auto;
                width: 3px;
                height: auto;
                border-radius: 0 2px 2px 0;
            }

            .dash-nav-badge {
                position: static;
                margin-left: auto;
            }

            .dash-nav-divider {
                display: block;
            }

            .dash-nav-item--danger {
                display: flex;
            }

            .dash-stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid-prod {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .pform-grid {
                grid-template-columns: 1fr;
            }

            .profile-form-card {
                padding: 20px;
                max-width: 100%;
                box-sizing: border-box;
            }

            .order-track-bar {
                padding: 14px 16px;
                overflow-x: auto;
            }

            .otb-line {
                min-width: 24px;
            }

            .dash-orders-table {
                overflow: hidden;
                max-width: 100%;
                box-sizing: border-box;
            }

            .dot-table-header {
                display: none;
            }

            .dot-row {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                padding: 14px 16px;
                min-width: 0;
                align-items: flex-start;
                box-sizing: border-box;
            }

            .dot-product {
                width: 100%;
                min-width: 0;
            }

            .dot-id,
            .dot-date,
            .dot-total {
                font-size: 12px;
            }

            .dot-total {
                flex: 1;
                min-width: 0;
            }

            .dot-status {
                font-size: 9px;
                padding: 3px 8px;
            }

            .dot-action-btn {
                padding: 5px 10px;
                font-size: 10px;
                margin-left: auto;
            }

            .wrap {
                padding-left: 20px !important;
                padding-right: 20px !important;
                box-sizing: border-box;
                max-width: 100%;
            }

            .dash-two-col {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .dash-alert {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .ofc-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .ofc-footer {
                flex-direction: column;
            }

            .ofc-footer-actions {
                width: 100%;
            }

            .ofc-btn {
                flex: 1;
                text-align: center;
            }

            .dash-voucher-card {
                flex-direction: column;
            }

            .dash-voucher-left {
                width: auto;
                padding: 14px;
                flex-direction: row;
                gap: 12px;
            }

            .dash-voucher-divider {
                width: auto;
                height: 1px;
                margin: 0 16px;
                background: repeating-linear-gradient(to right,
                        var(--line) 0px,
                        var(--line) 6px,
                        transparent 6px,
                        transparent 12px);
            }

            .dash-voucher-right {
                padding: 14px 16px 16px;
            }

            .profile-avatar-row {
                flex-direction: column;
                text-align: center;
            }

            .voucher-input-inner {
                flex-direction: column;
                align-items: stretch;
            }

            .voucher-input-inner input {
                width: 100%;
                box-sizing: border-box;
            }

            .voucher-input-inner button {
                width: 100%;
            }

            .address-card {
                padding: 16px 18px;
            }

            .address-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .address-actions {
                flex-wrap: wrap;
            }

            .dash-section-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .orders-full-list {
                max-width: 100%;
                overflow: hidden;
            }

            .order-full-card {
                max-width: 100%;
            }

            .ofc-products {
                max-width: 100%;
                overflow: hidden;
            }

            .ofc-prod-row {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .wrap {
                padding-left: 16px !important;
                padding-right: 16px !important;
                max-width: 100%;
                box-sizing: border-box;
            }

            .dash-stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .dash-stat-card {
                padding: 12px;
                gap: 10px;
            }

            .dash-stat-num {
                font-size: 20px;
            }

            .dash-stat-icon {
                width: 34px;
                height: 34px;
            }

            .dash-stat-icon svg {
                width: 14px;
                height: 14px;
            }

            .dash-stat-label {
                font-size: 10px;
            }

            .dash-profile-card {
                padding: 18px 14px;
            }

            .dash-avatar {
                width: 64px;
                height: 64px;
            }

            .dash-avatar img {
                width: 64px;
                height: 64px;
            }

            .dash-nav-item {
                font-size: 9px;
                padding: 10px 8px;
            }

            .dash-nav-item svg {
                width: 14px;
                height: 14px;
            }

            .dot-row {
                padding: 12px 14px;
            }

            .dot-prod-img {
                width: 40px;
                height: 40px;
            }

            .dot-prod-name {
                font-size: 12px;
            }

            .ofc-header {
                padding: 12px 14px;
            }

            .ofc-products {
                padding: 10px 12px;
            }

            .ofc-prod-row img {
                width: 48px;
                height: 48px;
            }

            .ofc-footer {
                padding: 12px 14px;
            }

            .ofc-btn {
                flex: 1;
                padding: 10px 14px;
                font-size: 11px;
            }

            .order-track-bar {
                padding: 10px 12px;
            }

            .otb-step span {
                font-size: 9px;
            }

            .otb-dot {
                width: 10px;
                height: 10px;
            }

            .otb-line {
                min-width: 16px;
                margin: 0 4px;
                margin-top: -12px;
            }

            .dash-voucher-left {
                min-width: 80px;
                padding: 12px 10px;
                flex-direction: column;
                gap: 4px;
            }

            .dash-voucher-amount {
                font-size: 14px;
            }

            .dash-reco-item {
                padding: 10px 12px;
                gap: 10px;
            }

            .dash-reco-img {
                width: 44px;
                height: 44px;
            }

            .profile-form-card {
                padding: 16px;
                max-width: 100%;
                box-sizing: border-box;
            }

            .profile-avatar-big {
                width: 72px;
                height: 72px;
            }

            .profile-avatar-big img {
                width: 72px;
                height: 72px;
            }

            .profile-avatar-row {
                gap: 14px;
                padding-bottom: 16px;
            }

            .address-card {
                padding: 14px 16px;
            }

            .order-filter-tabs {
                width: 100%;
                overflow-x: auto;
                flex-wrap: nowrap;
                scrollbar-width: none;
            }

            .order-filter-tabs::-webkit-scrollbar {
                display: none;
            }

            .ofilter {
                font-size: 11px;
                padding: 6px 14px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .modal-box {
                max-height: 90vh;
            }

            .modal-header {
                padding: 14px 16px;
            }

            .modal-body {
                padding: 16px;
            }
        }
    </style>
@endpush

@php
    $dashUser = Auth::user();
    $dashName = $dashUser->full_name ?: $dashUser->nama;
    $dashInitials = strtoupper(substr($dashName, 0, 1));
@endphp
@section('content')
    <section class="section" style="padding:32px 0 64px;">
        <div class="wrap">
            <div class="dashboard-layout">

                <!-- ============ SIDEBAR ============ -->
                <aside class="dashboard-sidebar">
                    <!-- Profile Card -->
                    <div class="dash-profile-card">
                        <div class="dash-avatar">
                            @if ($dashUser->photo_profile)
                            <img src="{{ asset($dashUser->photo_profile) }}" alt="{{ $dashName }}">
                            @else
                            <div class="dash-avatar-initials">{{ $dashInitials }}</div>
                            @endif
                            <span class="dash-avatar-badge" title="Terverifikasi">✓</span>
                        </div>
                        <div class="dash-profile-info">
                            <h3>{{ $dashName }}</h3>
                            @if ($dashUser->role)
                            <span class="dash-member-tag" style="margin-top:6px;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="var(--accent-light)"
                                    stroke="none">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                {{ $dashUser->role->nama_role ?? 'Member' }}
                            </span>
                            @endif
                            <p>{{ $dashUser->email }}</p>
                        </div>
                    </div>

                    <!-- Nav Toggle (mobile) -->
                    <button class="dash-nav-toggle" onclick="toggleDashNav()" aria-label="Toggle menu">
                        <span>Menu Navigasi</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>

                    <!-- Nav Menu -->
                    <nav class="dash-nav">
                        <a href="{{ route('dashboard') }}" class="dash-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                            </svg>
                            Ringkasan
                        </a>
                        <a href="{{ route('dashboard.pesanan') }}" class="dash-nav-item {{ request()->routeIs('dashboard.pesanan*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 01-8 0" />
                            </svg>
                            Pesanan Saya
                            <span class="dash-nav-badge">{{ $dashUser->penjualan()->where('order_web', true)->count() }}</span>
                        </a>
                        <a href="{{ route('dashboard.wishlist') }}" class="dash-nav-item {{ request()->routeIs('dashboard.wishlist') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                            </svg>
                            Wishlist
                            <span class="dash-nav-badge">5</span>
                        </a>
                        <a href="{{ route('dashboard.profil') }}" class="dash-nav-item {{ request()->routeIs('dashboard.profil') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Profil & Akun
                        </a>
                        <a href="{{ route('dashboard.alamat') }}" class="dash-nav-item {{ request()->routeIs('dashboard.alamat') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Alamat Pengiriman
                        </a>
                        <a href="{{ route('dashboard.voucher') }}" class="dash-nav-item {{ request()->routeIs('dashboard.voucher*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24">
                                <path d="M20 12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                                <path d="M22 7H2v5h20V7z" />
                                <path d="M12 22V7" />
                                <path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z" />
                                <path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z" />
                            </svg>
                            Voucher & Promo
                        </a>
                        <div class="dash-nav-divider"></div>
                        <a href="/logout" class="dash-nav-item dash-nav-item--danger">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Keluar
                        </a>
                    </nav>
                </aside>

                <!-- ============ MAIN CONTENT ============ -->
                <main class="dashboard-main">
                    @yield('dashboard-content')
                </main>
            </div>
        </div>
    </section>

    <!-- ORDER DETAIL MODAL -->
    <div class="modal-overlay" id="orderModal" onclick="closeModal(event)">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Detail Pesanan <span id="modalOrderId"></span></h3>
                <button class="modal-close"
                    onclick="document.getElementById('orderModal').classList.remove('open')">✕</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Diisi JS -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /* ============================================
       DASHBOARD SCRIPTS — CHRISBALE
       ============================================ */

        // -- Toggle Nav (mobile) --
        function toggleDashNav() {
            var nav = document.querySelector('.dash-nav');
            var btn = document.querySelector('.dash-nav-toggle');
            if (nav) {
                nav.classList.toggle('collapsed');
            }
            if (btn) {
                btn.classList.toggle('open');
            }
        }

        // -- Order Filter --
        function filterOrders(btn, status) {
            document.querySelectorAll('.ofilter').forEach(function(b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            document.querySelectorAll('.order-full-card').forEach(function(card) {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = '';
                    card.style.animation = 'fadeInUp 0.3s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // -- Copy Voucher Code --
        function copyCode(code, btn) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(code).then(function() {
                    var original = btn.textContent;
                    btn.textContent = '✓ Tersalin!';
                    btn.style.background = 'var(--green)';
                    btn.style.borderColor = 'var(--green)';
                    btn.style.color = '#fff';
                    setTimeout(function() {
                        btn.textContent = original;
                        btn.style.background = '';
                        btn.style.borderColor = '';
                        btn.style.color = '';
                    }, 2000);
                });
            } else {
                var textArea = document.createElement('textarea');
                textArea.value = code;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                btn.textContent = '✓ Tersalin!';
                setTimeout(function() {
                    btn.textContent = 'Salin Kode';
                }, 2000);
            }
        }

        // -- Remove Wishlist Item --
        function removeWish(btn) {
            var card = btn.closest('.prod-card');
            if (card) {
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(function() {
                    card.remove();
                    var items = document.querySelectorAll('#panel-wishlist .prod-card').length;
                    var badge = document.querySelector('.dash-nav-item:nth-child(3) .dash-nav-badge');
                    if (badge) badge.textContent = items;
                }, 300);
            }
        }

        // -- Toggle Address Form --
        function toggleAddressForm() {
            var form = document.getElementById('addressFormWrap');
            if (!form) return;
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                form.style.animation = 'fadeInUp 0.3s ease';
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } else {
                form.style.display = 'none';
            }
        }

        // -- Order Detail Modal --
        var orderData = {
            'CB-2024-089': {
                items: [{
                    name: 'CHRISBALE Urban Runner',
                    meta: 'UK 42 · Hitam · Qty: 1',
                    price: 'Rp2.490.000',
                    img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=120&q=80&auto=format&fit=crop'
                }],
                status: 'Dikirim',
                date: '10 Jan 2025',
                payment: 'Transfer Bank — BCA · Lunas',
                shipping: 'JNE Express · JNECB20241089',
                address: 'Jl. Sudirman No.12, Senayan, Jakarta Selatan',
                subtotal: 'Rp2.490.000',
                ongkir: 'Rp50.000',
                total: 'Rp2.540.000'
            },
            'CB-2024-075': {
                items: [{
                    name: 'Gold Leather Loafer',
                    meta: 'UK 40 · Emas · Qty: 1',
                    price: 'Rp3.290.000',
                    img: 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?w=120&q=80&auto=format&fit=crop'
                }],
                status: 'Selesai',
                date: '28 Des 2024',
                payment: 'QRIS · Lunas',
                shipping: 'SiCepat REG · Tiba 31 Des 2024',
                address: 'Jl. Sudirman No.12, Senayan, Jakarta Selatan',
                subtotal: 'Rp3.290.000',
                ongkir: 'Rp50.000',
                total: 'Rp3.340.000'
            },
            'CB-2024-061': {
                items: [{
                        name: 'Combat Boot — Hitam',
                        meta: 'UK 41 · Hitam · Qty: 1',
                        price: 'Rp2.890.000',
                        img: 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=120&q=80&auto=format&fit=crop'
                    },
                    {
                        name: 'Classic Slip-On Putih',
                        meta: 'UK 39 · Putih · Qty: 1',
                        price: 'Rp1.490.000',
                        img: 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=120&q=80&auto=format&fit=crop'
                    }
                ],
                status: 'Selesai',
                date: '10 Des 2024',
                payment: 'GoPay · Lunas',
                shipping: 'JNE Regular · Tiba 14 Des 2024',
                address: 'Jl. Sudirman No.12, Senayan, Jakarta Selatan',
                subtotal: 'Rp4.380.000',
                ongkir: 'Rp50.000',
                total: 'Rp4.430.000'
            }
        };

        function openOrderDetail(id) {
            var data = orderData[id];
            if (!data) return;

            document.getElementById('modalOrderId').textContent = '#' + id;

            var statusClass = {
                'Dikirim': 'dot-status--shipping',
                'Selesai': 'dot-status--done',
                'Diproses': 'dot-status--process'
            } [data.status] || 'dot-status--process';

            var itemsHtml = data.items.map(function(item) {
                return '<div style="display:flex;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid var(--line-soft);">' +
                    '<img src="' + item.img +
                    '" style="width:56px;height:56px;border-radius:var(--radius-sm);object-fit:cover;flex-shrink:0;">' +
                    '<div style="flex:1;">' +
                    '<div style="font-size:13.5px;font-weight:500;color:var(--ink);">' + item.name + '</div>' +
                    '<div style="font-size:12px;color:var(--ink-muted);margin-top:3px;">' + item.meta + '</div>' +
                    '</div>' +
                    '<div style="font-size:13px;font-weight:600;color:var(--accent);flex-shrink:0;">' + item.price +
                    '</div>' +
                    '</div>';
            }).join('');

            document.getElementById('modalBody').innerHTML =
                '<div style="margin-bottom:16px;">' +
                '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">' +
                '<span style="font-size:12px;color:var(--ink-muted);">Status</span>' +
                '<span class="dot-status ' + statusClass + '">' + data.status + '</span>' +
                '</div>' +
                '<div style="display:flex;justify-content:space-between;margin-bottom:4px;">' +
                '<span style="font-size:12px;color:var(--ink-muted);">Tanggal</span>' +
                '<span style="font-size:12.5px;color:var(--ink);">' + data.date + '</span>' +
                '</div>' +
                '</div>' +
                '<div style="margin-bottom:20px;">' + itemsHtml + '</div>' +
                '<div style="background:var(--bg);border-radius:var(--radius-sm);padding:16px;display:flex;flex-direction:column;gap:8px;font-size:13px;">' +
                '<div style="display:flex;justify-content:space-between;"><span style="color:var(--ink-muted);">Pembayaran</span><span>' +
                data.payment + '</span></div>' +
                '<div style="display:flex;justify-content:space-between;"><span style="color:var(--ink-muted);">Pengiriman</span><span>' +
                data.shipping + '</span></div>' +
                '<div style="display:flex;justify-content:space-between;"><span style="color:var(--ink-muted);">Alamat</span><span style="text-align:right;max-width:280px;">' +
                data.address + '</span></div>' +
                '<hr style="border:none;border-top:1px solid var(--line-soft);margin:4px 0;">' +
                '<div style="display:flex;justify-content:space-between;"><span style="color:var(--ink-muted);">Subtotal</span><span>' +
                data.subtotal + '</span></div>' +
                '<div style="display:flex;justify-content:space-between;"><span style="color:var(--ink-muted);">Ongkir</span><span>' +
                data.ongkir + '</span></div>' +
                '<div style="display:flex;justify-content:space-between;font-weight:700;font-size:14px;"><span>Total</span><span style="color:var(--accent);">' +
                data.total + '</span></div>' +
                '</div>';

            document.getElementById('orderModal').classList.add('open');
        }

        function closeModal(e) {
            if (e.target === document.getElementById('orderModal')) {
                document.getElementById('orderModal').classList.remove('open');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('orderModal').classList.remove('open');
            }
        });
    </script>
@endpush
