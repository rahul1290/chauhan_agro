@extends('admin.layouts.admin')

@section('title', 'Hero Banners & Sliders')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Hero Sliders & Banners</h2>
            <p class="text-muted small mb-0">Manage high-impact homepage visual banners, CTA buttons, and multilingual headlines.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-agro-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Banner Slide
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Preview</th>
                        <th>Banner Headline / Badge</th>
                        <th>CTA Link</th>
                        <th>Translations</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $b)
                        <tr>
                            <td>
                                <div class="rounded-3 border overflow-hidden" style="width: 100px; height: 55px;">
                                    <img src="{{ asset($b->image_path) }}" alt="{{ $b->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                @if($b->badge_text)
                                    <span class="badge bg-warning text-dark mb-1">{{ $b->badge_text }}</span>
                                @endif
                                <div class="fw-bold text-dark">{{ $b->title }}</div>
                                <small class="text-muted">{{ Str::limit($b->subtitle, 80) }}</small>
                            </td>
                            <td>
                                @if($b->button_text)
                                    <div><span class="badge bg-light text-dark border">{{ $b->button_text }}</span></div>
                                    <small class="text-muted"><code>{{ $b->button_url }}</code></small>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($b->hasTranslation('en')) <span class="badge bg-success bg-opacity-10 text-success">EN</span> @endif
                                    @if($b->hasTranslation('hi')) <span class="badge bg-success bg-opacity-10 text-success">HI</span> @endif
                                </div>
                            </td>
                            <td>
                                @if($b->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.banners.edit', $b) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" onsubmit="return confirm('Delete this banner slide?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No banners found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
