# SnowTricks

[![SymfonyInsight](https://insight.symfony.com/projects/2fe06158-e373-4e79-a93c-8feb0ccb7edd/big.svg)](https://insight.symfony.com/projects/2fe06158-e373-4e79-a93c-8feb0ccb7edd)

OpenClassrooms project, a snowboard tricks blog, using symfony 7.

## Requirements

| Dependency  |
| ----------- |
| PHP         |
| Composer    |
| Symfony cli |
| MySql       |

### Setup

To setup the environnement run :

```shell
$ git clone
```

```shell
$ cd <project>
```

```shell
$ composer install
```

```shell
$ npm install
$ npm run build
```

### Config

In the project folder run this command :

```shell
$ cp .env .env.local
```

And then fill the this variable :

```
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
```

### DB setup

To create the database and all the tables execute this command :

```
$ php bin/console doctrine:database:create
```

To load dataFixtures in the database run :

```
$ php bin/console doctrine:fixtures:load
```

### Run

To start the symfony server run :

```
$ symfony server:start
```
