class CookieClicker {
    constructor() {
        this.score = parseInt(localStorage.getItem('score')) || 0;
        this.clickValue = parseInt(localStorage.getItem('clickValue')) || 1;
        this.upgradeCost = parseInt(localStorage.getItem('upgradeCost')) || 10;
        this.upgradeMultiplier = 1.2;
        this.clickMultiplier = 1.2;
        this.upgradeCount = parseInt(localStorage.getItem('upgradeCount')) || 0;
        this.cps = parseFloat(localStorage.getItem('cps')) || 0;

        this.cookie = document.getElementById('cookie');
        this.scoreElement = document.getElementById('score');
        this.cpsElement = document.getElementById('cps');
        this.playArea = document.getElementById('playArea');
        this.upgradeButton = document.getElementById('upgradeButton');
        this.doubleClickValueButton = document.getElementById('doubleClickValueButton');
        this.scoreMultiplierButton = document.getElementById('scoreMultiplierButton');
        this.megaKlikButton = document.getElementById('megaKlikButton');
        this.comboClickerButton = document.getElementById('comboClickerButton');
        this.doubleClickValueCost = 150;
        this.scoreMultiplierCost = 500;
        this.megaKlikCost = 300;
        this.comboClickerCost = 250;
        this.upgradePurchased = localStorage.getItem('upgradePurchased') === 'true';
        this.doubleClickValuePurchased = localStorage.getItem('doubleClickValuePurchased') === 'true';
        this.scoreMultiplierPurchased = localStorage.getItem('scoreMultiplierPurchased') === 'true';
        this.megaKlikPurchased = localStorage.getItem('megaKlikPurchased') === 'true';
        this.comboClickerPurchased = localStorage.getItem('comboClickerPurchased') === 'true' || localStorage.getItem('speedBoostPurchased') === 'true';
        this.clickCount = parseInt(localStorage.getItem('clickCount')) || 0;
        this.lastClickTime = 0;
        this.comboChain = 0;
        this.resetButton = document.getElementById('resetButton');
        this.upgradeCountElement = document.getElementById('upgradeCount');

        this.buildings = [
            { name: 'Chocolate chip cookie', baseCost: 100, cost: 0, cps: 0.1, count: parseInt(localStorage.getItem('building_0')) || 0 },
            { name: 'Kaneelkoekje', baseCost: 500, cost: 0, cps: 0.5, count: parseInt(localStorage.getItem('building_1')) || 0 },
            { name: 'Boterkoekje', baseCost: 1000, cost: 0, cps: 1, count: parseInt(localStorage.getItem('building_2')) || 0 },
            { name: 'Bastognekoekie', baseCost: 1500, cost: 0, cps: 1.5, count: parseInt(localStorage.getItem('building_3')) || 0 },
            { name: 'Chocoladekoekje', baseCost: 2000, cost: 0, cps: 2, count: parseInt(localStorage.getItem('building_4')) || 0 },
            { name: 'Gevulde koekie', baseCost: 2500, cost: 0, cps: 2.5, count: parseInt(localStorage.getItem('building_5')) || 0 }
        ];

        this.buildings.forEach(function(building) {
            building.cost = Math.ceil(building.baseCost * Math.pow(1.15, building.count));
        });

        this.buildingButtons = [
            document.getElementById('buildingButton0'),
            document.getElementById('buildingButton1'),
            document.getElementById('buildingButton2'),
            document.getElementById('buildingButton3'),
            document.getElementById('buildingButton4'),
            document.getElementById('buildingButton5')
        ];
        this.buildingCountElements = [
            document.getElementById('buildingCount0'),
            document.getElementById('buildingCount1'),
            document.getElementById('buildingCount2'),
            document.getElementById('buildingCount3'),
            document.getElementById('buildingCount4'),
            document.getElementById('buildingCount5')
        ];

        this.productionInterval = null;
        this.displayedScore = this.score;
        this.scoreAnimationFrame = null;

        this.calculateCPS();
        this.updateUI();
        this.addEventListeners();
        this.startProduction();
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
            const smallValue = absValue % 1 === 0 ? absValue.toLocaleString('en-US') : absValue.toFixed(2).replace(/\.?(?:0+)$/, '');
            return (negative ? '-' : '') + smallValue;
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
        if (this.scoreElement) {
            this.scoreElement.textContent = 'Score: ' + this.beautifyCookies(this.displayedScore);
        }
        if (this.cpsElement) {
            this.cpsElement.textContent = 'CPS: ' + this.beautifyCookies(this.cps);
        }
        if (this.upgradeButton) {
            if (this.upgradePurchased) {
                this.upgradeButton.style.display = 'none';
            } else {
                this.upgradeButton.style.display = '';
                this.upgradeButton.textContent = 'Upgrade (' + this.beautifyCookies(this.upgradeCost) + ')';
            }
        }

        if (this.doubleClickValueButton) {
            if (this.doubleClickValuePurchased) {
                this.doubleClickValueButton.style.display = 'none';
            } else {
                this.doubleClickValueButton.style.display = '';
                this.doubleClickValueButton.textContent = 'Double Click Value (' + this.beautifyCookies(this.doubleClickValueCost) + ')';
            }
        }

        if (this.scoreMultiplierButton) {
            if (this.scoreMultiplierPurchased) {
                this.scoreMultiplierButton.style.display = 'none';
            } else {
                this.scoreMultiplierButton.style.display = '';
                this.scoreMultiplierButton.textContent = 'Score Multiplier x3 (' + this.beautifyCookies(this.scoreMultiplierCost) + ')';
            }
        }

        if (this.megaKlikButton) {
            if (this.megaKlikPurchased) {
                this.megaKlikButton.style.display = 'none';
            } else {
                this.megaKlikButton.style.display = '';
                this.megaKlikButton.textContent = 'Mega Klik (' + this.beautifyCookies(this.megaKlikCost) + ')';
            }
        }

        if (this.comboClickerButton) {
            if (this.comboClickerPurchased) {
                this.comboClickerButton.style.display = 'none';
            } else {
                this.comboClickerButton.style.display = '';
                this.comboClickerButton.textContent = 'Combo Clicker (' + this.beautifyCookies(this.comboClickerCost) + ')';
            }
        }

        this.upgradeCountElement.textContent = this.upgradeCount;
        this.updateBuildings();
    }

