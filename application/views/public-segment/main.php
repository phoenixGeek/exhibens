<script>
    segment_list = JSON.parse('<?=json_encode($segments)?>');
</script>

<div class="container">

    <div class="jumbotron">

        <h1 class="display-4"><?=$presentation->title?></h1>

        <div class="row collapse" id="top-pl">
    
            <div class="col-md-12"><video id="segment-preview1" style="width:100%;height:auto;" poster="<?=base_url().'assets/pa_dashboard/sample-player.png'?>"></video></div>
            <div class="col-md-3">
                <button id="preview-play1" data-playing="0" class="btn btn-primary">Play</button>
            </div>

            <input type="hidden" id="minTime-store1" value="0">
            <input type="hidden" id="maxTime-store1" value="0">
            <input type="hidden" id="vid-duration1" value="0">
            <input type="hidden" id="segment-index" value="0">
            
        </div>

        <hr class="my-4">
        <p>Posted by <b><?=$author->first_name ?></b> at  <i><?=$presentation->created_on?></i></p>
        <div class="row">
            <div class="list-group col-md-6">
            <?php
            
            $passDuration = 0;
            foreach($segments as $i => $s){
                ?>
                <div class="list-group-item list-group-item-action flex-column align-items-start segment-link" data-index="<?=$i?>" data-start="<?=$passDuration?>">
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