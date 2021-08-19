<?php
/**
 * @var string $uniqid
 * @var int $zoom
 * @var string $title
 * @var string $text
 * @var float $latitude
 * @var float $longitude
 */
?>
<?php if (!empty($latitude) && !empty($longitude)): ?>

    <div class="widget widget-googlemaps">

        <?php if (!empty($title)): ?>
            <h2><?= $title ?></h2>
        <?php endif; ?>

        <?php if (!empty($text)): ?>
            <p><?php echo $text ?></p>
        <?php endif; ?>

        <div id="<?= $uniqid ?>" style="height:250px;"></div>

    </div>

    <script type="text/javascript">
        function initMap() {
            var options = {
                mapTypeId: google.maps.MapTypeId.ROADMAP, // SATELLITE
                disableDefaultUI: true
            };
            var initialLocation = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
            map = new google.maps.Map(document.getElementById("<?= $uniqid ?>"), options);
            map.setCenter(initialLocation);
            map.setZoom(<?= $zoom ?>);
            var point = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
            var marker = new google.maps.Marker({
                map: map,
                position: point,
                clickable: false
            });
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBCysVBxBNAAbFzDAZQGTulZoImQNrhhI&callback=initMap">
    </script>
<?php endif; ?>
