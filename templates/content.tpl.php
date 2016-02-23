<div class="container">
    <div id="tabs">
        <ul>
            <li><a href="tab-0">Tab 1</a></li>
            <li><a href="tab-1">Tab 2</a></li>
            <li><a href="tab-2">Tab 3</a></li>
            <?php foreach ($tabs as $index => $tab): ?>
                <li>
                    <a href="tab-<?php echo $index; ?>">
                        <?php echo $tab['label']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php foreach ($tabs as $index => $tab): ?>
            <div id="tab-<?php echo $index; ?>">
                <?php echo $tab['content']; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
