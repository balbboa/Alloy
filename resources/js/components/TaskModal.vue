<template>
  <Transition name="modal" appear>
    <div v-if="show" class="modal-overlay" @click="handleBackdropClick">
      <div class="modal-container" @click.stop>
        <!-- Header -->
        <div class="modal-header">
          <div class="modal-title">
            <div class="title-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 11L12 14L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3>{{ isEditing ? 'Editar Tarefa' : 'Nova Tarefa' }}</h3>
          </div>
          <button 
            @click="closeModal" 
            class="close-button"
            type="button"
            aria-label="Fechar modal"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        
        <!-- Form -->
        <form @submit.prevent="saveTask" class="modal-form">
          <div class="modal-content">
            <div class="form-section">
              <div class="form-group">
                <label for="task-name" class="form-label">
                  Título <span class="required">*</span>
                </label>
                <div class="input-wrapper">
                  <input 
                    v-model="form.nome"
                    class="form-input" 
                    :class="{ 'error': errors.nome, 'filled': form.nome }"
                    maxlength="256" 
                    type="text" 
                    id="task-name" 
                    required
                    placeholder="Digite o título da tarefa"
                    autocomplete="off"
                  >
                  <div class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M21 12V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <span v-if="errors.nome" class="error-message">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2"/>
                  </svg>
                  {{ errors.nome }}
                </span>
                <div class="char-counter">{{ form.nome.length }}/256</div>
              </div>
              
              <div class="form-group">
                <label for="task-description" class="form-label">
                  Descrição
                </label>
                <div class="input-wrapper">
                  <textarea 
                    v-model="form.descricao"
                    class="form-textarea" 
                    :class="{ 'filled': form.descricao }"
                    maxlength="1000" 
                    id="task-description"
                    rows="4"
                    placeholder="Adicione detalhes sobre a tarefa (opcional)"
                  ></textarea>
                  <div class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <div class="char-counter">{{ form.descricao.length }}/1000</div>
              </div>
              
              <div class="form-group">
                <label for="task-deadline" class="form-label">
                  Data Limite
                </label>
                <div class="input-wrapper">
                  <input 
                    v-model="form.data_limite"
                    class="form-input" 
                    :class="{ 'filled': form.data_limite }"
                    type="datetime-local" 
                    id="task-deadline"
                    placeholder="Selecione a data limite"
                  >
                  <div class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                      <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" stroke-width="2"/>
                      <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" stroke-width="2"/>
                      <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/>
                    </svg>
                  </div>
                </div>
                <div class="help-text">Deixe em branco se não houver prazo específico</div>
              </div>
            </div>
          </div>
          
          <!-- Footer -->
          <div class="modal-footer">
            <div class="footer-actions">
              <button 
                type="button" 
                @click="closeModal" 
                class="btn btn-secondary"
                :disabled="loading"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cancelar
              </button>
              <button 
                type="submit"
                class="btn btn-primary"
                :disabled="loading || !form.nome.trim()"
              >
                <div v-if="loading" class="loading-spinner"></div>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19 21L12 16L5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ loading ? 'Salvando...' : (isEditing ? 'Atualizar' : 'Criar Tarefa') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useTaskStore } from '../stores/taskStore';
import { storeToRefs } from 'pinia';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'saved']);

const taskStore = useTaskStore();
const { selectedTask, loading } = storeToRefs(taskStore);

const form = ref({
  nome: '',
  descricao: '',
  data_limite: ''
});

const errors = ref({});

const isEditing = computed(() => !!selectedTask.value);

const resetForm = () => {
  form.value = {
    nome: '',
    descricao: '',
    data_limite: ''
  };
  errors.value = {};
};

// Watch for selectedTask changes to populate form
watch(selectedTask, (task) => {
  console.log('TaskModal selectedTask changed:', task);
  if (task) {
    form.value = {
      nome: task.nome || '',
      descricao: task.descricao || '',
      data_limite: task.data_limite ? new Date(task.data_limite).toISOString().slice(0, 16) : ''
    };
  } else {
    resetForm();
  }
}, { immediate: true });

watch(() => props.show, (val) => {
  console.log('TaskModal show prop changed:', val);
  if (val) {
    console.log('Modal should be visible now');
  }
});

const handleBackdropClick = (event) => {
  if (event.target === event.currentTarget) {
    closeModal();
  }
};

const validateForm = () => {
  errors.value = {};
  
  if (!form.value.nome.trim()) {
    errors.value.nome = 'O título é obrigatório';
    return false;
  }
  
  if (form.value.nome.length > 255) {
    errors.value.nome = 'O título deve ter no máximo 255 caracteres';
    return false;
  }
  
  return true;
};

