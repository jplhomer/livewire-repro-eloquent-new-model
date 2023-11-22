# Livewire v3 model binding issue reproduction

RE: https://github.com/livewire/livewire/pull/7214

## Livewire v2

```bash
git checkout main
composer install
php artisan test

# Everything passes
```

## Livewire v3

```bash
git checkout v3
composer install
php artisan view:clear
php artisan test

# Test fails:
# Database connection [1] not configured.
```
