<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default" v-show="test_count == 0">
                    <div class="panel-heading">Shop Auswahl</div>

                    <div class="panel-body">
                        <select v-model="shop">
                            <option v-for="s in shops" :value="s.value">
                                {{ s.value }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="panel panel-default" v-show="test_count > 0">
                    <div class="panel-heading">{{ shop }}</div>

                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :aria-valuenow="round(progress)" aria-valuemin="0" aria-valuemax="100" :style="style">
                                {{ round(progress) }}%
                            </div>
                        </div>

                        <div v-for="results in test_results">
                            <table class="table table-striped" v-for="result in results">
                                <tbody>
                                    <tr v-for="data in result.data">
                                        <td>
                                            {{ data.message }}
                                        </td>
                                        <td>
                                            <span class="label label-success pull-right">
                                                <i class="glyphicon glyphicon-ok-sign"></i>
                                                <span>Yolo</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-for="error in result.errors">
                                        <td>
                                            {{ error.message }}
                                        </td>
                                        <td>
                                            <span class="label label-danger pull-right">
                                                <i class="glyphicon glyphicon-remove-sign"></i>
                                                <span>Yolo</span>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
            }
        },

        mounted() {
            this.getShops();
        }
    }
</script>