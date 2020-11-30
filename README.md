# snapshot

Laravel DB table snapshot package


## Installation

  composer require sebrave/snapshot

  composer update

  php artisan vendor:publish --provider="SebRave\\Snapshot\\SnapshotServiceProvider"


## Setup

Adjust settings in config/snapshot.php


## Usage

  app('snapshot')->show(MyClass::class);      

Where MyClass::class is an Eloquent model

  app('snapshot')->show('mytable');      

Where mytable is a table in your SQL database

A snapshot of the table is output in the root directory:

snapshot_myclass.html
