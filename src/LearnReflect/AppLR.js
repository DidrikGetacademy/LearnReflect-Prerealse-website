

import { BrowserRouter, Routes, Route } from 'react-router-dom';
import React from "react";
import CountDown from "../../LearnReflect/src/LearnReflect/PreRelease/CountDown";
import HomePage from "../../LearnReflect/src/LearnReflect/LearnReflect Discipline/HomePage";
import LoginPage from "../../LearnReflect/src/LearnReflect/User/LoginPage";
import AboutPage from "../../LearnReflect/src/LearnReflect/LearnReflect Discipline/AboutPage.js";
import Futures from "../../LearnReflect/src/LearnReflect/User/FuturesPage";
import AuthProvider from "../../LearnReflect/src/LearnReflect/Components/Authanciation/AuthProvider";
import Dashboard from "../../LearnReflect/src/LearnReflect/User/Dashboard.js";
import PrivateRoute from "../../LearnReflect/src/LearnReflect/Components/Authanciation/PrivateRoute.js";
import PrivateRouteFuture from "../../LearnReflect/src/LearnReflect/Components/Authanciation/PrivateRouteFuture.js";
import FutureZero from "../../LearnReflect/src/LearnReflect/LearnReflect Discipline/FutureZero.js";
import LandingPage from "../../LearnReflect/src/LearnReflect/MainSite/LandingPage.js";
import ShopPage from "../../LearnReflect/src/LearnReflect/Shop/Shop.js";
import ProductCard from "../../LearnReflect/src/LearnReflect/Shop/ProductCard.js";
import Contact from "../../LearnReflect/src/LearnReflect/Contact/Contact.js";
import Completion from "../../LearnReflect/src/LearnReflect/Shop/PaymentStripe/Completion.js";
import AIUpscalePage from "../../LearnReflect/src/LearnReflect/AI-LearnReflect/AIUpscalePage.js";
import Admin from "../../LearnReflect/src/LearnReflect/AdminPanel/Admin.js";
import AdminRoute from "../../LearnReflect/src/LearnReflect/Components/Authanciation/AdminRoute.js";
import Timer from "../../LearnReflect/src/LearnReflect/Components/TimerComponent.js";
import Inspire from "../../LearnReflect/src/LearnReflect/Inspire/Inspire.js";
import RegistrationForm from "../../LearnReflect/src/LearnReflect/User/UserRegistration.js";
import Chatbot from "../../LearnReflect/src/LearnReflect/AI-LearnReflect/Chat/ChatbotRender.js";
import AudioEnchancerJSX from "../../LearnReflect/src/LearnReflect/AI-LearnReflect/Audio/AudioRender.js";
import VideoEnchancerJSX from "../../LearnReflect/src/LearnReflect/AI-LearnReflect/Video/VideoRender.js";
import LearnReflectFont from './LearnReflect/MainSite/LandingPage'


function AppLR() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          <Route path="/Landingpage" element={<LandingPage />} />
          <Route path="/Contact" element={<Contact />} />
          <Route path="../../Chatbot" element={<Chatbot />} />
          <Route path="/AudioEnchancer" element={<AudioEnchancerJSX />} />
          <Route path="/VideoEnchancerJSX" element={<VideoEnchancerJSX />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/Register" element={<RegistrationForm />} />
          <Route path="/Timer" element={<Timer />} />
          <Route path="/CountDown" element={<CountDown />} />
          <Route path="/Homepage" element={<HomePage />} />
          <Route path="/AboutPage" element={<AboutPage />} />
          <Route path="/AIUpscalePage" element={<AIUpscalePage />} />
          <Route path="/FutureZero" element={<FutureZero />} />
          <Route path="/Inspire" element={<Inspire />} />
          <Route
            path="/Futures"
            element={
              <PrivateRouteFuture>
                <Futures />
              </PrivateRouteFuture>
            }
          />
          <Route path="/ShopPage" element={<ShopPage />} />
          <Route path="/ProductCard" element={<ProductCard />} />
          <Route path="/Completion" element={<Completion />} />
          <Route
            path="/Dashboard"
            element={
              <PrivateRoute>
                <Dashboard />
              </PrivateRoute>
            }
          />
          <Route
            path="/Admin"
            element={
              <AdminRoute>
                <Admin />
              </AdminRoute>
            }
          />

        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default AppLR;
