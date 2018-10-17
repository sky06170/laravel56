<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        幣別:
                        <select name="category">
                            <option v-for="name in categories" v-bind:value="name">
                                {{ name }}
                            </option>
                        </select>
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
                categories: [],
            };
        },
        beforeCreate() {
            console.log('=== beforeCreate start ===');
        },
        created() {
            console.log('=== created strat ===');
        },
        beforeMount() {
            console.log('=== beforeMount start ===');
        },
        mounted() {
            console.log('=== mounted start ===');
            this.initPage();
        },
        methods: {
            initPage () {
                axios({
                    method: 'post',
                    url: '/api/currency/searchBarInfo'
                })
                .then((response) => {
                    console.log('axios then response');
                    console.log(response.data);
                    this.categories = response.data.categories;
                })
                .catch((error) => {
                    console.log('axios error response');
                    console.log(error);
                });
            }
        }
    }
</script>
