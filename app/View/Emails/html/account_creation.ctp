<img src="<?= FULL_BASE_URL ?>/<?= $sender['Profile']['Avatar']['url']['small'] ?>"><br/>
<?= $sender['Profile']['first_name']; ?>  <?= $sender['Profile']['last_name']; ?> (<?= $sender['User']['username']; ?>)<br/>
shared a password with you on <?= date('M d,Y \a\t H:i', strtotime($account['User']['created'])); ?><br/>

<br/>
Welcome <?= $account['Profile']['first_name']; ?>

<?= $sender['Profile']['first_name']; ?> just invited you to join <?= FULL_BASE_URL ?><br/>
Passbolt is password manager that is designed to allow sharing credentials with your team without making compromises on security!<br/>

Let's take the next five minutes to get you started!<br/>
<a href="<?= FULL_BASE_URL ?>">Get started!</a>