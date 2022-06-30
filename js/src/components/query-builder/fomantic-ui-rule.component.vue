<template>
    <!-- eslint-disable vue/no-v-html -->
    <div class="vqb-rule ui fluid card" :class="labels.spaceRule" :data-name="rule.id">
        <div class="content">
            <div class="ui grid">
                <div class="middle aligned row phlex-qb">
                    <div class="thirteen wide column">
                        <div class="ui horizontal list">
                            <div class="item vqb-rule-label">
                                <h5 class>{{ rule.label }}</h5>
                            </div>
                            <div class="item vqb-rule-operand" v-if="typeof rule.operands !== 'undefined'">
                                <!-- List of operands (optional) -->
                                <select v-model="query.operand" class="phlex-qb-select">
                                    <option v-for="operand in rule.operands" :key="operand">{{ operand }}</option>
                                </select>
                            </div>
                            <div class="item vqb-rule-operator"
                                 v-if="typeof rule.operators !== 'undefined'
                                 && rule.operators.length > 1">
                                <!-- List of operators (e.g. =, !=, >, <) -->
                                <select v-model="query.operator" class="phlex-qb-select">
                                    <option v-for="operator in rule.operators" :key="operator" :value="operator">
                                        {{operator}}
                                    </option>
                                </select>
                            </div>
                            <div class="item vqb-rule-input">
                                <!-- text input -->
                                <template v-if="canDisplay('input')">
                                    <div class="ui small input phlex-qb" >
                                        <input
                                                v-model="query.value"
                                                :type="rule.inputType"
                                                :placeholder="labels.textInputPlaceholder"
                                        >
                                    </div>
                                </template>
                                <!-- Checkbox or Radio input -->
                                <template v-if="canDisplay('checkbox')">
                                    <sui-form-fields inline class="phlex-qb">
                                        <div class="field" v-for="choice in rule.choices" :key="choice.value">
                                            <sui-checkbox
                                                :label="choice.label"
                                                :radio="isRadio"
                                                :value="choice.value"
                                                v-model="query.value">
                                            </sui-checkbox>
                                        </div>
                                    </sui-form-fields>
                                </template>
                                <!-- Select input -->
                                <template v-if="canDisplay('select')">
                                    <select v-model="query.value" class="phlex-qb-select">
                                        <option
                                            v-for="choice in rule.choices"
                                            :key="choice.value"
                                            :value="choice.value">
                                            {{choice.label}}
                                        </option>
                                    </select>
                                </template>
                              <!-- Custom component -->
                              <template v-if="canDisplay('custom-component')">
                                <div class="ui small input phlex-qb">
                                  <component :is="rule.component"
                                      :config="rule.componentProps"
                                      :value="query.value"
                                      :optionalValue="query.option"
                                      @onChange="onChange"
                                      @setDefault="onChange">
                                  </component>
                                </div>
                              </template>
                            </div>
                        </div>
                    </div>
                    <div class="right aligned three wide column">
                        <!-- Remove rule button -->
                        <i :class="labels.removeRule" @click="remove" class="phlex-qb-remove"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import QueryBuilderRule from 'vue-query-builder/dist/rule/QueryBuilderRule.umd';
import phlexDatePicker from '../share/phlex-date-picker';
import phlexLookup from '../share/phlex-lookup';

export default {
    extends: QueryBuilderRule,
    components: { 'phlex-date-picker': phlexDatePicker, 'phlex-lookup': phlexLookup },
    data: function () {
        return {};
    },
    inject: ['getRootData'],
    computed: {
        isInput: function () {
            return this.rule.type === 'text' || this.rule.type === 'numeric';
        },
        isComponent: function () {
            return this.rule.type === 'custom-component';
        },
        isRadio: function () {
            return this.rule.type === 'radio';
        },
        isCheckbox: function () {
            return this.rule.type === 'checkbox' || this.isRadio;
        },
        isSelect: function () {
            return this.rule.type === 'select';
        },
    },
    methods: {
        /**
       * Check if an input can be display in regards to:
       * it's operator and then it's type.
       *
       * @param type
       * @returns {boolean|*}
       */
        canDisplay: function (type) {
            if (this.labels.hiddenOperator.includes(this.query.operator)) {
                return false;
            }

            switch (type) {
            case 'input': return this.isInput;
            case 'checkbox': return this.isCheckbox;
            case 'select': return this.isSelect;
            case 'custom-component': return this.isComponent;
            default: return false;
            }
        },
        onChange: function (value) {
            this.query.value = value;
        },
    },
};
</script>

<style>
    .ui.input.phlex-qb > input, .ui.input.phlex-qb span > input, .ui.form .input.phlex-qb {
        padding: 6px;
    }
    .ui.grid > .row.phlex-qb {
        padding: 8px 0px;
        min-height: 62px;
    }
    .inline.fields.phlex-qb, .ui.form .inline.fields.phlex-qb {
        margin: 0px;
    }
    .phlex-qb-date-picker {
        border: 1px solid rgba(34, 36, 38, 0.15);
    }
    input[type=input].phlex-qb-date-picker:focus {
        border-color: #85b7d9;
    }
    .ui.card.vqb-rule > .content {
        padding-bottom: 0.5em;
        padding-top: 0.5em;
        background-color: #f3f4f5;
    }
</style>
