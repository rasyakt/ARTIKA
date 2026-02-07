@extends('layouts.app')

@section('content')
	<style>
		.page-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 2rem;
			gap: 1.5rem;
		}

		.header-title-group {
			flex: 1;
		}

		.header-controls {
			display: flex;
			align-items: center;
			gap: 1rem;
		}

		.search-form {
			display: flex;
			gap: 0.75rem;
			align-items: center;
		}

		.search-input-group {
			position: relative;
			min-width: 320px;
		}

		.search-input-group i {
			position: absolute;
			left: 1.25rem;
			top: 50%;
			transform: translateY(-50%);
			color: #a18072;
			opacity: 0.7;
		}

		.search-input {
			width: 100%;
			border-radius: 12px;
			padding: 0.75rem 1rem 0.75rem 3rem;
			border: 1px solid #e2d8d4;
			background: #ffffff;
			transition: all 0.3s;
			font-size: 0.95rem;
		}

		.search-input:focus {
			border-color: #8a6b57;
			box-shadow: 0 0 0 4px rgba(138, 107, 87, 0.1);
			outline: none;
		}

		.category-select {
			min-width: 200px;
			border-radius: 12px;
			padding: 0.75rem 1rem;
			border: 1px solid #e2d8d4;
			background: #ffffff;
			height: 100%;
			font-size: 0.95rem;
		}

		.btn-add-product {
			background: #6f5849;
			color: white;
			border: none;
			border-radius: 16px;
			padding: 1rem 1.5rem;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			min-width: 120px;
			transition: all 0.3s;
			box-shadow: 0 4px 12px rgba(111, 88, 73, 0.2);
			line-height: 1.2;
		}

		.btn-add-product:hover {
			background: #5d4a3d;
			transform: translateY(-2px);
			box-shadow: 0 6px 15px rgba(111, 88, 73, 0.3);
			color: white;
		}

		.btn-add-product i {
			font-size: 1.25rem;
			margin-bottom: 0.4rem;
		}

		.btn-add-product span {
			font-size: 0.85rem;
			font-weight: 600;
			text-align: center;
		}

		.card-table {
			border-radius: 20px;
			border: none;
			overflow: hidden;
		}

		.table thead {
			background: #f8f5f4;
		}

		.table thead th {
			font-size: 0.75rem;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #a18072;
			padding: 1.25rem 1rem;
		}

		.product-badge {
			width: 44px;
			height: 44px;
			border-radius: 12px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: #f2e8e5;
			color: #6f5849;
			font-size: 1.1rem;
		}

		.product-name-text {
			color: #4b382f;
			font-size: 0.95rem;
		}

		.product-row {
			transition: all 0.2s;
		}

		.product-row:hover {
			background-color: #fdfbf9 !important;
		}

		@media (max-width: 1200px) {
			.page-header {
				flex-wrap: wrap;
			}

			.header-controls {
				width: 100%;
				justify-content: space-between;
			}

			.search-form {
				flex: 1;
			}

			.search-input-group {
				min-width: 200px;
				flex: 1;
			}
		}

		@media (max-width: 768px) {
			.header-controls {
				flex-direction: column;
				align-items: stretch;
			}

			.search-form {
				flex-direction: column;
			}

			.btn-add-product {
				flex-direction: row;
				padding: 0.75rem;
				min-width: 0;
			}

			.btn-add-product i {
				margin-bottom: 0;
				margin-right: 0.5rem;
			}
		}
	</style>

	<div class="container-fluid py-4">
		<div class="page-header">
			<div class="header-title-group">
				<h2 class="fw-bold mb-1" style="color: #4b382f;">
					<i class="fa-solid fa-box-open me-2"></i>{{ __('admin.product_management') }}
				</h2>
				<p class="text-muted mb-0">{{ __('admin.product_management_subtitle') }}</p>
			</div>

			<div class="header-controls">
				<form action="{{ route('admin.products') }}" method="GET" class="search-form">
					<div class="search-input-group">
						<i class="fa-solid fa-magnifying-glass"></i>
						<input name="search" class="search-input" type="text"
							placeholder="{{ __('common.search_placeholder') }}" value="{{ request('search') }}">
					</div>
					<select name="category_id" class="form-select category-select" onchange="this.form.submit()">
						<option value="">{{ __('common.all_categories') }}</option>
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
								{{ $cat->name }}
							</option>
						@endforeach
					</select>
				</form>

				<a href="{{ route('admin.products.create') }}" class="btn-add-product">
					<i class="fa-solid fa-plus"></i>
					<span>{{ __('admin.add_product') }}</span>
				</a>
			</div>
		</div>


		<div class="card card-table shadow-sm">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead style="background: #f2e8e5;">
							<tr>
								<th class="ps-4 border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.product') }}
								</th>
								<th class="border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.category') }}
								</th>
								<th class="border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.barcode') }}
								</th>
								<th class="border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.cost_price') }}
								</th>
								<th class="border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.sell_price') }}
								</th>
								<th class="border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.margin') }}
								</th>
								<th class="border-0 fw-semibold" style="color:var(--color-primary-dark);">
									{{ __('common.stock') }}
								</th>
								<th class="border-0 fw-semibold text-center" style="color:var(--color-primary-dark);">
									{{ __('common.actions') }}
								</th>
							</tr>
						</thead>
						<tbody id="productsTableBody">
							@forelse($products as $product)
								@php
									$totalStock = $product->stocks->sum('quantity');
									$margin = $product->cost_price > 0 ? (($product->price - $product->cost_price) / $product->cost_price) * 100 : 0;
								@endphp
								<tr class="product-row" data-name="{{ strtolower($product->name) }}"
									data-barcode="{{ $product->barcode }}" data-category="{{ $product->category->name ?? '' }}">
									<td class="ps-4">
										<div class="d-flex align-items-center">
											<div class="me-3 product-badge">
												<i class="fa-solid fa-box"></i>
											</div>
											<div>
												<div class="fw-bold product-name-text">
													{{ $product->name }}
												</div>
												<small class="text-muted">ID: #{{ $product->id }}</small>
											</div>
										</div>
									</td>
									<td>
										<span class="badge"
											style="background:#e9e2df; color:var(--color-primary-dark); padding:0.4rem 0.6rem; border-radius:8px;">{{ $product->category->name ?? '-' }}</span>
									</td>
									<td>
										<code
											style="background:#fdf8f6; padding:0.25rem 0.5rem; border-radius:6px; color:var(--color-primary-dark);">{{ $product->barcode }}</code>
									</td>
									<td class="text-muted">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
									<td class="fw-bold" style="color:var(--color-accent-warm);">Rp
										{{ number_format($product->price, 0, ',', '.') }}
									</td>
									<td>
										<span
											class="badge {{ $margin > 30 ? 'bg-success' : ($margin > 15 ? 'bg-warning' : 'bg-danger') }}">{{ number_format($margin, 1) }}%</span>
									</td>
									<td>
										<span
											class="badge {{ $totalStock > 50 ? 'bg-success' : ($totalStock > 20 ? 'bg-warning' : 'bg-danger') }}">{{ $totalStock }}
											{{ __('common.units') }}</span>
									</td>
									<td class="text-center">
										<div class="btn-group">
											<button class="btn btn-sm btn-light action-btn" data-bs-toggle="dropdown"
												data-bs-boundary="viewport" aria-expanded="false">
												<i class="fa-solid fa-ellipsis-vertical"></i>
											</button>
											<ul class="dropdown-menu dropdown-menu-end" style="border-radius:12px;">
												<li>
													<a class="dropdown-item"
														href="{{ route('admin.products.edit', $product->id) }}"><i
															class="fa-solid fa-pen me-2"></i>{{ __('common.edit') }}</a>
												</li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li>
													<form action="{{ route('admin.products.delete', $product->id) }}"
														method="POST" class="delete-form">
														@csrf
														@method('DELETE')
														<button type="button" class="dropdown-item text-danger btn-delete"><i
																class="fa-solid fa-trash me-2"></i>{{ __('common.delete') }}</button>
													</form>
												</li>
											</ul>
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="8" class="text-center py-5">
										<div style="font-size:4rem; opacity:0.2;"><i class="fa-solid fa-box"></i></div>
										<p class="text-muted mb-0">{{ __('admin.no_products_found') }}</p>
										<a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3"><i
												class="fa-solid fa-plus me-1"></i> {{ __('admin.add_first_product') }}</a>
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>

			@if(method_exists($products, 'links'))
				<div class="card-footer border-0 d-flex justify-content-end">
					{{ $products->links('vendor.pagination.custom-brown') }}
				</div>
			@endif
		</div>
	</div>

	<script>
		// Auto-submit search form on typing (with debounce)
		document.addEventListener('DOMContentLoaded', function () {
			const searchInput = document.querySelector('input[name="search"]');
			if (searchInput) {
				let timeout = null;
				searchInput.addEventListener('input', function () {
					clearTimeout(timeout);
					timeout = setTimeout(() => {
						this.form.submit();
					}, 500);
				});

				// Place cursor at the end of the text if focused
				if (searchInput.value) {
					searchInput.focus();
					const val = searchInput.value;
					searchInput.value = '';
					searchInput.value = val;
				}
			}
		});

		// Handle delete confirmation with SweetAlert2
		document.addEventListener('DOMContentLoaded', function () {
			const deleteButtons = document.querySelectorAll('.btn-delete');
			deleteButtons.forEach(button => {
				button.addEventListener('click', function () {
					const form = this.closest('form');
					confirmAction({
						text: "{{ __('admin.delete_product_confirm') }}",
						confirmButtonText: "{{ __('common.delete') }}"
					}).then((result) => {
						if (result.isConfirmed) {
							form.submit();
						}
					});
				});
			});
		});
	</script>

@endsection