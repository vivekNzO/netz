import { createSlice, nanoid } from "@reduxjs/toolkit";

// nanoid generates unique ids

// initial state at first

const initialState = {
    todos : [{id:1,text:"Hellow world"}]
}

export const todoSlice = createSlice({
    name: 'todo',
    initialState,

    reducers:{
        // properties + functio
        addTodo : (state,action)=>{
            // parameter values or payload comes from action
            const todo = {
                id: nanoid(),
                text : action.payload
            }
            state.todos.push(todo)
        },

        removeTodo : (state,action)=>{
            state.todos = state.todos.filter((todo)=>todo.id!==action.payload)
        }
    }
})

export const {addTodo,removeTodo} = todoSlice.actions

export default todoSlice.reducer