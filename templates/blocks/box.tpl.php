<div<?php echo $attr; ?>>
    <div class="box-header">
        <a href="#">
            <h3 class="box-title"><?php echo $title; ?></h3>
            <span class="glyphicon glyphicon-menu-down"></span>
            <span class="glyphicon glyphicon-menu-up"></span>
        </a>
    </div>
    <div class="box-body" style="display:none;">
        <?php if (is_array($list)): ?>
            <ul class="box-list">
                <?php foreach ($list as $item): ?>
                    <li class="box-item<?php echo isset($item['total']) ? ' total' : ''; ?>">
                        <div class="wrapper" data-type="<?php echo $item['name']; ?>">
                            <div class="label"><?php echo $item['label']; ?></div>
                            <div class="value">
                                <em>R$ </em><?php echo $item['value']; ?>
                            </div>
                        </div>
                        <span class="glyphicon <?php echo ($item['value'] < 0) ? 'glyphicon-minus-sign' : 'glyphicon-plus-sign'; ?>"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?php echo $list; ?>
        <?php endif; ?>
    </div>
</div>
