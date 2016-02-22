<div class="wrapper">
    <div id="tabController">
        <?php echo $sidebar; ?>
    </div>
    <div id="tabContent">
        <div class="row column">
            <?php foreach ($tabs as $tab): ?>
                <?php echo $tab; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
