<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const initialHosts = [
    { url: 'api.example.com', editing: false, editUrl: '' },
    { url: 'cdn.cloudprovider.org', editing: false, editUrl: '' },
    { url: 'mail.mydomain.com', editing: false, editUrl: '' },
    { url: 'db.internal', editing: false, editUrl: '' },
    { url: 'monitoring.example.io', editing: false, editUrl: '' }
];

const hosts = ref([...initialHosts]);
const newHost = ref('');

const addHost = () => {
    if (newHost.value.trim()) {
        hosts.value.push({
            url: newHost.value.trim(),
            editing: false,
            editUrl: ''
        });
        newHost.value = '';
    }
};

const startEditing = (index) => {
    hosts.value[index].editing = true;
    hosts.value[index].editUrl = hosts.value[index].url;
};

const saveHost = (index) => {
    if (hosts.value[index].editUrl.trim()) {
        hosts.value[index].url = hosts.value[index].editUrl.trim();
        hosts.value[index].editing = false;
    }
};

const deleteHost = (index) => {
    hosts.value.splice(index, 1);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-center text-white mb-6">Add new host</h2>

                        <form @submit.prevent="addHost" class="space-y-4">
                            <div>
                                <label for="hostInput" class="block text-sm font-medium text-gray-300 mb-2">Adress</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        id="hostInput"
                                        v-model="newHost"
                                        type="text"
                                        placeholder="example.com"
                                        required
                                        class="bg-gray-700 text-white w-full pl-10 pr-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                                    />
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                            >
                                Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
                        <div class="p-6 md:p-8">
                            <h2 class="text-2xl font-bold text-center text-white mb-6">My hosts</h2>

                            <div v-if="hosts.length === 0" class="text-center text-gray-400 py-4">
                                Host is null
                            </div>

                            <ul v-else class="divide-y divide-gray-700">
                                <li v-for="(host, index) in hosts" :key="index" class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div v-if="!host.editing" class="flex items-center">
                                            <svg class="h-6 w-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                                            </svg>
                                            <span class="text-white font-mono break-all">{{ host.url }}</span>
                                        </div>

                                        <div v-else class="flex-1 mr-4">
                                            <input
                                                v-model="host.editUrl"
                                                type="text"
                                                class="bg-gray-700 text-white w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                                @keyup.enter="saveHost(index)"
                                            />
                                        </div>

                                        <div class="flex space-x-2 ml-4">
                                            <button
                                                v-if="!host.editing"
                                                @click="startEditing(index)"
                                                class="p-2 text-gray-400 hover:text-yellow-400 transition duration-200"
                                                title="Редактировать"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>

                                            <button
                                                v-else
                                                @click="saveHost(index)"
                                                class="p-2 text-gray-400 hover:text-green-400 transition duration-200"
                                                title="Сохранить"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>

                                            <button
                                                @click="deleteHost(index)"
                                                class="p-2 text-gray-400 hover:text-red-400 transition duration-200"
                                                title="Удалить"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
button {
    transition: color 0.2s ease;
}

input {
    transition: all 0.2s ease;
}

input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    -webkit-text-fill-color: white;
    -webkit-box-shadow: 0 0 0px 1000px #374151 inset;
    transition: background-color 5000s ease-in-out 0s;
}
</style>
