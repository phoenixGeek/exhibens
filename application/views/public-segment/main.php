<script>
    segment_list = JSON.parse('<?=json_encode($segments)?>');
</script>

<div class="container jumbotron">
    <h1 class="display-4"><?=$presentation->title?></h1>
    <?php
        if( $segments[0]->is_composite ) {
            $videoSrc = $segments[0]->path;
        } else if ( $segments[0]->fade_path ) {
            $videoSrc = $segments[0]->fade_path;
        } else if ( $segments[0]->segment_path ) {
            $videoSrc = $segments[0]->segment_path;
        }
    ?>
    <div id="player-wrapper">
        <video width="100%" controls autoplay>
            <source src="<?= $videoSrc ?>" type="video/mp4">
        </video>
    </div>

    <div>
        <br />
        <p class="lead"><?=$presentation->description?></p>
        <hr class="my-4">
        <p>Posted by <b><?=$author->first_name ?></b> at  <i><?=$presentation->created_on?></i></p>
        <h3 class="lead">Video segments :</h3>
        <div class="row">
            <div class="list-group col-md-6">

                <?php if($segments[0]->is_composite): ?>
                <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                    <table class="table dataTable my-0" class="userTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Public Page</th>
                            </tr>
                        </thead>
                        <tbody id="segments-selection">
                            <?php foreach ($segsForComp as $segment) : ?>
                                <?php if(!$segment->is_composite): ?>
                                <tr class="segments-list" data-segid="<?php echo $segment->id; ?>">
                                    
                                    <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                   
                                    
                        
                                    <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                        <td><a href="<?= $segment->segment_url ?>" target="_blank">View Segment</a></td>
                                    <?php else: ?>
                                        <td><a href="<?=base_url()."segment/index/".$segment->presentation_id . "/" .$segment->id ?>" target="_blank">View Segment</a></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>



            <?php
            
            $passDuration = 0;
            foreach($segments as $i => $s){
                ?>
                <!-- <div class="list-group-item list-group-item-action flex-column align-items-start" data-index="<?=$i?>" data-start="<?=$passDuration?>"> -->
                <div class="list-group-item flex-column align-items-start" data-index="<?=$i?>" data-start="<?=$passDuration?>">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= gmdate("H:i:s", $passDuration) . "-" . gmdate("H:i:s", $passDuration + $s->duration)?></h5>
                    <small class="text-muted"><?=gmdate("H:i:s", $s->duration)?></small>
                    </div>
                    <p class="mb-1"><?=$s->content?></p>                    
                </div>
                <?php
                $passDuration = $passDuration + $s->duration;
            }
            ?>
            </div>
        </div>
    </div>
</div>