import { BrowserRouter, Routes, Route } from 'react-router-dom';
import React from "react";
import MenuBar from './PreRealse/MenuBar.js';
import PageComponent from './PreRealse/PageComponent.js';
import ContactNavBar from './PreRealse/Contact.js';
import Payment from './PreRealse/payment.js';
function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<MenuBar />} />
        <Route path="/PageComponent" element={<PageComponent />} />
        <Route path="/ContactNavBar" element={<ContactNavBar />} />
        <Route path="/Payment" element={<Payment />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;