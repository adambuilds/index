## Alias artisan commands

```bash
# ~/.bashrc  or  ~/.zshrc
index () {
  (cd /var/www/index && php artisan "$@")
}
```