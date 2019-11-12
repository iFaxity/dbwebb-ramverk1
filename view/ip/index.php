<?php
$color = $valid ? '#0f0' : '#f00';
?>

<h1>IP validerare</h1>

<?php if (!is_null($valid)) : ?>
    <h3>IP addressen är <span style="color: <?= $color ?>"><?= $valid ? "giltig" : "ogiltig" ?></span></h3>
    <h3>Domännamnet till addressen är <?= $domain ?></h3>
<?php endif; ?>

<form method="GET" action="">
    <div class="textfield">
        <input id="ip1" name="ip" value="<?= esc($ip) ?>" placeholder=" " autocomplete="off"/>
        <label for="ip1">IP-address</label>
    </div>

    <button type="submit" class="solid">Validera</button>
</form>


<h2>JSON API</h2>
<p>
    För att få ut samma information som formuläret ovan så finns ett API att använda på sidan.
    Testa IP APIet direkt via formuläret nedan.
</p>
<form method="post" action="<?= esc($apiUrl) ?>">
    <div class="textfield">
        <input id="ip2" name="ip" placeholder=" " autocomplete="off"/>
        <label for="ip2">IP-address</label>
    </div>

    <button type="submit" class="solid">Validera</button>
</form>

<h3>Test addresser</h3>
<p>Testa APIet direkt via testen nedan, klicka på en knapp för att testa med förbestämda IP addresser.</p>
<form method="post" action="<?= esc($apiUrl) ?>">
    <?php foreach ($addrs as $idx => $ip) : ?>
        <button type="submit" name="ip" value="<?= esc($ip) ?>">Test <?= $idx + 1 ?></button>
    <?php endforeach; ?>
</form>

<h3>Om APIet</h3>
<p>För att använda dig av valideringsverktygets API använd:</p>
<p>POST <?= esc($apiUrl . "?ip=<ip>") ?></p>
<p>
    Där &lt;ip&gt; är IP addressen som skall verifieras.
    Om parametern glöms eller är tom så skickas en respons med statusen 400 och kan se ut såhär:
    <pre><code><?= $examples->err ?></code></pre>
</p>
<p>
    Annars skickas en respons med statusen 200 och kan se ut såhär:
    <pre><code><?= $examples->ok ?></code></pre>
</p>
<p>
    Attributen &quot;ip&quot; är alltid den skickade ip addressen, även om addressen inte är giltig.
    Attributen &quot;valid&quot; kan vara true eller false beroende på om IP addressen var giltig eller ej.
    Attributen &quot;domain&quot; kan vara en sträng om ett domännamn hittades eller null om inget domännamn hittades.
</p>
