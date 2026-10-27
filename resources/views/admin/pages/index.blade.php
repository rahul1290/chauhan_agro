@extends('admin.layouts.admin')

@section('title', 'Page Management')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">CMS Page Management</h2>
            <p class="text-muted small mb-0">Create and edit website pages, manage multilingual content, and build dynamic reusable sections.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-agro-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Create New Page
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.pages.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search page title or slug...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- All Publication Statuses --</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-agro-primary btn-sm">Apply Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-light btn-sm border">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Pages Table Card -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Title / Slug</th>
                        <th>Template</th>
                        <th>Translations</th>
                        <th>Sections</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $page->title ?: 'Untitled Page' }}</div>
                                <small class="text-muted"><code>/{{ $page->slug }}</code></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">{{ $page->template }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @foreach($languages as $lang)
                                        @if($page->hasTranslation($lang->code))
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1" title="{{ $lang->name }}: Translated">
                                                {{ $lang->code }} <i class="bi bi-check2"></i>
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1" title="{{ $lang->name }}: Missing Translation">
                                                {{ $lang->code }} <i class="bi bi-x"></i>
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.pages.sections.index', $page) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-layers-fill text-warning me-1"></i>
                                    {{ $page->sections()->count() }} Sections
                                </a>
                            </td>
                            <td>
                                @if($page->is_published)
                                    <span class="badge-status badge-published">Published</span>
                                @else
                                    <span class="badge-status badge-draft">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ loc_url('/' . ($page->slug === 'home' ? '' : $page->slug)) }}" target="_blank" class="btn btn-sm btn-light border" title="Preview Live">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary" title="Edit Page Content">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(!in_array($page->slug, ['home', 'contact', 'about-us']))
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Page">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No pages found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $pages->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
