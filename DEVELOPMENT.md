# 👨‍💻 ARTIKA POS - Development Guide

Panduan development untuk developers yang akan maintain atau extend ARTIKA POS system.

---

## 📋 Table of Contents

- [Development Environment Setup](#development-environment-setup)
- [Coding Standards](#coding-standards)
- [Git Workflow](#git-workflow)
- [Testing](#testing)
- [Debugging](#debugging)
- [Available Commands](#available-commands)
- [Adding New Features](#adding-new-features)
- [Contributing](#contributing)

---

## Development Environment Setup

### Prerequisites

Install the following tools:

- PHP 8.2+
- Composer 2.x
- Node.js 18+
- MySQL 5.7+ atau MariaDB
- Git
- Code editor (VS Code recommended)

### Initial Setup

```bash
# Clone repository
git clone https://github.com/yourusername/artika-pos.git
cd artika-pos

# Install dependencies
composer install
npm install

# Environment configuration
cp .env.example .env
php artisan key:generate

# Configure database di .env
DB_CONNECTION=mysql
DB_DATABASE=artika_dev
DB_USERNAME=root
DB_PASSWORD=

# Create database
mysql -u root -p -e "CREATE DATABASE artika_dev"

# Run migrations & seeders
php artisan migrate:fresh --seed

# Start development servers
# Terminal 1: PHP server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

Access: `http://localhost:8000`

---

## Coding Standards

### PHP (PSR-12)

Follow **PSR-12** coding standard:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $items = Item::all();

        return view('items.index', compact('items'));
    }
}
```

**Key Rules:**

- Use 4 spaces untuk indentation (no tabs)
- Opening braces `{` on same line untuk methods
- Type hints untuk parameters dan return types
- DocBlocks untuk methods

**Check with Pint:**

```bash
./vendor/bin/pint
```

### Blade Templates

```blade
{{-- Good --}}
@foreach ($products as $product)
    <div class="product-card">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->formatted_price }}</p>
    </div>
@endforeach

{{-- Avoid XSS: use {{ }} not {!! !!} unless necessary --}}
```

### JavaScript/CSS

- Use ES6+ syntax
- Consistent naming: camelCase untuk variables/functions
- Comment complex logic
- Format dengan Prettier (if configured)

---

## Git Workflow

### Branch Strategy

```
main (production)
 ├── develop (integration)
 │    ├── feature/feature-name
 │    ├── bugfix/bug-description
 │    └── hotfix/critical-fix
```

**Branch Naming:**

- `feature/add-discount-system`
- `bugfix/fix-stock-calculation`
- `hotfix/critical-payment-bug`

### Workflow Steps

1. **Create Feature Branch**

    ```bash
    git checkout develop
    git pull origin develop
    git checkout -b feature/new-feature
    ```

2. **Make Changes**

    ```bash
    # Make code changes
    # Test locally
    ```

3. **Commit**

    ```bash
    git add .
    git commit -m "Add new feature: description"
    ```

4. **Push & Create PR**

    ```bash
    git push origin feature/new-feature
    # Create Pull Request on GitHub
    ```

5. **Code Review**
    - Wait for review
    - Address feedback
    - Merge to develop

6. **Deploy to Production**
    ```bash
    git checkout main
    git merge develop
    git push origin main
    ```

### Commit Message Format

```
type(scope): subject

body (optional)

footer (optional)
```

**Types:**

- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation only
- `style:` Code style (formatting)
- `refactor:` Code refactoring
- `test:` Add tests
- `chore:` Build/config changes

**Examples:**

```
feat(pos): add hold transaction feature

fix(stock): correct stock calculation on return

docs(readme): update installation instructions
```

---

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# With coverage
php artisan test --coverage
```

### Writing Tests

**Feature Test Example:**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class PosTest extends TestCase
{
    public function test_cashier_can_access_pos()
    {
        $cashierRole = Role::where('name', 'cashier')->first();
        $user = User::factory()->create(['role_id' => $cashierRole->id]);

        $response = $this->actingAs($user)->get('/pos');

        $response->assertStatus(200);
        $response->assertSee('Point of Sale');
    }

    public function test_admin_cannot_access_pos()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($user)->get('/pos');

        $response->assertStatus(403);
    }
}
```

**Unit Test Example:**

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_formatted_price_accessor()
    {
        $product = new Product(['price' => 15000]);

        $this->assertEquals('Rp 15.000', $product->formatted_price);
    }
}
```

---

## Debugging

### Laravel Debugbar

Install untuk debugging:

```bash
composer require barryvdh/laravel-debugbar --dev
```

### Log Files

Logs located at `storage/logs/laravel.log`

```php
// Add logs dalam code
Log::info('User logged in', ['user_id' => $user->id]);
Log::error('Payment failed', ['transaction_id' => $id]);
```

View logs:

```bash
tail -f storage/logs/laravel.log
```

### dd() and dump()

```php
// Debug variable dan stop execution
dd($variable);

// Debug variable tanpa stop
dump($variable);
```

### Tinker (Laravel REPL)

```bash
php artisan tinker

>>> $user = User::find(1)
>>> $user->name
>>> Product::count()
```

---

## Available Commands

### Artisan Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh migrate + seed
php artisan db:seed              # Run seeders only

# Cache
php artisan cache:clear          # Clear application cache
php artisan config:clear         # Clear config cache
php artisan view:clear           # Clear compiled views
php artisan route:clear          # Clear route cache

# Development
php artisan serve                # Start dev server
php artisan tinker               # REPL
php artisan make:controller ControllerName
php artisan make:model ModelName
php artisan make:migration create_table_name
php artisan make:seeder SeederName

# Queue
php artisan queue:work           # Process queue jobs
php artisan queue:listen         # Listen for queue jobs

# Testing
php artisan test                 # Run tests
php artisan test --parallel      # Run tests in parallel
```

### NPM Commands

```bash
npm run dev        # Start Vite dev server (HMR)
npm run build      # Build for production
npm run lint       # Run linter (if configured)
```

### Custom Commands (if created)

```bash
php artisan app:generate-report  # Generate sales report
php artisan app:cleanup-old-data # Cleanup old data
```

---

## Adding New Features

### Example: Add Discount Feature

#### 1. Migration

```bash
php artisan make:migration add_discount_to_transactions_table
```

```php
// database/migrations/xxxx_add_discount_to_transactions_table.php
public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->decimal('discount_amount', 15, 2)->default(0)->after('tax');
        $table->string('discount_type')->nullable()->after('discount_amount');
    });
}
```

Run: `php artisan migrate`

#### 2. Update Model

```php
// app/Models/Transaction.php
protected $fillable = [
    // ...
    'discount_amount',
    'discount_type',
];

public function calculateTotal()
{
    $total = $this->subtotal + $this->tax - $this->discount_amount;
    return max(0, $total);
}
```

#### 3. Update Controller

```php
// app/Http/Controllers/PosController.php
public function store(Request $request)
{
    $validated = $request->validate([
        // ...
        'discount_amount' => 'nullable|numeric|min:0',
        'discount_type' => 'nullable|in:fixed,percentage',
    ]);

    $transaction = Transaction::create([
        // ...
        'discount_amount' => $validated['discount_amount'] ?? 0,
        'discount_type' => $validated['discount_type'] ?? null,
    ]);

    // ...
}
```

#### 4. Update View

```blade
{{-- resources/views/pos/index.blade.php --}}
<div class="discount-section">
    <label>Discount</label>
    <select name="discount_type" id="discountType">
        <option value="fixed">Fixed Amount</option>
        <option value="percentage">Percentage</option>
    </select>
    <input type="number" name="discount_amount" id="discountAmount" min="0" step="0.01">
</div>
```

#### 5. Add JavaScript

```javascript
// Calculate discount
document
    .getElementById("discountAmount")
    .addEventListener("input", function () {
        const type = document.getElementById("discountType").value;
        const amount = parseFloat(this.value) || 0;
        const subtotal = calculateSubtotal();

        let discount = 0;
        if (type === "percentage") {
            discount = subtotal * (amount / 100);
        } else {
            discount = amount;
        }

        updateTotal(subtotal - discount);
    });
```

#### 6. Write Tests

```php
public function test_discount_applied_correctly()
{
    $response = $this->post('/pos/checkout', [
        'items' => [...],
        'discount_amount' => 5000,
        'discount_type' => 'fixed',
        // ...
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('transactions', [
        'discount_amount' => 5000,
    ]);
}
```

#### 7. Update Documentation

Update `API.md` dan `USER_GUIDE_CASHIER.md` dengan discount feature.

---

## Performance Optimization

### Database Query Optimization

**Bad (N+1 Problem):**

```php
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name; // N+1 queries!
}
```

**Good (Eager Loading):**

```php
$products = Product::with('category')->get();
foreach ($products as $product) {
    echo $product->category->name; // 2 queries total
}
```

### Caching

```php
use Illuminate\Support\Facades\Cache;

// Cache for 1 hour
$categories = Cache::remember('categories', 3600, function () {
    return Category::all();
});

// Clear cache
Cache::forget('categories');
```

### Index Database Columns

```php
// Migration
$table->index('barcode');
```

---

## Security Checklist

- ✅ Use CSRF protection (enabled by default)
- ✅ Validate all user inputs
- ✅ Use parameter binding (Eloquent does this)
- ✅ Escape output in views (Blade `{{ }}` does this)
- ✅ Use bcrypt for passwords
- ✅ Implement role-based access control
- ✅ Keep dependencies updated
- ✅ Don't expose sensitive info in `.env`
- ✅ Use HTTPS in production

---

## Troubleshooting Development Issues

### Clear All Caches

```bash
php artisan optimize:clear
```

This clears:

- Application cache
- Route cache
- Config cache
- View cache

### Permission Issues (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Composer Issues

```bash
composer dump-autoload
composer install --no-scripts
```

---

## Resources

### Laravel Documentation

- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Eloquent ORM](https://laravel.com/docs/12.x/eloquent)
- [Blade Templates](https://laravel.com/docs/12.x/blade)

### Tools

- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper)
- [PHPStan](https://phpstan.org/) - Static analysis

### Code Quality

```bash
# Install Pint (Laravel's code formatter)
composer require laravel/pint --dev

# Run Pint
./vendor/bin/pint

# Install PHPStan
composer require phpstan/phpstan --dev

# Run PHPStan
./vendor/bin/phpstan analyse
```

---

## Related Documentation

- [ARCHITECTURE.md](file:///c:/laragon/www/ARTIKA/ARCHITECTURE.md) - Architecture details
- [DATABASE.md](file:///c:/laragon/www/ARTIKA/DATABASE.md) - Database schema
- [API.md](file:///c:/laragon/www/ARTIKA/API.md) - API reference
- [CONTRIBUTING.md](file:///c:/laragon/www/ARTIKA/CONTRIBUTING.md) - Contribution guidelines

---

**Happy Coding! 👨‍💻**

**Version:** 2.5  
**Last Updated:** 2026-01-23
