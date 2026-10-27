@extends('admin.layouts.admin')

@section('title', 'Edit Machinery: ' . $product->name)

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Machinery List
            </a>
            <h2 class="fw-bold text-dark mb-1">Edit Machinery: {{ $product->name }}</h2>
            <p class="text-muted small mb-0">Update technical specs, multilingual copy, photo gallery, and PDF brochure.</p>
        </div>
        <a href="{{ loc_url('/products/' . $product->slug) }}" target="_blank" class="btn btn-light btn-sm border">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Live on Site
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left 8 Cols: Multilingual Content & Specs -->
            <div class="col-lg-8">
                <!-- Multilingual Information -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-translate text-success me-2"></i>Multilingual Details</h5>
                    </div>
                    <div class="admin-card-body">
                        <!-- Language Tabs Header -->
                        <ul class="nav nav-tabs multilingual-tabs" id="prodLangTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                @php $trans = $translations->get($lang->code); @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="prodtab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#prodcontent-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                        @if($trans) <i class="bi bi-check2 text-success"></i> @endif
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Language Tabs Content -->
                        <div class="tab-content" id="prodLangTabsContent">
                            @foreach($languages as $index => $lang)
                                @php $trans = $translations->get($lang->code); @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="prodcontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Product Name ({{ $lang->name }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][name]" value="{{ old('translations.' . $lang->code . '.name', $trans?->name) }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Language URL Slug</label>
                                        <input type="text" name="translations[{{ $lang->code }}][slug]" value="{{ old('translations.' . $lang->code . '.slug', $trans?->slug) }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Short Summary Description</label>
                                        <textarea name="translations[{{ $lang->code }}][short_description]" rows="2" class="form-control">{{ old('translations.' . $lang->code . '.short_description', $trans?->short_description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Comprehensive Description (HTML allowed)</label>
                                        <textarea name="translations[{{ $lang->code }}][description]" rows="8" class="form-control">{{ old('translations.' . $lang->code . '.description', $trans?->description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Key Highlight Bullet Points (1 per line)</label>
                                        @php
                                            $featuresStr = is_array($trans?->features_json) ? implode("\n", $trans->features_json) : '';
                                        @endphp
                                        <textarea name="translations[{{ $lang->code }}][features]" rows="4" class="form-control">{{ old('translations.' . $lang->code . '.features', $featuresStr) }}</textarea>
                                    </div>

                                    <div class="p-3 bg-light rounded-3 border">
                                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-search text-warning me-1"></i> SEO Meta ({{ $lang->name }})</h6>
                                        <div class="mb-2">
                                            <input type="text" name="translations[{{ $lang->code }}][meta_title]" value="{{ old('translations.' . $lang->code . '.meta_title', $trans?->meta_title) }}" class="form-control" placeholder="Meta title">
                                        </div>
                                        <div>
                                            <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2" class="form-control" placeholder="Meta description">{{ old('translations.' . $lang->code . '.meta_description', $trans?->meta_description) }}</textarea>
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
                                    @php $specCounter = 0; @endphp
                                    @foreach($specs as $spec)
                                        @php
                                            $enSpec = $spec->translations->firstWhere('locale', 'en');
                                            $hiSpec = $spec->translations->firstWhere('locale', 'hi');
                                            $rowId = 'specRow' . $specCounter;
                                        @endphp
                                        <tr id="{{ $rowId }}">
                                            <td>
                                                <input type="text" name="specs[{{ $specCounter }}][en][key]" value="{{ $enSpec?->spec_key }}" class="form-control form-control-sm mb-1" placeholder="Parameter (EN)">
                                                <input type="text" name="specs[{{ $specCounter }}][hi][key]" value="{{ $hiSpec?->spec_key }}" class="form-control form-control-sm" placeholder="पैरामीटर (HI)">
                                            </td>
                                            <td>
                                                <input type="text" name="specs[{{ $specCounter }}][en][val]" value="{{ $enSpec?->spec_value }}" class="form-control form-control-sm mb-1" placeholder="Value (EN)">
                                                <input type="text" name="specs[{{ $specCounter }}][hi][val]" value="{{ $hiSpec?->spec_value }}" class="form-control form-control-sm" placeholder="मान (HI)">
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeSpecRow('{{ $rowId }}')"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                        @php $specCounter++; @endphp
                                    @endforeach
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
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">SKU / Model Number</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary System Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-control" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Min HP</label>
                                <input type="number" name="tractor_hp_min" value="{{ old('tractor_hp_min', $product->tractor_hp_min) }}" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Max HP</label>
                                <input type="number" name="tractor_hp_max" value="{{ old('tractor_hp_max', $product->tractor_hp_max) }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Working Width</label>
                            <input type="text" name="working_width" value="{{ old('working_width', $product->working_width) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Weight Approx</label>
                            <input type="text" name="weight_approx" value="{{ old('weight_approx', $product->weight_approx) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Blades Count</label>
                            <input type="number" name="blades_count" value="{{ old('blades_count', $product->blades_count) }}" class="form-control">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="pFeatured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="pFeatured">Feature on Homepage</label>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="pActive" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
                            @if($product->featured_image)
                                <div class="mb-2 rounded border overflow-hidden" style="max-height: 120px;">
                                    <img src="{{ asset($product->featured_image) }}" alt="Preview" class="img-fluid w-100">
                                </div>
                            @endif
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Downloadable PDF Brochure</label>
                            @if($product->brochure_pdf)
                                <div class="mb-2 small">
                                    <a href="{{ asset($product->brochure_pdf) }}" target="_blank" class="text-danger fw-bold">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Current Brochure PDF
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="brochure_pdf" class="form-control" accept="application/pdf">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Related Machinery Models</label>
                            @php
                                $currentRelatedIds = $product->relatedProducts->pluck('id')->toArray();
                            @endphp
                            <select name="related_ids[]" class="form-select" multiple size="4">
                                @foreach($allProducts as $ap)
                                    <option value="{{ $ap->id }}" {{ in_array($ap->id, $currentRelatedIds) ? 'selected' : '' }}>
                                        {{ $ap->name }} ({{ $ap->hp_range }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Update Machinery Product
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
    let specIndex = {{ $specCounter ?? 10 }};
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
