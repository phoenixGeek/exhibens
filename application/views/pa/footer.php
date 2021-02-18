</main>
<!-- page-content -->
</div>

<footer class="bg-white sticky-footer">
  <div class="container my-auto">
  </div>
</footer>
</div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
<script src="<?= base_url() ?>assets/dashboard/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/js/theme.js"></script>


<script type="text/javascript">
  let current_url = window.location.origin + window.location.pathname;
  var base_url = "<?= base_url() ?>";

  function get_base_url(m_url) {
    return base_url + m_url;
  }

  jQuery(function($) {

    $(".sidebar-dropdown > a").click(function() {
      $(".sidebar-submenu").slideUp(200);
      if (
        $(this)
        .parent()
        .hasClass("active")
      ) {
        $(".sidebar-dropdown").removeClass("active");
        $(this)
          .parent()
          .removeClass("active");
      } else {
        $(".sidebar-dropdown").removeClass("active");
        $(this)
          .next(".sidebar-submenu")
          .slideDown(200);
        $(this)
          .parent()
          .addClass("active");
      }
    });

    $("#close-sidebar").click(function() {
      $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
      $(".page-wrapper").addClass("toggled");
    });

    $(".sidebar-menu a").each((index, el) => {
      let el_href = $(el).attr('href');
      if (el_href == current_url) {
        if ($(el).parents(".sidebar-submenu").length) {
          $(el).parents('.sidebar-dropdown').addClass('active');
          $(el).parents(".sidebar-submenu").show();
        }
        $(el).parents('li').addClass('active');
      }
    })

  });
</script>

<script>
var segment_list = [];
var video_list = [];
</script>

<?php

if(isset($videos_added) && $videos_added){
  ?>
  <script>
    video_list = JSON.parse('<?=json_encode($videos_added)?>'); 
  </script>
  <?php
};

if(isset($segments_added) && $segments_added){
  ?>
  <script>
    segment_list = JSON.parse('<?=json_encode($segments_added)?>'); 
  </script>
  <?php
  }
  ?>
  
<!-- Load footer scripts -->
<?php
if (isset($footer_script) && count($footer_script) > 0) {
  foreach ($footer_script as $key => $script) {
    echo "\n";
    echo '<script type="text/javascript" src="' . base_url() . 'assets/' . $script . '?ver=' . time() . '"></script>';
    echo "\n";
  }
}
?>

<script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>

<script>

  $("#preview-play").click(function(){
    console.log("play");
    var values = $("#ex2").slider('getValue');
    var min = values[0];
    var max = values[1];
    var duration = parseInt($("#ex2").attr("data-slider-max"));
    
    var isPlaying = $(this).attr("data-playing");
    console.log("min value: ", min);
    console.log("max value: ", max);
    console.log("duration value: ", duration);

    var video = $("#segment-preview")[0];

    if(isPlaying != 1){
      video.play();
      isPlaying = 1;
      $(this).attr("data-playing","1");
      $(this).html("Pause");
    } else {
      video.pause();
      $(this).html("Play");
      $(this).attr("data-playing","0");
    }
    
  });
</script>

</body>
</html>