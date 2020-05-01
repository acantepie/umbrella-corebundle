import Component from "umbrella_core/core/Component";

export default class TagsInput extends Component {
    constructor($view) {
        super($view);

        if ($.tagsinput) {
            console.error("Can't use TagsInput, tagsinput plugin not loaded");
            return;
        }
        this.$view.tagsinput();
    }
}
