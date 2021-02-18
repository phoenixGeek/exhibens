<script>
    segment_list = JSON.parse('<?=json_encode($segments)?>');
</script>

<div class="container">
    <div class="jumbotron">

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
                <a href="<?=base_url()."segment/index/".$presentation->id . "/" .$s->id ?>" target="_blank" class="list-group-item list-group-item-action flex-column align-items-start segment-link" data-index="<?=$i?>" data-start="<?=$passDuration?>">
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