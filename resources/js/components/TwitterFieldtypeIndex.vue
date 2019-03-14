<template>

    <div>

        <a
            class="cursor-pointer hover:text-blue"
            @click="previewing = true"
        >
            <template v-if="tweets.length === 1">
                {{ tweets[0].text.substring(0, 50) }}&hellip;
            </template>
            <template v-else>
                {{ tweets.length }} tweets
            </template>
        </a>

        <stack name="tweet-preview" v-if="previewing" @closed="previewing = false">
            <div class="h-full bg-white p-3 flex items-center">
                <div class="max-w-md mx-auto">
                    <div v-for="tweet in tweets" :key="tweet.id" class="my-8">
                        <div class="text-2xl mb-3">{{ tweet.text }}</div>
                        <div class="text-grey">
                            {{ tweet.user }} – {{ tweet.date_relative }}
                        </div>
                    </div>
                </div>
            </div>
        </stack>
    </div>

</template>

<script>
export default {
    mixins: [IndexFieldtype],
    data() {
        return {
            previewing: false
        }
    },
    computed: {
        tweets() {
            if (Array.isArray(this.value)) {
                return this.value;
            }

            return [this.values];
        }
    }
}
</script>
