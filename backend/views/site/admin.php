<?php
    use backend\models\Apple;
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h1>Сгенерируйте необходимое кол-во яблок:</h1>
        <form method="post">
            <label style="margin-top:20px">Введите число от 1 до 100:</label><br />
            <input type="number" name="num" placeholder="Число" required/>
            <input type="submit" value="Отправить" />
        </form>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h3>Яблоки:</h3>
        <?php if(!empty($appels) && is_array($appels)): ?>
            <?php foreach($appels as $apple): ?>
                <table>
                    <tr>
                        <td colspan="5">ЯБЛОКО: <?= $apple['id']; ?></td>
                    </tr>
                    <tr>
                        <td>Цвет</td>
                        <td>Дата-Созрело</td>
                        <td>Дата-Упало</td>
                        <td>Состояние</td>
                        <td>Размер(%)</td>
                    </tr>
                    <tr>
                        <td><?= $apple['color']; ?></td>
                        <td><?= Apple::getCreateDate($apple['created_at']); ?></td>
                        <td><?= Apple::getUpdateDate($apple['updated_at']); ?></td>
                        <td><?= Apple::getAppleStatus($apple['status']); ?></td>
                        <td><?= $apple['size']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><a href="/site/fall/<?= $apple['id']; ?>">упасть</a></td>
                        <td colspan="2"><a href="/site/eat/<?= $apple['id']; ?>">съесть(25%)</a></td>
                    </tr>      
                </table>
            <?php endforeach ?>
        <?php else: ?>
            <h3>На дереве нет яблок</h3>
        <?php endif; ?>            
    </div>
</div>