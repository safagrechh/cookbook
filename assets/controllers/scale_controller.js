import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['factor', 'amount'];

    connect() {
        this.update();
    }

    update() {
        const factor = parseFloat(this.factorTarget.value);
        const isValid = Number.isFinite(factor) && factor > 0;

        this.factorTarget.classList.toggle('is-invalid', !isValid);

        this.amountTargets.forEach((element) => {
            const baseAmount = Number(element.dataset.baseAmount);
            const amount = isValid ? baseAmount * factor : baseAmount;
            element.textContent = this.format(amount);
        });
    }

    format(number) {
        return new Intl.NumberFormat('en', { maximumFractionDigits: 2 }).format(number);
    }
}
