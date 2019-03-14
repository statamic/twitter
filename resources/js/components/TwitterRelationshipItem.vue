<template>

    <div class="item mb-1 ">
        <div class="item-move">&nbsp;</div>
        <div class="item-inner">
            <div class="p-3">
                <p class="text-lg mb-2">{{ tweet.text }}</p>
                <p class="text-grey">{{ tweet.user }} – {{ tweet.date_relative }}</p>
            </div>
        </div>
        <div class="pr-1 flex items-center">
            <dropdown-list>
                <ul class="dropdown-menu">
                    <li class="warning"><a @click.prevent="$emit('removed')" v-text="__('Unlink')"></a></li>
                </ul>
            </dropdown-list>
        </div>
    </div>

</template>

<script>
export default {

    props: {
        item: Object
    },

    computed: {

        tweet() {
            return this.item;
        }

    }

}
</script>
