@extends('admin.layouts.admin')

@section('title', 'Customer Enquiries')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Customer Enquiries & Quotations</h2>
            <p class="text-muted small mb-0">Track commercial leads, quotation requests, dealership inquiries, and follow-up history.</p>
        </div>
        <a href="{{ route('admin.enquiries.export') }}" class="btn btn-agro-primary btn-sm">
            <i class="bi bi-file-earmark-spreadsheet-fill me-1"></i> Export to CSV
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills bg-white p-2 rounded-4 shadow-sm border gap-1">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active bg-dark fw-bold' : 'text-dark' }}" href="{{ route('admin.enquiries.index') }}">
                    All Leads <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'unread' ? 'active bg-danger fw-bold' : 'text-danger' }}" href="{{ route('admin.enquiries.index', ['status' => 'unread']) }}">
                    Unread <span class="badge bg-danger ms-1">{{ $counts['unread'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'read' ? 'active bg-info fw-bold' : 'text-dark' }}" href="{{ route('admin.enquiries.index', ['status' => 'read']) }}">
                    Read <span class="badge bg-info ms-1">{{ $counts['read'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'contacted' ? 'active bg-warning text-dark fw-bold' : 'text-dark' }}" href="{{ route('admin.enquiries.index', ['status' => 'contacted']) }}">
                    Contacted <span class="badge bg-warning text-dark ms-1">{{ $counts['contacted'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'resolved' ? 'active bg-success fw-bold' : 'text-dark' }}" href="{{ route('admin.enquiries.index', ['status' => 'resolved']) }}">
                    Resolved <span class="badge bg-success ms-1">{{ $counts['resolved'] }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.enquiries.index') }}" method="GET" class="row g-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by customer name, email, phone, company, or subject...">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-agro-primary btn-sm flex-grow-1">Search</button>
                @if(request()->hasAny(['search']))
                    <a href="{{ route('admin.enquiries.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-light btn-sm border">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Enquiries Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Date</th>
                        <th>Customer / Location</th>
                        <th>Target Machinery / Subject</th>
                        <th>Language</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries as $enq)
                        <tr class="{{ $enq->status === 'unread' ? 'table-warning bg-opacity-10' : '' }}">
                            <td class="text-muted small">
                                <div>{{ $enq->created_at->format('M d, Y') }}</div>
                                <small style="font-size: 0.72rem;">{{ $enq->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $enq->name }}</div>
                                <div class="small text-muted">
                                    <i class="bi bi-telephone text-secondary me-1"></i>{{ $enq->phone ?? '-' }}
                                    @if($enq->email)
                                        &bull; {{ $enq->email }}
                                    @endif
                                </div>
                                @if($enq->city || $enq->country)
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $enq->city ?? '' }}{{ $enq->city && $enq->country ? ', ' : '' }}{{ $enq->country ?? '' }}</small>
                                @endif
                            </td>
                            <td>
                                @if($enq->product)
                                    <div class="badge bg-light text-dark border mb-1">
                                        <i class="bi bi-tractor text-success me-1"></i> {{ $enq->product->name }}
                                    </div>
                                @endif
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 280px;" title="{{ $enq->subject }}">
                                    {{ $enq->subject }}
                                </div>
                                <small class="text-muted text-truncate d-block" style="max-width: 280px;">{{ $enq->message }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">{{ $enq->locale }}</span>
                            </td>
                            <td>
                                @if($enq->status === 'unread')
                                    <span class="badge-status badge-unread">Unread</span>
                                @elseif($enq->status === 'read')
                                    <span class="badge-status badge-read">Read</span>
                                @elseif($enq->status === 'contacted')
                                    <span class="badge-status badge-contacted">Contacted</span>
                                @elseif($enq->status === 'resolved')
                                    <span class="badge-status badge-resolved">Resolved</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ ucfirst($enq->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.enquiries.show', $enq) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye-fill me-1"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No customer enquiries found in this view.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enquiries->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $enquiries->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
