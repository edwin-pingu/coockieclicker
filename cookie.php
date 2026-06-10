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

        this.displayedScore = this.score;
        this.scoreAnimationFrame = null;

        this.updateUI();
        this.addEventListeners();

        if (this.autoClickerActive) {
            this.startAutoClicker();
        }
    }

    beautifyCookies(value) {
        value = Number(value) || 0;

        if (!isFinite(value)) {
            return 'Infinity';
        }

        const units = [
            '',
            ' thousand',
            ' million',
            ' billion',
            ' trillion',
            ' quadrillion',
            ' quintillion',
            ' sextillion',
            ' septillion',
            ' octillion',
            ' nonillion'
        ];

        const negative = value < 0;
        let absValue = Math.abs(value);

        if (absValue < 1000) {
            return (negative ? '-' : '') + absValue.toLocaleString('en-US');
        }

        let unitIndex = 0;
        while (absValue >= 1000 && unitIndex < units.length - 1) {
            absValue /= 1000;
            unitIndex++;
        }

        const decimals = absValue < 10 ? 3 : absValue < 100 ? 3 : absValue < 1000 ? 3 : 0;
        const formatted = absValue.toFixed(decimals).replace(/\.?(?:0+)$/, '');
        return (negative ? '-' : '') + formatted + units[unitIndex];
    }

    updateUI() {
        this.scoreElement.textContent = 'Score: ' + this.beautifyCookies(this.displayedScore);
        this.upgradeButton.textContent = 'Upgrade (' + this.beautifyCookies(this.upgradeCost) + ')';
        this.upgradeCountElement.textContent = this.upgradeCount;
        this.autoClickerCountElement.textContent = this.autoClickerCount;
        if (this.autoClickerActive) {
            this.autoClickerButton.textContent = 'Auto Clicker Active';
            this.autoClickerButton.disabled = true;
            this.disableAutoClickerButton.style.display = 'inline';
        } else {
            this.autoClickerButton.textContent = 'Auto Clicker (' + this.beautifyCookies(this.autoClickerCost) + ')';
            this.autoClickerButton.disabled = false;
            this.disableAutoClickerButton.style.display = 'none';
        }
        this.doubleClickValueButton.textContent = 'Double Click Value (' + this.beautifyCookies(this.doubleClickValueCost) + ')';
        this.scoreMultiplierButton.textContent = 'Score Multiplier x3 (' + this.beautifyCookies(this.scoreMultiplierCost) + ')';
        this.extraBonusButton.textContent = 'Extra Bonus (' + this.beautifyCookies(this.extraBonusCost) + ')';
        this.speedBoostButton.textContent = 'Speed Boost (' + this.beautifyCookies(this.speedBoostCost) + ')';
    }

    animateScore() {
        if (this.scoreAnimationFrame) {
            cancelAnimationFrame(this.scoreAnimationFrame);
        }

        const startScore = this.displayedScore;
        const targetScore = this.score;
        const duration = 250;
        const startTime = performance.now();

        const step = (timestamp) => {
            const elapsed = timestamp - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 2);
            this.displayedScore = startScore + (targetScore - startScore) * eased;
            this.scoreElement.textContent = 'Score: ' + this.beautifyCookies(Math.round(this.displayedScore));

            if (progress < 1) {
                this.scoreAnimationFrame = requestAnimationFrame(step);
            } else {
                this.displayedScore = targetScore;
                this.scoreElement.textContent = 'Score: ' + this.beautifyCookies(this.score);
                this.scoreAnimationFrame = null;
            }
        };

        this.scoreAnimationFrame = requestAnimationFrame(step);
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
            this.clickValue = Math.ceil(this.clickValue * this.clickMultiplier);
            this.upgradeCost = Math.ceil(this.upgradeCost * this.upgradeMultiplier);
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
            this.score += 500;
            this.updateScore();
        }
    }

    speedBoost() {
        if (this.score >= this.speedBoostCost && !this.isSpeedBoostActive) {
            this.score -= this.speedBoostCost;
            this.isSpeedBoostActive = true;
            this.clickValue += 5;
            this.updateScore();
            setTimeout(() => {
                this.clickValue -= 5;
                this.isSpeedBoostActive = false;
                this.updateUI();
            }, 10000);
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

        if (this.autoClickerInterval) {
            clearInterval(this.autoClickerInterval);
        }

        this.autoClickerInterval = setInterval(() => {
            this.score += this.clickValue * upgradeMultiplier;
            this.updateScore();
        }, 1000 / upgradeMultiplier);
    }

    reset() {
        this.score = 0;
        this.displayedScore = 0;
        this.clickValue = 1;
        this.upgradeCost = 10;
        this.upgradeCount = 0;
        this.autoClickerCount = 0;
        this.autoClickerActive = false;
        clearInterval(this.autoClickerInterval);
        if (this.scoreAnimationFrame) {
            cancelAnimationFrame(this.scoreAnimationFrame);
            this.scoreAnimationFrame = null;
        }
        this.updateUI();
        this.saveState();
    }

    updateScore() {
        this.saveState();
        this.animateScore();
    }

    updateUpgrade() {
        this.upgradeButton.textContent = 'Upgrade (' + this.beautifyCookies(this.upgradeCost) + ')';
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
