<style>
    @import '../../css/app.css';
</style>
<script setup>
    const user = JSON.parse(localStorage.getItem("user")).name;
</script>
<template>
    <div class="content">
        <h1>Hello, {{user.name}}!</h1>
        <hr>
        <div class="jobs">
        </div>
        <div class="users">
        </div>
    </div>
</template>
