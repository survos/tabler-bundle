# Demo App

This directory contains a minimal Symfony 8 / PHP 8.4 demo application for `survos/tabler-bundle`.

It exists to validate the bundle contract in isolation:

- apps extend `@SurvosTabler/base.html.twig`
- apps fill slots with listeners
- empty slots disappear
- shared chrome is owned by the bundle

## Install

From the `demo/` directory:

```bash
composer install
php -S 127.0.0.1:8000 -t public
```

The demo uses a Composer path repository pointing to `..`, so local changes in this bundle are reflected immediately.

## Pages

- `/`
- `/admin`
- `/tenants`
- `/tenants/dev`
- `/tenants/dev/intakes`
- `/tenants/dev/images`
- `/museums`
- `/museums/demo/epochs`
- `/search`

## Purpose

Use this app as the bundle contract testbed while refactoring slots, layout placement, and Twig helper APIs.
