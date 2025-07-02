<template>
  <div class="content-tasks">
    <div class="header">
      <h2>Tarefas</h2>
      <div @click="openCreateModal" class="button rounded">
        <div class="icon w-embed">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </div>
      </div>
    </div>
    
    <TaskList @edit-task="openEditModal" />
    
    <TaskModal 
      :show="showModal" 
      @close="closeModal" 
      @saved="onTaskSaved"
    />
    
    <div class="footer">
      <div>Alloy</div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useTaskStore } from '../stores/taskStore';
import TaskList from './TaskList.vue';
import TaskModal from './TaskModal.vue';

const taskStore = useTaskStore();
const showModal = ref(false);

const openCreateModal = () => {
  taskStore.clearSelectedTask();
  showModal.value = true;
};

const openEditModal = (task) => {
  taskStore.selectTask(task);
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const onTaskSaved = () => {
  console.log('onTaskSaved called');
  // Task was saved successfully
  // The store will automatically update the list
};

// Watch for modal state changes
watch(showModal, (newVal) => {
  console.log('TasksContainer showModal changed to:', newVal);
});
</script>
