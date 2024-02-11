<template>
    <div v-if="message" class="left message">
        <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436200.jpg?w=740&t=st=1707563581~exp=1707564181~hmac=3ef67fa5fc1d8d2b4aff9fa2fdc9f2dedaab7ddf873e3c4fb98a8276be64b91f"
            alt="LEFT">
        <code>{{  code }}</code>
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
        }
    }
}
</script>