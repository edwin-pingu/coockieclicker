<?php
?>
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
        
        class CookieClicker {
            constructor() {
                this.score = parseInt(localStorage.getItem('score')) || 0;
                this.clickValue = parseInt(localStorage.getItem('clickValue')) || 1;
                this.autoClickerCost = 100;
                this.autoClickerInterval = null;
                this.autoClickerActive = localStorage.getItem('autoClickerActive') === 'true';
                this.autoClickerUpgradeCosts = [200, 500, 1000, 2000];
                this.autoClickerUpgradeLevels = [0, 0, 0, 0];
                this.upgradeCost = parseInt(localStorage.getItem('upgradeCost')) || 10;
                this.upgradeMultiplier = 1.2;
                this.clickMultiplier = 1.2;
                this.upgradeCount = parseInt(localStorage.getItem('upgradeCount')) || 0;
                this.autoClickerCount = parseInt(localStorage.getItem('autoClickerCount')) || 0;

                this.cookie = document.getElementById('cookie');
                this.scoreElement = document.getElementById('score');
                this.upgradeButton = document.getElementById('upgradeButton');
                this.doubleClickValueButton = document.getElementById('doubleClickValueButton');
                this.scoreMultiplierButton = document.getElementById('scoreMultiplierButton');
                this.extraBonusButton = document.getElementById('extraBonusButton');
                this.speedBoostButton = document.getElementById('speedBoostButton');
                this.doubleClickValueCost = 150;
                this.scoreMultiplierCost = 500;
                this.extraBonusCost = 300;
                this.speedBoostCost = 250;
                this.isSpeedBoostActive = false;
                this.autoClickerButton = document.getElementById('autoClickerButton');
                this.disableAutoClickerButton = document.getElementById('disableAutoClickerButton');
                this.resetButton = document.getElementById('resetButton');
                this.upgradeCountElement = document.getElementById('upgradeCount');
                this.autoClickerCountElement = document.getElementById('autoClickerCount');

                this.autoClickerUpgradeButtons = [
            document.getElementById('autoClickerUpgrade1'),
            document.getElementById('autoClickerUpgrade2'),
            document.getElementById('autoClickerUpgrade3'),
            document.getElementById('autoClickerUpgrade4')
        ];

                this.updateUI();
                this.addEventListeners();
                if (this.autoClickerActive) {
                    this.startAutoClicker();
                }
            }

            updateUI() {
                this.scoreElement.textContent = 'Score: ' + this.score;
                this.upgradeButton.textContent = 'Upgrade (' + this.upgradeCost + ')';
                this.upgradeCountElement.textContent = this.upgradeCount;
                this.autoClickerCountElement.textContent = this.autoClickerCount;
                if (this.autoClickerActive) {
                    this.autoClickerButton.textContent = 'Auto Clicker Active';
                    this.autoClickerButton.disabled = true;
                    this.disableAutoClickerButton.style.display = 'inline';
                }
                this.doubleClickValueButton.textContent = 'Double Click Value (' + this.doubleClickValueCost + ')';
                this.scoreMultiplierButton.textContent = 'Score Multiplier x3 (' + this.scoreMultiplierCost + ')';
                this.extraBonusButton.textContent = 'Extra Bonus (' + this.extraBonusCost + ')';
                this.speedBoostButton.textContent = 'Speed Boost (' + this.speedBoostCost + ')';
            }

            addEventListeners() {
                this.cookie.addEventListener('click', () => this.incrementScore());
                this.upgradeButton.addEventListener('click', () => this.upgrade());
                this.autoClickerButton.addEventListener('click', () => this.buyAutoClicker());
                this.disableAutoClickerButton.addEventListener('click', () => this.disableAutoClicker());
                this.resetButton.addEventListener('click', () => this.reset());
                this.doubleClickValueButton.addEventListener('click', () => this.doubleClickValue());
                this.scoreMultiplierButton.addEventListener('click', () => this.scoreMultiplier());
                this.extraBonusButton.addEventListener('click', () => this.extraBonus());
                this.speedBoostButton.addEventListener('click', () => this.speedBoost());
            }

            incrementScore() {
                this.score += this.clickValue;
                this.updateScore();
            }

            upgrade() {
                if (this.score >= this.upgradeCost) {
                    this.score -= this.upgradeCost;
                    this.clickValue *= this.clickMultiplier;
                    this.upgradeCost *= this.upgradeMultiplier;
                    this.upgradeCount++;
                    this.updateScore();
                    this.updateUpgrade();
                }
            }

            doubleClickValue() {
        if (this.score >= this.doubleClickValueCost) {
            this.score -= this.doubleClickValueCost;
            this.clickValue *= 2;
            this.updateScore();
        }
    }

            scoreMultiplier() {
        if (this.score >= this.scoreMultiplierCost) {
            this.score -= this.scoreMultiplierCost;
            this.clickValue *= 3;
            this.updateScore();
        }
    }

            extraBonus() {
        if (this.score >= this.extraBonusCost) {
            this.score -= this.extraBonusCost;
            this.score += 500;  // Extra bonus punten
            this.updateScore();
        }
    }

            speedBoost() {
        if (this.score >= this.speedBoostCost && !this.isSpeedBoostActive) {
            this.score -= this.speedBoostCost;
            this.isSpeedBoostActive = true;
            this.clickValue += 5;  // Tijdelijke klikwaarde boost
            this.updateScore();
            setTimeout(() => {
                this.clickValue -= 5;  // Verwijder de boost na 10 seconden
                this.isSpeedBoostActive = false;
                this.updateUI();
            }, 10000); // 10 seconden boost
        }
    }

            buyAutoClicker() {
                if (this.score >= this.autoClickerCost && !this.autoClickerActive) {
                    this.score -= this.autoClickerCost;
                    this.autoClickerActive = true;
                    this.autoClickerCount++;
                    this.startAutoClicker();
                    this.updateScore();
                    this.updateAutoClicker();
                }
            }

            startAutoClicker() {
                this.autoClickerInterval = setInterval(() => {
                    this.score += this.clickValue;
                    this.updateScore();
                }, 1000);
            }

            disableAutoClicker() {
                if (this.autoClickerActive) {
                    clearInterval(this.autoClickerInterval);
                    this.autoClickerActive = false;
                    this.autoClickerButton.textContent = 'Auto Clicker (' + this.autoClickerCost + ')';
                    this.autoClickerButton.disabled = false;
                    this.disableAutoClickerButton.style.display = 'none';
                    localStorage.setItem('autoClickerActive', this.autoClickerActive);
                }
            }

            applyAutoClickerUpgrades() {
        const upgradeMultiplier = this.autoClickerUpgradeLevels.reduce((multiplier, level) => {
            return multiplier + level * 0.5;
        }, 1);

        if (this.autoClickerInterval) clearInterval(this.autoClickerInterval);
        this.autoClickerInterval = setInterval(() => {
            this.score += this.clickValue * upgradeMultiplier;
            this.updateScore();
        }, 1000 / upgradeMultiplier);
    }

            reset() {
                this.score = 0;
                this.clickValue = 1;
                this.upgradeCost = 10;
                this.upgradeCount = 0;
                this.autoClickerCount = 0;
                this.autoClickerActive = false;
                clearInterval(this.autoClickerInterval);
                this.updateUI();
                this.saveState();
            }

            updateScore() {
                this.scoreElement.textContent = 'Score: ' + this.score;
                localStorage.setItem('score', this.score);
            }

            updateUpgrade() {
                this.upgradeButton.textContent = 'Upgrade (' + this.upgradeCost + ')';
                this.upgradeCountElement.textContent = this.upgradeCount;
                localStorage.setItem('clickValue', this.clickValue);
                localStorage.setItem('upgradeCost', this.upgradeCost);
                localStorage.setItem('upgradeCount', this.upgradeCount);
            }

            updateAutoClicker() {
                this.autoClickerButton.textContent = 'Auto Clicker Active';
                this.autoClickerButton.disabled = true;
                this.disableAutoClickerButton.style.display = 'inline';
                this.autoClickerCountElement.textContent = this.autoClickerCount;
                localStorage.setItem('autoClickerActive', this.autoClickerActive);
                localStorage.setItem('autoClickerCount', this.autoClickerCount);
            }

            saveState() {
                localStorage.setItem('score', this.score);
                localStorage.setItem('clickValue', this.clickValue);
                localStorage.setItem('upgradeCost', this.upgradeCost);
                localStorage.setItem('upgradeCount', this.upgradeCount);
                localStorage.setItem('autoClickerCount', this.autoClickerCount);
                localStorage.setItem('autoClickerActive', this.autoClickerActive);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new CookieClicker();
        });
    </script>
</body>
</html>