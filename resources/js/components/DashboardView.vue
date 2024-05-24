<style>
    @import '../../css/app.css';
</style>
<script setup>
    import axios from 'axios';
    import { ref } from 'vue';

    const user = JSON.parse(localStorage.getItem("user"));
    var users = ref([]);
    var users_tasks = ref([]);
    var unassigned_tasks = ref([]);
    const isLoading = ref(true);


    function dragStart(e) {
        // console.log(e.target.dataset.taskid);
    }

    function dragEnd(e) {
        let targetEl;
        const task_id = e.target.dataset.taskid;
        targetEl = document.elementFromPoint(event.clientX, event.clientY);
        const isDropZone = returnRealDropZone(targetEl, 2);
        if(!isDropZone) {
            return
        }
        targetEl = returnRealDropZone(targetEl, 2);
        const user_id = targetEl.dataset.userid;
        // console.log(task_id);
        addTaskToUser(task_id, user_id);
    }

    function returnRealDropZone(element, parentsCount) {
        if (!element) return false;
        if (element.classList.contains("drop-zone")) {
          return element;
        } else if (parentsCount > 0) {
          return returnRealDropZone(
            element.parentElement,
            parentsCount - 1
          );
        } else {
          return false;
        }
    }

    function updateData() {
        Promise.all([
            axios.get('api/users/'),
            axios.get('api/tasks/unassigned'),
        ]).then(function(values) {
            users.value = values[0].data.data;
            unassigned_tasks.value = values[1].data.data;
            isLoading.value = false;
        })
    }

    function addTaskToUser(task, user) {
        axios.put(
            'api/tasks/'+task,
            {
                user_id: parseInt(user),
            }
        ).then(function (tasks) {
            console.log("Cool!");
        }).catch(function () {
            console.log("ERROR!");
        });
    }

    function getUserTasks(user) {
        axios.get('api/tasks/user/'+user).then(function (tasks) {
            users_tasks.value = tasks.data.data;
        })
    }

    updateData();

</script>
<template>
    <div class="content">
        <h1>Hello to the dashboard, {{user.name}}!</h1>
        <hr>
        <div v-if="isLoading">Loading data...</div>
        <div v-else>
            <div class="jobs">
                <div draggable="true" @dragstart="dragStart" @dragend="dragEnd" class="box-jobs" v-for="task in unassigned_tasks" :key="task.id" :data-taskId="task.id">
                    <h5>{{ task.title }}</h5>
                    <hr>
                    {{ task.task }} <br>
                    <hr>
                    Status: {{ task.status }} <br>
                    Created at: {{ task.created_at }}
                    Deadline: {{ task.deadline }}
                </div>
            </div>
            <div class="users">
                <div class="box drop-zone" v-for="user in users" :key="user.id" :data-userId="user.id">
                    <h3>{{ user.name }}</h3>
                    <hr>
                    <div class="user-jobs">
                        {{ getUserTasks(user.id) }}
                        <div class="box-jobs" v-for="task in users_tasks" :key="task.id" :data-taskId="task.id">
                            <h5>{{ task.title }}</h5>
                            <hr>
                            {{ task.task }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
