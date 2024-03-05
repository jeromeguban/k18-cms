<template>
    <div>
        <!-- <keep-alive include="live-auction"> -->
            <router-view
                :user="user"
                :account_executive="account_executive"
                :developer="developer"
                :username="name"
                :url="url"/>
        <!-- </keep-alive> -->

    </div>
</template>
<script>
export default {

    props: {
        user: {
            type: String,
            default: null
        },
        name: {
            type: String,
            default: null
        },
        account_executive: {
            type: String,
            default: null
        },
        developer: {
            type: String,
            default: null
        },
        url: {
            type: String,
            default: null
        },

    },

}

</script>
