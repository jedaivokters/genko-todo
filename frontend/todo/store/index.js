import { gql } from 'graphql-tag';

const apiUrl = '/graphql/';

const ADD_TODO_MUTATION = `
  mutation AddTodo($text: String!) {
    addTodo(text: $text) {
      id
      text
      completed
    }
  }
`;

const DELETE_TODO_MUTATION = `
  mutation DestroyTodo($id: ID!) {
    destroyTodo(id: $id)
  }
`;

const UPDATE_TODO_MUTATION = `
  mutation UpdateTodo($id: ID!, $text: String!, $completed: Boolean!) {
    updateTodo(id: $id, text: $text, completed: $completed) {
      id
      text
      completed
    }
  }
`;

const DELETE_TODOS_MUTATION = `
  mutation DestroyAllTodos {
    destroyAllTodos
  }
`;

const DELETE_COMPLETED_TODOS_MUTATION = `
  mutation DestroyCompletedTodos {
    destroyCompletedTodos
  }
`;

export const state = () => ({
    todos: [],
});

export const mutations = {
    setTodos(state, todos) {
        state.todos = todos;
    },
    addTodo(state, todo) {
        state.todos.push(todo);
    },
    deleteTodo(state, index) {
        state.todos.splice(index, 1);
    },
    updateTodoText(state, { id, text }) {
        const todo = state.todos.find(todo => todo.id === id);
        if (todo) {
            todo.text = text;
        }
    },
    updateTodoComplete(state, { id, completed }) {
        const todo = state.todos.find(todo => todo.id === id);
        if (todo) {
            todo.completed = completed;
        }
    },
    clearTodos(state) {
        // Clear the todos array
        state.todos = [];
    },
    clearCompletedTodos(state) {
        state.todos = state.todos.filter(todo => !todo.completed);
    }
};

// Actions
export const actions = {
    async fetchTodos({ commit }) {
      try {
        const response = await this.$axios.post(apiUrl, {
          query: `
            query {
              todos {
                id
                text
                completed
              }
            }
          `,
        });
        commit('setTodos', response.data.data.todos);
      } catch (error) {
        console.error('Error fetching todos:', error);
      }
    },
    async addTodo({ commit }, text) {
      try {
        const response = await this.$axios.post(apiUrl, {
          query: ADD_TODO_MUTATION,
          variables: { text },
        });
        commit('addTodo', response.data.data.addTodo);
      } catch (error) {
        console.error('Error adding todo:', error);
      }
    },
    async deleteTodo({ commit, dispatch }, {id, index}) {
      try {
        await this.$axios.post(apiUrl, {
          query: DELETE_TODO_MUTATION,
          variables: { id },
        });

        commit('deleteTodo', index);

      } catch (error) {
        console.error('Error deleting todo:', error);
      }
    },
    async updateTodoText({ commit, state }, { id, text }) {
      const todo = state.todos.find(todo => todo.id === id);
      const completed = todo.completed;

      try {
        await this.$axios.post(apiUrl, {
          query: UPDATE_TODO_MUTATION,
          variables: { id, text, completed },
        });
        commit('updateTodoText', { id, text});
      } catch (error) {
        console.error('Error updating todo text:', error);
      }
    },
    async updateTodoComplete({ commit, state }, { id, completed }) {
        const todo = state.todos.find(todo => todo.id === id);
        const text = todo.text;
  
        try {
          await this.$axios.post(apiUrl, {
            query: UPDATE_TODO_MUTATION,
            variables: { id, text, completed },
          });

          commit('updateTodoComplete', { id, completed });
        } catch (error) {
          console.error('Error updating todo complete:', error);
        }
      },
    async deleteAllTodos({ commit }) {
        try {
            await this.$axios.post(apiUrl, {
                query: DELETE_TODOS_MUTATION,
            });

            // Commit the mutation to clear the todos array
            commit('clearTodos');
        } catch (error) {
            console.error('Error deleting all todos:', error);
        }
    },
    async deleteAllCompletedTodos({ commit }) {
        try {
            await this.$axios.post(apiUrl, {
                query: DELETE_COMPLETED_TODOS_MUTATION,
            });

            commit('clearCompletedTodos');
        } catch (error) {
            console.error('Error deleting all completed todos:', error);
        }
    }
};