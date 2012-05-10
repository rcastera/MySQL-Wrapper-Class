MySQL Abstraction Class
=============

This is a class that I've used in some of my projects for years. It's a simple wrapper to MySQL written in PHP5 that allows you to connect, run queries and get results. 
It's been used in production sites for some time now and I've re-factored it several times.


Examples
-----------
    Select all records from contacts table.
    <?php
      require_once('Class.Database.php');
      $Db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
    ?>
    <?php $contacts = $Db->executeQuery('SELECT * FROM contacts')->asObject(); ?>
      <?php if ($contacts): ?>
        <ul>
          <?php foreach($contacts as $contact): ?>
            <li><?php echo $contact->first_name; ?> <?php echo $contact->last_name; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
      <p>No contacts found.</p>
      <?php endif; ?>
    <?php unset($Db); ?>

    Insert a record into the contacts table.
    <?php
      require_once('Class.Database.php');
      $Db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
    ?>
    <?php $inserted = $Db->executeQuery('INSERT INTO contacts (first_name, last_name, email) VALUES ("Isabella", "Castera", "email@domain.com")')->wasInserted(); ?>
      <?php if ($inserted): ?>
        <p>Contact Isabella inserted with id, <?php echo $inserted; ?></p>
      <?php else: ?>
        <p>No contacts found.</p>
      <?php endif; ?>
    <?php unset($Db); ?>

    Delete a record from the contacts table.
    <?php
      require_once('Class.Database.php');
      $Db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
    ?>
    <?php $deleted = $Db->executeQuery('DELETE FROM contacts WHERE first_name = "Isabella"')->wasDeleted(); ?>
      <?php if ($deleted): ?>
        <p>Contact Isabella deleted.</p>
      <?php else: ?>
        <p>No contacts found.</p>
      <?php endif; ?>
    <?php unset($Db); ?>

    Update a record in the contacts table.
    <?php
      require_once('Class.Database.php');
      $Db = new Database('localhost', 'DATABASE', 'USERNAME', 'PASSWORD');
    ?>
    <?php $updated = $Db->executeQuery('UPDATE contacts SET last_name = "Branson" WHERE first_name = "Richard"')->wasUpdated(); ?>
      <?php if ($updated): ?>
        <p>Contact Richard updated.</p>
      <?php else: ?>
        <p>No contacts found.</p>
      <?php endif; ?>
    <?php unset($Db); ?>
      
Contributing
------------

1. Fork it.
2. Create a branch (`git checkout -b my_branch`)
3. Commit your changes (`git commit -am "Added something"`)
4. Push to the branch (`git push origin my_branch`)
5. Create an [Issue][1] with a link to your branch
6. Enjoy a refreshing Coke and wait
