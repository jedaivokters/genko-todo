<template>
    <div class="todo-card card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-end">
          <div class="pill-counter bg-primary">
            <span class="label">Tasks</span>
            <span class="badge"><span class="counter">{{ todos.length }}</span></span>
          </div>
          <div class="pill-counter bg-primary">
            <span class="label">Tasks Done</span>
            <span class="badge"><span class="counter">{{ completedTodos.length }}</span></span>
          </div>
        </div>
        <div>
          <button v-if="todos.length > 0" class="btn btn-danger" @click="deleteAll"><i class="fas fa-trash"></i><span class="ml-1">Delete all Tasks</span></button>
          <button v-if="completedTodos.length > 0" class="btn btn-warning" @click="deleteCompleted"><i class="fas fa-trash"></i> Delete Completed Tasks</button>
        </div>
      </div>
      <div class="card-body">
        <div v-for="(todo, index) in todos" :key="index" class="todo-item">
          <div class="todo-box">
            <template v-if="editModeIndex !== index">
              <i :class="['far', todo.completed ? 'fa-check-circle marker text-success' : 'fa-check-circle marker']" @click="toggleCompleteTodo(todo.id)"></i>
              <div class="w-100" @click="toggleEditMode(index)">
                <div :class="[todo.completed ? 'completed' : '']">{{ todo.text }}</div>
              </div>
              <button class="btn btn-danger btn-sm trash" @click="deleteTodo(todo.id, index)"><i class="fas fa-trash"></i></button>
            </template>
            <template v-else>
              <input type="text" :value="todo.text" @keyup.enter="updateTodoText($event, todo.id)" autofocus>
            </template>
          </div>
        </div>
        <div class="add-todo">
          <input type="text" @keyup.enter="addTodo" v-model="newTodo" placeholder="Add a new task" class="form-control rounded-pill">
          <button @click="addTodo" class="btn btn-primary rounded-pill"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { mapState } from 'vuex';

  export default {
    name: 'Todo',
    mounted() {
      this.$store.dispatch('fetchTodos');
    },
    data() {
      return {
        newTodo: '',
        editModeIndex: null // Track the index of the todo being edited
      };
    },
    computed: {
      ...mapState(['todos']),
      completedTodos() {
        return this.todos.filter(todo => todo.completed);
      }
    },
    methods: {
      async addTodo() {
        if (this.newTodo.trim() !== '') {
          await this.$store.dispatch('addTodo', this.newTodo);
          this.newTodo = '';
        }
      },
      async deleteTodo(id, index) {
        await this.$store.dispatch('deleteTodo', { id, index });
      },
      async completeTodo(index) {
        await this.$store.dispatch('completeTodo', this.todos[index].id);
      },
      toggleEditMode(index) {
        this.editModeIndex = this.editModeIndex === index ? null : index;
      },
      async updateTodoText(event, id) {
        const text = event.target.value;

        await this.$store.dispatch('updateTodoText', { id, text });

        this.editModeIndex = null;
      },
      async toggleCompleteTodo(id) {
        const todo = Object.assign({}, this.todos.find(todo => todo.id === id));
        const completed = (todo.completed) ? false : true;

        await this.$store.dispatch('updateTodoComplete', { id, completed });
      },
      async deleteAll() {
        await this.$store.dispatch('deleteAllTodos');
      },
      async deleteCompleted() {
        await this.$store.dispatch('deleteAllCompletedTodos');
      }
    }
  };
  </script>
  
  <style scoped>
    .todo-card {
      margin-top: 20px;
    }

    .pill-counter {
      display: flex;
      align-items: center;
      margin-right: 10px;
      border-radius: 20px;
      padding: 4px 10px;
    }

    .bg-primary {
      background-color: #007bff; /* Blue background */
    }

    .bg-success {
      background-color: #28a745; /* Green background */
    }

    .badge {
      background-color: white; /* White background */
      color: black; /* Black text */
      border-radius: 50%;
      padding: 8px;
    }

    .counter {
      font-size: 12px;
      color: #007bff;
    }

    .label {
      margin-right: 5px;
      color: white;
    }

    .todo-item {
      margin-top: 10px;
    }

    .todo-box {
      display: flex;
      align-items: center;
      padding: 10px;
      border: 1px solid #ccc;
    }

    .todo-box:hover {
      background-color: #f8f9fa; /* Light gray background on hover */
    }

    .todo-box .marker {
      font-size: 26px;
      margin-right: 10px;
      cursor: pointer;
    }

    .todo-box .marker:hover {
      color: green; /* Change color on hover */
    }

    .todo-box button {
      margin-left: auto; /* Align the trash icon to the right */
      visibility: hidden; /* Hide the trash icon by default */
    }

    .todo-box:hover button {
      visibility: visible; /* Show the trash icon on hover */
    }

    .todo-box .completed {
      text-decoration: line-through;
    }

    .add-todo {
      margin-top: 20px;
      display: flex;
      align-items: center;
    }

    .add-todo input {
      flex: 1;
      margin-right: 10px;
    }

    .add-todo button {
      width: 40px;
    }
  </style>