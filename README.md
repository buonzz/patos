# PHP AST to SQL

Tool to parse PHP Source code and store the data collected into a MySQL Database.
You can then explore, query the database to understand more about the codebase.


## Why?

It detects the following list in your source code: 
* classes
* list of methods within those classes
* user-defined constants currently in-use, and where they are used
* functions
* array-key values


## Installation

```bash
composer install
```



## Run

```bash
./bin/patos 
```