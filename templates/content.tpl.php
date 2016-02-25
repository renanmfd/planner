<div id="tabs">
    <div id="tabsHead">
        <div class="container">
            <ul class="tab-list">
                <?php foreach ($tabs as $index => $tab): ?>
                    <li class="tab-link">
                        <a href="#tab<?php echo $index; ?>">
                            <span class="<?php echo $tab['icon']; ?>"></span>
                            <span class="tab-label"><?php echo $tab['label']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div id="tabsBody">
        <div class="container">
            <div id="tabEmpty" class="tab-item">
                <div class="empty">
                    <span class="glyphicon glyphicon-"></span>
                    <p>Click some <em>view</em> to see where you money is.</p>
                </div>
            </div>
            <?php foreach ($tabs as $index => $tab): ?>
                <div id="tab<?php echo $index; ?>" class="tab-item hidden">
                    <?php echo $tab['content']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
