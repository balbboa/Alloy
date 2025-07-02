import { defineStore } from 'pinia';
import taskService from '../services/taskService';

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
    loading: false,
    error: null,
    selectedTask: null,
  }),
  actions: {
    async fetchTasks() {
      console.log('fetchTasks called');
      this.loading = true;
      this.error = null;
      try {
        this.tasks = await taskService.getTasks();
        console.log('fetchTasks result:', this.tasks);
      } catch (e) {
        this.error = e.message || 'Erro ao buscar tarefas';
        console.error('fetchTasks error:', e);
      } finally {
        this.loading = false;
      }
    },
    async createTask(task) {
      console.log('createTask called', task);
      this.loading = true;
      this.error = null;
      try {
        const newTask = await taskService.createTask(task);
        this.tasks.unshift(newTask);
        console.log('createTask result:', newTask);
      } catch (e) {
        this.error = e.message || 'Erro ao criar tarefa';
        console.error('createTask error:', e);
      } finally {
        this.loading = false;
      }
    },
    async updateTask(id, task) {
      this.loading = true;
      this.error = null;
      try {
        const updated = await taskService.updateTask(id, task);
        const idx = this.tasks.findIndex(t => t.id === id);
        if (idx !== -1) this.tasks[idx] = updated;
      } catch (e) {
        this.error = e.message || 'Erro ao atualizar tarefa';
      } finally {
        this.loading = false;
      }
    },
    async deleteTask(id) {
      this.loading = true;
      this.error = null;
      try {
        await taskService.deleteTask(id);
        this.tasks = this.tasks.filter(t => t.id !== id);
      } catch (e) {
        this.error = e.message || 'Erro ao excluir tarefa';
      } finally {
        this.loading = false;
      }
    },
    async toggleTask(id) {
      this.loading = true;
      this.error = null;
      try {
        const toggled = await taskService.toggleTask(id);
        const idx = this.tasks.findIndex(t => t.id === id);
        if (idx !== -1) this.tasks[idx] = toggled;
      } catch (e) {
        this.error = e.message || 'Erro ao alternar tarefa';
      } finally {
        this.loading = false;
      }
    },
    selectTask(task) {
      this.selectedTask = task;
    },
    clearSelectedTask() {
      this.selectedTask = null;
    },
  },
}); 