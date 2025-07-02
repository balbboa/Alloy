<template>
  <div class="tasks">
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Carregando tarefas...</p>
    </div>
    
    <div v-else-if="error" class="error">
      <p>{{ error }}</p>
      <button @click="fetchTasks" class="retry-btn">Tentar novamente</button>
    </div>
    
    <div v-else-if="tasks.length === 0" class="empty-state">
      <p>Nenhuma tarefa encontrada.</p>
      <p>Crie sua primeira tarefa clicando no botão +</p>
    </div>
    
    <div v-else class="form-fields no-space-top w-form">
      <div class="block-tasks">
        <div 
          v-for="task in tasks" 
          :key="task.id" 
          class="task"
          :class="{ 'completed': task.finalizado, 'overdue': task.is_overdue }"
        >
          <div class="task-main-content">
            <label class="w-checkbox checkbox-field">
              <div 
                class="w-checkbox-input w-checkbox-input--inputType-custom checkbox"
                :class="{ 'checked': task.finalizado }"
                @click="toggleTask(task.id)"
              ></div>
              <input 
                type="checkbox" 
                :checked="task.finalizado"
                @change="toggleTask(task.id)"
                style="opacity:0;position:absolute;z-index:-1"
              >
              <span 
                class="checkbox-label w-form-label"
                :class="{ 'checked': task.finalizado }"
              >
                {{ task.nome }}
              </span>
            </label>
            
            <div v-if="task.formatted_deadline" class="date-button">
              <div>{{ task.formatted_deadline }}</div>
            </div>
          </div>
          
          <div v-if="task.descricao" class="task-details">
            <div>{{ task.descricao }}</div>
          </div>
          
          <div class="task-actions">
            <button 
              @click="editTask(task)"
              class="button outlined rounded small edit-btn"
              title="Editar tarefa"
              type="button"
            >
              <div class="icon w-embed">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M18.5 2.50023C18.8978 2.10297 19.4374 1.87891 20 1.87891C20.5626 1.87891 21.1022 2.10297 21.5 2.50023C21.897 2.89733 22.1211 3.43667 22.1211 3.99923C22.1211 4.56179 21.897 5.10113 21.5 5.49823L12 15.0002L8 16.0002L9 12.0002L18.5 2.50023Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </button>
            
            <button 
              @click="deleteTask(task.id)"
              class="button outlined rounded small remove-task"
              title="Excluir tarefa"
              type="button"
            >
              <div class="icon w-embed">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useTaskStore } from '../stores/taskStore';
import { storeToRefs } from 'pinia';

const emit = defineEmits(['edit-task']);

const taskStore = useTaskStore();
const { tasks, loading, error } = storeToRefs(taskStore);

const fetchTasks = () => {
  taskStore.fetchTasks();
};

const toggleTask = (id) => {
  taskStore.toggleTask(id);
};

const editTask = (task) => {
  console.log('editTask called', task);
  taskStore.selectTask(task);
  emit('edit-task', task);
};

const deleteTask = async (id) => {
  if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
    await taskStore.deleteTask(id);
  }
};

onMounted(() => {
  fetchTasks();
});
</script>

<style scoped>
.loading, .error, .empty-state {
  text-align: center;
  padding: 2rem 1rem;
  color: #666;
}

.spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #3498db;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.retry-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.875rem;
  transition: background-color 0.2s;
}

.retry-btn:hover {
  background: #0056b3;
}

.block-tasks {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.task {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding: 1.25rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: white;
  transition: all 0.2s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.task:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transform: translateY(-1px);
}

.task.completed {
  opacity: 0.7;
  background: #f8f9fa;
}

.task.completed .checkbox-label {
  text-decoration: line-through;
  color: #6c757d;
}

.task.overdue {
  border-left: 4px solid #dc3545;
  background: #fff5f5;
}

.task-main-content {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.checkbox-field {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex: 1;
  min-width: 0;
}

.checkbox {
  cursor: pointer;
  transition: all 0.2s;
  flex-shrink: 0;
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  position: relative;
}

.checkbox.checked {
  background-color: #007bff;
  border-color: #007bff;
}

.checkbox.checked::after {
  content: '✓';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 12px;
  font-weight: bold;
}

.checkbox-label {
  font-size: 1rem;
  font-weight: 500;
  color: #374151;
  line-height: 1.5;
  word-break: break-word;
}

.checkbox-label.checked {
  color: #6c757d;
}

.date-button {
  background: #f3f4f6;
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
  white-space: nowrap;
  flex-shrink: 0;
}

.task-details {
  margin-left: 2.75rem;
  padding: 0.75rem;
  background: #f9fafb;
  border-radius: 6px;
  border-left: 3px solid #e5e7eb;
}

.task-details div {
  color: #6b7280;
  font-size: 0.875rem;
  line-height: 1.5;
}

.task-actions {
  display: flex;
  gap: 0.5rem;
  margin-left: auto;
  flex-shrink: 0;
}

.edit-btn, .remove-task {
  padding: 0.5rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  height: 40px;
}

.edit-btn {
  background: #28a745;
  color: white;
}

.edit-btn:hover {
  background: #218838;
  transform: translateY(-1px);
}

.remove-task {
  background: #dc3545;
  color: white;
  opacity: 1 !important;
  display: flex !important;
  position: static !important;
}

.remove-task:hover {
  background: #c82333;
  transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .task {
    padding: 1rem;
    gap: 0.5rem;
  }
  
  .task-main-content {
    gap: 0.75rem;
  }
  
  .checkbox-field {
    gap: 0.5rem;
  }
  
  .task-details {
    margin-left: 2.25rem;
    padding: 0.5rem;
  }
  
  .task-actions {
    gap: 0.25rem;
  }
  
  .edit-btn, .remove-task {
    min-width: 36px;
    height: 36px;
  }
  
  .date-button {
    padding: 0.375rem 0.5rem;
    font-size: 0.75rem;
  }
}

@media (max-width: 480px) {
  .loading, .error, .empty-state {
    padding: 1.5rem 0.75rem;
  }
  
  .task {
    padding: 0.75rem;
  }
  
  .task-main-content {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .checkbox-field {
    width: 100%;
  }
  
  .date-button {
    align-self: flex-start;
  }
  
  .task-details {
    margin-left: 0;
    width: 100%;
  }
  
  .task-actions {
    margin-left: 0;
    width: 100%;
    justify-content: flex-end;
  }
  
  .checkbox-label {
    font-size: 0.875rem;
  }
  
  .task-details div {
    font-size: 0.8rem;
  }
}

@media (min-width: 1024px) {
  .task {
    padding: 1.5rem;
  }
  
  .task-main-content {
    gap: 1.25rem;
  }
  
  .checkbox-field {
    gap: 1rem;
  }
  
  .task-actions {
    gap: 0.75rem;
  }
  
  .edit-btn, .remove-task {
    min-width: 44px;
    height: 44px;
  }
}
</style> 