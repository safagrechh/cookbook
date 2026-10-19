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
        this.indexValue++;
    }

    remove(event) {
        event.target.closest('[data-form-collection-item]').remove();
    }
}
