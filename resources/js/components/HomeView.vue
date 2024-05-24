<style>
    @import '../../css/app.css';
</style>
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios';
    import { ref } from 'vue';

    const router = useRouter()
    const route = useRoute()
    const user = JSON.parse(localStorage.getItem("user"));
    const isLoading = ref(true);

    var user_tasks = ref([]);
    var task_status = ref({});

    function taskStatus(task) {
        return function() {
            axios.put(
                'api/tasks/'+task,
                {
                    status: task_status.value[task].status,
                }
            ).then(function (tasks) {
                console.log("Status updated.");
            }).catch(function () {
                console.log("ERROR updating task status.");
            });
        }
    }

    function getUserTasks() {
        axios.get('api/tasks/user/'+user.id).then(function (tasks) {
            const tasks_data = tasks.data.data;
            user_tasks.value = tasks_data;
            isLoading.value = false;
            tasks_data.map((task) => {
                task_status.value[task.id] = { status: task.status, update: taskStatus(task.id) };
            })
        })
    }

    function signOut() {
        localStorage.clear();
        router.back();
    }

    getUserTasks();
</script>
<template>
    <div class="content">
        <a @click="signOut" href="#signout">Sign out</a>
        <h1>Hello, {{user.name}}!</h1>
        <hr>
        <h2>My tasks</h2>
        <br>
        <div class="jobs">
            <div class="box-jobs" v-for="task in user_tasks" :key="task.id" :data-taskId="task.id">
                <h5>{{ task.title }}</h5>
                <hr>
                {{ task.task }} <br>
                <hr>
                Status:
                <select @change="task_status[task.id].update" v-model="task_status[task.id]">
                    <option :value="{ status: 0, update: taskStatus(task.id) }" :selected="task.status == 0">Idle</option>
                    <option :value="{ status: 1, update: taskStatus(task.id) }" :selected="task.status == 1">On track</option>
                    <option :value="{ status: 2, update: taskStatus(task.id) }" :selected="task.status == 2">Finished</option>
                    <option :value="{ status: 3, update: taskStatus(task.id) }" :selected="task.status == 3">Failed</option>
                </select>
                <br>
                Created at: <br>{{ task.created_at }} <br>
                Deadline: <br> {{ task.deadline }}
            </div>
        </div>
    </div>
</template>
