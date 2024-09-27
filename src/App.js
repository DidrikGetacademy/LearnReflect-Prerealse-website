import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import VideoIntro from './VideoIntro.js';
import './Css/App.css'
function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<VideoIntro/>} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;