<?php $reports = $data;
/** @var Report $report */
$report = $reports[0];
$users = $report->getData()['users'];

?>
<ul>
    <h1>List of employees which dit not completed their setup</h1>
    <ul>
        <?php use App\Utility\Purifier;
        use Passbolt\Reports\Utility\Report;

        foreach ($users as $user): ?>
            <li><?= Purifier::clean($user->username); ?></li>
        <?php endforeach; ?>
    </ul>
</ul>
