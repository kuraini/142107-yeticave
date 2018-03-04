<ul class="lots__list">
<?php foreach ($lots as $lot): ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="<?=htmlspecialchars($lot['image'] ?? ''); ?>" width="350" height="260"
                alt="<?=htmlspecialchars($lot['title'] ?? ''); ?>">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?=$lot['category'] ?? ''; ?></span>
            <h3 class="lot__title">
                <a class="text-link" href="lot.php?id=<?=$lot['id'] ?? ''; ?>"><?=htmlspecialchars($lot['title'] ?? ''); ?></a>
            </h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=formatSum(htmlspecialchars($lot['start_price'] ?? 0)); ?></span>
                </div>
                <div class="lot__timer timer
                <?php if ((($lot['date_end'] ?? 0) - time()) < 0): ?>
                    timer--end
                <?php elseif ((($lot['date_end'] ?? 0) - time()) <= 3600): ?>
                    timer--finishing
                <?php endif; ?>">
                    <?=countRemainingTime($lot['date_end'] ?? 0); ?>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>
</ul>
