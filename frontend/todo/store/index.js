import gql from 'graphql-tag';

const apiUrl = 'YOUR_GRAPHQL_API_URL';

const ADD_TODO_MUTATION = gql`
  mutation AddTodo($text: String!) {
    addTodo(text: $text) {
      id
      text
      completed
    }
  }
`;

const DELETE_TODO_MUTATION = gql`
  mutation DeleteTodo($id: ID!) {
    deleteTodo(id: $id)
  }
`;

const UPDATE_TODO_MUTATION = gql`
  mutation UpdateTodo($id: ID!, $text: String!, $completed: Boolean!) {
    updateTodo(id: $id, text: $text) {
      id
      text
      completed
    }
  }
`;

export const state = () => ({
    todos: [
        {id: 1, completed: false, text: 'Task'},
        {id: 2, completed: false, text: 'Task 2'}
    ],
});

export const mutations = {
    setTodos(state, todos) {
        state.todos = todos;
    },
    addTodo(state, todo) {
        state.todos.push(todo);
    },
    deleteTodo(state, id) {
        state.todos = state.todos.filter(todo => todo.id !== id);
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
};

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
    async deleteTodo({ commit, dispatch }, id) {
      try {
        await this.$axios.post(apiUrl, {
          query: DELETE_TODO_MUTATION,
          variables: { id },
        });
        commit('deleteTodo', id);

        await dispatch('fetchTodos');

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
};