const saveTask = async () => {
  console.log('saveTask called', { form: form.value, isEditing: isEditing.value });
  if (!validateForm()) return;
  
  try {
    const taskData = {
      nome: form.value.nome.trim(),
      descricao: form.value.descricao.trim() || null,
      data_limite: form.value.data_limite || null
    };
    
    console.log('Saving task data:', taskData);
    
    if (isEditing.value) {
      console.log('Updating task:', selectedTask.value.id);
      await taskStore.updateTask(selectedTask.value.id, taskData);
    } else {
      console.log('Creating new task');
      await taskStore.createTask(taskData);
    }
    
    console.log('Task saved successfully');
    closeModal();
    emit('saved');
  } catch (error) {
    console.error('Error saving task:', error);
  }
};

const closeModal = () => {
  console.log('closeModal called');
  taskStore.clearSelectedTask();
  resetForm();
  emit('close');
};
</script>

<style scoped>
/* Modal Transitions */
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from {
  opacity: 0;
  transform: scale(0.95);
}

.modal-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 1rem;
}

/* Modal Container */
.modal-container {
  background: white;
  border-radius: 16px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  border: 1px solid rgba(0, 0, 0, 0.1);
  animation: modalSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Modal Header */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #f1f5f9;
  background: #fafafa;
}

.modal-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.title-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 10px;
  color: white;
}

.modal-title h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
}

.close-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border: none;
  background: transparent;
  border-radius: 8px;
  color: #64748b;
  cursor: pointer;
  transition: all 0.2s ease;
}

.close-button:hover {
  background: #f1f5f9;
  color: #1e293b;
  transform: scale(1.05);
}

/* Modal Content */
.modal-content {
  padding: 2rem;
  overflow-y: auto;
  max-height: calc(90vh - 140px);
}

.form-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.required {
  color: #ef4444;
  font-weight: 600;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 0.875rem 1rem 0.875rem 2.5rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  background: #ffffff;
  transition: all 0.2s ease;
  font-family: inherit;
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
  padding-top: 1rem;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  transform: translateY(-1px);
}

.form-input.filled,
.form-textarea.filled {
  border-color: #10b981;
  background: #f0fdf4;
}

.form-input.error {
  border-color: #ef4444;
  background: #fef2f2;
}

.form-input.error:focus {
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.input-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  transition: color 0.2s ease;
}

.form-input:focus + .input-icon,
.form-textarea:focus + .input-icon,
.form-input.filled + .input-icon,
.form-textarea.filled + .input-icon {
  color: #667eea;
}

.form-textarea + .input-icon {
  top: 1.25rem;
  transform: none;
}

.error-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #ef4444;
  font-size: 0.875rem;
  font-weight: 500;
  margin-top: 0.25rem;
}

.help-text {
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

.char-counter {
  font-size: 0.75rem;
  color: #9ca3af;
  text-align: right;
  margin-top: 0.25rem;
}

/* Modal Footer */
.modal-footer {
  padding: 1.5rem 2rem;
  border-top: 1px solid #f1f5f9;
  background: #fafafa;
}

.footer-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 10px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  min-width: 120px;
  justify-content: center;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
}

.btn-secondary {
  background: #f8fafc;
  color: #64748b;
  border: 2px solid #e2e8f0;
}

.btn-secondary:hover:not(:disabled) {
  background: #f1f5f9;
  color: #475569;
  border-color: #cbd5e1;
  transform: translateY(-1px);
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 14px 0 rgba(102, 126, 234, 0.4);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px 0 rgba(102, 126, 234, 0.5);
}

.btn-primary:active:not(:disabled) {
  transform: translateY(0);
}

/* Loading Spinner */
.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 640px) {
  .modal-overlay {
    padding: 0.5rem;
  }
  
  .modal-container {
    border-radius: 12px;
  }
  
  .modal-header {
    padding: 1rem 1.5rem;
  }
  
  .modal-content {
    padding: 1.5rem;
  }
  
  .modal-footer {
    padding: 1rem 1.5rem;
  }
  
  .footer-actions {
    flex-direction: column-reverse;
  }
  
  .btn {
    width: 100%;
  }
}

/* Focus Management */
.modal-container:focus {
  outline: none;
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .modal-enter-active,
  .modal-leave-active,
  .btn,
  .form-input,
  .form-textarea,
  .close-button {
    transition: none;
  }
  
  .loading-spinner {
    animation: none;
  }
}
</style> 