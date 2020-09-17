<template>
    <div class="container py-2">
        <div class="row justify-content-center mb-3">
            <div class="col-md-6">
                <p class="h2">Failed messages in the last 24 hours</p>

                <div class="card mb-2" v-for="textMessage in todaysFailedMessages">
                    <div class="card-body">
                        <p class="card-text">{{ textMessage.body }}</p>
                        <div class="d-flex justify-content-between">
                            <span class="btn btn-danger btn-sm">{{ textMessage.status }}</span>
                            <time>{{ textMessage.updated_at | moment("DD.MM.YYYY h:mm") }}</time>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <p class="h2">Last 50 messages</p>

                <div class="card mb-2" v-for="textMessage in textMessages">
                    <div class="card-body">
                        <p class="card-text">{{ textMessage.body }}</p>
                        <div class="d-flex justify-content-between">
                            <span class="btn btn-warning btn-sm">{{ textMessage.status }}</span>
                            <time>{{ textMessage.updated_at | moment("DD.MM.YYYY h:mm") }}</time>
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
                textMessages: null,
                todaysFailedMessages: null
            };
        },
        mounted() {
            axios
                .get('/api/text-messages/latest?limit=50')
                .then(response => (this.textMessages = response.data));
            axios
                .get('/api/text-messages/failed')
                .then(response => (this.todaysFailedMessages = response.data));
        }
    }
</script>
