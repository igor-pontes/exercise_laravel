<script setup>
    import { useRoute, useRouter } from 'vue-router'
    const router = useRouter()
    const route = useRoute()
    import { ref } from 'vue';

    const status = ref("");

    const user = defineModel('user')
    // const [user, userModifier] = defineModel('user')
    const pass = defineModel('pass')
    const email = defineModel('email')

    function createUser() {
        axios.post(
            '/api/users',
            {
                name: user.value,
                password: pass.value,
                email: email.value,
            }
        ).then(function (tasks) {
            status.value = "User created successfully.";
        }).catch(function () {
            status.value = "An error occurred while creating user.";
        });
    }

    function goToLogin() {
        router.go(-1);
    }
</script>
<template>
        <br>
        {{ status }}
        <br>
        <p>Register</p>
        <p>User: <input type="text" required v-model="user"></p>
        <p>Password: <input type="password" v-model="pass" required></p>
        <p>Email: <input type="email" v-model="email" required></p>
        <button @click="createUser" type="submit">Register</button>
        <br/>
        <a @click="goToLogin" href="#" type="submit">Sign in</a>
</template>
