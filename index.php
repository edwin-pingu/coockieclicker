<!DOCTYPE html>
<html>
<head>
    <title>Cookie Clicker</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>Cookie Clicker</h1>
    <div id="cookie"></div>
    <p id="score">Score: 0</p>
    <button id="upgradeButton">Upgrade (10)</button>
    <button id="doubleClickValueButton">Double Click Value (Cost: 150)</button>
    <button id="scoreMultiplierButton">Score Multiplier x3 (Cost: 500)</button>
    <button id="extraBonusButton">Extra Bonus (Cost: 300)</button>
    <button id="speedBoostButton">Speed Boost (Cost: 250)</button>
    <button id="autoClickerButton">Auto Clicker (100)</button>
    <button id="autoClickerUpgrade1">Auto Clicker Upgrade 1 (Cost: 200)</button>
    <button id="autoClickerUpgrade2">Auto Clicker Upgrade 2 (Cost: 500)</button>
    <button id="autoClickerUpgrade3">Auto Clicker Upgrade 3 (Cost: 1000)</button>
    <button id="autoClickerUpgrade4">Auto Clicker Upgrade 4 (Cost: 2000)</button>
    <button id="disableAutoClickerButton" style="display:none;">Disable Auto Clicker</button>
    <button id="resetButton">Reset</button>


<h2>overzicht</h2>
    <p>Upgrade: <span id="upgradeCount">0</span></p>   
    <p>Auto Clicker: <span id="autoClickerCount">0</span></p>
 

    <script>
        <?php include 'cookie.php'; ?>
        document.addEventListener('DOMContentLoaded', () => {
            new CookieClicker();
        });
    </script>
</body>
</html>