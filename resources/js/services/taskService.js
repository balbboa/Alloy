import axios from 'axios';

const API_URL = '/api/tasks';

export default {
  async getTasks() {
    const { data } = await axios.get(API_URL);
    return data;
  },
  async createTask(task) {
    const { data } = await axios.post(API_URL, task);
    return data;
  },
  async updateTask(id, task) {
    const { data } = await axios.put(`${API_URL}/${id}`, task);
    return data;
  },
  async deleteTask(id) {
    await axios.delete(`${API_URL}/${id}`);
  },
  async toggleTask(id) {
    const { data } = await axios.patch(`${API_URL}/${id}/toggle`);
    return data;
  },
}; 