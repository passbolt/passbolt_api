You (<?php echo $sender['User']['username']; ?>)
have saved a new password on <?php echo date('M d,Y \a\t H:i', strtotime($resource['Secret'][0]['modified'])); ?>

Name : <?php echo $resource['Resource']['name']; ?>
Username : <?php echo $resource['Resource']['username']; ?>
Url : <?php echo $resource['Resource']['uri']; ?>
Description : <?php echo $resource['Resource']['description']; ?>

Password (encrypted):
<?php echo $resource['Secret'][0]['data']; ?>
