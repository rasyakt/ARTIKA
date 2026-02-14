@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.pre_orders.index') }}"
                                style="color: #85695a;">{{ __('admin.supplier_pre_orders') ?? 'Pre-Order Supplier' }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('admin.add_pre_order') ?? 'Tambah Pre-Order' }}
                        </li>
                    </ol>
                </nav>
                <h4 class="fw-bold mb-0" style="color: #6f5849;">
                    <i class="fa-solid fa-plus me-2"></i>{{ __('admin.create_new_pre_order') ?? 'Buat Pre-Order Baru' }}
                </h4>
            </div>
        </div>

        <form action="{{ route('admin.suppliers.pre_orders.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: #6f5849;">
                                {{ __('admin.order_info') ?? 'Informasi Pesanan' }}
                            </h5>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.supplier') }}
                                    *</label>
                                <select name="supplier_id" class="form-select custom-input" required>
                                    <option value="" disabled selected>{{ __('admin.select_supplier') }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"
                                    style="color: #6f5849;">{{ __('admin.reference_number') ?? 'Nomor Referensi' }}</label>
                                <input type="text" name="reference_number" class="form-control custom-input"
                                    placeholder="e.g. PO-2024-001">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"
                                    style="color: #6f5849;">{{ __('admin.expected_arrival') ?? 'Estimasi Kedatangan' }}</label>
                                <input type="date" name="expected_arrival_date" class="form-control custom-input">
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold"
                                    style="color: #6f5849;">{{ __('admin.notes') }}</label>
                                <textarea name="notes" class="form-control custom-input" rows="3"
                                    placeholder="{{ __('admin.notes_placeholder') }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: #6f5849;">
                                {{ __('admin.order_items') ?? 'Daftar Barang' }}
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-borderless align-middle" id="items-table">
                                    <thead class="text-muted small text-uppercase">
                                        <tr>
                                            <th style="width: 35%;">{{ __('admin.product') }}</th>
                                            <th style="width: 15%;">{{ __('admin.unit') }}</th>
                                            <th style="width: 12%;">Qty</th>
                                            <th style="width: 12%;">Pcs/Unit</th>
                                            <th style="width: 20%;">{{ __('admin.purchase_price') ?? 'Harga Beli' }}</th>
                                            <th style="width: 40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-container">
                                        <tr class="item-row">
                                            <td>
                                                <select name="items[0][product_id]"
                                                    class="form-select custom-input select-product" required>
                                                    <option value="" disabled selected>{{ __('admin.select_product') }}
                                                    </option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-price="{{ $product->cost_price }}"
                                                            data-unit="{{ $product->unit }}">
                                                            {{ $product->name }} ({{ $product->barcode }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="items[0][unit_name]"
                                                    class="form-select custom-input select-unit-type" required>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                    <option value="Lainnya">Lainnya...</option>
                                                </select>
                                                <input type="text"
                                                    class="form-control custom-input mt-1 d-none other-unit-input"
                                                    placeholder="Nama Satuan...">
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][quantity]"
                                                    class="form-control custom-input input-quantity" min="1" value="1"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][pcs_per_unit]"
                                                    class="form-control custom-input input-pcs-per-unit" min="1" value="1"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][unit_price]"
                                                    class="form-control custom-input input-price" min="0" step="0.01"
                                                    required>
                                                <div class="small text-muted mt-1 px-2">per <span
                                                        class="product-unit-text">Pcs</span> (HPP)</div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-outline-brown btn-sm mt-2" id="add-item-btn"
                                style="border-radius: 8px;">
                                <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_product') }}
                            </button>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.suppliers.pre_orders.index') }}" class="btn btn-light px-4"
                                        style="border-radius: 10px;">
                                        {{ __('common.cancel') }}
                                    </a>
                                </div>
                                <div class="text-end d-flex align-items-center gap-4">
                                    <div class="text-end">
                                        <div class="text-muted small text-uppercase">
                                            {{ __('admin.total_purchase_amount') ?? 'Total Harga Beli' }}
                                        </div>
                                        <div class="h3 fw-bold mb-0" style="color: #6f5849;">Rp <span
                                                id="grand-total">0</span></div>
                                    </div>
                                    <button type="submit" class="btn btn-brown px-5 py-2 shadow-sm"
                                        style="border-radius: 10px; font-weight: 600;">
                                        <i class="fa-solid fa-floppy-disk me-1"></i>
                                        {{ __('admin.save_pre_order') ?? 'Simpan Pesanan' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowCount = 1;
            const container = document.getElementById('items-container');
            const addBtn = document.getElementById('add-item-btn');
            const grandTotalEl = document.getElementById('grand-total');

            function calculateGrandTotal() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.input-quantity').value) || 0;
                    const pcsPerUnit = parseFloat(row.querySelector('.input-pcs-per-unit').value) || 0;
                    const price = parseFloat(row.querySelector('.input-price').value) || 0;
                    total += qty * pcsPerUnit * price;
                });
                grandTotalEl.textContent = new Intl.NumberFormat('id-ID').format(total);
            }

            function handleProductChange(e) {
                const row = e.target.closest('tr');
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                const unit = selectedOption.dataset.unit || 'Pcs';

                row.querySelector('.input-price').value = price;
                // Auto set unit to product default
                const unitSelect = row.querySelector('.select-unit-type');
                if ([...unitSelect.options].some(opt => opt.value === unit)) {
                    unitSelect.value = unit;
                } else {
                    unitSelect.value = 'Lainnya';
                    const otherInput = row.querySelector('.other-unit-input');
                    otherInput.classList.remove('d-none');
                    otherInput.value = unit;
                    otherInput.name = unitSelect.name;
                    unitSelect.name = "";
                }

                row.querySelectorAll('.product-unit-text').forEach(el => el.textContent = unit);
                calculateGrandTotal();
            }

            function handleUnitChange(e) {
                const row = e.target.closest('tr');
                const otherInput = row.querySelector('.other-unit-input');
                const unitSelect = e.target;

                if (unitSelect.value === 'Lainnya') {
                    otherInput.classList.remove('d-none');
                    otherInput.name = unitSelect.dataset.name || unitSelect.name;
                    unitSelect.dataset.name = otherInput.name;
                    unitSelect.name = "";
                    otherInput.focus();
                } else {
                    otherInput.classList.add('d-none');
                    if (unitSelect.dataset.name) {
                        unitSelect.name = unitSelect.dataset.name;
                    }
                    otherInput.name = "";
                }

                const unitText = unitSelect.value === 'Lainnya' ? (otherInput.value || 'unit') : unitSelect.value;
                row.querySelectorAll('.product-unit-text').forEach(el => el.textContent = unitText);

                // Auto set pcs_per_unit common values
                const pcsPerUnit = row.querySelector('.input-pcs-per-unit');
                if (unitSelect.value === 'Pcs') pcsPerUnit.value = 1;
                else if (unitSelect.value === 'Lusin') pcsPerUnit.value = 12;
                else if (unitSelect.value === 'Box' || unitSelect.value === 'Pack' || unitSelect.value === 'Dus') {
                    // For these, we might want to clear or set a default like 1, or leave it to user
                    // For now, let's set to 1 if it's not already set by product data
                    if (pcsPerUnit.value === '12') pcsPerUnit.value = 1; // Reset if it was Lusin
                }
            }

            addBtn.addEventListener('click', function () {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = `
                                                <td>
                                                    <select name="items[${rowCount}][product_id]" class="form-select custom-input select-product" required>
                                                        <option value="" disabled selected>{{ __('admin.select_product') }}</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" data-price="{{ $product->cost_price }}" data-unit="{{ $product->unit }}">
                                                                {{ $product->name }} ({{ $product->barcode }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                <select name="items[${rowCount}][unit_name]" class="form-select custom-input select-unit-type" required>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                    <option value="Lainnya">Lainnya...</option>
                                                </select>
                                                <input type="text" class="form-control custom-input mt-1 d-none other-unit-input" placeholder="Nama Satuan...">
                                            </td>
                                                <td>
                                                    <input type="number" name="items[${rowCount}][quantity]" class="form-control custom-input input-quantity" min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[${rowCount}][pcs_per_unit]" class="form-control custom-input input-pcs-per-unit" min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[${rowCount}][unit_price]" class="form-control custom-input input-price" min="0" step="0.01" required>
                                                    <div class="small text-muted mt-1 px-2">per <span class="product-unit-text">unit</span></div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 delete-row-btn">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            `;
                container.appendChild(newRow);

                // Add event listeners to new row
                newRow.querySelector('.select-product').addEventListener('change', handleProductChange);
                newRow.querySelector('.select-unit-type').addEventListener('change', handleUnitChange);
                newRow.querySelector('.input-quantity').addEventListener('input', calculateGrandTotal);
                newRow.querySelector('.input-pcs-per-unit').addEventListener('input', calculateGrandTotal);
                newRow.querySelector('.input-price').addEventListener('input', calculateGrandTotal);
                newRow.querySelector('.delete-row-btn').addEventListener('click', function () {
                    newRow.remove();
                    calculateGrandTotal();
                });

                rowCount++;
            });

            // Initial event listeners
            document.querySelector('.select-product').addEventListener('change', handleProductChange);
            document.querySelector('.select-unit-type').addEventListener('change', handleUnitChange);
            document.querySelector('.input-quantity').addEventListener('input', calculateGrandTotal);
            document.querySelector('.input-pcs-per-unit').addEventListener('input', calculateGrandTotal);
            document.querySelector('.input-price').addEventListener('input', calculateGrandTotal);

            calculateGrandTotal();
        });
    </script>

    <style>
        .btn-brown {
            background: #6f5849;
            color: white;
            border: none;
        }

        .btn-brown:hover {
            color: white;
            opacity: 0.9;
            background: #826857ff;
        }

        .btn-outline-brown {
            border: 2px solid #85695a;
            color: #85695a;
        }

        .btn-outline-brown:hover {
            background: #85695a;
            color: white;
        }

        .custom-input {
            border-radius: 12px;
            border: 2px solid #f2e8e5;
            padding: 0.6rem 1rem;
        }

        .custom-input:focus {
            border-color: #85695a !important;
            box-shadow: 0 0 0 0.25rem rgba(111, 88, 73, 0.1) !important;
            background: #fff !important;
        }

        /* Hide spin buttons for number inputs */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "\f105";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.75rem;
            color: #d4c4bb;
        }

        #items-table thead th {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
    </style>
@endsection