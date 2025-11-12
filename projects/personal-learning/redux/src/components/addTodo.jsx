import React, { useState } from 'react'
import { useDispatch } from 'react-redux'
import {addTodo} from '../features/todo/todoSlice'

const addTodo = () => {

    const [input,setInput] = useState('')
    const dispatch = useDispatch()

    const addTodoHandler = (e)=>{
        e.preventDefault()
        // dispatch reducer ko use krke store me value change krta h
        dispatch(addTodo(input))
    }
  return (
    <form onSubmit={addTodoHandler}>
        <input
            type='text'
            placeholder='enter a todo...'
            value={input}
            onChange={(e)=>setInput(e.target.value)}
        />
        <button type='submit'>
            Add todo
        </button>

    </form>
  )
}

export default addTodo