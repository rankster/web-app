<div class="row">
    <?php $GLOBALS['gameId'] = 1; echo \Rankster\Service\Output::process('game-top-list'); ?>
    <?php $GLOBALS['gameId'] = 2; echo \Rankster\Service\Output::process('game-top-list'); ?>

</div>
<?php echo \Rankster\Service\Output::process('match-request')  ?>