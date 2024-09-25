import React from "react";
import "./Css/PreRealse.css";
import AI1 from "./Images/AI1.png";
import LComponent from "./LogoComponent";
function Page() {
  return (
    <div>
      {" "}<div className="PreReleaseContainer">
        <div c className="scroll-container">
        <div className="scroll-page">
          <h1 class="HomeTopTitle">
            Empowering Your Self-Development Journey with Cutting-Edge AI
          </h1>
          <br />
          <h3>LearnReflect's under Development</h3>
          <LComponent />
          <div className="LearnReflect">
            <p >
              LearnReflect is a cutting-edge self-improvement platform that
              harnesses the power of AI<br /> To help - Assist & guide you to
              achieve personal growth.
            </p>
            <p >
              Build discipline & stay motivated.<br /> Our suite of AI-driven
              tools includes routine planners and personalized motivation
              strategies. The LR-Chatbot is pre-trained for self-improvement and
              will continuously learn from your conversations, becoming tailored
              to your specific goals. Over time, you'll have your own AI
              assistant that knows you, guiding you with personalized
              discipline-building techniques to help you stay focused on your
              goals.
            </p>
            <p >
              In addition<br /> we offer advanced AI models for enhancing video
              and audio quality, making LearnReflect an all-in-one solution<br />{" "}
              for anyone seeking to improve themselves and their productivity.{" "}
              <br />
              Whether you're aiming to boost your daily habits or <br /> achieve
              long-term success,<br /> LearnReflect is your guide to becoming
              the best version of yourself.
            </p>
            <img className="AI1" alt="AI4" src={AI1} />
        </div>
          </div>
        </div>
      </div>
    </div>
  );
}
export default Page;
