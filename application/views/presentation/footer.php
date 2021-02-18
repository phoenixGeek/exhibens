<script src="<?= base_url() ?>assets/dashboard/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script type="text/javascript">
    let current_url = window.location.origin + window.location.pathname;
    var base_url = "<?= base_url() ?>";

    function get_base_url(m_url) {
        return base_url + m_url;
    }
</script>

<?php
if (isset($footer_script) && count($footer_script) > 0) {
    foreach ($footer_script as $key => $script) {
        echo "\n";
        echo '<script type="text/javascript" src="' . base_url() . 'assets/' . $script . '?ver=' . time() . '"></script>';
        echo "\n";
    }
}
?>

<script>
    jQuery(document).ready(function() {
        for (let i = 0; i < segment_list.length; i++) {
            var seg = segment_list[i];
            seg.start = parseFloat(seg.start);
            seg.end = parseFloat(seg.end);
            seg.duration = parseFloat(seg.duration);
        }

        jQuery("#player-wrapper").show();

    });
</script>
</body>

</html>