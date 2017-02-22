<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default" v-show="!shop">
                    <div class="panel-heading">Shop Auswahl</div>

                    <div class="panel-body">
                        <select class="form-control" v-model="shop">
                            <option v-for="s in shops" :value="s.value">
                                {{ s.value }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="panel panel-default" v-show="shop">
                    <div class="panel-heading">
                        {{ shop }}
                        <button v-show="progress == 100" class="btn btn-xs btn-primary pull-right" @click="refresh">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </button>
                    </div>

                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :aria-valuenow="round(progress)" aria-valuemin="0" aria-valuemax="100" :style="style">
                                {{ round(progress) }}%
                            </div>
                        </div>

                        <table class="table table-striped" v-for="results in test_results">
                            <tbody>
                                <tr v-for="result in results">
                                    <td width="50%">
                                        <div class="test-name">
                                            <strong>{{ result.name }}</strong><br>
                                            <p class="hidden-xs expandable" v-html="result.description"></p>
                                        </div>

                                        <div class="bs-callout bs-callout-danger" v-if="result.has_error && result.error.message && result.error.message.length > 0">
                                            <h4>Fehler</h4>
                                            <p>{{ result.error.message }}</p>
                                        </div>

                                        <div class="bs-callout bs-callout-info" v-if="result.has_error && result.error.solution && result.error.solution.length > 0">
                                            <h4>Lösung</h4>
                                            <p>{{ result.error.solution }}</p>
                                        </div>
                                    </td>

                                    <td width="25%">
                                        <span v-if="result.data && result.data.expected">{{ result.data.expected }}</span>
                                    </td>

                                    <td width="25%">
                                        <!-- No Error -->
                                        <button type="button" class="btn btn-test-result btn-success btn-xs" v-if="!result.has_error">
                                            <i class="glyphicon glyphicon-ok"></i>
                                        </button>

                                        <!-- Warning -->
                                        <button type="button" class="btn btn-test-result btn-warning btn-xs" v-else-if="result.has_error && result.error.level == 2">
                                            <i class="glyphicon glyphicon-warning-sign"></i>
                                        </button>

                                        <!-- Error -->
                                        <button type="button" class="btn btn-test-result btn-danger btn-xs" v-else>
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </button>

                                        <span v-if="result.data && result.data.actual">{{ result.data.actual }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                shop: '',
                shops: [],
                test_count: 0,
                test_sorts: [],
                test_results: [],
                progress: 0.0,
                progress_step: 100
            }
        },

        computed: {
            style: function() {
                return {
                    width: Math.round(this.progress) + '%'
                }
            }
        },

        watch: {
            shop: function(val) {
                this.getSorts();
            }
        },

        methods: {
            round: function(value) {
                return Math.round(value);
            },

            getShops: function() {
                axios.get('api.php', {
                    params: {
                        a: 'get_shops'
                    }
                })
                    .then(response => {
                        this.shops = response.data.data;
                    });
            },

            getSorts: function() {
                axios.get('api.php', {
                    params: {
                        a: 'get_sorts',
                        s: this.shop
                    }
                })
                    .then(response => {
                        response.data.data.forEach((elem, index, array) => {
                            this.test_sorts.push(elem.value);
                        });

                        this.test_count = response.data.data.length;
                        this.progress_step = this.progress_step / this.test_count;

                        if (this.test_count > 0) {
                            var number = this.test_sorts.shift();
                            this.runTest(number);
                        }
                    });
            },

            runTest: function(number) {
                axios.get('api.php', {
                    params: {
                        a: 'run_test',
                        s: this.shop,
                        t: number
                    }
                })
                    .then(response => {
                        this.test_results.push(response.data.results);
                        this.progress += this.progress_step;

                        var number = this.test_sorts.shift();
                        if (typeof(number) !== 'undefined') {
                            this.runTest(number);
                        }
                    });
            },

            refresh () {
                this.test_count = 0;
                this.test_sorts = [];
                this.test_results = [];
                this.progress = 0.0;
                this.progress_step = 100;

                this.getSorts();
            }
        },

        mounted() {
            this.getShops();
        }
    }
</script>