    calculateCPS() {
        this.cps = this.buildings.reduce(function(sum, building) {
            return sum + building.cps * building.count;
        }, 0);
    }

    startProduction() {
        if (this.productionInterval) {
            clearInterval(this.productionInterval);
        }

        this.productionInterval = setInterval(function() {
            if (this.cps > 0) {
                this.score += this.cps;
                this.updateScore();
            }
        }.bind(this), 1000);
    }

    buyBuilding(index) {
        var building = this.buildings[index];
        if (this.score >= building.cost) {
            this.score -= building.cost;
            building.count++;
            building.cost = Math.ceil(building.baseCost * Math.pow(1.15, building.count));
            this.calculateCPS();
            if (!this.productionInterval) {
                this.startProduction();
            }
            this.updateScore();
            this.updateBuildings();
            this.saveState();
        }
    }

    updateBuildings() {
        for (var i = 0; i < this.buildings.length; i++) {
            var building = this.buildings[i];
            var button = this.buildingButtons[i];
            var countEl = this.buildingCountElements[i];
            if (button) {
                button.textContent = building.name + ' (' + this.beautifyCookies(building.cost) + ')';
                button.disabled = this.score < building.cost;
            }
            if (countEl) {
                countEl.textContent = building.count;
            }
        }
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
        this.resetButton.addEventListener('click', () => this.reset());
        this.doubleClickValueButton.addEventListener('click', () => this.doubleClickValue());
        this.scoreMultiplierButton.addEventListener('click', () => this.scoreMultiplier());
        this.megaKlikButton.addEventListener('click', () => this.megaKlik());
        this.comboClickerButton.addEventListener('click', () => this.comboClicker());
        for (var i = 0; i < this.buildingButtons.length; i++) {
            if (this.buildingButtons[i]) {
                this.buildingButtons[i].addEventListener('click', this.buyBuilding.bind(this, i));
            }
        }
    }

    incrementScore() {
        var baseAmount = this.clickValue;
        this.clickCount++;
        var bonus = 0;

        if (this.megaKlikPurchased && this.clickCount % 10 === 0) {
            bonus += this.clickValue * 10;
        }

        var now = Date.now();
        if (this.comboClickerPurchased && now - this.lastClickTime <= 700) {
            this.comboChain++;
        } else {
            this.comboChain = 1;
        }

        this.lastClickTime = now;

        if (this.comboClickerPurchased && this.comboChain >= 4) {
            // Gradual bonus: each extra click beyond 3 adds 25% of clickValue,
            // capped at +100% (after 4 extra clicks). This yields a smooth
            // increase instead of a large jump.
            var extraSteps = Math.min(this.comboChain - 3, 4);
            bonus += Math.ceil(this.clickValue * 0.25 * extraSteps);
        }

        var totalAmount = baseAmount + bonus;
        this.score += totalAmount;
        this.showClickPopup(totalAmount);
        this.updateScore();
    }

