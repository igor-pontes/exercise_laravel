<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios'

    const router = useRouter()
    const route = useRoute()

    const user = defineModel('user')
    // const [user, userModifier] = defineModel('user')
    const pass = defineModel('pass')

    function login() {
        axios.get('api/users/email/' + user.value)
            .then(function (response) {
                const user_data = response.data.data;
                user_data.timestamp = new Date().getTime();
                localStorage.setItem("user", JSON.stringify(user_data));
                router.push('/');
            })
            .catch(function (error) {
                alert("User does not exist.")
            })
    }

    function goToRegister() {
        router.push('/register');
    }
</script>
<template>
        <p>Login</p>
        <p>User: <input type="email" required v-model="user"></p>
        <p>Password: <input type="password" required v-model="pass"></p>
        <button @click="login" type="submit">Login</button>
        <br/>
        <a @click="goToRegister" href="#" type="submit">Sign up</a>
</template>
