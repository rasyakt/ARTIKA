@extends('layouts.app')

@section('content')
	<style>
		.page-header {
			display: flex;
			gap: 1rem;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 1rem;
		}

		.search-filter {
			display: flex;
			gap: 0.75rem;
			align-items: center;
		}

		.search-input {
			min-width: 260px;
			border-radius: 12px;
			padding: 0.5rem 0.75rem;
			border: 1px solid #e9e2df;
		}

		.card-table {
			border-radius: 16px;
			border: none;
			overflow: hidden;
		}

		.product-badge {
			width: 48px;
			height: 48px;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: #f2e8e5;
			color: #6f5849;
		}

		.action-btn {
			border-radius: 8px;
			padding: 0.35rem 0.6rem;
		}

		.table-responsive {
			overflow-x: auto;
		}

		@media (max-width:768px) {
			.search-input {
				min-width: 140px;
			}

			.page-header {
				flex-direction: column;
				align-items: flex-start;
				gap: 0.75rem;
			}
		}
	</style>

	<div class="container-fluid py-4">
		<div class="page-header">
			<div>
				<h2 class="fw-bold mb-1" style="color: var(--color-primary-dark);"><i
						class="fa-solid fa-box me-2"></i>{{ __('admin.product_management') }}</h2>
				<p class="text-muted mb-0">{{ __('admin.product_management_subtitle') }}</p>
			</div>

			<div class="d-flex align-items-center">
				<div class="search-filter me-3">
					<input id="productSearch" class="search-input" type="text"
						placeholder="{{ __('common.search_placeholder') }}">
					@if(isset($categories) && $categories->count())
						<select id="filterCategory" class="form-select" style="border-radius:12px;">
							<option value="">{{ __('common.all_categories') }}</option>
							@foreach($categories as $cat)
								<option value="{{ $cat->name }}">{{ $cat->name }}</option>
							@endforeach
						</select>
					@endif
				</div>

				<a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm"
					style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border:none; border-radius:12px; padding:0.6rem 1rem;">
					<i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_product') }}
				</a>
			</div>
		</div>

		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius:12px; border:none;">
				<strong><i class="fa-solid fa-circle-check me-1"></i>{{ __('common.success') }}</strong>
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="card card-table shadow-sm">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead style="background: var(--color-primary-light);">
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
								<tr data-name="{{ strtolower($product->name) }}" data-barcode="{{ $product->barcode }}"
									data-category="{{ $product->category->name ?? '' }}">
									<td class="ps-4">
										<div class="d-flex align-items-center">
											<div class="me-3 product-badge">
												<i class="fa-solid fa-box"></i>
											</div>
											<div>
												<div class="fw-bold" style="color:var(--color-primary-dark);">
													{{ $product->name }}
												</div>
												<small class="text-muted">ID: {{ $product->id }}</small>
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
												aria-expanded="false"> <i class="fa-solid fa-ellipsis-vertical"></i></button>
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
														method="POST"
														onsubmit="return confirm('{{ __('admin.delete_product_confirm') }}');">
														@csrf
														@method('DELETE')
														<button type="submit" class="dropdown-item text-danger"><i
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
				<div class="card-footer bg-white border-0 d-flex justify-content-end">
					{{ $products->links('vendor.pagination.no-prevnext') }}
				</div>
			@endif
		</div>
	</div>

	<script>
		// Client-side search & category filter (simple, non-destructive)
		(function () {
			const input = document.getElementById('productSearch');
			const category = document.getElementById('filterCategory');
			const tbody = document.getElementById('productsTableBody');
			if (!tbody) return;

			function filter() {
				const q = input ? input.value.trim().toLowerCase() : '';
				const cat = category ? category.value : '';
				Array.from(tbody.querySelectorAll('tr')).forEach(row => {
					// ignore placeholder/empty rows
					if (!row.dataset) return;
					const name = (row.dataset.name || '').toLowerCase();
					const barcode = (row.dataset.barcode || '').toLowerCase();
					const rowCat = (row.dataset.category || '');
					const matchQ = q === '' || name.includes(q) || barcode.includes(q);
					const matchCat = !cat || rowCat === cat;
					row.style.display = (matchQ && matchCat) ? '' : 'none';
				});
			}

			let timeout = null;
			if (input) input.addEventListener('input', () => { clearTimeout(timeout); timeout = setTimeout(filter, 150); });
			if (category) category.addEventListener('change', filter);
		})();
	</script>

@endsection