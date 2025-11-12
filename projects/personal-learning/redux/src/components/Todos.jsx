import React from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { removeTodo } from '../features/todo/todoSlice' 

const Todos = () => {
    const todos = useSelector(state => state.todos)
    const dispatch = useDispatch()
  return (
    <div>
        <div>My todos</div>
        {todos.map((todo)=>(
            <li key={todo.id}>
                {todo.text}
                <button onClick={()=>dispatch(removeTodo(todo.id))}>X</button>
            </li>
        ))}
    </div>
  )
}

export default Todos