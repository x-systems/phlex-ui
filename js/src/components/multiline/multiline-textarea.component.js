/**
 * Simple text area input to display in multiline component.
 */
export default {
    name: 'phlex-textarea',
    template: '<textarea v-model="text" @input="handleChange"></textarea>',
    props: { value: [String, Number] },
    data: function () {
        return { text: this.value };
    },
    methods: {
        handleChange: function (event) {
            this.$emit('input', event.target.value);
        },
    },
};
