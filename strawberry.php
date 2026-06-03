<script>
    class StrawberryClicker {
        constructor() {
            // Beginwaarden en instellingen, opgeslagen in de browser
            this.score = parseInt(localStorage.getItem('score')) || 0; // Huidige score
            this.clickValue = parseInt(localStorage.getItem('clickValue')) || 1; // Waarde per klik
            this.autoClickerCost = 100; // Kosten voor de Auto Clicker
            this.autoClickerInterval = null; // Interval voor Auto Clicker
            this.autoClickerActive = localStorage.getItem('autoClickerActive') === 'true'; // Controleren of Auto Clicker actief is
            this.autoClickerUpgradeCosts = [200, 500, 1000, 2000]; // Kosten voor upgrades van Auto Clicker
            this.autoClickerUpgradeLevels = [0, 0, 0, 0]; // Niveaus van Auto Clicker upgrades
            this.upgradeCost = parseInt(localStorage.getItem('upgradeCost')) || 10; // Kosten voor basis-upgrade
            this.upgradeMultiplier = 1.2; // Multiplier voor volgende upgrade kosten
            this.clickMultiplier = 1.2; // Multiplier voor elke upgrade klikwaarde
            this.upgradeCount = parseInt(localStorage.getItem('upgradeCount')) || 0; // Aantal keer geüpgraded
            this.autoClickerCount = parseInt(localStorage.getItem('autoClickerCount')) || 0; // Aantal Auto Clickers gekocht

            // HTML elementen opslaan voor later gebruik
            this.cookie = document.getElementById('cookie'); // Klikbare cookie
            this.scoreElement = document.getElementById('score'); // Scoreweergave
            this.upgradeButton = document.getElementById('upgradeButton'); // Upgrade knop
            this.doubleClickValueButton = document.getElementById('doubleClickValueButton'); // Dubbel klikwaarde knop
            this.scoreMultiplierButton = document.getElementById('scoreMultiplierButton'); // Vermenigvuldiger knop
            this.extraBonusButton = document.getElementById('extraBonusButton'); // Extra bonus knop
            this.speedBoostButton = document.getElementById('speedBoostButton'); // Snelheidsboost knop
            this.doubleClickValueCost = 150; // Kosten voor dubbele klikwaarde
            this.scoreMultiplierCost = 500; // Kosten voor score vermenigvuldiger
            this.extraBonusCost = 300; // Kosten voor extra bonus
            this.speedBoostCost = 250; // Kosten voor snelheidsboost
            this.isSpeedBoostActive = false; // Checkt of snelheidsboost actief is
            this.autoClickerButton = document.getElementById('autoClickerButton'); // Auto Clicker knop
            this.disableAutoClickerButton = document.getElementById('disableAutoClickerButton'); // Knop om Auto Clicker uit te schakelen
            this.resetButton = document.getElementById('resetButton'); // Resetknop
            this.upgradeCountElement = document.getElementById('upgradeCount'); // Telt aantal upgrades
            this.autoClickerCountElement = document.getElementById('autoClickerCount'); // Telt aantal Auto Clickers

            // UI bijwerken en event listeners toevoegen
            this.updateUI();
            this.addEventListeners();

            // Start Auto Clicker als deze al actief was bij vorige sessie
            if (this.autoClickerActive) {
                this.startAutoClicker();
            }
        }

        updateUI() {
            // UI elementen bijwerken om huidige spelstatus te tonen
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
            // Event listeners koppelen aan HTML elementen
            this.cookie.addEventListener('click', () => this.incrementScore()); // Klikken verhoogt score
            this.upgradeButton.addEventListener('click', () => this.upgrade()); // Upgrade knop
            this.autoClickerButton.addEventListener('click', () => this.buyAutoClicker()); // Auto Clicker kopen
            this.disableAutoClickerButton.addEventListener('click', () => this.disableAutoClicker()); // Auto Clicker uitschakelen
            this.resetButton.addEventListener('click', () => this.reset()); // Reset alle gegevens
            this.doubleClickValueButton.addEventListener('click', () => this.doubleClickValue()); // Dubbel klikwaarde knop
            this.scoreMultiplierButton.addEventListener('click', () => this.scoreMultiplier()); // Score vermenigvuldiger knop
            this.extraBonusButton.addEventListener('click', () => this.extraBonus()); // Extra bonus knop
            this.speedBoostButton.addEventListener('click', () => this.speedBoost()); // Snelheidsboost knop
        }

        incrementScore() {
            // Score verhogen bij elke klik op de 'cookie'
            this.score += this.clickValue;
            this.updateScore();
        }

        upgrade() {
            // Klikwaarde verhogen met upgrade multiplier en kosten verhogen voor volgende upgrade
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
            // Klikwaarde verdubbelen
            if (this.score >= this.doubleClickValueCost) {
                this.score -= this.doubleClickValueCost;
                this.clickValue *= 2;
                this.updateScore();
            }
        }

        // Andere functies zoals scoreMultiplier(), extraBonus(), speedBoost() volgen hetzelfde principe
        // Auto Clicker: koopt en start automatische klikfunctie indien geactiveerd.
        // Save State: slaat huidige spelstaat op in `localStorage`.

        reset() {
            // Reset spel naar standaardwaarden
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
            new StrawberryClicker();
        });
    </script>
</body>
</html>
