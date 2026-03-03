@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold" style="color: var(--color-primary-dark);">
                    <i class="fa-solid fa-gears me-2"></i>Advanced System Settings
                </h4>
                <p class="text-muted">Configure global system behavior and feature access per role.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('superadmin.settings.update') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-lg-3">
                    <!-- Tabs Navigation -->
                    <div class="card border-0 shadow-sm sticky-top" style="border-radius: 16px; top: 20px;">
                        <div class="card-body p-3">
                            <div class="nav flex-column nav-pills" id="settings-tabs" role="tablist">
                                @foreach($categories as $categoryName => $fields)
                                    <button class="nav-link @if($loop->first) active @endif mb-2 text-start d-flex align-items-center" 
                                            id="tab-{{ Str::slug($categoryName) }}" 
                                            data-bs-toggle="pill" 
                                            data-bs-target="#content-{{ Str::slug($categoryName) }}" 
                                            type="button" 
                                            role="tab"
                                            style="border-radius: 10px; font-weight: 600; padding: 12px 16px;">
                                        <i class="fa-solid @if($categoryName == 'General') fa-display @elseif($categoryName == 'POS & Struk') fa-receipt @elseif($categoryName == 'Admin Features') fa-user-shield @elseif($categoryName == 'Warehouse Features') fa-boxes-stacked @elseif($categoryName == 'Cashier Features') fa-cash-register @else fa-microchip @endif me-3"></i>
                                        {{ $categoryName }}
                                    </button>
                                @endforeach
                            </div>
                            <hr class="my-3" style="border-color: var(--brown-100);">
                            <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm" style="background: var(--color-primary-dark); border: none; border-radius: 12px; font-weight: 700;">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <!-- Tabs Content -->
                    <div class="tab-content" id="settings-content">
                        @foreach($categories as $categoryName => $fields)
                            <div class="tab-pane fade @if($loop->first) show active @endif" 
                                 id="content-{{ Str::slug($categoryName) }}" 
                                 role="tabpanel">
                                
                                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                                    <div class="card-header bg-white py-4 px-4" style="border-bottom: 2px solid var(--brown-100); border-radius: 16px 16px 0 0;">
                                        <h5 class="mb-0 fw-bold" style="color: var(--color-primary-dark);">{{ $categoryName }} Settings</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            @foreach($fields as $key => $config)
                                                <div class="col-12 mb-4">
                                                    @if($config['type'] === 'boolean')
                                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light" style="border-radius: 12px;">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold">{{ $config['label'] }}</h6>
                                                                <p class="text-muted small mb-0">Enable or disable this module for the respective role.</p>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="{{ $key }}" id="{{ $key }}" 
                                                                       @if($settings->get($key, 'true') === 'true') checked @endif
                                                                       style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
                                                            </div>
                                                        </div>
                                                    @elseif($config['type'] === 'select')
                                                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">{{ $config['label'] }}</label>
                                                        <select class="form-select form-select-lg" name="{{ $key }}" id="{{ $key }}"
                                                                style="border-radius: 12px; border: 2px solid var(--brown-100); padding: 14px 20px; font-weight: 600;">
                                                            @foreach($config['options'] as $optVal => $optLabel)
                                                                <option value="{{ $optVal }}" @if($settings->get($key, $config['default']) == $optVal) selected @endif>{{ $optLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($config['type'] === 'palette')
                                                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">{{ $config['label'] }}</label>
                                                        <div class="row g-3" id="palette-picker">
                                                            @foreach($palettePreviews as $paletteKey => $preview)
                                                                <div class="col-6 col-md-4">
                                                                    <label class="palette-card d-block p-3 rounded-4 border-2 cursor-pointer transition-all {{ $settings->get($key, $config['default']) == $paletteKey ? 'palette-active' : '' }}"
                                                                           style="border: 2px solid var(--brown-200); border-radius: 16px !important; cursor: pointer; transition: all 0.3s ease;"
                                                                           for="palette_{{ $paletteKey }}">
                                                                        <input type="radio" name="{{ $key }}" id="palette_{{ $paletteKey }}" 
                                                                               value="{{ $paletteKey }}" class="d-none palette-radio"
                                                                               @if($settings->get($key, $config['default']) == $paletteKey) checked @endif>
                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <i class="fa-solid {{ $preview['icon'] }} me-2" style="color: {{ $preview['colors'][0] }}; font-size: 1.1rem;"></i>
                                                                            <span class="fw-bold small">{{ $preview['name'] }}</span>
                                                                        </div>
                                                                        <div class="d-flex gap-1">
                                                                            @foreach($preview['colors'] as $color)
                                                                                <div style="width: 100%; height: 28px; background: {{ $color }}; border-radius: 8px;"></div>
                                                                            @endforeach
                                                                        </div>
                                                                        <div class="palette-check text-center mt-2 {{ $settings->get($key, $config['default']) == $paletteKey ? '' : 'd-none' }}">
                                                                            <i class="fa-solid fa-circle-check" style="color: {{ $preview['colors'][0] }};"></i>
                                                                            <span class="small fw-bold ms-1" style="color: {{ $preview['colors'][0] }};">Aktif</span>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div @if($key === 'custom_primary_color') id="custom-color-container" style="display: {{ $settings->get('site_color_theme') === 'custom' ? 'block' : 'none' }};" @endif>
                                                            <label class="form-label fw-bold text-muted small text-uppercase mb-2">{{ $config['label'] }}</label>
                                                            <input type="{{ $config['type'] }}" class="form-control form-control-lg @if($config['type'] === 'color') p-1 @endif" 
                                                                   name="{{ $key }}" id="{{ $key }}"
                                                                   value="{{ $settings->get($key, $config['default']) }}"
                                                                   style="border-radius: 12px; border: 2px solid var(--brown-100); @if($config['type'] !== 'color') padding: 14px 20px; @else height: 58px; @endif"
                                                                   @if($config['type'] === 'number') min="0" @endif>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach

                                            {{-- Invoice Preview (only for POS & Struk tab) --}}
                                            @if($categoryName === 'POS & Struk')
                                                <div class="col-12 mt-2">
                                                    <div class="p-4" style="border-radius: 14px; background: linear-gradient(135deg, var(--brown-50), #fff); border: 2px dashed var(--brown-200, #c4b5a8);">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <i class="fa-solid fa-eye me-2" style="color: var(--color-primary);"></i>
                                                            <h6 class="mb-0 fw-bold" style="color: var(--color-primary-dark);">Preview No. Struk</h6>
                                                        </div>
                                                        <div id="invoice-preview" class="text-center py-3 px-4 bg-white" style="border-radius: 10px; font-family: 'Courier New', monospace; font-size: 1.2rem; font-weight: 800; letter-spacing: 1px; color: var(--color-primary-dark); border: 1px solid var(--brown-100);">
                                                            {{-- Filled by JS --}}
                                                        </div>
                                                        <p class="text-muted small mb-0 mt-2 text-center">
                                                            <i class="fa-solid fa-info-circle me-1"></i>Contoh format yang akan tampil di struk
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .nav-pills .nav-link {
            color: var(--color-primary);
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link:hover {
            background-color: var(--brown-50);
            color: var(--color-primary-dark);
        }
        .nav-pills .nav-link.active {
            background-color: var(--color-primary-dark) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(111, 88, 73, 0.2);
        }
        .form-check-input:checked {
            background-color: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
        }
        .palette-card {
            background: var(--card-bg, #fff);
            transition: all 0.3s ease;
        }
        .palette-card:hover {
            border-color: var(--color-primary) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        .palette-active {
            border-color: var(--color-primary-dark) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            background: var(--brown-50) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const prefixEl = document.getElementById('invoice_prefix');
            const formatEl = document.getElementById('invoice_format');
            const randLenEl = document.getElementById('invoice_rand_length');
            const seqPadEl = document.getElementById('invoice_seq_padding');
            const previewEl = document.getElementById('invoice-preview');

            if (!prefixEl || !formatEl || !previewEl) return;

            function randomStr(len) {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let r = '';
                for (let i = 0; i < len; i++) r += chars.charAt(Math.floor(Math.random() * chars.length));
                return r;
            }

            function updatePreview() {
                const prefix = prefixEl.value || 'INV';
                const format = formatEl.value || '{PREFIX}-{RAND}';
                const randLen = parseInt(randLenEl.value) || 10;
                const seqPad = parseInt(seqPadEl.value) || 5;

                const now = new Date();
                const date = now.getFullYear().toString() +
                    String(now.getMonth() + 1).padStart(2, '0') +
                    String(now.getDate()).padStart(2, '0');

                let result = format;
                result = result.replaceAll('{PREFIX}', prefix);
                result = result.replaceAll('{DATE}', date);
                result = result.replaceAll('{RAND}', randomStr(randLen));
                result = result.replaceAll('{SEQ}', '1'.padStart(seqPad, '0'));

                previewEl.textContent = result;
            }

            [prefixEl, formatEl, randLenEl, seqPadEl].forEach(el => {
                if (el) el.addEventListener('input', updatePreview);
                if (el) el.addEventListener('change', updatePreview);
            });

            updatePreview();

            // Palette Picker
            const customColorContainer = document.getElementById('custom-color-container');
            document.querySelectorAll('input[name="site_color_theme"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update UI active state
                    document.querySelectorAll('.palette-card').forEach(card => {
                        card.classList.remove('palette-active');
                        card.querySelector('.palette-check')?.classList.add('d-none');
                    });
                    const card = this.closest('.palette-card');
                    card.classList.add('palette-active');
                    card.querySelector('.palette-check')?.classList.remove('d-none');

                    // Show/hide custom color picker
                    if (customColorContainer) {
                        customColorContainer.style.display = (this.value === 'custom') ? 'block' : 'none';
                    }
                });
            });
        });
    </script>
@endsection
