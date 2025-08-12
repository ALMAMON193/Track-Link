<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Job Status Update - {{ config('app.name') }}</title>

    <style>
        /* Reset and base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }

        /* Typography */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Inter, 'Helvetica Neue', Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f8fafc;
        }

        /* Container */
        .email-container {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 20px;
            min-height: 100vh;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #06b6d4 100%);
            padding: 32px 40px;
            text-align: center;
            position: relative;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .brand-logo {
            position: relative;
            z-index: 1;
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .header-subtitle {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Content */
        .email-content {
            padding: 48px 40px;
            background: #ffffff;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 24px;
        }

        .main-message {
            font-size: 16px;
            color: #374151;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .job-highlight {
            color: #1f2937;
            font-weight: 600;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #ffffff;
            margin: 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.8;
        }

        .status-pending { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .status-delayed { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .status-complete { background: linear-gradient(135deg, #10b981, #059669); }
        .status-in_transport { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .status-default { background: linear-gradient(135deg, #6b7280, #4b5563); }

        /* Job details card */
        .job-details {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin: 32px 0;
        }

        .job-details-header {
            background: #ffffff;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .job-details-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .job-id {
            font-size: 14px;
            color: #6b7280;
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
        }

        .job-details-body {
            padding: 0;
        }

        .detail-row {
            display: flex;
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row:hover {
            background: rgba(59, 130, 246, 0.02);
        }

        .detail-label {
            flex: 0 0 120px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .detail-value {
            flex: 1;
            color: #1f2937;
            font-size: 14px;
        }

        .date-range {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
        }

        .date-arrow {
            color: #9ca3af;
            font-weight: normal;
        }

        /* Notes section */
        .notes-section {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 20px;
            margin: 32px 0;
        }

        .notes-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .notes-content {
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Call to action */
        .cta-section {
            text-align: center;
            margin: 40px 0 32px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.4);
        }

        /* Support section */
        .support-section {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            margin-top: 32px;
        }

        .support-text {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .support-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .support-link:hover {
            color: #2563eb;
        }

        /* Footer */
        .email-footer {
            background: #1f2937;
            color: #d1d5db;
            padding: 32px 40px;
            text-align: center;
        }

        .footer-brand {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .footer-address {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 16px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-bottom: 20px;
        }

        .footer-link {
            color: #d1d5db;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .footer-link:hover {
            color: #ffffff;
        }

        .footer-copyright {
            font-size: 13px;
            color: #9ca3af;
            border-top: 1px solid #374151;
            padding-top: 20px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }

            .email-header,
            .email-content {
                padding: 32px 24px;
            }

            .brand-logo {
                font-size: 24px;
            }

            .detail-row {
                flex-direction: column;
                gap: 4px;
            }

            .detail-label {
                flex: none;
            }

            .footer-links {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="brand-logo">{{ config('app.name') }}</div>
        </div>
        <!-- Content -->
        <div class="email-content">
            <div class="greeting">
                Dear {{ $jobPost->user->name ?? 'Valued Customer' }},
            </div>

            <div class="main-message">
                We're writing to inform you that the <strong class="job-highlight">{{ strtolower($statusType) }}</strong>
                status for your shipment has been updated. Your package is progressing through our logistics network,
                and we wanted to keep you informed of this important milestone.
            </div>

            <!-- Job Details Card -->
            <div class="job-details">
                <div class="job-details-header">
                    <div class="job-details-title">Shipment Details</div>
                    <div class="job-id">Job ID: {{ $jobPost->job_id ?? 'N/A' }}</div>
                </div>
                <div class="job-details-body">
                    @php
                        $status = $statusType === 'Delivery' ? $jobPost->delivery_status : $jobPost->tracking_time;
                        $statusClass = match(strtolower(str_replace(' ', '_', $status ?? ''))) {
                            'pending' => 'status-pending',
                            'delayed' => 'status-delayed',
                            'complete' => 'status-complete',
                            'in_transport' => 'status-in_transport',
                            default => 'status-default'
                        };
                    @endphp

                    <div class="detail-row">
                        <div class="detail-label">Current Status:</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $statusClass }}">{{ $status ?? 'Processing' }}</span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Package:</div>
                        <div class="detail-value">{{ $jobPost->package_name ?? 'Standard Package' }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Pickup Location:</div>
                        <div class="detail-value">
                            {{ $jobPost->pickup_address ? $jobPost->pickup_address . ', ' : '' }}{{ $jobPost->pickup_city ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Delivery Location:</div>
                        <div class="detail-value">
                            {{ $jobPost->delivery_address ? $jobPost->delivery_address . ', ' : '' }}{{ $jobPost->delivery_city ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Schedule:</div>
                        <div class="detail-value">
                                <span class="date-range">
                                    {{ $jobPost->pickup_date ? \Carbon\Carbon::parse($jobPost->pickup_date)->format('M d, Y') : 'TBD' }}
                                    <span class="date-arrow">→</span>
                                    {{ $jobPost->delivery_date ? \Carbon\Carbon::parse($jobPost->delivery_date)->format('M d, Y') : 'TBD' }}
                                </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if(!empty($notes ?? null))
                <div class="notes-section">
                    <div class="notes-title">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        Additional Information
                    </div>
                    <div class="notes-content">{{ $notes }}</div>
                </div>
            @endif

            <!-- Call to Action -->
            <div class="cta-section">
                <a href="{{ config('app.url') }}/track/{{ $jobPost->job_id ?? '' }}" class="cta-button">
                    Track Your Shipment
                </a>
            </div>

            <!-- Support Section -->
            <div class="support-section">
                <div class="support-text">
                    Questions about your shipment? Our support team is here to help.
                </div>
                <a href="mailto:{{ config('mail.from.address') }}" class="support-link">
                    Contact Support
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-brand">{{ config('app.name') }}</div>
            <div class="footer-address">
                Reliable Logistics Solutions Worldwide
            </div>

            <div class="footer-links">
                <a href="{{ config('app.url') }}" class="footer-link">Website</a>
                <a href="{{ config('app.url') }}/track" class="footer-link">Track Package</a>
                <a href="{{ config('app.url') }}/support" class="footer-link">Support</a>
                <a href="{{ config('app.url') }}/privacy" class="footer-link">Privacy Policy</a>
            </div>

            <div class="footer-copyright">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                This email was sent regarding your shipment {{ $jobPost->job_id ?? 'N/A' }}.
            </div>
        </div>
    </div>
</div>
</body>
</html>
