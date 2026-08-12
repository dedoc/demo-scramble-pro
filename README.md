## About Cally

Cally is a simple demo application for Scramble PRO. This is a REST API for simple ATS (applications tracking system) software.

When browsing the codebase, notice how there are no redundant PHPDoc annotations contrary to other API documentation generation tools for Laravel. 

Cally's source: https://github.com/dedoc/demo-scramble-pro

For every documented endpoint and schema you'll find the link to the file on GitHub in the description, so you can quickly check the source.

## Quick start

Requirements: PHP 8.4+, Composer, Node.js with npm, and the credentials needed to install `dedoc/scramble-pro` from the configured Composer repository.

```shell
composer run setup
composer run dev
```

`composer run setup` installs the PHP dependencies and the small JavaScript development dependency used to run concurrent processes, creates the environment and SQLite database when missing, generates the application key, and runs the migrations and seeders. `composer run dev` starts the application server, queue listener, and log viewer together.

Open the interactive API documentation at http://127.0.0.1:8000/docs/api. The seeders create a demo user, three companies, four job positions, five candidates, and seven applications so list, include, filter, and statistics requests return useful results.

## Authentication

This API uses Laravel Sanctum bearer tokens. Generate a token for the seeded demo user:

```shell
php artisan tinker --execute="echo App\\Models\\User::where('email', 'demo@example.com')->firstOrFail()->createToken('demo')->plainTextToken;"
```

Copy the output and use it in the documentation's **Authorize** dialog, or call the API directly:

```shell
curl http://127.0.0.1:8000/api/company \
  --header "Accept: application/json" \
  --header "Authorization: Bearer YOUR_TOKEN"

curl "http://127.0.0.1:8000/api/company?include=jobs&filter[name]=Acme%20Analytics" \
  --header "Accept: application/json" \
  --header "Authorization: Bearer YOUR_TOKEN"

curl http://127.0.0.1:8000/api/statistics \
  --header "Accept: application/json"
```

Sanctum auth is represented automatically in the generated OpenAPI document through the security strategy in `config/scramble.php`.

## Contact

If you discover some issues, or have ideas, or have any questions, or just want to say hello, please send an e-mail to Roman via [roman@dedoc.com](mailto:roman@dedoc.com).
