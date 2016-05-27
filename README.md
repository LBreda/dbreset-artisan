dbreset-artisan
===============

This is an Laravel's Artisan command to reset your project database.

It will delete all the tables, to re-start all the migrations and re-seed
the database.

It is very useful if you, for development reasons, want to edit old migrations
instead of generating new update migrations.

Installation
------------

You have to copy DbReset on your `app/Console/Commands` directory, and you
have to register it adding the item `Commands\DbReset::class,` in the
`$commands` array you can find in the `app/Console/Kernel.php` file.

Usage
-----

You can launch the script with the `php artisan db:lreset` command. It will
require confirmation. You can skip the confirmation with the `--force` flag.
