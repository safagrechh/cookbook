import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['collectionContainer'];
    static values = {
        index: Number,
        prototype: String,
    };

    add() {
        const html = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.insertAdjacentHTML('beforeend', html);
        const newItem = this.collectionContainerTarget.lastElementChild;

        if (newItem) {
            newItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            newItem.querySelector('input, select, textarea')?.focus();
        }

        this.indexValue++;
    }

    remove(event) {
        event.target.closest('[data-form-collection-item]').remove();
    }
}
