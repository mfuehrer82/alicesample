[![SensioLabsInsight](https://insight.sensiolabs.com/projects/08fd2c1c-d70e-472e-bb19-1de0933edc04/mini.png)](https://insight.sensiolabs.com/projects/08fd2c1c-d70e-472e-bb19-1de0933edc04)
[![Build Status](https://travis-ci.org/mfuehrer82/symfony2-rest-example.svg?branch=master)](https://travis-ci.org/mfuehrer82/symfony2-rest-example)
[![Code Coverage](https://scrutinizer-ci.com/g/mfuehrer82/symfony2-rest-example/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mfuehrer82/symfony2-rest-example/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mfuehrer82/symfony2-rest-example/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mfuehrer82/symfony2-rest-example/?branch=master)

SYMFONY REST EXAMPLE
========================

### Start the App

```sh
$ composer install
$ app/console server:start
```
### Load Fixtures 
```sh
$ app/console hautelook_alice:fixtures:load --fixtures src/AppBundle/Tests/DataFixtures/ORM 
```

### Use the Api
```sh
http://127.0.0.1:8000/api/doc
```

### TODO

- Remove FormTypes and Replace with Validator Component
