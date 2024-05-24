<script setup>
    import { useRoute, useRouter } from 'vue-router'
    const router = useRouter()
    const route = useRoute()

    const user = defineModel('user')
    // const [user, userModifier] = defineModel('user')
    const pass = defineModel('pass')
    const email = defineModel('email')
    function createUser() {
        alert(user.value);
    }
    function goToLogin() {
        router.go(-1);
    }
</script>
<template>
        <p>Register</p>
        <p>User: <input type="text" required v-model="user"></p>
        <p>Password: <input type="password" v-model="pass" required></p>
        <p>Email: <input type="email" v-model="pass" required></p>
        <button @click="createUser" type="submit">Register</button>
        <br/>
        <a @click="goToLogin" href="#" type="submit">Sign in</a>
</template>
