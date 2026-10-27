@extends('admin.layouts.admin')

@section('title', 'Enquiry #' . $enquiry->id . ' - ' . $enquiry->name)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Enquiry Inbox
        </a>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Enquiry Lead #{{ $enquiry->id }}</h2>
                <p class="text-muted small mb-0">Received on {{ $enquiry->created_at->format('l, F j, Y \a\t h:i A') }} ({{ $enquiry->created_at->diffForHumans() }})</p>
            </div>
            <div>
                <span class="badge-status badge-{{ $enquiry->status }} fs-6">{{ ucfirst($enquiry->status) }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left 8 Cols: Message & Notes -->
        <div class="col-lg-8">
            <!-- Customer Message Card -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5><i class="bi bi-chat-left-text-fill text-warning me-2"></i>Inquiry Subject & Message</h5>
                    <span class="badge bg-light text-dark border text-uppercase">Locale: {{ $enquiry->locale }}</span>
                </div>
                <div class="admin-card-body">
                    <h5 class="fw-bold text-dark mb-3">{{ $enquiry->subject }}</h5>
                    <div class="p-4 bg-light rounded-4 border mb-4" style="line-height: 1.8; font-size: 1.05rem; white-space: pre-line; color: #1e293b;">
                        {{ $enquiry->message }}
                    </div>

                    @if($enquiry->product)
                        <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between bg-white">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset($enquiry->product->featured_image ?? 'assets/images/products/gold-rotary-tiller.jpg') }}" alt="" class="rounded border" style="width: 50px; height: 40px; object-fit: cover;">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Referenced Machinery Model</small>
                                    <strong class="text-dark">{{ $enquiry->product->name }}</strong>
                                    <span class="badge bg-dark bg-opacity-10 text-dark ms-1">{{ $enquiry->product->hp_range }}</span>
                                </div>
                            </div>
                            <a href="{{ loc_url('/products/' . $enquiry->product->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                View Specs <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Internal Follow-up Notes Card -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-journal-text text-primary me-2"></i>Internal Sales Notes ({{ $enquiry->notes->count() }})</h5>
                </div>
                <div class="admin-card-body">
                    <!-- Existing Notes -->
                    @forelse($enquiry->notes as $note)
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark small"><i class="bi bi-person-fill text-secondary me-1"></i>{{ $note->user->name ?? 'Sales Representative' }}</strong>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $note->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="text-secondary mb-0 small" style="line-height: 1.6;">{{ $note->note }}</p>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small mb-3">No internal follow-up notes recorded yet.</div>
                    @endforelse

                    <!-- Add Note Form -->
                    <form action="{{ route('admin.enquiries.addNote', $enquiry) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Add Follow-Up / Quotation Note</label>
                            <textarea name="note" rows="3" class="form-control" required placeholder="Log call outcome, quotation sent, or dealer assignment details..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-agro-primary btn-sm px-3">
                                <i class="bi bi-plus-circle me-1"></i> Append Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right 4 Cols: Contact Details & Status Updater -->
        <div class="col-lg-4">
            <!-- Pipeline Status Changer -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5><i class="bi bi-arrow-repeat text-success me-2"></i>Lead Status Pipeline</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.enquiries.updateStatus', $enquiry) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Current Pipeline Stage</label>
                            <select name="status" class="form-select">
                                <option value="unread" {{ $enquiry->status === 'unread' ? 'selected' : '' }}>Unread (New Lead)</option>
                                <option value="read" {{ $enquiry->status === 'read' ? 'selected' : '' }}>Read (In Review)</option>
                                <option value="contacted" {{ $enquiry->status === 'contacted' ? 'selected' : '' }}>Contacted (Quote Sent)</option>
                                <option value="resolved" {{ $enquiry->status === 'resolved' ? 'selected' : '' }}>Resolved (Closed / Order Placed)</option>
                                <option value="spam" {{ $enquiry->status === 'spam' ? 'selected' : '' }}>Mark as Spam</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-agro-secondary w-100 btn-sm py-2 fw-bold">
                            Update Lead Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer Contact Information Card -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-person-lines-fill text-secondary me-2"></i>Customer Profile</h5>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Full Name</small>
                        <h6 class="fw-bold text-dark mb-0">{{ $enquiry->name }}</h6>
                    </div>

                    @if($enquiry->company)
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Company / Farm Entity</small>
                            <div class="fw-semibold text-dark">{{ $enquiry->company }}</div>
                        </div>
                    @endif

                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Email Address</small>
                        <div class="fw-semibold text-dark mb-1">{{ $enquiry->email }}</div>
                        <a href="mailto:{{ $enquiry->email }}?subject=Re: {{ rawurlencode($enquiry->subject) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">
                            <i class="bi bi-envelope me-1"></i> Send Direct Email
                        </a>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Phone Number</small>
                        <div class="fw-semibold text-dark mb-1">{{ $enquiry->phone ?? 'Not provided' }}</div>
                        @if($enquiry->phone)
                            <a href="tel:{{ $enquiry->phone }}" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.75rem;">
                                <i class="bi bi-telephone me-1"></i> Call Phone
                            </a>
                        @endif
                    </div>

                    @if($enquiry->city || $enquiry->country)
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Location</small>
                            <div class="fw-semibold text-dark">{{ $enquiry->city ?? '' }}{{ $enquiry->city && $enquiry->country ? ', ' : '' }}{{ $enquiry->country ?? '' }}</div>
                        </div>
                    @endif

                    <div class="mb-0">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">IP Address</small>
                        <code class="small text-muted">{{ $enquiry->ip_address ?? '127.0.0.1' }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
