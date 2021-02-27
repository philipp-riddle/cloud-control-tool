# Cloud Tools (CLI & Web Interface Tools for Cloud File Systems)
Hi there! Nice that you found this repository. This is just me programming a cloud managent and overview system I've always wanted.

## Usage

To crawl any directories on your system execute the following:

```
php bin/crawl.php [DIR1] [DIR2] ... [DIR..]
```

This command will fill the database with all the files in the given directories.


You can access the web GUI by accessing `index.php`.

## Screenshots

![Dashboard](https://i.imgur.com/QIq5dmL.png)

The base dashboard.

## What will this system include?
It will include periodic updating, overview of how the storage changed over time, have overview over which directories take up the most space etc (file and directory analysis).
All that kind of stuff displayed in graphs with the help of charts.js.

## Tech Stack
* Mongo DB
* PHP
* Composer
* Twig
* Own little framework to make things work

## Why open-source?
I figured that I wanted to contribute more to the open source community and I think it's also nice for others to have access to this software as well.

## Feel free to contribute!