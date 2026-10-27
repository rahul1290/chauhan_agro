@extends('admin.layouts.admin')

@section('title', 'Language Management')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Multilingual Management</h2>
            <p class="text-muted small mb-0">Configure supported languages, set default locale, and control translation activation across the website.</p>
        </div>
        <button class="btn btn-agro-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLangModal">
            <i class="bi bi-plus-circle me-1"></i> Add Language
        </button>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-translate text-success me-2"></i>Active Languages ({{ $languages->count() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Flag</th>
                        <th>Locale Code</th>
                        <th>Language Name</th>
                        <th>Native Name</th>
                        <th>Default</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($languages as $lang)
                        <tr>
                            <td class="fs-4">{{ $lang->flag ?? '🌐' }}</td>
                            <td><code>{{ $lang->code }}</code></td>
                            <td class="fw-bold text-dark">{{ $lang->name }}</td>
                            <td>{{ $lang->native_name ?? '-' }}</td>
                            <td>
                                @if($lang->is_default)
                                    <span class="badge bg-success"><i class="bi bi-star-fill me-1"></i> Default</span>
                                @else
                                    <form action="{{ route('admin.languages.setDefault', $lang) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">Set Default</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($lang->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('admin.languages.toggleStatus', $lang) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $lang->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="Toggle Status">
                                            <i class="bi {{ $lang->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editLangModal{{ $lang->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>

                                <!-- Edit Language Modal -->
                                <div class="modal fade text-start" id="editLangModal{{ $lang->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.languages.update', $lang) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Language: {{ $lang->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Locale Code (read-only)</label>
                                                        <input type="text" class="form-control" value="{{ $lang->code }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Language Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $lang->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Native Script Name</label>
                                                        <input type="text" name="native_name" class="form-control" value="{{ $lang->native_name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Flag Emoji</label>
                                                        <input type="text" name="flag" class="form-control" value="{{ $lang->flag }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-agro-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Language Modal -->
    <div class="modal fade" id="addLangModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.languages.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Supported Language</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ISO 639-1 Language Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. mr, gu, fr, es" required maxlength="10">
                            <small class="text-muted">Short 2-letter locale prefix used in URLs (e.g. /mr/products)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Language Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Marathi, Gujarati" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Native Name</label>
                            <input type="text" name="native_name" class="form-control" placeholder="e.g. मराठी, ગુજરાતી">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Flag Emoji</label>
                            <input type="text" name="flag" class="form-control" placeholder="e.g. 🇮🇳">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-agro-primary">Add Language</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
