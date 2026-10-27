@extends('admin.layouts.admin')

@section('title', 'Add Machinery Product')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Machinery List
        </a>
        <h2 class="fw-bold text-dark mb-1">Add Machinery Product</h2>
        <p class="text-muted small mb-0">Create a new agricultural implement with technical specs, brochure, and multilingual translations.</p>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Left 8 Cols: Multilingual Content & Specs -->
            <div class="col-lg-8">
                <!-- Multilingual Information -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-translate text-success me-2"></i>Multilingual Content</h5>
                    </div>
                    <div class="admin-card-body">
                        <!-- Language Tabs Header -->
                        <ul class="nav nav-tabs multilingual-tabs" id="prodLangTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="prodtab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#prodcontent-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Language Tabs Content -->
                        <div class="tab-content" id="prodLangTabsContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="prodcontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Product Name ({{ $lang->name }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][name]" class="form-control" placeholder="e.g. {{ $lang->code === 'hi' ? 'एग्रोविज़न गोल्ड रोटावेटर' : 'AgroVision Gold Multi-Speed Rotary Tiller' }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Language URL Slug</label>
                                        <input type="text" name="translations[{{ $lang->code }}][slug]" class="form-control" placeholder="e.g. gold-rotary-tiller-{{ $lang->code }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Short Summary Description</label>
                                        <textarea name="translations[{{ $lang->code }}][short_description]" rows="2" class="form-control" placeholder="Concise 1-2 sentence overview for cards and listings..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Comprehensive Description (HTML allowed)</label>
                                        <textarea name="translations[{{ $lang->code }}][description]" rows="8" class="form-control" placeholder="<p>Full engineering specifications, metallurgy details, and field benefits...</p>"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Key Highlight Bullet Points (1 per line)</label>
                                        <textarea name="translations[{{ $lang->code }}][features]" rows="4" class="form-control" placeholder="Heavy-duty 4-speed multi-gearbox&#10;Heat-treated boron steel L/C blades&#10;Oil bath side gear drive"></textarea>
                                    </div>

                                    <div class="p-3 bg-light rounded-3 border">
                                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-search text-warning me-1"></i> SEO Meta ({{ $lang->name }})</h6>
                                        <div class="mb-2">
                                            <input type="text" name="translations[{{ $lang->code }}][meta_title]" class="form-control" placeholder="Meta title">
                                        </div>
                                        <div>
                                            <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2" class="form-control" placeholder="Meta description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Technical Specifications Table Builder -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-table text-warning me-2"></i>Technical Specifications Table</h5>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addSpecRow()">
                            <i class="bi bi-plus me-1"></i> Add Spec Row
                        </button>
                    </div>
                    <div class="admin-card-body p-0">
                        <div class="table-responsive">
                            <table class="table admin-table mb-0" id="specsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 35%;">Parameter (EN / HI)</th>
                                        <th style="width: 55%;">Value / Specification (EN / HI)</th>
                                        <th style="width: 10%;" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="specRow0">
                                        <td>
                                            <input type="text" name="specs[0][en][key]" value="Tractor Power Requirement" class="form-control form-control-sm mb-1" placeholder="Parameter (EN)">
                                            <input type="text" name="specs[0][hi][key]" value="ट्रैक्टर पावर आवश्यकता" class="form-control form-control-sm" placeholder="पैरामीटर (HI)">
                                        </td>
                                        <td>
                                            <input type="text" name="specs[0][en][val]" value="45 - 65 HP" class="form-control form-control-sm mb-1" placeholder="Value (EN)">
                                            <input type="text" name="specs[0][hi][val]" value="45 - 65 एचपी" class="form-control form-control-sm" placeholder="मान (HI)">
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeSpecRow('specRow0')"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr id="specRow1">
                                        <td>
                                            <input type="text" name="specs[1][en][key]" value="Working Width" class="form-control form-control-sm mb-1" placeholder="Parameter (EN)">
                                            <input type="text" name="specs[1][hi][key]" value="कार्य चौड़ाई" class="form-control form-control-sm" placeholder="पैरामीटर (HI)">
                                        </td>
                                        <td>
                                            <input type="text" name="specs[1][en][val]" value="2.0 Meters (7 Feet)" class="form-control form-control-sm mb-1" placeholder="Value (EN)">
                                            <input type="text" name="specs[1][hi][val]" value="2.0 मीटर (7 फीट)" class="form-control form-control-sm" placeholder="मान (HI)">
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeSpecRow('specRow1')"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 4 Cols: Attributes & Media -->
            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-gear-fill text-secondary me-2"></i>Classification & Specs</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Select Product Category --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">SKU / Model Number</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" class="form-control" placeholder="e.g. AGV-ROT-7FT">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary System Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" required placeholder="e.g. gold-rotary-tiller-rotavator">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Min HP</label>
                                <input type="number" name="tractor_hp_min" value="{{ old('tractor_hp_min') }}" class="form-control" placeholder="45">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Max HP</label>
                                <input type="number" name="tractor_hp_max" value="{{ old('tractor_hp_max') }}" class="form-control" placeholder="65">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Working Width</label>
                            <input type="text" name="working_width" value="{{ old('working_width') }}" class="form-control" placeholder="e.g. 2.0 Meters (7 Feet)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Weight Approx</label>
                            <input type="text" name="weight_approx" value="{{ old('weight_approx') }}" class="form-control" placeholder="e.g. 485 kg">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Blades Count (if applicable)</label>
                            <input type="number" name="blades_count" value="{{ old('blades_count') }}" class="form-control" placeholder="e.g. 48">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="pFeatured" value="1" checked>
                            <label class="form-check-label fw-semibold small" for="pFeatured">Feature on Homepage</label>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="pActive" value="1" checked>
                            <label class="form-check-label fw-semibold small" for="pActive">Active & Enabled</label>
                        </div>
                    </div>
                </div>

                <!-- Media & Documents Card -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Media & Brochure</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary Machinery Photo</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <small class="text-muted">High resolution JPG/PNG (up to 5MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Downloadable PDF Brochure</label>
                            <input type="file" name="brochure_pdf" class="form-control" accept="application/pdf">
                            <small class="text-muted">Official technical PDF leaflet (up to 10MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Related Machinery Models</label>
                            <select name="related_ids[]" class="form-select" multiple size="4">
                                @foreach($allProducts as $ap)
                                    <option value="{{ $ap->id }}">{{ $ap->name }} ({{ $ap->hp_range }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple related items</small>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Save & Publish Machinery
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    let specIndex = 2;
    function addSpecRow() {
        const tbody = document.querySelector('#specsTable tbody');
        const row = document.createElement('tr');
        row.id = 'specRow' + specIndex;
        row.innerHTML = `
            <td>
                <input type="text" name="specs[${specIndex}][en][key]" class="form-control form-control-sm mb-1" placeholder="Parameter (EN)">
                <input type="text" name="specs[${specIndex}][hi][key]" class="form-control form-control-sm" placeholder="पैरामीटर (HI)">
            </td>
            <td>
                <input type="text" name="specs[${specIndex}][en][val]" class="form-control form-control-sm mb-1" placeholder="Value (EN)">
                <input type="text" name="specs[${specIndex}][hi][val]" class="form-control form-control-sm" placeholder="मान (HI)">
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeSpecRow('specRow${specIndex}')"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        specIndex++;
    }

    function removeSpecRow(id) {
        const row = document.getElementById(id);
        if (row) row.remove();
    }
</script>
@endpush
