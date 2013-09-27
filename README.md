MySQL Abstraction Class
=============

This is a class that I've used in some of my projects for years. It's a simple wrapper to MySQL
written in PHP5 that allows you to connect, run queries and get results. It's been used in production
sites for some time now and I've re-factored it several times.

### Setup
-----------------
 Add a `composer.json` file to your project:

```javascript
{
  "require": {
      "rcastera/mysql-wrapper": "v1.0.0"
  }
}
```

Then provided you have [composer](http://getcomposer.org) installed, you can run the following command:

```bash
$ composer.phar install
```

That will fetch the library and its dependencies inside your vendor folder. Then you can add the following to your
.php files in order to use the library (if you don't already have one).

```php
require 'vendor/autoload.php';
```

Then you need to `use` the relevant class, and instantiate the class. For example:


### Getting Started
-----------------
```php
require 'vendor/autoload.php';

use rcastera\Mysql\Database;

$db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
```


### Examples
-----------------

##### Select all records from contacts table.
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Mysql\Database;
    $db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
?>
<?php $contacts = $db->executeQuery('SELECT * FROM contacts')->asObject(); ?>
<?php if ($contacts): ?>
<ul>
    <?php foreach($contacts as $contact): ?>
    <li><?php echo $contact->first_name; ?> <?php echo $contact->last_name; ?></li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No contacts found.</p>
<?php endif; ?>
<?php unset($db); ?>
```

##### Insert a record into the contacts table.
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Mysql\Database;
    $db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
?>
<?php $inserted = $db->executeQuery('INSERT INTO contacts (first_name, last_name, email) VALUES ("Isabella", "Castera", "email@domain.com")')->wasInserted(); ?>
    <?php if ($inserted): ?>
    <p>Contact Isabella inserted with id, <?php echo $inserted; ?></p>
    <?php else: ?>
    <p>No contacts found.</p>
    <?php endif; ?>
<?php unset($db); ?>
```

##### Delete a record from the contacts table.
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Mysql\Database;
    $db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
?>
<?php $deleted = $db->executeQuery('DELETE FROM contacts WHERE first_name = "Isabella"')->wasDeleted(); ?>
    <?php if ($deleted): ?>
    <p>Contact Isabella deleted.</p>
    <?php else: ?>
    <p>No contacts found.</p>
    <?php endif; ?>
<?php unset($db); ?>
```

##### Update a record in the contacts table.
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Mysql\Database;
    $db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
?>
<?php $updated = $db->executeQuery('UPDATE contacts SET last_name = "Branson" WHERE first_name = "Richard"')->wasUpdated(); ?>
    <?php if ($updated): ?>
    <p>Contact Richard updated.</p>
    <?php else: ?>
    <p>No contacts found.</p>
    <?php endif; ?>
<?php unset($db); ?>
```

### Contributing
-----------------
1. Fork it.
2. Create a branch (`git checkout -b my_branch`)
3. Commit your changes (`git commit -am "Added something"`)
4. Push to the branch (`git push origin my_branch`)
5. Create an Issue with a link to your branch
6. Enjoy a refreshing Coke and wait
