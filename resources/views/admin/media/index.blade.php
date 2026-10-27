@extends('admin.layouts.admin')

@section('title', 'Media Library')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Central Media & Document Library</h2>
            <p class="text-muted small mb-0">Upload, manage, search, and reuse images, diagrams, and PDF brochures across CMS pages.</p>
        </div>
        <button class="btn btn-agro-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Files
        </button>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.media.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search file name...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">-- All File Types --</option>
                    <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images (JPG, PNG, WebP)</option>
                    <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>Brochures (PDF)</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-agro-primary btn-sm">Filter</button>
                @if(request()->hasAny(['search', 'type']))
                    <a href="{{ route('admin.media.index') }}" class="btn btn-light btn-sm border">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Media Grid -->
    <div class="admin-card">
        <div class="admin-card-body">
            @if($mediaItems->isNotEmpty())
                <div class="row g-3">
                    @foreach($mediaItems as $media)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                            <div class="card h-100 border rounded-3 overflow-hidden shadow-sm">
                                <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 130px; overflow: hidden;">
                                    @if($media->isImage())
                                        <img src="{{ $media->url }}" alt="{{ $media->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif($media->isPdf())
                                        <div class="text-center p-2 text-danger">
                                            <i class="bi bi-file-earmark-pdf-fill display-4"></i>
                                            <div class="small fw-bold text-truncate" style="max-width: 110px;">PDF Document</div>
                                        </div>
                                    @else
                                        <div class="text-center p-2 text-secondary">
                                            <i class="bi bi-file-earmark-text-fill display-4"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-2">
                                    <div class="fw-bold small text-truncate" title="{{ $media->name }}">{{ $media->name }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $media->formatted_size }} &bull; {{ $media->created_at->format('M d') }}</small>
                                </div>
                                <div class="card-footer p-2 bg-white border-top d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2" style="font-size: 0.75rem;" onclick="navigator.clipboard.writeText('{{ $media->url }}'); alert('Copied URL to clipboard: {{ $media->url }}');" title="Copy URL">
                                        <i class="bi bi-link-45deg"></i> Copy
                                    </button>
                                    <form action="{{ route('admin.media.destroy', $media) }}" method="POST" onsubmit="return confirm('Delete this media file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($mediaItems->hasPages())
                    <div class="mt-4 pt-3 border-top d-flex justify-content-center">
                        {{ $mediaItems->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cloud-upload display-4 d-block mb-3 text-secondary"></i>
                    No media assets uploaded yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Media Modal -->
    <div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Upload Media Files</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Select Files <span class="text-danger">*</span></label>
                            <input type="file" name="files[]" class="form-control" multiple required accept="image/*,application/pdf">
                            <small class="text-muted d-block mt-1">Supported: JPG, PNG, WebP, SVG, PDF (Max 15MB each)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-agro-primary">Start Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
