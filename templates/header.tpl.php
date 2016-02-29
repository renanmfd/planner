<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="logo-wrapper">
                <img src="images/logo.png" alt="Logo" class="img-responsive" />
            </div>
            <?php echo $date_widget; ?>
        </div>
        <?php $i = 0; ?>
        <?php foreach ($boxes as $box): ?>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <?php echo $box; ?>
            </div>
            <?php if ($i % 2 == 0): ?>
                <div class="clearfix visible-sm-block"></div>
            <?php endif; ?>
            <?php $i += 1; ?>
        <?php endforeach; ?>
    </div>
</div>
