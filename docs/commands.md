# Commands
This package provide two commands.
## Import languages
This command will import the active languages in the judge0, and store them in the database, to access them you can use `Mouadbnl\Models\Languages model`.
```bash
php artisan judge0:import-languages
```
## Import Statuses
This command will import the available statuses in the judge0, and store them in the database, to access them you can use `Mouadbnl\Models\Statuses model`.
```bash
php artisan judge0:import-statuses
```