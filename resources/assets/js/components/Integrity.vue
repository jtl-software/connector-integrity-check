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
                            <div class="progress-bar" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" :style="style">
                                {{ progress }}%
                            </div>
                        </div>

                        <table class="table table-striped" v-for="results in test_results">
                            <tbody>
                                <tr v-for="result in results">
                                    <td v-for="data in result.data">
                                        {{ data.message }}
                                    </td>
                                    <td v-for="error in result.errors">
                                        {{ error.message }}
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
                    width: this.progress + '%'
                }
            }
        },

        watch: {
            shop: function(val) {
                this.getSorts();
            }
        },

        methods: {
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
                            this.test_sorts.forEach((elem, index, array) => {
                                this.runTest(elem);
                            });
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
                    });
            }
        },

        mounted() {
            this.getShops();
        }
    }
</script>