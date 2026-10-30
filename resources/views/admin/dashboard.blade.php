@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

    <!-- Page Title & Quick Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 animate-fade-in-up">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-bold text-dark mb-0">Executive Dashboard</h2>
                <span class="badge bg-white text-secondary border px-2 py-1 small d-inline-flex align-items-center gap-1 shadow-sm">
                    <span class="radar-dot me-1"></span> Live System
                </span>
            </div>
            <p class="text-muted small mb-0">Real-time overview of website content, machinery performance, and customer enquiries.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-agro-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Machinery
            </a>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-agro-outline btn-sm">
                <i class="bi bi-file-earmark-plus me-1"></i> New Page
            </a>
            <a href="{{ route('admin.enquiries.export') }}" class="btn btn-light btn-sm border shadow-sm">
                <i class="bi bi-download me-1"></i> Export Enquiries
            </a>
        </div>
    </div>

    <!-- Stat Metrics Row -->
    <div class="row g-4 mb-5">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card card-primary animate-fade-in-up delay-1">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">PAGES</span>
                    <div class="stat-icon-wrapper stat-icon-primary"><i class="bi bi-file-earmark-richtext"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_pages'] }}</h3>
                <small class="text-success" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill"></i> Published Live</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card card-secondary animate-fade-in-up delay-2">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">MACHINERY</span>
                    <div class="stat-icon-wrapper stat-icon-secondary">
                        <i class="bi bi-tractor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 640 512" style="vertical-align: -0.125em; display: inline-block;"><path d="M528 256c53 0 96 43 96 96s-43 96-96 96c-48.4 0-88.3-35.8-95-82.3l-59.5 29.8c-1.8 17.5-9.1 33.6-20.7 46.5H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H320c-17.7 0-32-14.3-32-32s14.3-32 32-32h14.2c2.9-7.2 6.9-13.8 11.8-19.6l-50.5-25.2c-15.6 19.3-39.3 31.7-65.6 31.7c-47.5 0-86.2-38-87.9-85.3l-45.7 18.3c-2.3 8.3-9.9 14.3-18.7 14.3H48c-11 0-20-9-20-20s9-20 20-20h23.1l60.2-24.1c1.5-8.5 4.8-16.5 9.7-23.4L81.6 280.9C73.8 281.6 65 288 64 288c-17.7 0-32-14.3-32-32s14.3-32 32-32h41.4l52.1-52.1C164.2 165.2 173 160 182.2 160H288V64c0-17.7 14.3-32 32-32s32 14.3 32 32v96h96c17.7 0 32 14.3 32 32v64h48zm-160 96c0-35.3 28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64s-64-28.7-64-64zm-192 48c26.5 0 48-21.5 48-48s-21.5-48-48-48s-48 21.5-48 48s21.5 48 48 48z"/></svg>
                        </i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_products'] }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;">Active Models</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card card-blue animate-fade-in-up delay-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">CATEGORIES</span>
                    <div class="stat-icon-wrapper stat-icon-blue"><i class="bi bi-grid-3x3-gap"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_categories'] }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;">Product Lines</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card card-purple animate-fade-in-up delay-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">TOTAL INQUIRIES</span>
                    <div class="stat-icon-wrapper stat-icon-purple"><i class="bi bi-inbox-fill"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_enquiries'] }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;">All Customer Leads</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card card-danger animate-fade-in-up delay-5">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-danger small fw-bold">NEW / UNREAD</span>
                    <div class="stat-icon-wrapper {{ $stats['unread_enquiries'] > 0 ? 'pulse-badge' : '' }}" style="background: #fee2e2; color: #ef4444;"><i class="bi bi-bell-fill"></i></div>
                </div>
                <h3 class="fw-bold text-danger mb-0">{{ $stats['unread_enquiries'] }}</h3>
                <small class="text-danger fw-medium" style="font-size: 0.75rem;">
                    @if($stats['unread_enquiries'] > 0)
                        <i class="bi bi-exclamation-circle-fill"></i> Requires Attention
                    @else
                        All Caught Up
                    @endif
                </small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card card-teal animate-fade-in-up delay-6">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">LANGUAGES</span>
                    <div class="stat-icon-wrapper" style="background: #ccfbf1; color: #0f766e;"><i class="bi bi-translate"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">2</h3>
                <small class="text-muted" style="font-size: 0.75rem;">EN, HI Active</small>
            </div>
        </div>
    </div>

    <!-- Recent Enquiries & Recent Activity Grid -->
    <div class="row g-4 mb-4">
        <!-- Recent Enquiries -->
        <div class="col-lg-8">
            <div class="admin-card h-100 animate-fade-in-up delay-3">
                <div class="admin-card-header">
                    <h5 class="d-flex align-items-center">
                        <i class="bi bi-envelope-open-fill text-warning me-2"></i>
                        Recent Customer Enquiries
                    </h5>
                    <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table admin-table align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Subject / Machinery</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEnquiries as $enq)
                                @php
                                    $words = preg_split('/\s+/', trim($enq->name));
                                    $initials = '';
                                    if (count($words) >= 2) {
                                        $initials = mb_substr($words[0], 0, 1) . mb_substr($words[count($words)-1], 0, 1);
                                    } else {
                                        $initials = mb_substr($enq->name, 0, 2);
                                    }
                                    $initials = strtoupper($initials);
                                @endphp
                                <tr class="{{ $enq->status === 'unread' ? 'table-row-unread' : '' }}">
                                    <td class="text-muted small text-nowrap">
                                        <div class="fw-semibold text-dark">{{ $enq->created_at->format('M d') }}</div>
                                        <div style="font-size: 0.75rem;">{{ $enq->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-initials avatar-{{ $loop->index % 4 }}">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $enq->name }}</div>
                                                <small class="text-muted">{{ $enq->phone ?? $enq->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 240px;">
                                            @if($enq->product)
                                                <span class="badge bg-light text-dark border me-1 fw-semibold">{{ $enq->product->name }}</span>
                                            @endif
                                            <span class="text-muted">{{ $enq->subject }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($enq->status === 'unread')
                                            <span class="badge-status badge-unread"><i class="bi bi-dot"></i>Unread</span>
                                        @elseif($enq->status === 'read')
                                            <span class="badge-status badge-read">Read</span>
                                        @elseif($enq->status === 'contacted')
                                            <span class="badge-status badge-contacted">Contacted</span>
                                        @else
                                            <span class="badge-status badge-resolved">Resolved</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.enquiries.show', $enq) }}" class="btn btn-sm btn-light border py-1 px-3 rounded-pill fw-semibold shadow-xs">
                                            <i class="bi bi-eye text-primary me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No customer inquiries received yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity Logs / Connected Audit Trail -->
        <div class="col-lg-4">
            <div class="admin-card h-100 animate-fade-in-up delay-4">
                <div class="admin-card-header">
                    <h5 class="d-flex align-items-center">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Audit Trail
                    </h5>
                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1 small">Live Stream</span>
                </div>
                <div class="admin-card-body p-4">
                    <ul class="audit-timeline mb-0">
                        @forelse($recentActivities as $act)
                            <li class="audit-timeline-item">
                                <div class="audit-timeline-dot">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-dark fw-semibold text-truncate" title="{{ $act->description ?? $act->action }}">
                                        {{ $act->description ?? $act->action }}
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="bi bi-clock me-1"></i>{{ $act->created_at->diffForHumans() }} &bull; <span class="fw-medium text-secondary">{{ $act->user->name ?? 'System' }}</span>
                                    </small>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-center py-4">No activity logs recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
