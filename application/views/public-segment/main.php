<script>
    segment_list = JSON.parse('<?=json_encode($segments)?>');
</script>

<div class="container">
    <div id="player-wrapper">                        
        <div id="player-controls">
            <div class="buttons-control mb-1">
                <a href="#" class="btn-play-media control"><i class="bi bi-play-btn"></i></a>
                <span>
                    <span class="video-chronos">00:00</span> / <span class="toal-duration">00:00</span>
                </span>
            </div>
            
            <div class="progress" style="height: 3px;">
                <div class="progress-bar" role="progressbar"  style="width: 0%;"  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>            
        </div> 
    </div>

    <div class="jumbotron">
        <div class="col-md-4 pull-right">
            <span>Play All Segments</span>
            <div class="custom-control custom-switch pull-right">
                
                <input type="checkbox" class="custom-control-input" id="playMode">
                <label class="custom-control-label" for="playMode">Play Single Segment</label>
            </div>
        </div>
        <h1 class="display-4"><?=$presentation->title?></h1>
        <p class="lead"><?=$presentation->description?></p>
        <hr class="my-4">
        <p>Posted by <b><?=$author->first_name ." ".$author->first_name ?></b> at  <i><?=$presentation->created_on?></i></p>
        <h3 class="lead">Video segments :</h3>
        <div class="row">
            <div class="list-group col-md-6">
            <?php
            
            $passDuration = 0;
            foreach($segments as $i => $s){
                ?>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start segment-link" data-index="<?=$i?>" data-start="<?=$passDuration?>">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= gmdate("H:i:s", $passDuration) . "-" . gmdate("H:i:s", $passDuration + $s->duration)?></h5>
                    <small class="text-muted"><?=gmdate("H:i:s", $s->duration)?></small>
                    </div>
                    <p class="mb-1"><?=$s->content?></p>                    
                </a>
                <?php
                $passDuration = $passDuration + $s->duration;
            }
            ?>
            </div>
        </div>
    </div>
</div>