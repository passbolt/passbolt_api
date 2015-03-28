<img src="<?= FULL_BASE_URL ?>/<?= $sender['Profile']['Avatar']['url']['small'] ?>"><br/>
<?= $sender['Profile']['first_name']; ?>  <?= $sender['Profile']['last_name']; ?> (<?= $sender['User']['username']; ?>)<br>
shared a password with you on <?= date('M d,Y \a\t H:i', strtotime($resource['Secret'][0]['modified'])); ?><br/>

Name : <?= $resource['Resource']['name']; ?><br/>
Username : <?= $resource['Resource']['username']; ?><br/>
Url : <?= $resource['Resource']['uri']; ?><br/>
Comment : <?= $resource['Resource']['description']; ?><br/>

<br/>
Password (encrypted):<br/>
<pre><?= $resource['Secret'][0]['data']; ?></pre>

