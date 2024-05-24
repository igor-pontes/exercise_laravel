<style>
    @import '../../css/app.css';
</style>
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios';
    import { ref } from 'vue';
    import NewTaskView from '../components/NewTaskView.vue'

    const router = useRouter()
    const route = useRoute()

    const user = JSON.parse(localStorage.getItem("user"));
    var users = ref([]);
    var users_tasks = ref([]);
    var unassigned_tasks = ref([]);
    var task_status = ref({});

    const isLoading = ref(true);

    // console.log(route.meta);

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

    function dragStart(e) {
        console.log(task_status.value);
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
        updateData();
        getUserTasks(user_id);
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
            console.log(values[0].data.data);
            users.value = values[0].data.data;
            unassigned_tasks.value = values[1].data.data;
            users.value.map(function(user) {
                getUserTasks(user.id);
            })
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
            const tasks_data = tasks.data.data;
            users_tasks.value[user] = tasks_data;
            tasks_data.map((task) => {
                task_status.value[task.id] = { status: task.status, update: taskStatus(task.id) };
            })
        })
    }

    function signOut() {
        localStorage.clear();
        router.push('/login');
    }

    function goToCreateTask() {
        router.push('/dashboard/newtask');
    }

    function goBack() {
        router.go(-2);
    }

</script>
<template>
    <div class="content">
        <div v-if="route.meta.default">
            <a @click="goToCreateTask" href="#createtask">Create Task</a> | <a @click="signOut" href="#signout">Sign out</a>
        </div>
        <div v-else>
            <a @click="goBack" href="#dashboard">Go to Dashboard</a> | <a @click="signOut" href="#signout">Sign out</a>
        </div>
        <hr>
        <h1>Hello to the dashboard, {{user.name}}!</h1>
        <hr>
        <div v-if="route.meta.newtask">
            {{ isLoading = true }}
            <NewTaskView/>
        </div>
        <div v-else>
            <div v-if="isLoading">
                {{ updateData() }}
                Loading data...
            </div>
            <div v-else>
                <div class="jobs">
                    <div draggable="true" @dragstart="dragStart" @dragend="dragEnd" class="box-jobs" v-for="task in unassigned_tasks" :key="task.id" :data-taskId="task.id">
                        <h5>{{ task.title }}</h5>
                        <hr>
                        {{ task.task }} <br>
                        <hr>
                        Status: {{ task.status }} <br>
                        Created at: <br> {{ task.created_at }} <br>
                        Deadline: <br> {{ task.deadline }}
                    </div>
                </div>
                <div class="users">
                    <div class="box drop-zone" v-for="user in users" :key="user.id" :data-userId="user.id">
                        <h3>{{ user.name }}</h3>
                        <hr>
                        <div class="user-jobs">
                            <!-- {{ getUserTasks(user.id) }} -->
                            <div class="box-jobs" v-for="task in users_tasks[user.id]" :key="task.id" :data-taskId="task.id">
                                <h5>{{ task.title }}</h5>
                                <hr>
                                {{ task.task }}
                                <br>
                                <br>
                                Status:
                                <select @change="task_status[task.id].update" v-model="task_status[task.id]">
                                    <option :value="{ status: 0, update: taskStatus(task.id) }" :selected="task.status == 0">Idle</option>
                                    <option :value="{ status: 1, update: taskStatus(task.id) }" :selected="task.status == 1">On track</option>
                                    <option :value="{ status: 2, update: taskStatus(task.id) }" :selected="task.status == 2">Finished</option>
                                    <option :value="{ status: 3, update: taskStatus(task.id) }" :selected="task.status == 3">Failed</option>
                                </select>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
