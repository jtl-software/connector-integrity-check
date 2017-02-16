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
                        <table class="table table-striped" v-for="results in tests">
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
                test_count: 0,
                shop: '',
                shops: [],
                tests: [],
                progress: 0.0
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
                this.getCount();
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

            getCount: function() {
                axios.get('api.php', {
                    params: {
                        a: 'get_count',
                        s: this.shop
                    }
                })
                    .then(response => {
                        this.test_count = response.data.data[0].value;

                        if (this.test_count > 0) {
                            for (var i = 1; i <= this.test_count; i++) {
                                this.runTest(i);
                            }
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
                        this.tests.push(response.data.results);
                    });
            }
        },

        mounted() {
            this.getShops();
        }
    }
</script>