@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

    <!-- Page Title & Quick Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Executive Dashboard</h2>
            <p class="text-muted small mb-0">Overview of website content, agricultural catalogue performance, and customer enquiries.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-agro-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Machinery
            </a>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-agro-outline btn-sm">
                <i class="bi bi-file-earmark-plus me-1"></i> New Page
            </a>
            <a href="{{ route('admin.enquiries.export') }}" class="btn btn-light btn-sm border">
                <i class="bi bi-download me-1"></i> Export Enquiries
            </a>
        </div>
    </div>

    <!-- Stat Metrics Row -->
    <div class="row g-4 mb-5">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">PAGES</span>
                    <div class="stat-icon-wrapper stat-icon-primary"><i class="bi bi-file-earmark-richtext"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_pages'] }}</h3>
                <small class="text-success" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill"></i> Published Live</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">MACHINERY</span>
                    <div class="stat-icon-wrapper stat-icon-secondary"><i class="bi bi-tractor"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_products'] }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;">Active Models</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">CATEGORIES</span>
                    <div class="stat-icon-wrapper stat-icon-blue"><i class="bi bi-grid-3x3-gap"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_categories'] }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;">Product Lines</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">TOTAL INQUIRIES</span>
                    <div class="stat-icon-wrapper stat-icon-purple"><i class="bi bi-inbox-fill"></i></div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total_enquiries'] }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;">All Customer Leads</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card" style="border-left: 4px solid #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-danger small fw-bold">NEW / UNREAD</span>
                    <div class="stat-icon-wrapper" style="background: #fee2e2; color: #ef4444;"><i class="bi bi-bell-fill"></i></div>
                </div>
                <h3 class="fw-bold text-danger mb-0">{{ $stats['unread_enquiries'] }}</h3>
                <small class="text-danger" style="font-size: 0.75rem;">Requires Attention</small>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">LANGUAGES</span>
                    <div class="stat-icon-wrapper stat-icon-primary"><i class="bi bi-translate"></i></div>
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
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h5><i class="bi bi-envelope-open-fill text-warning me-2"></i>Recent Customer Enquiries</h5>
                    <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table admin-table">
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
                                <tr>
                                    <td class="text-muted small">{{ $enq->created_at->format('M d, H:i') }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $enq->name }}</div>
                                        <small class="text-muted">{{ $enq->phone ?? $enq->email }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 240px;">
                                            @if($enq->product)
                                                <span class="badge bg-light text-dark border me-1">{{ $enq->product->name }}</span>
                                            @endif
                                            {{ $enq->subject }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($enq->status === 'unread')
                                            <span class="badge-status badge-unread">Unread</span>
                                        @elseif($enq->status === 'read')
                                            <span class="badge-status badge-read">Read</span>
                                        @elseif($enq->status === 'contacted')
                                            <span class="badge-status badge-contacted">Contacted</span>
                                        @else
                                            <span class="badge-status badge-resolved">Resolved</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.enquiries.show', $enq) }}" class="btn btn-sm btn-light border py-1 px-2">
                                            <i class="bi bi-eye"></i> View
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

        <!-- Recent Activity Logs -->
        <div class="col-lg-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h5><i class="bi bi-clock-history text-primary me-2"></i>Audit Trail</h5>
                </div>
                <div class="admin-card-body p-3">
                    <ul class="list-unstyled mb-0" style="font-size: 0.88rem;">
                        @forelse($recentActivities as $act)
                            <li class="mb-3 pb-3 border-bottom d-flex align-items-start gap-2">
                                <i class="bi bi-check2-circle text-success mt-1"></i>
                                <div class="overflow-hidden">
                                    <div class="text-dark fw-semibold text-truncate">{{ $act->description ?? $act->action }}</div>
                                    <small class="text-muted d-block">{{ $act->created_at->diffForHumans() }} &bull; {{ $act->user->name ?? 'System' }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-center py-3">No activity logs recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
