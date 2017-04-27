EverJob
=======

A very simple way to get and store new job from alsacreation website

### Installation

First of all, you need to get the project by cloning it with git with the following command :

```sh
$ git clone git@github.com:ktxwebdev/EverJob.git
```
or 

```sh
$ git clone https://github.com/ktxwebdev/EverJob.git
```

[Download and install composer](https://getcomposer.org/download/) to the project and install the project with the command :

```sh
$ php composer.json install
```

### Set up

Create the dabatase 

```sh
$ php app/console doctrine:database:create
```

Create the database schema

```sh
$ php app/console doctrine:schema:create
```

### Usage

In order to save the current job list, you need to execute the command :

```sh
$ php app/console everjob:job:update-job-list
```

That's it, all the last jobs have been added to the database

Now you can access the list by visiting the API documentation :

[http://yourdomainname/app_dev.php/api/doc](http://yourdomainname/app_dev.php/api/doc)