    showClickPopup(amount) {
        if (!this.playArea) return;
        var popup = document.createElement('div');
        popup.className = 'click-popup';
        popup.textContent = '+' + this.beautifyCookies(amount);
        this.playArea.appendChild(popup);
        requestAnimationFrame(function() {
            popup.classList.add('visible');
        });
        setTimeout(function() {
            popup.classList.remove('visible');
            setTimeout(function() {
                if (popup.parentNode) popup.parentNode.removeChild(popup);
            }, 300);
        }, 400);
    }

    upgrade() {
        if (this.upgradePurchased || this.score < this.upgradeCost) {
            return;
        }

        this.score -= this.upgradeCost;
        this.clickValue = Math.ceil(this.clickValue * this.clickMultiplier);
        this.upgradePurchased = true;
        this.upgradeCount++;
        this.updateScore();
        this.saveState();
    }

    doubleClickValue() {
        if (this.doubleClickValuePurchased || this.score < this.doubleClickValueCost) {
            return;
        }

        this.score -= this.doubleClickValueCost;
        this.clickValue *= 2;
        this.doubleClickValuePurchased = true;
        this.updateScore();
        this.saveState();
    }

    scoreMultiplier() {
        if (this.scoreMultiplierPurchased || this.score < this.scoreMultiplierCost) {
            return;
        }

        this.score -= this.scoreMultiplierCost;
        this.clickValue *= 3;
        this.scoreMultiplierPurchased = true;
        this.updateScore();
        this.saveState();
    }

    megaKlik() {
        if (this.megaKlikPurchased || this.score < this.megaKlikCost) {
            return;
        }

        this.score -= this.megaKlikCost;
        this.megaKlikPurchased = true;
        this.updateScore();
        this.saveState();
    }

    comboClicker() {
        if (this.comboClickerPurchased || this.score < this.comboClickerCost) {
            return;
        }

        this.score -= this.comboClickerCost;
        this.comboClickerPurchased = true;
        this.updateScore();
        this.saveState();
    }

    reset() {
        this.score = 0;
        this.displayedScore = 0;
        this.clickValue = 1;
        this.upgradeCost = 10;
        this.upgradeCount = 0;
        this.upgradePurchased = false;
        this.doubleClickValuePurchased = false;
        this.scoreMultiplierPurchased = false;
        this.megaKlikPurchased = false;
        this.comboClickerPurchased = false;
        this.clickCount = 0;
        this.lastClickTime = 0;
        this.comboChain = 0;
        this.buildings.forEach(function(building) {
            building.count = 0;
            building.cost = building.baseCost;
        });
        this.calculateCPS();
        if (this.scoreAnimationFrame) {
            cancelAnimationFrame(this.scoreAnimationFrame);
            this.scoreAnimationFrame = null;
        }
        this.updateUI();
        this.saveState();
    }

    updateScore() {
        this.saveState();
        this.updateUI();
        this.animateScore();
    }

    updateUpgrade() {
        if (this.upgradeButton) {
            this.upgradeButton.textContent = 'Upgrade (' + this.beautifyCookies(this.upgradeCost) + ')';
        }
        this.upgradeCountElement.textContent = this.upgradeCount;
        localStorage.setItem('clickValue', this.clickValue);
        localStorage.setItem('upgradeCost', this.upgradeCost);
        localStorage.setItem('upgradeCount', this.upgradeCount);
        localStorage.setItem('upgradePurchased', this.upgradePurchased ? 'true' : 'false');
    }

    saveState() {
        localStorage.setItem('score', this.score);
        localStorage.setItem('clickValue', this.clickValue);
        localStorage.setItem('upgradeCost', this.upgradeCost);
        localStorage.setItem('upgradeCount', this.upgradeCount);
        localStorage.setItem('upgradePurchased', this.upgradePurchased ? 'true' : 'false');
        localStorage.setItem('doubleClickValuePurchased', this.doubleClickValuePurchased ? 'true' : 'false');
        localStorage.setItem('scoreMultiplierPurchased', this.scoreMultiplierPurchased ? 'true' : 'false');
        localStorage.setItem('megaKlikPurchased', this.megaKlikPurchased ? 'true' : 'false');
        localStorage.setItem('comboClickerPurchased', this.comboClickerPurchased ? 'true' : 'false');
        localStorage.setItem('clickCount', this.clickCount);
        localStorage.setItem('cps', this.cps);
        for (var i = 0; i < this.buildings.length; i++) {
            localStorage.setItem('building_' + i, this.buildings[i].count);
        }
    }
}
