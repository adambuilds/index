# Initial Configuration

## Alias artisan commands

This will allow you to run `index list` or `index check:failed-users` from anywhere in the terminal.

`/var/www/index` is a placeholder for your actual project path. Adjust it accordingly.

```bash
# ~/.bashrc  or  ~/.zshrc
index () {
  (cd /var/www/index && php artisan "$@")
}
```