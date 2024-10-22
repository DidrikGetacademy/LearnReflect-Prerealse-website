import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import './Css/App.css'
import PageComponent from './PageComponent.js'
import MenuBar from './MenuBar.js';
import ContactNavBar from './Contact.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import Payment from './payment.js';
function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<MenuBar/>} />
        <Route path="/PageComponent" element={<PageComponent/>} /> 
        <Route path="/Contact" element={<ContactNavBar/>} /> 
        <Route path="/Payment" element={<Payment/>} /> 
      </Routes>
    </BrowserRouter>
  );
}

export default App;