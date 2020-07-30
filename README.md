# DriftPHP Skeleton

Welcome to the skeleton of DriftPHP. In this small repository you will find an
extraordinary way of starting using DriftPHP and ReactPHP in your projects. Just
install it, load dependencies, and you will be ready to start building fast and
insane applications on top of Symfony and ReactPHP components.

<p align="center">
  <img src="public/driftphp.png">
</p>

Some first steps for you!

- [Go to DOCS](https://driftphp.io)
- [Try a demo](https://github.com/driftphp/demo)

you can check out packages as well.

- [Redis adapter](https://github.com/driftphp/redis-bundle)
- [Mysql adapter](https://github.com/driftphp/mysql-bundle)
- [Twig adapter](https://github.com/driftphp/twig-bundle)

# Calls

First you need to start the server

```
php vendor/bin/server run 0.0.0.0:8000 --exchange=default
```

## GET

> curl -H "Content-Type: application/json" localhost:8000/users/{id}

## UPDATE

> curl -XPUT -H "Content-Type: application/json" localhost:8000/users/{id} -d'{"name": "Han Solo"}'

## DELETE

> curl -XDELETE -H "Content-Type: application/json" localhost:8000/users/{id} -d'{"name": "Han Solo}'