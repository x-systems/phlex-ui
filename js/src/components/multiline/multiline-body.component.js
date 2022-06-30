import multilineRow from './multiline-row.component';

export default {
    name: 'phlex-multiline-body',
    template: `
    <sui-table-body>
      <phlex-multiline-row v-for="(row , idx) in rows" :key="row.__phlex_multiline" 
      @onTabLastColumn="onTabLastColumn(idx)"
      :fields="fields" 
      :rowId="row.__phlex_multiline" 
      :isDeletable="isDeletableRow(row)" 
      :rowValues="row"
      :error="getError(row.__phlex_multiline)"></phlex-multiline-row>
    </sui-table-body>
  `,
    props: ['fieldDefs', 'rowData', 'deletables', 'errors'],
    data: function () {
        return { fields: this.fieldDefs };
    },
    created: function () {
    },
    components: {
        'phlex-multiline-row': multilineRow,
    },
    computed: {
        rows: function () {
            return this.rowData;
        },
    },
    methods: {
        onTabLastColumn: function (idx) {
            if (idx + 1 === this.rowData.length) {
                this.$emit('onTabLastRow');
            }
        },
        isDeletableRow: function (row) {
            return this.deletables.indexOf(row.__phlex_multiline) > -1;
        },
        getError: function (rowId) {
            if (rowId in this.errors) {
                return this.errors[rowId];
            }
            return null;
        },
    },
};
