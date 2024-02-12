<template>
    <div v-if="message" class="left message">
        <img :src="avatar" alt="LEFT">
        <code>{{ code }}</code>
        <p>{{ message }}</p>
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            default: '',
        },
        code: {
            type: Number,
            default: 0,
        },
        avatar: {
            type: String,
            default: '',
        }
    }
}
</script>