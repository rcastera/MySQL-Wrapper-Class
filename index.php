<!DOCTYPE html>
<html>
<head>
<title>MySQL Abstraction Class</title>
<meta charset=utf-8 />
<style>
  body {
    margin: 0px;
    padding: 0px;
    top: 0px;
    right: 0px;
    bottom: 0px;
    left: 0px;
    font-family: serif;
  }
  header {
    background-color: #000;
  }
    header h1 {
      margin: 0px;
      padding: 10px;
      color: #fff;
    }
  #content {
    padding: 20px;
  }
    #content .note {
      background-color: #FCF8E3;
      border: 1px solid #FBEED5;
      border-radius: 4px 4px 4px 4px;
      color: #C09853;
      margin-bottom: 18px;
      padding: 8px 35px 8px 14px;
      text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
    #content code {
      margin: 0px 0px 20px 0px;
      padding: 10px;
      display: block;
      background-color: #F7F7F9;
      border: 1px solid #E1E1E8;
      border-radius: 4px 4px 4px 4px;
      white-space: pre-wrap;
      word-wrap: break-word;
    }
</style>
</head>
<body>
  <header id="header">
    <h1>MySQL Abstraction Class</h1>
  </header>

  <div id="content">
    <p class="note">
      First thing you'll want to do is run the included sql file to add records to test with or connect 
      to an existing database.
    </p>

    <article>
      <?php
        require_once('Class.Database.php');
        $Db = new Database('localhost', 'test', 'root', 'bryanna');
      ?>
      <h2>Select all records from contacts table.</h2>
      <code><span style="color: #000000"><span style="color: #0000BB">&lt;?php<br>&nbsp;&nbsp;</span><span style="color: #007700">require_once(</span><span style="color: #DD0000">"Class.Database.php"</span><span style="color: #007700">);<br>&nbsp;&nbsp;</span><span style="color: #0000BB">$Db&nbsp;</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Database</span><span style="color: #007700">(</span><span style="color: #DD0000">"localhost"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"DATABASE"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"USERNAME"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"PASSWORD"</span><span style="color: #007700">);<br></span><span style="color: #0000BB">?&gt;<br></span><br><span style="color: #0000BB">&lt;?php&nbsp;$contacts&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">$Db</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">executeQuery</span><span style="color: #007700">(</span><span style="color: #DD0000">"SELECT&nbsp;*&nbsp;FROM&nbsp;contacts"</span><span style="color: #007700">)-&gt;</span><span style="color: #0000BB">asObject</span><span style="color: #007700">();&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">if&nbsp;(</span><span style="color: #0000BB">$contacts</span><span style="color: #007700">):&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;ul&gt;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">foreach(</span><span style="color: #0000BB">$contacts&nbsp;</span><span style="color: #007700">as&nbsp;</span><span style="color: #0000BB">$contact</span><span style="color: #007700">):&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$contact</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">first_name</span><span style="color: #007700">;&nbsp;</span><span style="color: #0000BB">?&gt;</span>&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$contact</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">last_name</span><span style="color: #007700">;&nbsp;</span><span style="color: #0000BB">?&gt;</span>&lt;/li&gt;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">endforeach;&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;/ul&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">else:&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&lt;p&gt;No&nbsp;contacts&nbsp;found.&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">endif;&nbsp;</span><span style="color: #0000BB">?&gt;<br>&lt;?php&nbsp;</span><span style="color: #007700">unset(</span><span style="color: #0000BB">$Db</span><span style="color: #007700">);&nbsp;</span><span style="color: #0000BB">?&gt;</span></span></code>
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
    </article>

    <article>
      <?php
        require_once('Class.Database.php');
        $Db = new Database('localhost', 'test', 'root', 'bryanna');
      ?>
      <h2>Insert a record into the contacts table.</h2>
      <code><span style="color: #000000"><span style="color: #0000BB">&lt;?php<br>&nbsp;&nbsp;</span><span style="color: #007700">require_once(</span><span style="color: #DD0000">"Class.Database.php"</span><span style="color: #007700">);<br>&nbsp;&nbsp;</span><span style="color: #0000BB">$Db&nbsp;</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Database</span><span style="color: #007700">(</span><span style="color: #DD0000">"localhost"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"DATABASE"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"USERNAME"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"PASSWORD"</span><span style="color: #007700">);<br></span><span style="color: #0000BB">?&gt;<br></span><br><span style="color: #0000BB">&lt;?php&nbsp;$inserted&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">$Db</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">executeQuery</span><span style="color: #007700">(</span><span style="color: #DD0000">"INSERT&nbsp;INTO&nbsp;contacts&nbsp;(first_name,&nbsp;last_name,&nbsp;email)&nbsp;VALUES&nbsp;("</span><span style="color: #0000BB">Isabella</span><span style="color: #DD0000">",&nbsp;"</span><span style="color: #0000BB">Castera</span><span style="color: #DD0000">",&nbsp;"</span><span style="color: #0000BB">email</span><span style="color: #007700">@</span><span style="color: #0000BB">domain</span><span style="color: #007700">.</span><span style="color: #0000BB">com</span><span style="color: #DD0000">")"</span><span style="color: #007700">)-&gt;</span><span style="color: #0000BB">wasInserted</span><span style="color: #007700">();&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">if&nbsp;(</span><span style="color: #0000BB">$inserted</span><span style="color: #007700">):&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Contact&nbsp;Isabella&nbsp;inserted&nbsp;with&nbsp;id,&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$inserted</span><span style="color: #007700">;&nbsp;</span><span style="color: #0000BB">?&gt;</span>&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">else:&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;No&nbsp;contacts&nbsp;found.&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">endif;&nbsp;</span><span style="color: #0000BB">?&gt;<br>&lt;?php&nbsp;</span><span style="color: #007700">unset(</span><span style="color: #0000BB">$Db</span><span style="color: #007700">);&nbsp;</span><span style="color: #0000BB">?&gt;</span></span></code>
      <?php $inserted = $Db->executeQuery('INSERT INTO contacts (first_name, last_name, email) VALUES ("Isabella", "Castera", "email@domain.com")')->wasInserted(); ?>
        <?php if ($inserted): ?>
          <p>Contact Isabella inserted with id, <?php echo $inserted; ?></p>
        <?php else: ?>
          <p>No contacts found.</p>
        <?php endif; ?>
      <?php unset($Db); ?>
    </article>

    <article>
      <?php
        require_once('Class.Database.php');
        $Db = new Database('localhost', 'test', 'root', 'bryanna');
      ?>
      <h2>Delete a record from the contacts table.</h2>
      <code><span style="color: #000000"><span style="color: #0000BB">&lt;?php<br>&nbsp;&nbsp;</span><span style="color: #007700">require_once(</span><span style="color: #DD0000">"Class.Database.php"</span><span style="color: #007700">);<br>&nbsp;&nbsp;</span><span style="color: #0000BB">$Db&nbsp;</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Database</span><span style="color: #007700">(</span><span style="color: #DD0000">"localhost"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"DATABASE"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"USERNAME"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"PASSWORD"</span><span style="color: #007700">);<br></span><span style="color: #0000BB">?&gt;<br></span><br><span style="color: #0000BB">&lt;?php&nbsp;$deleted&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">$Db</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">executeQuery</span><span style="color: #007700">(</span><span style="color: #DD0000">"DELETE&nbsp;FROM&nbsp;contacts&nbsp;WHERE&nbsp;first_name&nbsp;=&nbsp;"</span><span style="color: #0000BB">Isabella</span><span style="color: #DD0000">""</span><span style="color: #007700">)-&gt;</span><span style="color: #0000BB">wasDeleted</span><span style="color: #007700">();&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">if&nbsp;(</span><span style="color: #0000BB">$deleted</span><span style="color: #007700">):&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Contact&nbsp;Isabella&nbsp;deleted.&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">else:&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;No&nbsp;contacts&nbsp;found.&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">endif;&nbsp;</span><span style="color: #0000BB">?&gt;<br>&lt;?php&nbsp;</span><span style="color: #007700">unset(</span><span style="color: #0000BB">$Db</span><span style="color: #007700">);&nbsp;</span><span style="color: #0000BB">?&gt;</span></span></code>
      <?php $deleted = $Db->executeQuery('DELETE FROM contacts WHERE first_name = "Isabella"')->wasDeleted(); ?>
        <?php if ($deleted): ?>
          <p>Contact Isabella deleted.</p>
        <?php else: ?>
          <p>No contacts found.</p>
        <?php endif; ?>
      <?php unset($Db); ?>
    </article>

    <article>
      <?php
        require_once('Class.Database.php');
        $Db = new Database('localhost', 'test', 'root', 'bryanna');
      ?>
      <h2>Update a record in the contacts table.</h2>
      <code><span style="color: #000000"><span style="color: #0000BB">&lt;?php<br>&nbsp;&nbsp;</span><span style="color: #007700">require_once(</span><span style="color: #DD0000">"Class.Database.php"</span><span style="color: #007700">);<br>&nbsp;&nbsp;</span><span style="color: #0000BB">$Db&nbsp;</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Database</span><span style="color: #007700">(</span><span style="color: #DD0000">"localhost"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"DATABASE"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"USERNAME"</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">"PASSWORD"</span><span style="color: #007700">);<br></span><span style="color: #0000BB">?&gt;<br></span><br><span style="color: #0000BB">&lt;?php&nbsp;$updated&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">$Db</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">executeQuery</span><span style="color: #007700">(</span><span style="color: #DD0000">"UPDATE&nbsp;contacts&nbsp;SET&nbsp;last_name&nbsp;=&nbsp;"</span><span style="color: #0000BB">Branson</span><span style="color: #DD0000">"&nbsp;WHERE&nbsp;first_name&nbsp;=&nbsp;"</span><span style="color: #0000BB">Richard</span><span style="color: #DD0000">""</span><span style="color: #007700">)-&gt;</span><span style="color: #0000BB">wasUpdated</span><span style="color: #007700">();&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">if&nbsp;(</span><span style="color: #0000BB">$updated</span><span style="color: #007700">):&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Contact&nbsp;updated.&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">else:&nbsp;</span><span style="color: #0000BB">?&gt;<br></span>&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;No&nbsp;contacts&nbsp;found.&lt;/p&gt;<br>&nbsp;&nbsp;<span style="color: #0000BB">&lt;?php&nbsp;</span><span style="color: #007700">endif;&nbsp;</span><span style="color: #0000BB">?&gt;<br>&lt;?php&nbsp;</span><span style="color: #007700">unset(</span><span style="color: #0000BB">$Db</span><span style="color: #007700">);&nbsp;</span><span style="color: #0000BB">?&gt;</span></span></code>
      <?php $updated = $Db->executeQuery('UPDATE contacts SET last_name = "Branson" WHERE first_name = "Richard"')->wasUpdated(); ?>
        <?php if ($updated): ?>
          <p>Contact Richard updated.</p>
        <?php else: ?>
          <p>No contacts found.</p>
        <?php endif; ?>
      <?php unset($Db); ?>
    </article>

  </div>

  <footer id="footer">
    
  </footer>

</body>
</html>