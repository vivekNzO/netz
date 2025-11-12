import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import Todos from './components/Todos'
import addTodo from './components/addTodo'

function App() {
  const [count, setCount] = useState(0)

  return (
    <>
      <h1>Redux toolkit</h1>
      <addTodo/>
      <Todos/>
    </>
  )
}

export default App
