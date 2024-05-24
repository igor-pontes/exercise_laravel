<style>
    @import '../../css/app.css';
</style>
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios';
    import { ref } from 'vue';

    const router = useRouter()
    const route = useRoute()

    const task = ref({});
    const status = ref("");

    function createTask() {
        axios.post(
            '/api/tasks',
            {
                title: task.value.title,
                task: task.value.description,
                deadline: task.value.deadline,
            }
        ).then(function (tasks) {
            status.value = "Task created successfully.";
        }).catch(function () {
            status.value = "An error occurred while creating task.";
        });
    }

    function log() {
        console.log(task.value);
    }

</script>
<template>
    <h1>New task</h1>
    <p>Title: <br>
    <input placeholder="Nova Tarefa" type="text" required v-model="task.title"></p>
    <p>Task description: <br>
    <textarea required v-model="task.description" rows="4" cols="50"/></p>
    <p>Task deadline: <br>
    <input type="datetime-local" required v-model="task.deadline"></p>
    <button @click="createTask" type="submit">Create Task</button>
    <br/>
    {{ status }}
    <br/>
</template>

