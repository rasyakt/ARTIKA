# 🤝 Contributing to ARTIKA POS

Thank you for considering contributing to ARTIKA POS! This document provides guidelines for contributing to the project.

---

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Process](#development-process)
- [Pull Request Process](#pull-request-process)
- [Coding Guidelines](#coding-guidelines)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)

---

## Code of Conduct

### Our Pledge

We pledge to make participation in our project a harassment-free experience for everyone, regardless of:
- Age, body size, disability, ethnicity
- Gender identity and expression
- Level of experience
- Nationality, personal appearance, race, religion
- Sexual identity and orientation

### Our Standards

**Positive behavior:**
- Using welcoming and inclusive language
- Being respectful of differing viewpoints
- Gracefully accepting constructive criticism
- Focusing on what is best for the community

**Unacceptable behavior:**
- Trolling, insulting/derogatory comments
- Public or private harassment
- Publishing others' private information
- Other conduct inappropriate in a professional setting

---

## How Can I Contribute?

### 1. Reporting Bugs

**Before submitting:**
- Check if bug already reported in Issues
- Verify bug exists in latest version
- Collect information about the bug

**Bug Report Template:**

```markdown
**Describe the bug**
A clear description of the bug.

**To Reproduce**
Steps to reproduce:
1. Go to '...'
2. Click on '....'
3. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
 - OS: [e.g. Windows 10]
 - Browser: [e.g. Chrome 120]
 - Version: [e.g. 2.0]

**Additional context**
Any other context about the problem.
```

### 2. Suggesting Features

**Feature Request Template:**

```markdown
**Is your feature request related to a problem?**
A clear description of the problem.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Other solutions or features you've considered.

**Additional context**
Any other context, mockups, or examples.
```

### 3. Code Contributions

We welcome code contributions! See [Development Process](#development-process) below.

---

## Development Process

### 1. Fork the Repository

```bash
# Fork on GitHub, then clone your fork
git clone https://github.com/YOUR_USERNAME/artika-pos.git
cd artika-pos

# Add upstream remote
git remote add upstream https://github.com/ORIGINAL_OWNER/artika-pos.git
```

### 2. Create a Branch

```bash
# Update your local develop
git checkout develop
git pull upstream develop

# Create feature branch
git checkout -b feature/your-feature-name
```

**Branch naming:**
- `feature/feature-name` - New features
- `bugfix/bug-description` - Bug fixes
- `docs/documentation-update` - Documentation
- `refactor/code-improvement` - Code refactoring
- `test/add-tests` - Adding tests

### 3. Make Changes

- Write clean, readable code
- Follow [Coding Guidelines](#coding-guidelines)
- Add tests for new features
- Update documentation as needed
- Commit regularly with clear messages

### 4. Commit

```bash
# Stage changes
git add .

# Commit with clear message
git commit -m "feat(pos): add discount feature to checkout"
```

**Commit message format:**
```
type(scope): subject

body (optional)

footer (optional)
```

**Types:**
- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation
- `style` - Code formatting
- `refactor` - Code refactoring
- `test` - Tests
- `chore` - Build/config

### 5. Test

```bash
# Run tests
php artisan test

# Run code formatter
./vendor/bin/pint

# Check for errors
php artisan config:clear
php artisan cache:clear
```

### 6. Push

```bash
git push origin feature/your-feature-name
```

### 7. Create Pull Request

- Go to GitHub repository
- Click "New Pull Request"
- Select your branch
- Fill out PR template
- Wait for review

---

## Pull Request Process

### PR Template

```markdown
## Description
Brief description of changes.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
Describe testing done:
- [ ] All tests pass
- [ ] Added new tests
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Commenting added where necessary
- [ ] Documentation updated
- [ ] No new warnings generated
- [ ] Tests added/updated
```

### Review Process

1. **Automated Checks**
   - Tests must pass
   - Code style must comply

2. **Code Review**
   - At least 1 reviewer approval required
   - Address all review comments

3. **Merge**
   - Maintainer will merge PR
   - Your contribution will be credited!

---

## Coding Guidelines

### PHP (Laravel)

Follow **PSR-12** standard:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): View
    {
        $products = Product::with('category')->paginate(20);
        
        return view('products.index', compact('products'));
    }
}
```

**Key points:**
- 4 spaces indentation
- Type hints for parameters and return
- DocBlocks for all methods
- Descriptive variable names
- Single responsibility per method

### JavaScript

```javascript
// Use const/let, not var
const productPrice = 15000;

// CamelCase for variables/functions
function calculateTotal(items) {
    return items.reduce((sum, item) => sum + item.price, 0);
}

// Add comments for complex logic
// Calculate discount based on customer tier
const discount = customerTier === 'gold' ? 0.1 : 0.05;
```

### Blade Templates

```blade
{{-- Use {{ }} for output (auto-escaped) --}}
<h3>{{ $product->name }}</h3>

{{-- Use @directives for control structures --}}
@foreach ($products as $product)
    <div class="product-card">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->formatted_price }}</p>
    </div>
@endforeach
```

### Database

**Migrations:**
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('barcode')->unique();
    $table->decimal('price', 15, 2);
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
});
```

**Queries:**
```php
// Good: Use Eloquent relationships
$product = Product::with('category', 'stocks')->find($id);

// Good: Eager loading to avoid N+1
$products = Product::with('category')->get();

// Avoid: Raw queries without binding
// Use parameter binding or Eloquent
```

---

## Reporting Bugs

### Bug Severity Levels

| Level | Description | Example |
|-------|-------------|---------|
| **Critical** | System crash, data loss | Database corruption |
| **High** | Major feature broken | Cannot complete checkout |
| **Medium** | Feature works but has issues | Incorrect calculation |
| **Low** | Minor issue, cosmetic | UI alignment issue |

### Security Vulnerabilities

**Do NOT open public issues for security vulnerabilities!**

Instead:
- Email: security@artika-pos.com
- Or use GitHub Security Advisory

We will respond within 48 hours.

---

## Suggesting Features

### Feature Proposal Process

1. **Check existing feature requests** to avoid duplicates

2. **Create detailed proposal**
   - Use Case: Why is this needed?
   - Proposed Solution: How should it work?
   - Alternatives: Other approaches considered
   - Impact: Who benefits?

3. **Community Discussion**
   - Gather feedback
   - Refine proposal

4. **Approval**
   - Maintainers will review
   - If approved, can be added to roadmap

5. **Implementation**
   - You or someone else can implement
   - Follow development process

---

## Documentation

### Updating Documentation

Documentation changes are welcome!

**Types:**
- Fixing typos/errors
- Clarifying unclear sections
- Adding examples
- Translating to other languages
- Adding new guides

**Process:**
- Same as code contributions
- Update relevant `.md` files
- Create PR with docs label

---

## Getting Help

### Resources

- **Documentation:** Check [README.md](README.md) and docs/
- **Existing Issues:** Search GitHub issues
- **Discussions:** GitHub Discussions tab

### Ask Questions

- **General questions:** GitHub Discussions
- **Development help:** Issues with "question" label
- **Chat:** Discord/Slack (if available)

**Please:**
- Search before asking
- Provide context and details
- Be respectful and patient

---

## Recognition

### Contributors

All contributors will be listed in:
- `CONTRIBUTORS.md` file
- Release notes
- GitHub contributors page

### Types of Contributions

We recognize various contributions:
- 💻 Code
- 📖 Documentation
- 🐛 Bug reports
- 💡 Feature ideas
- 🎨 Design
- 🌐 Translations
- ⚠️ Security reports

---

## License

By contributing, you agree that your contributions will be licensed under the same [MIT License](LICENSE) that covers this project.

---

## Questions?

Feel free to reach out:
- GitHub Issues
- GitHub Discussions
- Email: contribute@artika-pos.com

---

**Thank you for contributing to ARTIKA POS! 🙏**

**Last Updated:** 2026-01-09
