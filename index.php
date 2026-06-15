<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cookie Clicker</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="gameContainer">
        <header id="topBar">
            <div class="title-block">
                <h1>Cookie Clicker</h1>
                <p class="subtitle">Klik om koekjes te bakken en koop heerlijke koekjes</p>
            </div>
            <div class="score-box">
                <div id="score">Score: 0</div>
                <div id="cps">CPS: 0</div>
            </div>
        </header>

        <div id="content">
            <main id="playArea">
                <div class="cookie-frame">
                    <div id="cookie"></div>
                </div>
                <div id="buttonRow">
                    <button id="upgradeButton">Upgrade (10)</button>
                    <button id="doubleClickValueButton">Double Click Value (150)</button>
                    <button id="scoreMultiplierButton">Score Multiplier x3 (500)</button>
                    <button id="megaKlikButton">Mega Klik (300)</button>
                    <button id="comboClickerButton">Combo Clicker (250)</button>
                    <button id="resetButton">Reset</button>
                </div>
            </main>

            <aside id="shopPanel">
                <div class="panel">
                    <h2>Koekjeswinkel</h2>
                    <div class="buildingRow">
                        <button id="buildingButton0">Chocolate chip cookie (100)</button>
                        <div class="buildingCount">x <span id="buildingCount0">0</span></div>
                    </div>
                    <div class="buildingRow">
                        <button id="buildingButton1">Kaneelkoekje (500)</button>
                        <div class="buildingCount">x <span id="buildingCount1">0</span></div>
                    </div>
                    <div class="buildingRow">
                        <button id="buildingButton2">Boterkoekje (1000)</button>
                        <div class="buildingCount">x <span id="buildingCount2">0</span></div>
                    </div>
                    <div class="buildingRow">
                        <button id="buildingButton3">Bastognekoekie (1500)</button>
                        <div class="buildingCount">x <span id="buildingCount3">0</span></div>
                    </div>
                    <div class="buildingRow">
                        <button id="buildingButton4">Chocoladekoekje (2000)</button>
                        <div class="buildingCount">x <span id="buildingCount4">0</span></div>
                    </div>
                    <div class="buildingRow">
                        <button id="buildingButton5">Gevulde koekie (2500)</button>
                        <div class="buildingCount">x <span id="buildingCount5">0</span></div>
                    </div>
                </div>
                <div class="panel">
                    <h2>Overzicht</h2>
                    <p>Upgrades: <span id="upgradeCount">0</span></p>
                </div>
            </aside>
        </div>
    </div>

    <script>
        <?php include 'cookie.php'; ?>
        document.addEventListener('DOMContentLoaded', () => {
            new CookieClicker();
        });
    </script>
</body>
</html>
