<template>
    <div v-if="message" class="right message">
        <p>{{ message }}</p>
        <code>{{ code }}</code>
        <img :src="avatar" alt="RIGHT">
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