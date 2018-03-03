<?=renderTemplate('templates/nav.php', ['categories' => $categories]); ?>
<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if (!empty($bets)): ?>
    <table class="rates__list">
    <?php foreach ($bets as $bet): ?>
      <tr class="rates__item <?=$bet['is_winner'] ?? ''; ?> <?=$bet['rates_end'] ?? ''; ?>">
        <td class="rates__info">
          <div class="rates__img">
            <img src="<?=$bet['lot_image']?>" width="54" height="40" alt="<?=htmlspecialchars($bet['lot_title']); ?>">
          </div>
          <h3 class="rates__title"><a href="lot.php?id=<?=$bet['lot_id'] ?? ''; ?>"><?=htmlspecialchars($bet['lot_title']); ?></a></h3>
          <?php if (isset($bet['is_winner'])): ?>
          <p><?=htmlspecialchars($bet['contacts']); ?></p>
          <?php endif;?>
        </td>
        <td class="rates__category">
          <?=$bet['category'] ?? ''; ?>
        </td>
        <td class="rates__timer">
          <div class="timer <?=$bet['timer'] ?? ''; ?>">
          <?php if (is_numeric($bet['date_end'])): ?>
            <?=countRemainingTime(htmlspecialchars($bet['date_end'])); ?>
          <?php else: ?>
            <?=htmlspecialchars($bet['date_end']); ?>
          <?php endif; ?>
          </div>
        </td>
        <td class="rates__price">
          <?=formatSum(htmlspecialchars($bet['price']), '&nbsp;р'); ?>
        </td>
        <td class="rates__time">
          <?=formatTime(htmlspecialchars($bet['date_add']))?>
        </td>
      </tr>
    <?php endforeach; ?>
    </table>
<?php else: ?>
    <h3>У вас еще нет ставок</h3>
<?php endif; ?>
</section>
