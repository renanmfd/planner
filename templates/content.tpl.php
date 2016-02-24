<div class="container">
    <div id="tabs">
        <div id="tabsHead">
            <ul class="tab-list">
                <li class="tab-link active">
                    <a href="#tabEmpty" id="tabDefault">
                        <span class="glyphicon glyphicon-align-justify"></span>
                        <span class="tab-label">Choose a <em>view</em></span>
                    </a>
                </li>
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
        <div id="tabsBody">
            <div id="tabEmpty">
                <div class="empty">
                    <span class="glyphicon glyphicon-"></span>
                    <p>Click a <em>view</em> to see where you money is.</p>
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
