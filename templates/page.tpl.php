<div id="page">
    <aside id="sidebar" class="collapsed">
        <?php echo $sidebar; ?>
    </aside>

    <header id="header">
        <?php echo $header; ?>
    </header>

    <main id="content">
        <?php echo $content; ?>
    </main>

    <footer id="footer">
        <?php echo $footer; ?>
    </footer>

    <?php if (!empty($debug)): ?>
        <section id="debug">
            <div class="container">
                <?php echo $debug; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
