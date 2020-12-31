<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Laravel API Video Catalog

![Badge](https://img.shields.io/static/v1?label=PHP&message=7.4&color=777BB4&style=for-the-badge&logo=php&logoColor=777BB4)
![Badge](https://img.shields.io/static/v1?label=Laravel&message=8.x&color=ff2d20&style=for-the-badge&logo=laravel&logoColor=ff2d20)
![Badge](https://img.shields.io/static/v1?label=LICENSE&message=MIT&color=32CD32&style=for-the-badge)


### Entities
- [x] Category
- [x] Genre

### Feature Tests
- [X] Category
- [X] Genre

### Unit Tests
- [X] Category
- [X] Genre

### Running the tests

```bash
# After access the app container, you can run the command below to run both all Features Tests and all Unit Tests.
/var/www $ php artisan test

# However, if you want to choose one of them, you can use the  --testsuite argument to specify which test you want to run.
/var/www $ php artisan test --testsuite=Feature

# And if you want to stop the tests when you found one error, you can use the --stop-on-failure argument.
/var/www $ php artisan test --testsuite=Feature --stop-on-failure

# If you need something more specific, you can search for phpUnit command arguments, these arguments may also be passed to the Artisan test command.
```