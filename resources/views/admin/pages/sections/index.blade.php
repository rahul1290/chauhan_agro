@extends('admin.layouts.admin')

@section('title', 'Sections for ' . $page->title)

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Page Details
            </a>
            <h2 class="fw-bold text-dark mb-1">Section Builder: {{ $page->title ?: $page->slug }}</h2>
            <p class="text-muted small mb-0">Add, reorder, configure, and customize reusable dynamic content components for this page.</p>
        </div>
        <a href="{{ route('admin.pages.sections.create', $page) }}" class="btn btn-agro-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add New Section
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-layers-fill text-warning me-2"></i>Page Components ({{ $sections->count() }})</h5>
            <span class="small text-muted">Drag or use arrow buttons to arrange order</span>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Order</th>
                        <th>Component Type</th>
                        <th>Title / Headline</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $sec)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold text-muted me-1">#{{ $sec->order }}</span>
                                    <form action="{{ route('admin.pages.sections.reorder', [$page, $sec, 'up']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light border py-0 px-1" title="Move Up"><i class="bi bi-chevron-up"></i></button>
                                    </form>
                                    <form action="{{ route('admin.pages.sections.reorder', [$page, $sec, 'down']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light border py-0 px-1" title="Move Down"><i class="bi bi-chevron-down"></i></button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark font-monospace fw-bold px-2 py-1">
                                    {{ $sec->type }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $sec->title ?: 'Untitled Section' }}</div>
                                @if($sec->subtitle)
                                    <small class="text-muted">{{ Str::limit($sec->subtitle, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($sec->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-1">Disabled</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.pages.sections.edit', [$page, $sec]) }}" class="btn btn-sm btn-outline-primary" title="Edit Component">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.pages.sections.destroy', [$page, $sec]) }}" method="POST" onsubmit="return confirm('Delete this section?');">
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
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-layers display-6 d-block mb-2 text-muted"></i>
                                No dynamic sections added to this page yet.
                                <div class="mt-3">
                                    <a href="{{ route('admin.pages.sections.create', $page) }}" class="btn btn-sm btn-agro-primary">
                                        <i class="bi bi-plus-circle me-1"></i> Add First Section
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
