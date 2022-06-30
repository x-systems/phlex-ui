import multilineReadOnly from './multiline-readonly.component';
import multilineTextarea from './multiline-textarea.component';
import phlexDatePicker from '../share/phlex-date-picker';
import phlexLookup from '../share/phlex-lookup';

export default {
    name: 'phlex-multiline-cell',
    template: ` 
    <component :is="getComponent()"
        :fluid="true"  
        class="fluid" 
        @input="onInput"
        @onChange="onChange"
        v-model="inputValue"
        :name="inputName"
        ref="cell"
        v-bind="getComponentProps()"></component>
  `,
    components: {
        'phlex-multiline-readonly': multilineReadOnly,
        'phlex-multiline-textarea': multilineTextarea,
        'phlex-date-picker': phlexDatePicker,
        'phlex-lookup': phlexLookup,
    },
    props: ['cellData', 'fieldValue'],
    data: function () {
        return {
            fieldName: this.cellData.name,
            type: this.cellData.type,
            inputName: '-' + this.cellData.name,
            inputValue: this.fieldValue,
        };
    },
    methods: {
        getComponent: function () {
            return this.cellData.definition.component;
        },
        getComponentProps: function () {
            if (this.getComponent() === 'phlex-multiline-readonly') {
                return { readOnlyValue: this.fieldValue };
            }
            return this.cellData.definition.componentProps;
        },
        onInput: function (value) {
            this.inputValue = this.getTypeValue(value);
            this.$emit('update-value', this.fieldName, this.inputValue);
        },
        onChange: function (value) {
            this.onInput(value);
        },
        /**
         * return input value based on their type.
         */
        getTypeValue: function (value) {
            let r = value;
            if (this.type === 'boolean') {
                r = value.target.checked;
            }
            return r;
        },
    },
};
