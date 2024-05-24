<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios'
    import bcrypt from 'bcryptjs';

    const router = useRouter()
    const route = useRoute()

    const email = defineModel('email')
    // const [user, userModifier] = defineModel('user')
    const pass = defineModel('pass')

    function login() {
        axios.get('/api/users/email/' + email.value)
            .then(function (response) {
                var user_data = response.data.data;
                const hash = user_data.password.replace(/^\$2y(.+)$/i, '$2a$1');
                bcrypt.compare(pass.value, hash, function(err, res) {
                    if(res) {
                        user_data.timestamp = new Date().getTime();
                        localStorage.setItem("user", JSON.stringify(user_data));
                        router.push('/');
                    } else {
                        alert("Email or password incorrect.")
                    }
                });
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
        <p>Email: <input type="email" required v-model="email"></p>
        <p>Password: <input type="password" required v-model="pass"></p>
        <button @click="login" type="submit">Login</button>
        <br/>
        <a @click="goToRegister" href="#" type="submit">Sign up</a>
</template>
