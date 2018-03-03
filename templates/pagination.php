<?php if ($pages_count > 1): ?>
<ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
        <a <?php if ($cur_page != 1): ?>href="<?=$page_name; ?>?page=<?=$cur_page - 1; ?>"<?php endif; ?>>Назад</a>
    </li>
<?php foreach ($pages as $page): ?>
    <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
        <a <?php if ($page != $cur_page): ?>href="<?=$page_name; ?>?page=<?=$page; ?>"<?php endif; ?>><?=$page; ?></a>
    </li>
<?php endforeach; ?>
    <li class="pagination-item pagination-item-next">
        <a <?php if ($cur_page != $pages_count): ?>href="<?=$page_name; ?>?page=<?=$cur_page + 1;?>"<?php endif; ?>>Вперед</a>
    </li>
</ul>
<?php endif; ?>
