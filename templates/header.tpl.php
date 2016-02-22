<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="logo-wrapper">
                <img src="images/logo.png" alt="Logo" class="img-responsive" />
            </div>
            <?php echo $date_widget; ?>
        </div>
        <?php foreach ($boxes as $box): ?>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php echo $box; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
