<p align="center">Task Manager</a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Installation

clone repo and go to repositorys folder 
```bash
    git clone git@github.com:sajjadgozal/task-manager.git
    cd task-manager 
```
run composer command to install dependencies
```bash
    composer install
```

setUp database in env file
```PHP
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=username
DB_PASSWORD=password
```
migrate database. 
Use --seed for injecting dummy tasks and projects  
```bash
php artisan migrate --seed
```

run the server 
```bash 
    php artisan serve 
```

go to <a> http://127.0.0.1:8000 </a> 

