import React, { useState } from "react";
import IntroVideoFile from "./video/video.mp4";
import "../Css/videointro.css";
import PageComponent from "./PageComponent.js";

const VideoIntro = () => {
  const [played, setPlayed] = useState(false);
  const [fadeIn, setFadeIn] = useState(false);

  const handleVideoEnd = () => {
    setPlayed(true);
    setFadeIn(true);
  };

  if (played) {
    return (
      <div className={`fade-in ${fadeIn ? "visible" : ""}`}>
        <PageComponent />
      </div>
    );
  }

  return (
    <div className="video-intro">
      <video 
        autoPlay 
        muted 
        playsInline 
        onEnded={handleVideoEnd} 
        style={{ width: "100%", height: "100%" }}
      >
        <source src={IntroVideoFile} type="video/mp4" />
        Your browser does not support the video tag.
      </video>
    </div>
  );
};

export default VideoIntro;
