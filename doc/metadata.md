# Metadata

Metadata complete the database schema with additional information to support datadriven develoopment.

Metadata can be stored as json encoded data in the database column comments or inside a special database table nammed metadata.

## Metadata table seeder

During development it is more convenient to fill the metadata table and then to generate the seeder from the table.

### Laravel Seeder Generator

https://github.com/orangehill/iseed

    php artisan iseed metadata --env=testing

