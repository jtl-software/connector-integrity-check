<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" :style="style">
                                {{ progress }}%
                            </div>
                        </div>

                        <button class="btn btn-default" @click="run">Go</button>
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

        methods: {
            run: function() {
                this.getData(1);
            },

            getData: function(t) {
                axios.get('api.php', {
                    params: {
                        t: t
                    }
                })
                    .then(response => {
                        this.progress = response.data.data.progress;
                        if (!response.data.data.finished) {
                            this.getData(++t);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        mounted() {

        }
    }
</script>