# Contributing

Contributions are welcome. Please open an issue first for larger changes.

## Development

```bash
composer install
composer ci      # coding style, static analysis, unit tests, mutation tests
composer cs-fix  # fixes the coding style
```

Pull requests must keep the code coverage at 100 % and the mutation score indicator (MSI) at 100 %, and every change
must be described in `CHANGELOG.md`. Mutation tests and coverage require the PHP extension `pcov` or `xdebug`.
