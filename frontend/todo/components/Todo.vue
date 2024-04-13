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
        <button class="btn btn-danger" @click="deleteAll"><i class="fas fa-trash"></i><span class="ml-1">Delete all Tasks</span></button>
        <button v-if="completedTodos.length > 0" class="btn btn-danger" @click="deleteCompleted"><i class="fas fa-trash"></i> Delete Completed Tasks</button>
      </div>
      <div class="card-body">
        <div v-for="(todo, index) in todos" :key="index" class="todo-item">
          <div class="todo-box">
            <template v-if="!todo.editMode">
              <i :class="['far', todo.completed ? 'fa-check-circle marker text-success' : 'fa-check-circle marker']" @click="toggleCompleteTodo(index)"></i>
              <div style="width: 100%;" @click="toggleEditMode(todo)">{{ todo.text }}</div>
              <button class="btn btn-danger btn-sm trash" @click="deleteTodo(index)"><i class="fas fa-trash"></i></button>
            </template>
            <template v-else>
              <input type="text" v-model="todo.text" @keyup.enter="updateTodo(index)" @blur="updateTodo(index)" autofocus>
            </template>
          </div>
        </div>
        <div class="add-todo">
          <input type="text" v-model="newTodo" placeholder="Add a new task" class="form-control rounded-pill">
          <button @click="addTodo" class="btn btn-primary rounded-pill"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Todo',
    data() {
      return {
        todos: [
          {text: "test", completed: true, editMode: false},
          {text: "test 2", completed: false, editMode: false}
        ],
        newTodo: ''
      };
    },
    computed: {
      completedTodos() {
        return this.todos.filter(todo => todo.completed);
      }
    },
    methods: {
      toggleEditMode(todo) {
        todo.editMode = true;
      },
      toggleCompleteTodo(index) {
        // Your implementation for completing a todo
        console.log("Completing todo:", index);
        const todoCloned = Object.assign({}, this.todos[index]);

        this.todos[index].completed = (todoCloned.completed) ? false : true;
      },
      deleteTodo(index) {
        // Your implementation for deleting a todo
        console.log("Deleting todo:", index);
      },
      addTodo() {
        if (this.newTodo.trim() !== '') {
          // Your implementation for adding a new todo
          this.todos.push({
            completed: false,
            text: this.newTodo,
            editMode: false
          });
          console.log("Adding new todo:", this.newTodo);
          this.newTodo = '';

        }
      },
      deleteAll() {
        // Your implementation for deleting all todos
        console.log("Deleting all todos");
      },
      updateTodo(index) {
        this.todos[index].editMode = false;
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