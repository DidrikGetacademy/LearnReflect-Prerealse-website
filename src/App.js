import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import PageComponent from './PageComponent.js'
import './Css/App.css'
function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<PageComponent/>} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;