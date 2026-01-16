<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'DejaVu Sans', 'Arial', sans-serif; 
            font-size: 9px; 
            line-height: 1.4; 
            color: #2c2c2c; 
            padding: 15px;
            background: #ffffff;
        }
        
        /* Header Section */
        .header { 
            margin-bottom: 20px; 
            padding-bottom: 12px; 
            border-bottom: 3px solid #85695a;
            text-align: center;
        }
        
        .header h1 { 
            font-size: 20px; 
            color: #6f5849; 
            margin-bottom: 5px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .header .subtitle { 
            font-size: 11px;
            color: #78716c; 
            margin-bottom: 3px;
            font-weight: 600;
        }
        
        .header .meta { 
            font-size: 8px;
            color: #a8a29e;
        }
        
        /* Summary Grid */
        .summary-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 10px; 
            margin-bottom: 20px; 
        }
        
        .summary-box { 
            border: 1.5px solid #d4c4bb; 
            padding: 10px; 
            background: #faf9f8;
            border-radius: 4px;
            text-align: center;
        }
        
        .summary-box h3 { 
            font-size: 8px; 
            color: #78716c; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .summary-box .value { 
            font-size: 16px; 
            font-weight: 700; 
            color: #6f5849;
            margin-bottom: 3px;
        }
        
        .summary-box .label { 
            font-size: 7px; 
            color: #a8a29e;
        }
        
        /* Section Title */
        .section-title {
            font-size: 12px;
            color: #6f5849;
            margin: 15px 0 10px 0;
            padding-left: 10px;
            border-left: 4px solid #85695a;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Table Styles */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px;
            font-size: 8px;
        }
        
        table th { 
            background: #6f5849;
            color: white;
            font-weight: 600; 
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 6px 4px;
            border: 1px solid #5a4639;
            font-size: 7px;
        }
        
        table td { 
            border: 1px solid #e0cec7; 
            padding: 5px 4px; 
            vertical-align: top;
        }
        
        table tr:nth-child(even) { 
            background-color: #faf9f8; 
        }
        
        table tr:hover {
            background-color: #f5f0ed;
        }
        
        /* Badge & Labels */
        .badge { 
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            background: #e0cec7;
            color: #6f5849;
            white-space: nowrap;
        }
        
        .user-name {
            font-weight: 700;
            color: #6f5849;
            font-size: 8px;
        }
        
        .role-badge {
            display: inline-block;
            padding: 1px 4px;
            background: #85695a;
            color: white;
            border-radius: 2px;
            font-size: 6px;
            margin-top: 2px;
        }
        
        .cashier-badge {
            background: #0284c7;
        }
        
        .admin-badge {
            background: #dc2626;
        }
        
        .text-muted { 
            color: #78716c; 
            font-size: 7px; 
        }
        
        .small { 
            font-size: 7px; 
            line-height: 1.3;
        }
        
        code {
            background: #f5f0ed;
            padding: 1px 3px;
            border-radius: 2px;
            font-family: 'Courier New', monospace;
            font-size: 7px;
            color: #6f5849;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e0cec7;
            text-align: center;
            color: #a8a29e;
            font-size: 7px;
            line-height: 1.6;
        }
        
        .footer strong {
            color: #6f5849;
        }
        
        /* Print Optimizations */
        @media print {
            body { padding: 10px; }
            .summary-grid { page-break-inside: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
        
        /* Icons using Unicode */
        .icon-user::before { content: "👤 "; }
        .icon-calendar::before { content: "📅 "; }
        .icon-activity::before { content: "⚡ "; }
        .icon-network::before { content: "🌐 "; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>📋 AUDIT LOG REPORT</h1>
        <div class="subtitle">Period: {{ $startDate }} - {{ $endDate }}</div>
        <div class="meta">Generated: {{ now()->format('d M Y H:i:s') }} WIB | ARTIKA POS System</div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-box">
            <h3>Total Logs</h3>
            <div class="value">{{ number_format($summary['total_logs']) }}</div>
            <div class="label">Activity Records</div>
        </div>
        <div class="summary-box">
            <h3>Transactions</h3>
            <div class="value">{{ number_format($summary['total_transactions']) }}</div>
            <div class="label">Transaction Logs</div>
        </div>
        <div class="summary-box">
            <h3>Total Amount</h3>
            <div class="value" style="font-size: 14px;">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
            <div class="label">From Transactions</div>
        </div>
        <div class="summary-box">
            <h3>Unique Users</h3>
            <div class="value">{{ count($summary['by_user']) }}</div>
            <div class="label">Active Users</div>
        </div>
    </div>

    <!-- Detail Section -->
    <div class="section-title">Detailed Activity Logs</div>

    <table>
        <thead>
            <tr>
                <th style="width: 11%;">Date & Time</th>
                <th style="width: 13%;">User Info</th>
                <th style="width: 10%;">Action</th>
                <th style="width: 11%;">Target</th>
                <th style="width: 9%;">Amount</th>
                <th style="width: 13%;">Network Info</th>
                <th style="width: 12%;">Device</th>
                <th style="width: 21%;">Additional Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <!-- Date & Time -->
                    <td style="text-align: center;">
                        <div style="font-weight: 600;">{{ $log->created_at->format('d/m/Y') }}</div>
                        <div class="text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    
                    <!-- User Info -->
                    <td>
                        <div class="user-name">{{ $log->user?->name ?? 'System' }}</div>
                        @if($log->user && $log->user->role)
                            <div>
                                <span class="role-badge {{ $log->user->role->name === 'cashier' ? 'cashier-badge' : ($log->user->role->name === 'admin' ? 'admin-badge' : '') }}">
                                    {{ strtoupper($log->user->role->name) }}
                                </span>
                            </div>
                            @if($log->user->role->name === 'cashier')
                                <div class="small" style="margin-top: 2px;">
                                    <div>🆔 {{ $log->user->nis ?? '-' }}</div>
                                    <div>👤 {{ $log->user->username ?? '-' }}</div>
                                </div>
                            @endif
                        @endif
                    </td>
                    
                    <!-- Action -->
                    <td style="text-align: center;">
                        <span class="badge">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    
                    <!-- Target Model -->
                    <td>
                        <div style="font-weight: 600; color: #85695a;">{{ $log->model_type ?? 'N/A' }}</div>
                        @if($log->model_id)
                            <div class="text-muted">ID: #{{ $log->model_id }}</div>
                        @endif
                    </td>
                    
                    <!-- Amount -->
                    <td style="text-align: right;">
                        @if($log->amount)
                            <div style="font-weight: 700; color: #16a34a;">Rp {{ number_format($log->amount, 0, ',', '.') }}</div>
                            @if($log->payment_method)
                                <div class="small" style="color: #78716c;">{{ ucfirst($log->payment_method) }}</div>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    
                    <!-- Network Info -->
                    <td>
                        <div><strong>IP:</strong> <code>{{ $log->ip_address }}</code></div>
                        @if($log->mac_address)
                            <div class="small"><strong>MAC:</strong> <code style="font-size: 6px;">{{ $log->mac_address }}</code></div>
                        @endif
                    </td>
                    
                    <!-- Device -->
                    <td>
                        <div style="font-weight: 600; font-size: 7px;">{{ $log->device_name ?? 'Unknown Device' }}</div>
                    </td>
                    
                    <!-- Additional Details -->
                    <td>
                        @if($log->notes)
                            <div class="small" style="line-height: 1.4;">{{ Str::limit($log->notes, 80) }}</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; color: #a8a29e;">
                        ❌ No audit logs found for this period
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div><strong>ARTIKA POS System</strong> - Comprehensive Audit Log Report</div>
        <div>This is a computer-generated report. No signature required.</div>
        <div style="margin-top: 5px;">
            📊 Total Records: <strong>{{ $logs->count() }}</strong> | 
            📅 Generated: <strong>{{ now()->format('d M Y H:i:s') }}</strong> | 
            🔒 Confidential Document
        </div>
    </div>

</body>
</html>