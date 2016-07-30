<div class="row">
<?php
    $game = new \Rankster\Entity\Game();
    $game->id = 1;
    $game = $game->findSaved();
?>
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b><?php echo $game->name ?></b></h4>
                            <p class="text-muted font-13 m-b-25">
                                <img class="img-circle" width="75" src="<?php echo $game->getFullUrl(); ?>">
                            </p>

                            <table class="table m-0">
                                <tbody>
                                    <?php foreach (\Rankster\Entity\Rank::getRanks($game->id) as $i => $rank) : ?>
                                    <tr>
                                        <th scope="row"><?php echo ($i + 1); ?></th>
                                        <td><img class="img-circle" src="<?php echo \Rankster\Entity\User::patchToUrl($rank['picture_path']); ?>"/></td>
                                        <td><?php echo $rank['name']; ?></td>
                                        <td><?php echo $rank['rank']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Striped rows</b></h4>
                            <p class="text-muted font-13 m-b-25">
                                Use <code>.table-striped</code> to add zebra-striping to any table row within the <code>&lt;tbody&gt;</code>.
                            </p>


                            <table class="table table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Username</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Larry</td>
                                        <td>the Bird</td>
                                        <td>@twitter</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
<?php echo \Rankster\Service\Output::process('match-request')  ?>