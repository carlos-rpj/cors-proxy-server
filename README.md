# CORS Proxy Server

A simple CORS proxy server built in PHP that allows you to make cross-origin requests to any URL.  

> It is intended to be used as a development tool, and should not be used in production.

## Instalation

First, you need to install the packages with composer:

```bash
composer install
```

## Usage

Open `index.php` and change the constant `API_URL` to the URL you want to make requests to.  

Then, you can start the server with:  

```bash
php -S localhost:8080
```

## Host the application

You just need to copy all contents of this repository to your server (including the vendor folder).  

> If you host your application in a subdirectory, you will need to set the constant `BASE_PATH` to the path of subdirectory (ex: /foo/bar).  
